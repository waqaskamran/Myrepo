<?php
require_once(LIB_DIR . "ach_batch_interface.iface.php");
require_once(LIB_DIR . "ach_utils.class.php");

/**
 * Abstract ACH Batch Class
 *
 */
abstract class ACH_Batch implements ACH_Batch_Interface 
{
	protected $log;
	protected $server;
	protected $db;
	
	protected $ach_utils;
	
	protected $ach_company_name;
	protected $ach_tax_id;
	protected $ach_company_id;
	protected $ach_report_company_id;
	protected $ach_credit_bank_aba;
	protected $ach_debit_bank_aba;
	protected $ach_credit_bank_acct;
	protected $ach_debit_bank_acct;
	protected $ach_credit_bank_acct_type;
	protected $ach_debit_bank_acct_type;
	protected $ach_phone_number;
	
	protected $credit_count;
	protected $credit_amount;
	protected $debit_count;
	protected $debit_amount;
	
	protected $company_abbrev;
	protected $company_id;
	
	protected static $modifiers = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	protected $RS		  = "\n";		
	
	/**
	 * Used to determine whether or not the ACH batch file will contain
	 * both the credits and debits in one file or to send two separate
	 * files.
	 */
	protected $COMBINED_BATCH = FALSE;
	
	public function __construct(Server $server)
	{
		$this->server			= $server;
		$this->db = ECash_Config::getMasterDbConnection();
		$this->company_id		= $server->company_id;
		$this->company_abbrev	= strtolower($server->company);
		// Set up separate log object for ACH purposes
		$this->log = new Applog(APPLOG_SUBDIRECTORY.'/ach', APPLOG_SIZE_LIMIT, APPLOG_FILE_LIMIT, strtoupper($this->company_abbrev));
		$this->ach_utils = new ACH_Utils($server);
		
		// Now grab a few company-based properties
		$this->ach_company_name			 = isset(eCash_Config::getInstance()->ACH_COMPANY_NAME) ? eCash_Config::getInstance()->ACH_COMPANY_NAME : eCash_Config::getInstance()->COMPANY_NAME;
		$this->ach_phone_number			 = eCash_Config::getInstance()->COMPANY_PHONE_NUMBER;
		$this->ach_company_id 			 = eCash_Config::getInstance()->ACH_COMPANY_ID;
		$this->ach_report_company_id 	 = eCash_Config::getInstance()->ACH_REPORT_COMPANY_ID;
		$this->ach_tax_id				 = eCash_Config::getInstance()->ACH_TAX_ID;

		$this->ach_debit_bank_aba		 = eCash_Config::getInstance()->ACH_DEBIT_BANK_ABA;
		$this->ach_debit_bank_acct		 = eCash_Config::getInstance()->ACH_DEBIT_BANK_ACCOUNT_NUMBER;
		$this->ach_debit_bank_acct_type  = eCash_Config::getInstance()->ACH_DEBIT_BANK_ACCOUNT_TYPE;

		$this->ach_credit_bank_aba 	 	 = eCash_Config::getInstance()->ACH_CREDIT_BANK_ABA;
		$this->ach_credit_bank_acct 	 = eCash_Config::getInstance()->ACH_CREDIT_BANK_ACCOUNT_NUMBER;
		$this->ach_credit_bank_acct_type = eCash_Config::getInstance()->ACH_CREDIT_BANK_ACCOUNT_TYPE;
		
		$this->Initialize_Batch();
	}
	
	public function Initialize_Batch()
	{
		$this->ach_batch_id	= NULL;
		$this->clk_ach_id	= NULL;
		$this->file		= "";
		$this->rowcount		= 0;
		$this->blockcount	= 0;
		$this->clk_total_amount	= 0;

		$this->process_log_ids		= array();
		$this->clk_trace_numbers	= array();
		$this->customer_trace_numbers	= array();
	}
	
	public function Do_Batch($batch_type, $batch_date)
	{
		$this->batch_type	= strtolower($batch_type);
		$this->batch_date	= $batch_date;
		$this->ach_utils->Set_Batch_Date($batch_date);

		// If we are already inside of a transaction, bail out with error
		if ($this->db->getInTransaction())
		{
			throw new General_Exception("ACH: Cannot be invoked from within a transaction; this class manages its own transactions.");
		}

		$this->log->Write("ACH: Received request to run a {$batch_type} batch for date {$batch_date}");

		// Note start of ACH process
		$this->ach_utils->Set_Process_Status('ach_send', 'started');

		// Generate data for a new batch
		$result = $this->Create_Batch();

		// Trap condition where there isn't anything to process and exit gracefully
		if (!$result)
		{
			$this->ach_utils->Set_Process_Status('ach_send', 'completed');

			return array(	'status'	=> 'no_data', 
							'batch_id'	=> NULL, 
							'ref_no'	=> NULL );
		}

		// Launch the batch into cyberspace
		$response = $this->Send_Batch();

		if ($response['status'] == 'sent')
		{
			// We're done
			$this->log->Write("ACH '{$this->batch_type}' batch {$this->ach_batch_id} prepared and sent for {$this->batch_date}.", LOG_INFO);
			$this->ach_utils->Set_Process_Status('ach_send', 'completed');

			return array(	'status'	=> 'sent', 
							'batch_id'	=> $this->ach_batch_id, 
							'ref_no'	=> (!empty($response['intercept']['REF']) ? $response['intercept']['REF'] : NULL),
							'db_amount'     => $response['intercept']['DA'],
							'db_count'      => $response['intercept']['DC'],
							'cr_amount'     => $response['intercept']['CA'],
							'cr_count'      => $response['intercept']['CC'] );
		}
		else
		{
			// Ahhh, shit
			$this->log->Write("ACH transmission of '{$this->batch_type}' batch {$this->ach_batch_id} for {$this->batch_date} has failed.", LOG_ERR);
			$this->ach_utils->Set_Process_Status('ach_send', 'failed');
			
			return array(	'status'	=> 'failed', 
							'batch_id'	=> $this->ach_batch_id, 
							'ref_no'	=> NULL );
		}
	}
	
	private function Create_Batch ()
	{
		$today = date('Y-m-d');

		$closing_timestamp = $this->Get_Closing_Timestamp($today);
		if (!$closing_timestamp)
		{
			$this->log->Write("ACH: No batch close has been performed for today.", LOG_ERR);
			return false;
		} else {
			$this->log->Write("ACH: Using closing stamp of $closing_timestamp");
		}

		// Gather eligible transactions from transaction_register
		//	If there are none, bail out
		
		$ach_transaction_ary = array();
		
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
					SELECT
						tr.transaction_register_id,
						tr.event_schedule_id,
						es.origin_group_id,
						tr.application_id,
						IF(tr.amount < 0, 'debit', 'credit') as ach_type,
						abs(tr.amount) as amount,
						 a.bank_aba,
						 trim(a.bank_account) as bank_account,
						 a.bank_account_type,
						 concat(upper(a.name_last), ', ', upper(a.name_first)) as name,
						 a.name_first,
						 a.name_last,
						 tt.name_short as transaction_type,
						 a.date_fund_estimated,
						 a.loan_type_id,
						 a.fund_actual,
						 a.date_first_payment,
						 a.street as address_1,
						 a.unit as address_2,
						 a.city,
						 a.state,
						 a.zip_code,
						 a.phone_home
					FROM transaction_register tr
					JOIN application a ON (tr.application_id = a.application_id)
					JOIN event_schedule es ON (tr.event_schedule_id = es.event_schedule_id)
					JOIN transaction_type tt ON (tr.transaction_type_id = tt.transaction_type_id)
					JOIN application_status_flat asf ON (a.application_status_id = asf.application_status_id)
					LEFT OUTER JOIN 
						(SELECT application_id, COUNT(*) AS cust_no_ach
							FROM application_flag af 
							JOIN flag_type ft USING (flag_type_id) 
							WHERE name_short = 'cust_no_ach'
							AND af.active_status = 'active'
							GROUP BY application_id) cnaf
						ON (tr.application_id = cnaf.application_id)
					WHERE
						     tt.clearing_type		= 'ach'
						AND  tr.transaction_status	= 'new'
						AND  cnaf.cust_no_ach IS NULL
						AND asf.level0			<> 'hold'
						AND  tr.company_id		=  {$this->company_id}
						AND  tr.date_effective		<= '{$this->batch_date}'
						AND  es.date_created		< '$closing_timestamp'
		";
		if ($this->batch_type == 'debit')
		{
			$query.= "
						AND tr.amount < 0
			";
		}
		else if($this->batch_type == 'credit')
		{
			$query.= "
						AND tr.amount > 0
			";
		}
		else 
		{
			$query.= "
						AND tr.amount <> 0
			";
		}
		$query .= "
					ORDER BY
						tr.date_effective, transaction_register_id
		";
		
		$result = $this->db->Query($query);
		$count = $result->rowCount();
		if ($count == 0)
		{
			$this->log->Write("ACH: No new transactions of type '{$this->batch_type}' were eligible for batch processing.", LOG_INFO);
			return false;
		} else {
			$this->log->Write("ACH: Discovered {$count} transactions for processing.");
		}

		// Get next ach_batch_id via skeletal insert for use in population of ach (and ach_company) rows
		$this->Get_Next_ACH_Batch_ID();
		$biz_rules = new ECash_Business_Rules($this->db);
		
		try
		{
			// Loop through customer data
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				// Do transaction_type check and then determine if we need to send APR change letter
				$rule_set_id = $biz_rules->Get_Current_Rule_Set_Id($row['loan_type_id']);
				$rules = $biz_rules->Get_Rule_Set_Tree($rule_set_id);
				
				if($row['transaction_type'] == 'loan_disbursement' && isset($rules['apr_change_notification']) && $rules['apr_change_notification'])
				{
					if($this->batch_date > $row['date_fund_estimated'])
					{
						//Compute new APR using batch_date & update the value in the db
						$new_apr = Update_APR($row['date_first_payment'], $this->batch_date, $row['fund_actual'], $row['application_id'], $row['loan_type_id'], $this->server->company);
						$this->log->Write("ACH: Sending APR Change Letter for AppID: {$row['application_id']} ({$new_apr})");
						//Send APR Change Letter
						require_once (LIB_DIR . "/Document/AutoEmail.class.php");
						eCash_Document_AutoEmail::Queue_For_Send($this->server, $row['application_id'], 'APR_CHANGE_LETTER');
					}
				}
				
				$this->db->beginTransaction();
				// Insert customer ach row as status='created'
				$ach_id = $this->ach_utils->Insert_ACH_Row('customer', $row);
				
				$row['ach_id'] = $ach_id;
				
				$ach_transaction_ary[] = $row;
								
				// Mark transaction_register row as 'pending' and note the ach_id
				$this->ach_utils->Update_Transaction_Register($row['transaction_register_id'], $ach_id);
				$app = ECash::getApplicationById($row['application_id']);	
				$engine = $app->getEngine();
				$engine->executeEvent('PENDING_TRANSACTION');

				$this->db->commit();
			}

			// Group array by ach_id, summarizing transaction amount
			$ach_transaction_ary = $this->Array_Summarize_Amt_By_ACH_Id($ach_transaction_ary);
			
			// Prepare ach_company insert for offsetting CLK transaction
			$ach_insert['amount']				= 0;
			if($this->batch_type == 'credit') {
				$ach_insert['bank_account']			= $this->ach_credit_bank_acct;
				$ach_insert['bank_aba']				= $this->ach_credit_bank_aba;
				$ach_insert['bank_account_type']	= $this->ach_credit_acct_type;
				$ach_insert['ach_type']				= 'debit';
			} else {
				$ach_insert['bank_account']			= $this->ach_debit_bank_acct;
				$ach_insert['bank_aba']				= $this->ach_debit_bank_aba;
				$ach_insert['bank_account_type']	= $this->ach_debit_acct_type;
				$ach_insert['ach_type']				= 'credit';
			}

			// Create ACH File content
			$this->Build_ACH_File($ach_transaction_ary);
			
			// Create ACH file in /tmp on local filesystem
			$this->Create_Local_File();

			// Insert settlement ach_company row
			$this->db->beginTransaction();
			$this->clk_ach_id = $this->ach_utils->Insert_ACH_Row('company', $ach_insert);

			// Mark customer ach rows as 'batched' while updating the ach_trace_number
			foreach ($ach_transaction_ary as $record)
			{
				$this->ach_utils->Update_ACH_Row('customer', $record['ach_id'], 'batched', $this->customer_trace_numbers[$record['ach_id']]);
			}

			// Mark CLK ach row as 'batched' while updating the total amount and ach_trace_number
			$this->ach_utils->Update_ACH_Row('company', $this->clk_ach_id, 'batched', $this->clk_trace_numbers[$this->clk_ach_id]);
			
			// Insert batch file content into existing ach_batch row,
			$this->Insert_ACH_Batch_File($this->file);
			$this->db->commit();
		}				
		catch(Exception $e)
		{
			$this->log->Write("ACH: Creation of batch {$this->ach_batch_id} failed and transaction will be rolled back.", LOG_ERR);
			$this->log->Write("ACH: No data recovery should be necessary after the cause of this problem has been determined.", LOG_INFO);
			if ($this->db->getInTransaction())
			{
				$this->db->rollback();
			}
			$this->ach_utils->Set_Process_Status('ach_send', 'failed');
			throw $e;
		}
		
		return true;
	}
	
	public function Resend_Failed_Batch($batch_id, $batch_date)
	{
		// Batch date will get re-established upon retrieval of the actual data for the passed batch_id
		//	we set here just for the sake of the initial process log entry
		$this->batch_date	= $batch_date;
		$this->ach_batch_id = $batch_id;
		
		// Note start of ACH resend process
		$this->ach_utils->Set_Process_Status('ach_resend', 'started');
		
		$this->log->Write("ACH: User has requested retransmission of batch {$this->ach_batch_id}.", LOG_INFO);
		
		// Get batch file content stored as a CLOB in the database
		$result = $this->Retrieve_Batch_File();
		
		if (!$result)
		{
			$this->log->Write("ACH: Batch {$this->ach_batch_id} does not exist or is not eligible for retransmission.", LOG_INFO);
			$this->ach_utils->Set_Process_Status('ach_resend', 'failed');
			return array(	'status'	=> 'failed', 
					'batch_id'	=> $this->ach_batch_id, 
					'ref_no'	=> NULL
					);
		}
		
		// Create ACH file in /tmp on local filesystem
		$this->Create_Local_File();
		
		// Attempt to launch the batch into cyberspace again
		$response = $this->Send_Batch();

		if ($response['status'] == 'sent')
		{
			// We're done
			$this->log->Write("ACH '{$this->batch_type}' batch {$this->ach_batch_id} was retransmitted for {$this->batch_date}.", LOG_INFO);
			$this->ach_utils->Set_Process_Status('ach_resend', 'completed');

			return array(	'status'	=> 'sent', 
					'batch_id'	=> $this->ach_batch_id, 
					'ref_no'	=> $response['intercept']['REF']
					);
		}
		else
		{
			// Ahhh, shit
			$this->log->Write("ACH retransmission of '{$this->batch_type}' batch {$this->ach_batch_id} for {$this->batch_date} has failed.", LOG_ERR);
			$this->ach_utils->Set_Process_Status('ach_resend', 'failed');
			
			return array(	'status'	=> 'failed', 
					'batch_id'	=> $this->ach_batch_id, 
					'ref_no'	=> NULL
					);
		}
		
	}

	protected function Send_Batch ()
	{
		$batch_login = eCash_Config::getInstance()->ACH_BATCH_LOGIN;
		$batch_pass  = eCash_Config::getInstance()->ACH_BATCH_PASS;

		try {
			$transport_type   = eCash_Config::getInstance()->ACH_TRANSPORT_TYPE;
			$transport_url    = eCash_Config::getInstance()->ACH_BATCH_URL;
			$transport_server = eCash_Config::getInstance()->ACH_BATCH_SERVER;
			$transport_port   = eCash_Config::getInstance()->ACH_BATCH_SERVER_PORT;

			$transport = ACHTransport::CreateTransport($transport_type, $transport_server, $batch_login, $batch_pass, $transport_port);

			if (EXECUTION_MODE != 'LIVE' && $transport->hasMethod('setBatchKey')) 
			{
				$transport->setBatchKey(eCash_Config::getInstance()->ACH_BATCH_KEY);
			}

			$batch_response = '';

			$remote_filename = $this->Get_Remote_Filename();

			$batch_success = $transport->sendBatch($this->ach_filename, $remote_filename, $batch_response);
		} catch (Exception $e) {
			$this->log->write($e->getMessage());
			$batch_response = '';
			$batch_success = false;
		}

		if ($batch_success) {
			$batch_status = 'sent';
		} else {
			$this->log->write("ACH file send: No response from '" . $remote_filename . "'.", LOG_ERR);
			$batch_status = 'failed';
		}

		/**
		 * SFTP/FTP/FTPS Responses are boolean and not a string like HTTPS.  This code will 
		 * populate the fields for the Batch History and Summary since they expect a string
		 * like this which is defined by Intercept.
		 */
		if($batch_response === TRUE)
		{
			// BC=1&DC=16194&CC=0&CA=1342686.75&DA=1342686.75&AC=0&FS=0&IC=0&REF=ECASH20061129.01&ER=0
			$bc  = $this->batch_count;       // Batch Count
			$cc  = 0;	// Credit Count
			$ca  = 0;	// Credit Amount
			$dc  = 0;	// Debit Count
			$da  = 0;	// Debit Amount
			$fs  = 0;	// File Size (bytes)
			$er  = 0;	// Error Code
			$ref = '';	// Reference Number (Intercept)
			$ac  = 0;	// Unknown
			$ic  = 0;	// Unknown

			if($this->batch_type === 'credit')
			{
				$cc = $this->batch_count;
				$ca = floatval($this->clk_total_amount);
			}
			else if ($this->batch_type === 'debit')
			{
				$dc = $this->batch_count;
				$da = floatval($this->clk_total_amount);
			}
			else
			{
				$cc = $this->credit_count;
				$ca = floatval($this->credit_amount);
				$dc = $this->debit_count;
				$da = floatval($this->debit_amount);
			}

			$batch_response = "BC=$bc&DC=$dc&CC=$cc&CA=$ca&DA=$da&AC=$ac&FS=%fs&IC=$ic&REF=$ref&ER=$er";
		}

		// Update response and corresponding status into ach_batch table,
		$this->Update_ACH_Batch_Response($batch_response, $batch_status);

		// Delete temp ACH file
		$this->Destroy_Local_File();

		// Set up return values
		$return_val = array();
		parse_str($batch_response, $return_val['intercept']);
		$return_val['status'] = $batch_status;

		return $return_val;
	}

	public function Get_Remote_Filename()
	{
		$transport_type   = eCash_Config::getInstance()->ACH_TRANSPORT_TYPE;
		$transport_url    = eCash_Config::getInstance()->ACH_BATCH_URL;
		// If we're using SFTP, we need to specify the whole path including a filename
			if(in_array($transport_type, array('SFTP', 'SFTP_AGEAN', 'FTP', 'FTPS'))) {
				// This needs to be modified based on the company
				$filename = "$transport_url/{$this->server->company}_{$this->batch_type}_" . date('Ymd') . ".txt"; 
			} else {
				$filename = $transport_url;
			}
			
			return $filename;
	}
	
	public function Check_Digit_DFI ($number)
	{
		$number = str_pad((string)$number, 8, '0', STR_PAD_LEFT);
		if (!preg_match("/^[0-9]{8,8}$/", $number))
		{
			return false;
		}

		$weights = array(3, 7, 1, 3, 7, 1, 3, 7);
		$result = 0;

		for($i = 0; $i < 8; $i++)
		{
			$result += $number[$i] * $weights[$i];
		}

		$result = (10 - ($result % 10)) % 10;

		return $result;
	}
	
	private function Array_Summarize_Amt_By_ACH_Id ($input_ary)
	{
		$output_ary = array();
		
		for ($i = 0; $i < count($input_ary); $i++)
		{
			if (array_key_exists($input_ary[$i]['ach_id'], $output_ary))
			{
				$output_ary[$input_ary[$i]['ach_id']]['amount'] += $input_ary[$i]['amount'];
			}
			else
			{
				$output_ary[$input_ary[$i]['ach_id']] = $input_ary[$i];
			}
		}
		
		return array_values($output_ary);
	}
	
	private function Create_Local_File ()
	{
		$tmp_file_sfx = date("YmdHis") . $this->microseconds();
		$this->ach_filename = "/tmp/ecash_{$this->company_abbrev}_" . $tmp_file_sfx . ".ach";

		try
		{	
			$fh = fopen($this->ach_filename, 'w+'); 
			fwrite($fh, $this->file);
			fclose($fh);
		}
		catch(Exception $e)
			{
				throw $e;
			}
		
		return true;
	}

	protected function Destroy_Local_File ()
	{
		unlink($this->ach_filename);
		return true;
	}
	
	private function microseconds()
	{
		list($usec, $sec) = explode(" ", microtime(false));
		$microseconds = (integer)((float)$usec * 1000000);
		return str_pad($microseconds, 6, '0', STR_PAD_LEFT);
	}

	/**
	 * Creates the ach_batch record and sets the ach_batch_id on the class
	 *
	 * @return boolean success
	 */
	private function Get_Next_ACH_Batch_ID ()
	{
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
					INSERT INTO ach_batch
						(
							date_created,
							company_id,
							ach_file_outbound,
							remote_response,
							batch_status,
							batch_type
						)
					VALUES
						(
							current_timestamp,
							{$this->company_id},
							'',
							NULL,
							'created',
							'{$this->batch_type}'
						)
		";
		
		$this->db->Query($query);
		$this->ach_batch_id = $this->db->lastInsertId();
		$this->ach_utils->ach_batch_id = $this->ach_batch_id;

		return true;
	}

	/**
	 * Updates the ach_batch record with the file contents
	 *
	 * @param string $ach_file_content
	 * @return boolean success
	 */
	private function Insert_ACH_Batch_File (&$ach_file_content)
	{
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
					UPDATE ach_batch
						SET
							ach_file_outbound = ?
						WHERE
							ach_batch_id = {$this->ach_batch_id}
		";
		
		// MySQL max_allowed_packet setting must be big enough to accommodate the largest conceivable ach batch;
		//	otherwise, we'll have to bind ach_file_outbound as 'b' (blob) and use 
		//	$stmt->send_long_data(0, $ach_file_content) repeatedly (until it returns FALSE ??!? -- poorly documented).

		$stmt = $this->db->Prepare($query);
		$stmt->bindParam(1, $ach_file_content, PDO::PARAM_STR);
		$stmt->execute();
		
		return true;
	}

	/**
	 * Updates the ach_batch record
	 *
	 * @param string $remote_response
	 * @param string $status
	 * @return boolean success
	 */
	protected function Update_ACH_Batch_Response (&$remote_response, $status)
	{
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
					UPDATE ach_batch
						SET
							remote_response	= ?,
							batch_status	= '$status'
						WHERE
							ach_batch_id = {$this->ach_batch_id}
		";
		
		$stmt = $this->db->Prepare($query);
		$stmt->bindParam(1, $remote_response, PDO::PARAM_STR);
		$stmt->execute();

		return true;
	}
	
	private function Retrieve_Batch_File ()
	{
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
					SELECT
						ach_batch.ach_file_outbound,
						ach.ach_type as batch_type,
						ach.ach_date as batch_date
					FROM
						ach_batch,
						ach
					WHERE
							ach_batch.ach_batch_id	= ach.ach_batch_id
						AND ach_batch.ach_batch_id	= {$this->ach_batch_id}
						AND ach_batch.company_id	= {$this->company_id}
						AND ach_batch.batch_status	= 'failed'
					LIMIT 1
		";
		
		$result = $this->db->Query($query);
		
		if($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			if (strlen($row['ach_file_outbound']) > 0)
			{
				$this->batch_type = $row['batch_type'];
				$this->batch_date = $row['batch_date'];
				$this->file = $row['ach_file_outbound'];
				
				return true;
			}
		}

		return false;
	}
	
	protected function Get_Next_File_ID_Modifier()
	{
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
					SELECT
						count(*) as count
					FROM
						process_log
					WHERE
						business_day	= DATE_SUB('{$this->batch_date}', INTERVAL 1 DAY)
					AND	step			= 'ach_send'
					AND company_id = {$this->company_id}
		";
		
		$result = $this->db->Query($query);
		$row = $result->fetch(PDO::FETCH_ASSOC);

		// Count must be at least one, because of process log entry for THIS run
		$idx = $row['count'] - 1;
		if($idx < 0) $idx = 0;
		
		if ($idx > 26)
		{
			throw new General_Exception("ACH: send invoked too many times for this business date ({$this->batch_date}). Do you know what you're doing?");
		}
		else 
		{
			$char = self::$modifiers[$idx];
		}
		
		$this->log->Write("Using File ID Modifier: {$char}\n");
		return $char;
	}
	
	public function Fetch_ACH_Batch_Stats ($start_date, $end_date)
	{
		$return_ary = false;
		$i = 0;
		
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
					SELECT
						date_created,
						date_modified,
						ach_batch_id,
						remote_response,
						batch_status
					FROM
						ach_batch
					WHERE
							date_created BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'
						AND company_id = {$this->company_id}
					ORDER BY
						date_created
		";
		
		$result = $this->db->Query($query);
		
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			if ($i == 0)
			{
				$return_ary = array();
			}
			$return_ary[$i] = array();

			$row_response_ary			= array();
			$row_response_ary['BC']		= "";
			$row_response_ary['DC']		= "";
			$row_response_ary['CC']		= "";
			$row_response_ary['CA']		= "";
			$row_response_ary['DA']		= "";
			$row_response_ary['FS']		= "";
			$row_response_ary['ER']		= "";
			$row_response_ary['REF']	= "";
			
			foreach (explode('&', $row['remote_response']) as $parm_value_pair)
			{
				if (strpos($parm_value_pair, "=") > 0)
				{
					list($key, $value) = explode('=', $parm_value_pair);
					if ( !in_array($key, array('IC', 'AC')) )
					{
						$row_response_ary[$key] = $value;
					}
				}
			}
			
			$return_ary[$i]['batch_created']	= $row['date_created'];
			$return_ary[$i]['batch_sent']		= $row['date_modified'];
			$return_ary[$i]['batch_id']			= $row['ach_batch_id'];
			$return_ary[$i]['batch_status']		= $row['batch_status'];
			
			$return_ary[$i]['batch_count']		= $row_response_ary['BC'];
			$return_ary[$i]['credit_count']		= $row_response_ary['CC'];
			$return_ary[$i]['credit_amount']	= $row_response_ary['CA'];
			$return_ary[$i]['debit_count']		= $row_response_ary['DC'];
			$return_ary[$i]['debit_amount']		= $row_response_ary['DA'];
			$return_ary[$i]['filesize_bytes']	= $row_response_ary['FS'];
			$return_ary[$i]['error_code']		= $row_response_ary['ER'];
			$return_ary[$i]['intercept_refno']	= $row_response_ary['REF'];
			
			$i++;
		}
		
		return $return_ary;
	}

	public function Preview_ACH_Batches ($batch_date, $sort_by = NULL, $sort_order = NULL)
	{
		$today = date('Y-m-d');

		$ach_event_id_ary = array();
		$ach_disbursement_ary = array();
		
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
				SELECT DISTINCT 
					et.event_type_id 
				FROM 
					event_transaction et, 
					transaction_type tt 
				WHERE
						et.transaction_type_id	= tt.transaction_type_id 
					AND tt.clearing_type		= 'ach'
					AND tt.name_short != 'loan_disbursement'
					AND et.active_status = 'active'
					AND tt.company_id = {$this->company_id}
		";
		
		$result = $this->db->Query($query);
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$ach_event_id_ary[] = $row['event_type_id'];
		}
		
		if (count($ach_event_id_ary) == 0)
		{
			return false;
		}

		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
				SELECT DISTINCT 
					et.event_type_id 
				FROM 
					event_transaction et, 
					transaction_type tt 
				WHERE
						et.transaction_type_id	= tt.transaction_type_id 
					AND tt.clearing_type		= 'ach'
					AND tt.name_short = 'loan_disbursement'
					AND et.active_status = 'active'
					AND tt.company_id = {$this->company_id}
		";

		$result = $this->db->Query($query);
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$ach_disbursement_ary[] = $row['event_type_id'];
		}
		
		if (count($ach_disbursement_ary) == 0)
		{
			return false;
		}		

		$closing_timestamp = $this->Get_Closing_Timestamp($today);
		
		$return_ary = false;
		$i = 0;

		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
				SELECT
					es.application_id,
					abs(es.amount_principal + es.amount_non_principal) as amount,
					 a.bank_aba,
					 trim(a.bank_account) as bank_account,
					 a.bank_account_type,
					 concat(upper(a.name_last), ', ', upper(a.name_first)) as name,
					 (
					 	CASE
					 		WHEN (es.amount_principal + es.amount_non_principal) < 0 THEN 'debit'
					 		ELSE 'credit'
					 	END
					 ) as ach_type
				FROM event_schedule es 
				JOIN application a ON (es.application_id = a.application_id)
				JOIN application_status ass ON (a.application_status_id = ass.application_status_id )
				LEFT JOIN transaction_register tr ON (tr.event_schedule_id = es.event_schedule_id)
				LEFT OUTER JOIN 
					(SELECT application_id, COUNT(*) AS cust_no_ach
						FROM application_flag af 
						JOIN flag_type ft USING (flag_type_id) 
						WHERE name_short = 'cust_no_ach'
						AND af.active_status = 'active'
						GROUP BY application_id) cnaf 
					ON (a.application_id = cnaf.application_id)
				WHERE
					(
						es.event_status			= 'scheduled'
						OR
						(es.event_status 		= 'registered'
						AND
						tr.transaction_status	= 'new')
					)
					AND cnaf.cust_no_ach IS NULL
					AND ass.name_short	<> 'hold'
					AND  es.company_id				=  {$this->company_id}
					AND  es.event_type_id 	IN (" . implode(',', array_merge($ach_event_id_ary, $ach_disbursement_ary)) . ")
					AND es.date_event <= '$today' 
					AND  es.date_effective			<= '$batch_date'
					AND es.date_created			< '$closing_timestamp'
					";

		// should we sort the results?
		if ($sort_by !== NULL)
		{
			// determine sorting fields
			switch (strtoupper($sort_by))
			{
			case 'NAME': $sort_by = 'name'; break;
			case 'APPLICATION_ID': $sort_by = 'es.application_id'; break;
			case 'ACH_TYPE': $sort_by = 'ach_type'; break;
			default: $sort_by = 'ach_type, es.date_effective, es.event_schedule_id'; break;
			}
			
			// sort order
			$sort_order = ($sort_order == 'DESC') ? 'DESC' : '';
			
			$query .= "
						ORDER BY
							{$sort_by} {$sort_order}
			";
		}

		$result = $this->db->Query($query);
		
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			
			if ($i == 0)
			{
				$return_ary = array();
			}
			
			$return_ary[$i] = array();

			$return_ary[$i]['application_id']		= $row['application_id'];
			$return_ary[$i]['name']					= $row['name'];
			$return_ary[$i]['bank_aba']				= $row['bank_aba'];
			$return_ary[$i]['bank_account']			= $row['bank_account'];
			$return_ary[$i]['bank_account_type']	= $row['bank_account_type'];
			$return_ary[$i]['amount']				= $row['amount'];
			$return_ary[$i]['ach_type']				= $row['ach_type'];
			
			$i++;
			
		}
		
		return $return_ary;
	}
	
	public function Get_Closing_Timestamp ($date_current)
	{
		// Get the most recent ACH closing timestamp for "current" date
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
					SELECT 
						date_started as cutoff_timestamp
					FROM 
						process_log
					WHERE
							step			= 'ach_batchclose'
						AND	state			= 'completed'
						AND business_day	= '$date_current'
						AND company_id		= {$this->company_id}
					ORDER BY
						date_started desc
					LIMIT 1
		";

		$result = $this->db->Query($query);

		if ($row = $result->fetch(PDO::FETCH_OBJ))
		{
			return $row->cutoff_timestamp;
		}
		
		return false;
	}

	public function Set_Closing_Timestamp ($date_current)
	{
		$this->batch_date = $date_current;
		
		$this->ach_utils->Set_Process_Status('ach_batchclose', 'started');
		$this->ach_utils->Set_Process_Status('ach_batchclose', 'completed');
		
		return true;
	}

	public function Has_Sent_ACH ($date_current)
	{
		$query = '-- /* SQL LOCATED IN file=' . __FILE__ . ' line=' . __LINE__ . ' method=' . __METHOD__ . " */
				SELECT
					process_log_id
				FROM
					process_log
				WHERE
						step			= 'ach_send'
					AND state			= 'completed'
					AND business_day	= '$date_current'
					AND company_id		= {$this->company_id}
				LIMIT 1
		";

		$result = $this->db->Query($query);
		if ($result->fetch(PDO::FETCH_OBJ))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Changes the company this ach object is focused on
	 * ONLY FOR THE batch_review_report.  I didn't check if it completely changes
	 * the company for all features
	 * @author wmf
	 * @param integer $company_id
	 */
	public function Set_Company( $company_id )
	{
		$company_id = $this->db->quote($company_id);

		$short_name_query = "
			-- eCash 3.5: File: " . __FILE__ . ", Method: " . __METHOD__ . ", Line: " . __LINE__ . "
			SELECT
				c.name_short AS name
			 FROM
				company      AS c
			 WHERE
				c.company_id = $company_id
			";

		$short_name_result = $this->db->Query($short_name_query);

		if( $short_name_result->rowCount() !== 1 )
			throw new Exception( "Unrecognized company_id: {$company_id} in " . __METHOD__ . " of " . __FILE__ . ".", LOG_ERR );

		$line = $short_name_result->fetch(PDO::FETCH_OBJ);

		$this->company_abbrev = strtolower($line->name);
		$this->company_id     = $company_id;
	}
	
	public function Get_Company_Id()
	{
		return $this->company_id;
	}

	public function Get_Company_Abbrev()
	{
		return $this->company_abbrev;
	}
	
	public function PGP_Encrypt_Batch()
	{
		// there's a few variables needed for this to work.
		// ACH_BATCH_USE_PGP          --- used for deciding whether to do nothing or not, actually, this should be checked before this is called
		// ACH_SENDER                 --- used for deciding which key to digitally sign with
		// ACH_RECIPIENT              --- used to decide which public key to sign with
		// ACH_PRIVATE_KEY_PASSPHRASE --- Needed to use the private key
		if (!isset(eCash_Config::getInstance()->ACH_BATCH_USE_PGP) ||
			!isset(eCash_Config::getInstance()->ACH_SENDER)        ||
			!isset(eCash_Config::getInstance()->ACH_RECIPIENT)     ||
			!isset(eCash_Config::getInstance()->ACH_PRIVATE_KEY_PASSPHRASE))
		{
			throw new Exception('Could not encrypt batchfile, one or more of the following required config variables were not set: ACH_BATCH_USE_PGP, ACH_SENDER, ACH_RECIPIENT, PRIVATE_KEY_PASSPHRASE');
		}

		putenv('GNUPGHOME=' . CUSTOMER_CODE_DIR . '/' . CUSTOMER . '/Config/gpg_keyring/');

		try
		{
			// Create our GPG class
			$gpg = new gnupg();
		}
		catch (Exception $e)
		{
			// The gnupg extension is not properly linked in
			throw new Exception('Could not initialize GPG library. http://wiki.ecash-commercial.ept.tss/index.php/GPG');
		}

		$gpg->seterrormode(gnupg::ERROR_EXCEPTION);

		$recipient  = eCash_Config::getInstance()->ACH_RECIPIENT;
		$sender     = eCash_Config::getInstance()->ACH_SENDER;

		$passphrase = eCash_Config::getInstance()->ACH_PRIVATE_KEY_PASSPHRASE;
	
		// the batch when this called is located in $this->ach_filename, of course it's in plaintext
		// I'm just going to get the contents of it, then put the new encrypted contents of it until
		// something better is proposed.
		$plaintext = file_get_contents($this->ach_filename);

		try 
		{
			$gpg->addencryptkey($recipient);
			$gpg->addsignkey($sender, $passphrase);
			$ciphertext = $gpg->encryptsign($plaintext);
		} 
		catch (Exception $e) 
		{
			throw new Exception('ERROR: ' . $e->getMessage());
		}
			
		// Now $ciphertext is the encrypted batch file, put it back before anyone notices
		file_put_contents($this->ach_filename, $ciphertext);

		return;
	}

	/**
	 * Helper method to get the COMBINED_BATCH flag from the class.
	 *
	 * @return bool
	 */
	public function useCombined()
	{
		return $this->COMBINED_BATCH;
	}
	
}

?>
