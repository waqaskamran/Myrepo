<?php

require_once(SERVER_CODE_DIR . "module_interface.iface.php");
require_once(LIB_DIR . "/Application/FieldAttribute.class.php");
require_once(SQL_LIB_DIR . "do_not_loan.class.php");
require_once(SQL_LIB_DIR . "application.func.php");
require_once(SQL_LIB_DIR . "loan_actions.func.php");
require_once(LIB_DIR.'AgentAffiliation.php');
require_once(SERVER_CODE_DIR . "search.class.php");
require_once(SERVER_CODE_DIR . "edit.class.php");
require_once(SERVER_MODULE_DIR. "loan_servicing/loan_servicing.class.php");

abstract class Master_Module implements Module_Interface
{
	protected $server;
	protected $request;
	protected $module_name;
	protected $search;
	protected $callbacks = Array();
	
	const DUPE_IP_ADDRESS_LIMIT = 25;

	public function __construct(Server $server, $request, $module_name) 
	{
		$this->server = $server;
		$this->request = $request;		
		$this->module_name = $module_name;
		$this->search = new Search($server, $request);
	}

	protected function _add_edit_object()
	{
		$this->edit = new Edit($this->server, $this->request);
	}

	// Consolidate from collections/module.class.php and conversion/module.class.php
	protected function Adjustment()
	{
		$this->edit->Internal_Adjustment($this->request->mode);
		ECash::getTransport()->Add_Levels($this->request->mode);
	}

	// [Mantis:3224] Consolidate from collections/module.class.php and loan_servicing/module.class.php
	protected function Add_Current_Level()
	{
		ECash::getTransport()->Add_Levels($this->module_name);
	}

	protected function Master_Main()
	{
		switch($this->request->action)
		{
			/**
			 * This is required to set up the appropriate queues
			 * after a company switch.
			 */
			case "switch_company" :
				$this->server->Set_Company($this->request->new_company_id);
				$obj = $this->{$this->module_name};
				$obj->setupQueues($this);

				if(ECash::getTransport()->page_array[2] == 'batch_mgmt')
				{
					ECash::getTransport()->Add_Levels('batch_history');
				}
				else 
				{
					ECash::getTransport()->Add_Levels('search');
				}
				break;
			
			case "get_next_application":
				$obj = $this->{$this->module_name};
				$obj->Get_Next_Application();
				$obj->setupQueues();
				break;
				
			case "loan_action":
				ECash::getTransport()->Set_Levels('popup', 'loan_action');
				break;
				
			case "paydate_wizard":
				$data = ECash::getTransport()->Get_Data();
				# build querystring
				$qs = array();
				if (isset($_GET['paydate']))
				{
					// Check the data format to work with the widget
					if( isset($_GET['paydate']['biweekly_date']) )
					{
						$temp  = explode( " ", $_GET['paydate']['biweekly_date'] );
						$date  = explode( "-", $temp[0] );
						$stamp = mktime( 0, 0, 0, $date[1], $date[2], $date[0] );
				
						// Forward the date in the database to either this week or last week
						while( strtotime(date("d-M-Y", $stamp)) < strtotime("-2 weeks") )
						{
							$stamp = strtotime( "+2 weeks", $stamp );
						}
				
						$_GET['paydate']['biweekly_date'] = date( "m/d/Y", $stamp );
					}
					// Some should be upper case, some lower...
					isset($_GET['paydate']['biweekly_day'])      && $_GET['paydate']['biweekly_day']      = strtoupper($_GET['paydate']['biweekly_day']);
					isset($_GET['paydate']['twicemonthly_type']) && $_GET['paydate']['twicemonthly_type'] = strtolower($_GET['paydate']['twicemonthly_type']);
					isset($_GET['paydate']['monthly_type'])      && $_GET['paydate']['monthly_type']      = strtolower($_GET['paydate']['monthly_type']);
				
					foreach( $_GET['paydate'] as $k => $v )
					{
						$qs[] = urlencode("paydate[" . $k . "]") . "=" . urlencode($v);
					}
				}
				
				$url_paydate_widget = ECash::getConfig()->URL_PAYDATE_WIDGET;
				$data->paydate_widget = file_get_contents($url_paydate_widget . "?" . join("&", $qs));
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Set_Levels('popup', 'paydate_wizard');
				break;
			
			case 'dup_ip_address':
				$data = ECash::getTransport()->Get_Data();
				
				$data->ip_address = $_REQUEST['ip'];
				$data->company_id = $_REQUEST['cid'];
				$data->ip_count = 0;
				$data->records = array();
				
				$app_client = ECash::getFactory()->getWebServiceFactory()->getWebService('application');
				$ip_search_criteria = array(
					array(	'field'          => 'ip_address',
							'strategy'       => 'is',
							'searchCriteria' => $data->ip_address));
				$ip_address_search = $app_client->applicationSearch($ip_search_criteria, self::DUPE_IP_ADDRESS_LIMIT);

				if(!empty($ip_address_search))
				{
					foreach($ip_address_search as $row)
					{
						$record = array();
						$record['date_created'] = $row->date_created;
						$record['ssn'] = '***-**-' . substr($row->ssn, 5, 4);
						$record['application_id'] = $row->application_id;
						$record['status'] = $row->application_status;
						$record['name_first'] = $row->name_first;
						$record['name_last'] = $row->name_last;
						$record['street'] = $row->street;
						$record['city'] = $row->city;
						$record['state'] = $row->state;

						$data->records[] = $record;
						$data->ip_count++;
					}

					/**
					 * Since the search is limited due to performance and
					 * SOAP timeout issues, we want to indicate to the user
					 * that we've hit that limitation.
					 */
					if($data->ip_count >= self::DUPE_IP_ADDRESS_LIMIT)
					{
						$data->ip_count = ">= LIMIT of " . self::DUPE_IP_ADDRESS_LIMIT;
					}
				}
				
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Set_Levels('popup', 'dup_ip_address');
				break;
				
			case "dup_bank_account":
				$data = ECash::getTransport()->Get_Data();
				$app_data = ECash::getFactory()->getData('Application');
				$result = $app_data->getDuplicateBankInfo($this->request->bank_aba, $this->request->bank_account, FALSE);

				$row_count = 0;
				$ssn_count = 0;
				$ssn_break_prev = "";
				$data->info_table = '';
				if(is_array($result) && count($result) > 0)
				{
					foreach($result as $row)
					{
						$row_count++;

						if ($row->ssn != $ssn_break_prev)
						{
							$ssn_count++;
						}
						$ssn_break_prev = $row->ssn;

						 $cdate = ($row->date_created) ? date("m/d/Y H:i:s", strtotime($row->date_created)) : '';

						if (strlen($row->unit) > 0)
						{
							$address_line_1 = $row->street . ' #' . $row->unit;
						}
						else
						{
							$address_line_1 = $row->street;
						}

						$data->info_table .= "<tr>";
						$data->info_table .= "<td>" . $row->ssn							. "</td>";
						$data->info_table .= "<td>" . ucwords(strtolower($row->application_id))	. "</td>";
						$data->info_table .= "<td>" . ucwords(strtolower($row->name_first))		. " " . ucwords(strtolower($row->name_last)) . "</td>";
						$data->info_table .= "<td>" . ucwords(strtolower($address_line_1))		. "</td>";
						$data->info_table .= "<td>" . ucwords(strtolower($row->city))			. "</td>";
						$data->info_table .= "<td>" . strtoupper($row->state)					. "</td>";
						$data->info_table .= "<td>" . $cdate								. "</td>";
						$data->info_table .= "<td>" . $row->application_status							. "</td>";
						$data->info_table .= "</tr>\n";
					}
				}
				$data->summary = $row_count	? "Number of transactions with this ABA/Bank Account : &nbsp;&nbsp; <b>$row_count</b>\n" 
							: "[ No transactions with this ABA/Bank Account were found. ]\n";
				if ($row_count > 0)
				{
					$data->summary .= "<br>Number of different SSN's associated with this ABA/Bank Account : &nbsp;&nbsp; <b>$ssn_count</b>\n";
				}
				
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Set_Levels('popup', 'dup_bank_account');
				break;				

			case "outgoing_call_dispositions":
				$data = ECash::getTransport()->Get_Data();

				$agent = ECash::getAgent();
		
				$data->curmode        = $this->mode;
				$data->curmodule      = $this->module_name; 
				$data->application_id = $this->request->application_id;
			
				$module = $this->module_name;

				// No canned comments yet
				$data->canned_comments_html = "";
		
				// Get the call dispositions for the various call types
				$las_model = ECash::getFactory()->getModel("LoanActionSection");
		
				// Placeholder
				$la_model = ECash::getFactory()->getModel('LoanActionsList');
		
				$data->work_dispositions  = array();
				$data->cell_dispositions  = array();
				$data->home_dispositions  = array();
				$data->ref_1_dispositions = array();
				$data->ref_2_dispositions = array();
		
				// Work
				if ($las_model->loadBy(array('name_short' => strtoupper($module) . '_CALL_WORK')) !== FALSE)
				{
					// Get all associated LoanAction models for this given LoanActionSection
					$lasr_model = ECash::getFactory()->getModel("LoanActionSectionRelationList");
			
					if ($lasr_model->loadBy(array('loan_action_section_id' => $las_model->loan_action_section_id)) !== FALSE)
					{
						foreach($lasr_model as $row)
						{
							// Each row will have a $row->loan_action_id which tells you which 
							// loan actions can be used for this loan action section type
							if ($la_model->loadBy(array('loan_action_id' => $row->loan_action_id)) !== FALSE)
							{
								foreach ($la_model as $loan_action)
								{
									$data->work_dispositions[$loan_action->loan_action_id] = $loan_action->description;
								}
							}
						}	
					}
				}
		
				// Cell
				if ($las_model->loadBy(array('name_short' => strtoupper($module) . '_CALL_CELL')) !== FALSE)
				{
					// Get all associated LoanAction models for this given LoanActionSection
					$lasr_model = ECash::getFactory()->getModel("LoanActionSectionRelationList");
			
					if ($lasr_model->loadBy(array('loan_action_section_id' => $las_model->loan_action_section_id)) !== FALSE)
					{
						foreach($lasr_model as $row)
						{
							// Each row will have a $row->loan_action_id which tells you which 
							// loan actions can be used for this loan action section type
							if ($la_model->loadBy(array('loan_action_id' => $row->loan_action_id)) !== FALSE)
							{
								foreach ($la_model as $loan_action)
								{
									$data->cell_dispositions[$loan_action->loan_action_id] = $loan_action->description;
								}
							}
						}	
					}
				}
		
				// Home
				if ($las_model->loadBy(array('name_short' => strtoupper($module) . '_CALL_HOME')) !== FALSE)
				{
					// Get all associated LoanAction models for this given LoanActionSection
					$lasr_model = ECash::getFactory()->getModel("LoanActionSectionRelationList");
			
					if ($lasr_model->loadBy(array('loan_action_section_id' => $las_model->loan_action_section_id)) !== FALSE)
					{
						foreach($lasr_model as $row)
						{
							// Each row will have a $row->loan_action_id which tells you which 
							// loan actions can be used for this loan action section type
							if ($la_model->loadBy(array('loan_action_id' => $row->loan_action_id)) !== FALSE)
							{
								foreach ($la_model as $loan_action)
								{
									$data->home_dispositions[$loan_action->loan_action_id] = $loan_action->description;
								}
							}
						}	
					}
				}
		
				// References
				if ($las_model->loadBy(array('name_short' => strtoupper($module) . '_CALL_REF_1')) !== FALSE)
				{
					// Get all associated LoanAction models for this given LoanActionSection
					$lasr_model = ECash::getFactory()->getModel("LoanActionSectionRelationList");
			
					if ($lasr_model->loadBy(array('loan_action_section_id' => $las_model->loan_action_section_id)) !== FALSE)
					{
						foreach($lasr_model as $row)
						{
							// Each row will have a $row->loan_action_id which tells you which 
							// loan actions can be used for this loan action section type
							if ($la_model->loadBy(array('loan_action_id' => $row->loan_action_id)) !== FALSE)
							{
								foreach ($la_model as $loan_action)
								{
									$data->ref_1_dispositions[$loan_action->loan_action_id] = $loan_action->description;
								}
							}
						}	
					}
				}
		
				if ($las_model->loadBy(array('name_short' => strtoupper($module) . '_CALL_REF_2')) !== FALSE)
				{
					// Get all associated LoanAction models for this given LoanActionSection
					$lasr_model = ECash::getFactory()->getModel("LoanActionSectionRelationList");
			
					if ($lasr_model->loadBy(array('loan_action_section_id' => $las_model->loan_action_section_id)) !== FALSE)
					{
						foreach($lasr_model as $row)
						{
							// Each row will have a $row->loan_action_id which tells you which 
							// loan actions can be used for this loan action section type
							if ($la_model->loadBy(array('loan_action_id' => $row->loan_action_id)) !== FALSE)
							{
								foreach ($la_model as $loan_action)
								{
									$data->ref_2_dispositions[$loan_action->loan_action_id] = $loan_action->description;
								}
							}
						}	
					}
				}
		
				// Required information for reassociation at save time
				$data->application_id       = $this->request->application_id;
				$data->agent_id             = $agent->AgentId;


				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Set_Levels('popup', 'outgoing_call_dispositions');


				break;


         	case "show_applicant":
            	if($this->search->Show_Applicant())
            	{
					$data = ECash::getTransport()->Get_Data();
				
					if(!empty($data->fraud_rules) || !empty($data->risk_rules)) //show idv pane if they're high risk/fraud
					{
						ECash::getTransport()->Add_Levels('overview','idv','view','general_info','view');
					}
            		else if (isset($this->module_name) && ($this->module_name == 'collections')) //then show collections
					{
						ECash::getTransport()->Add_Levels('overview','personal','view','general_info','view');
	            	}
					else
					{
						ECash::getTransport()->Add_Levels('overview','loan_actions','view','general_info','view'); //otherwise show normal
	            	}
            	}
    	        break;
    	        
			case "add_follow_up":
				$this->edit->Add_Follow_Up($this->request->application_id, $this->request->comment, $this->request->interval, $this->request->follow_up_date);
				break;

			case "change_status":
				$this->Change_Status();
				break;

			case "refund":
				$this->loan_servicing->Refund();
				$this->Add_Current_Level();
				break;

      		case "complete":
				$this->edit->Complete_Pending_Items();
				$this->Add_Current_Level();
				break;

			case "adjustment":
				$this->Adjustment();
				break;

			case "debt_company":
			case "debt_company_edit":
				$this->edit->Debt_Company($this->request->debt_company_id);
				//$this->Add_Current_Level();
				ECash::getTransport()->Add_Levels('overview','loan_actions','view','general_info','view');
				break;

			case 'refinance':
				$this->loan_servicing = $this->loan_servicing ? $this->loan_servicing : new Loan_Servicing($this->server,$this->request,$this->request->mode, $this);

				$this->loan_servicing->Add_Refinance();
				$this->Add_Current_Level();
				break;
			case 'rollover':
				$this->loan_servicing = $this->loan_servicing ? $this->loan_servicing : new Loan_Servicing($this->server,$this->request,$this->request->mode, $this);

				$this->loan_servicing->Add_Rollover(false);
				$this->Add_Current_Level();
				break;
			case 'request_rollover':
				$this->loan_servicing = $this->loan_servicing ? $this->loan_servicing : new Loan_Servicing($this->server,$this->request,$this->request->mode, $this);

				$this->loan_servicing->Add_Rollover(true);
				$this->Add_Current_Level();
				break;
			case 'grace_period_arrangement':
				$this->loan_servicing = $this->loan_servicing ? $this->loan_servicing : new Loan_Servicing($this->server,$this->request,$this->request->mode, $this);

				$this->loan_servicing->Add_Grace_Period_Arrangement();
				$this->Add_Current_Level();

				break;
			case "paydown":
//				$this->loan_servicing = $this->loan_servicing ? $this->loan_servicing : new Loan_Servicing($this->server,$this->request,$request->mode);

				$this->loan_servicing = $this->loan_servicing ? $this->loan_servicing : new Loan_Servicing($this->server,$this->request,$request->mode, $this);

				$this->loan_servicing->Add_Paydown();
				$this->Add_Current_Level();
				break;

			case "payment_card_payoff":
//				$this->loan_servicing = $this->loan_servicing ? $this->loan_servicing : new Loan_Servicing($this->server,$this->request,$request->mode);

				$this->loan_servicing = $this->loan_servicing ? $this->loan_servicing : new Loan_Servicing($this->server,$this->request,$request->mode, $this);

				$this->loan_servicing->Add_Payment_Card_Payoff();
				$this->Add_Current_Level();
				break;
			case "manual_ach":
				$this->loan_servicing = $this->loan_servicing ? $this->loan_servicing : new Loan_Servicing($this->server,$this->request,$request->mode, $this);
				$this->loan_servicing->Add_Paydown(TRUE);
				$this->Add_Current_Level();
				break;

			case "details":
				$this->edit->Show_Transaction_Details();
				$this->Add_Current_Level();
				break;

			case "post_debt_consolidation":
				$this->edit->Post_DebtConsolidation_Payment($this->request);
				break;

			case "modify_transaction":
				$this->edit->Modify_Transaction();
				$this->Add_Current_Level();
				break;

			case "recovery":
				$this->edit->Recovery();
				$this->Add_Current_Level();
				break;

			case "writeoff":
				$this->edit->Writeoff();
				$this->Add_Current_Level();
				break;

			case "refresh_pop_up":
				$this->Refresh_Pop_Up();
				break;

			case "add_comment":
				$this->Add_Comment();
				break;

			case "modify_document":
				$this->edit->ModifyDocument();
				$this->Add_Current_Level();
				break;

			case "delete_document":
				$this->edit->DeleteDocument();
				$this->Add_Current_Level();
				break;
			
			case "resend_email":
				$this->edit->resendDocument('email');
				$this->Add_Current_Level();
				break;
			
			case "resend_fax":
				$this->edit->resendDocument('fax');
				$this->Add_Current_Level();
				break;

			case "id_recheck":
				$agent_id = ECash::getAgent()->AgentId;
				$this->edit->Id_Recheck($this->request->application_id, $this->request->recheck_type, $agent_id);

				break;
				
			case 'fraud_risk_rules':
				$data  = new stdClass();
				$data->application_id = $this->request->application_id;
				
				$fraud = ECash::getFactory()->getData('Fraud')->getFraudRulesAndFields($this->request->application_id);
				
				foreach($fraud as $fraud_column => $fraud_value)
				{
					$data->{$fraud_column} = $fraud_value;
				}
				
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Set_Levels('popup', 'fraud_risk_rules');
				break;
				
			case "remove_watch_status":
				$this->fraud->Remove_Watch();
				break;

			case "add_watch_status":
				$this->fraud->Add_Watch();
				break;
				
// Moved to server/modules/collections/module.class.php ... why was it here to begin with?!?
//         	case "external_apps":         // this is the inquiry screen.
//				$this->external_collections->Get_Pending_Count($this->server->system_id);
//				$this->external_collections->Show_Available_Batch_Downloads($this->server->system_id, $this->from_date, $this->to_date); //mantis:5598 -  $this->to_date
//				break;
//
//         	case "post_collections":
//				$this->external_collections->Incoming_EC_Files_Metadata();
//				break;
//         		
			case 'quick_check_view_download':
				$this->collections->View_Batch($this->request->quick_checks_batch_id);
				break;

         	case 'quick_check_resend':
				$this->collections->Resend($this->request->quick_checks_batch_id);
				break;

			case 'quick_check_download_subbatch':
				$this->collections->Download_Subbatch($this->request->quick_checks_subbatch_id);
				break;

         	case "receive_quick_checks":
            	$this->collections->Receive_Quick_Checks();
            	break;

			case "download_external_apps":
				$this->external_collections->Download_External_Collections_File($this->request->ext_collections_batch_id);
				break;

			case "external_adj_process":
	            $date = $this->Get_Date();
    	        $this->from_date = new stdClass();
        	    $this->from_date->from_date_month = $date['month'];
    	        $this->from_date->from_date_day   = $date['day'];
	            $this->from_date->from_date_year  = $date['year'];
        	    ECash::getTransport()->Set_Data($this->from_date);
            	$this->external_collections->Process_Adjustments();
            	$this->external_collections->Get_Pending_Count($this->server->system_id);
            	$this->external_collections->Show_Available_Batch_Downloads($this->server->system_id, $this->from_date, $this->to_date);
				break;
			case "cancel_loan":
				$this->loan_servicing->Cancel_Loan();
				ECash::getTransport()->Add_Levels('overview', 'schedule','view');
				break;
			case "payout":
				$this->loan_servicing->Schedule_Payout();
				ECash::getTransport()->Add_Levels('overview', 'schedule','view');
				break;
			case "monitor_batch":
				$data = ECash::getTransport()->Get_Data();
				$data->company_id = ECash::getCompany()->company_id;
				$data->progress_process_type = $this->request->process_type;
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Add_Levels('monitor_batch', 'refresh_view');
				break;

         	case "no_rights":
            	//do nothing
            	break;

         	case "to_quickcheck_ready":
            	$this->edit->To_Quickcheck_Ready();
            	break;

			case "save_dispositions":
				$this->edit->Save_Dispositions();
				break;

			case "save_card_info":
				ECash::getTransport()->Add_Levels('overview','personal','view','payment_card','view'); // add - mantis:3561
				$this->edit->Save_Card_Info();
				$obj = $this->{$this->module_name};
				$obj->setupQueues($this);
				break;
			case "save_general_info":
				ECash::getTransport()->Add_Levels('overview','personal','view','general_info'); // add - mantis:3561
				$this->edit->Save_General_Info();
				$obj = $this->{$this->module_name};
				$obj->setupQueues($this);
				//ECash::getTransport()->Add_Levels('overview','personal','view','general_info','view'); // delete - mantis:3561
				break;

				
			case 'add_application_flag':
				//add validation and stuffs!
				require_once(SQL_LIB_DIR . '/app_flags.class.php');
				$flags = NEW Application_Flags($this->server,$this->request->application_id);
				
				$flag_type = $this->request->flag_type;
				if ($flag_type == '**custom**')
				{
					$flags->Add_Flag_Type($this->request->custom_flag_name,$this->request->custom_flag_description);
					$flag_type = $this->request->custom_flag_name;
				}
				
				
				$flags->Add_Application_Flag_History($flag_type,'added');
				$flags->Add_Flag($flag_type);
				$this->search->Show_Applicant($this->request->application_id);
				ECash::getTransport()->Add_Levels('overview','application_flag','view');
				
				
			break;
				
			case 'remove_application_flag':
				//add validation and stuffs
				$flag_type = $this->request->flag_type;
				require_once(SQL_LIB_DIR . '/app_flags.class.php');
				$flags = NEW Application_Flags($this->server,$this->request->application_id);
				
				$flags->Add_Application_Flag_History($flag_type,'removed');
				
				$flags->Remove_Flag($flag_type);
				$this->search->Show_Applicant($this->request->application_id);
				ECash::getTransport()->Add_Levels('overview','application_flag','view');
				
			break;
				
				
			case 'save_contact_information':
				require_once(LIB_DIR . '/Application/Contact.class.php');
				$app_con_inf = new eCash_Application_Contact($this->request->contact['application_id']);
				$errors = $app_con_inf->setContactInformation($this->request->contact);
			
				$return_obj = $_SESSION['current_app'];
				if (!empty($errors))
				{
					$return_obj->validation_errors = $errors;
					$return_obj->saved_error_data = $this->request;
				}

				ECash::getTransport()->Set_Data($return_obj);
				$this->search->Show_Applicant($this->request->application_id);
				ECash::getTransport()->Add_Levels('overview','contact_information','view');
				//$this->search->Show_Applicant($this->request->application_id);
				break;

			case "send_documents":
				$application_id = $_SESSION['current_app']->application_id;
				$application = ECash::getApplicationById($application_id);
				$documents = $application->getDocuments();
				$log = get_log();
				// if there are docs to send
				if (isset($this->request->document_list) && (count($this->request->document_list) > 0))
				{
					$document_list = array();
      				$destination = "";
      				$send_command = "";
	      			$submit_found = FALSE;

    	  			if (($this->request->submit == 'Send Email')
            			&& isset($this->request->customer_email) && strlen($this->request->customer_email))
      				{
         				$send_command .= "EMAIL";
         				$destination .= $this->request->customer_email;
         				$document_list = $this->request->document_list;
         				$submit_found = TRUE;
      				}
      				else if (($this->request->submit == 'Send Fax')
               			&& isset($this->request->phone_fax) && strlen($this->request->phone_fax))
      				{
	         			$send_command .= "FAX";
    	     			$destination .= $this->request->phone_fax;
        	 			$document_list = $this->request->document_list;
         				$submit_found = TRUE;
	      			}
    	  			else if (($this->request->submit == 'Send ESig')
        	   			&& isset($this->request->customer_email) && strlen($this->request->customer_email))
      				{
      					//TODO  change he send command here to esig.. but make sure it won't break anything
         				$send_command .= "ESIG";
         				$destination .= $this->request->customer_email;
         				$document_list = array('1' => ECash::getConfig()->DOCUMENT_DEFAULT_ESIG_BODY);
	         			$submit_found = TRUE;
    	  			}
      				else if (($this->request->submit == 'Email Package')
            				&& isset($this->request->customer_email) && strlen($this->request->customer_email))
      				{
         				$send_command .= "EMAIL";
	         			$destination .= $this->request->customer_email;
						if($package = $documents->getPackageByName(array_shift($this->request->document_list)))
						{
							$doc_list = $documents->createPackage($package);
							$transports = $doc_list->getTransportTypes();
							$transports['email']->setEmail($destination);
							if(!$doc_list->send($transports['email']))
							{
								$log->Write("Document package failed to send: " . var_export($this->request->document_list,true));
							}
						}
						else
						{
							$log->Write("Document package failed to create: " . var_export($this->request->document_list,true));
						}

	      			}

					if ($submit_found)
					{
						if(!empty($document_list)) 
						{
							foreach($document_list as $doc_id => $doc_name)
							{
								if($template = $documents->getTemplateByNameShort($doc_name))
								{
									if($document = $documents->create($template))
									{
										$transports = $document->getTransportTypes();
										switch($send_command)
										{
											case "EMAIL":
											case "ESIG":
												$transport_obj = $transports['email'];
												$transport_obj->setEmail($destination);
											break;
											case "FAX":
												$transport_obj = $transports['fax'];
												$transport_obj->setPhoneNumber($destination);
												$transport_obj->setCoverSheet(ECash::getConfig()->DOCUMENT_DEFAULT_FAX_COVERSHEET);
											break;
										}
										if(!$result = $document->send($transport_obj, ECash::getAgent()->getAgentId()))
										{
											if($result == 0)
											{
												$_SESSION['error_message'] = 'Recipient Address in Blacklist, unable to send document.';
												//throw new ECash_Documents_Exception('Recipient Address in Blacklist, unable to send.');
											}
											else
											{
												$_SESSION['error_message'] = 'Document Failed to Send.';
												//throw new ECash_Documents_Exception('Document Failed to Send');
											}
										}
									}
									else
									{
										$_SESSION['error_message'] = 'Document Failed Creation.';
										//throw new ECash_Documents_Exception('Document Failed Creation');	
									}
								}
								else
								{
									$_SESSION['error_message'] = 'Document Template Failed Creation.';
									//throw new ECash_Documents_Exception('Document Template Failed Creation');
								}
								
							} 

						} 
						else 
						{
							$log->Write("Document list empty for requested document(s): " . var_export($this->request->document_list,true));
						}
					}
				}

				$_SESSION['current_app']->docs = $documents->getSentandRecieved();
				$_SESSION['current_app']->receive_doc_list = $documents->getRecievable();;
				ECash::getTransport()->Set_Data($_SESSION['current_app']);
				ECash::getTransport()->Add_Levels('overview','send_documents','edit','documents','view');
				break;

			case "send_react_offer":

				$agent = ECash::getAgent();
				$agent->getTracking()->add('react_offer', $this->request->application_id);

				$this->SendReactOffer();

				$comments = ECash::getApplicationById($this->request->application_id)->getComments();
				$comments->add("React Offer Sent", ECash::getAgent()->AgentId);

				$loan_data = new Loan_Data(ECash::getServer());
				ECash::getTransport()->Set_Data($loan_data->Fetch_Loan_All($this->request->application_id));
				ECash::getTransport()->Add_Levels('overview','loan_actions','view','general_info','view');
				break;

			case "send_confirm_email":

				$agent = ECash::getAgent();
				$agent->getTracking()->add('send_react_confirm', $this->request->application_id);

				$this->SendConfirm();
				$comments = ECash::getApplicationById($this->request->application_id)->getComments();
				$comments->add(
					"React Confirm Sent.",
					ECash::getAgent()->AgentId
				);

				$loan_data = new Loan_Data(ECash::getServer());
				ECash::getTransport()->Set_Data($loan_data->Fetch_Loan_All($this->request->application_id));
								ECash::getTransport()->Add_Levels('overview','loan_actions','view','general_info','view');
				break;

			case "display_application_flag_modify":
				ECash::getTransport()->Set_Levels('popup', 'application_flag_modify');
				$loan_data = new Loan_Data(ECash::getServer());

				$flags = Ecash::getApplicationById($this->request->application_id)->getFlags();
				$flag_types = ECash::getFactory()->getReferenceList("FlagType");

				$data->module = $this->request->module;
				$data->mode = $this->request->mode;

				$data->flag = $this->request->flag;
				$data->application_id = $this->request->application_id;
				$data->flag_description = $flag_types[$data->flag]->name;
				$data->flag_state = $flags->get($data->flag);

				$data->permission_state = ECash::getACL()->Acl_Check_For_Access(Array($data->module, 'application_flag', $data->flag));
				ECash::getTransport()->Set_Data($data);

				break;
				
			case "set_application_flag":
				ECash::getTransport()->Set_Levels('close_pop_up');

				if (ECash::getACL()->Acl_Check_For_Access(array($this->request->module, 'application_flag', $this->request->flag)))
				{
					$app = Ecash::getApplicationById($this->request->application_id);
					$flags = $app->getFlags();
					$flags->set($this->request->flag);
				}

				break;

			case "clear_application_flag":
				ECash::getTransport()->Set_Levels('close_pop_up');


				if (ECash::getACL()->Acl_Check_For_Access(array($this->request->module, 'application_flag', $this->request->flag)))
				{
					$flags = Ecash::getApplicationById($this->request->application_id)->getFlags();
					$flags->clear($this->request->flag);
				}


			case "change_contact":
				ECash::getTransport()->Set_Levels('close_pop_up');


				$columns = array('phone_home', 'phone_cell', 'phone_work', 'customer_email',
								 'ref_phone_1', 'ref_phone_2', 'ref_phone_3', 'ref_phone_4', 'ref_phone_5', 'ref_phone_6', 'ssn', 'street' );


				$contact_flags = ECash::getApplicationById($this->request->application_id)->getContactFlags();

				foreach($this->request as $key => $value)
				{
					
					if(in_array($key, $columns))
					{
						//$contact_flags->clearAllByColumn($key);
						$contact_flags->clear($this->request->contact_setting,$key, 'application', NULL, ECash::getAgent()->AgentId);
						if (!empty($value))
						{
							$contact_flags->set(ECash::getAgent()->AgentId, $this->request->contact_setting, $key);
						}
					}
				}
				$loan_data = new Loan_Data(ECash::getServer());
				$data = $loan_data->Fetch_Loan_All($this->request->application_id);
				ECash::getTransport()->Set_Data($data);
				if (empty($_POST['panel']))                                
				{
					$panel = 'personal';
				}
				else
				{
					$panel = $_POST['panel'];
				}
				ECash::getTransport()->Add_Levels('overview',$panel,'view','general_info','view');

				break;

			//mantis:4360
			case "set_do_not_loan":
				ECash::getTransport()->Set_Levels('close_pop_up');

				$dnl = ECash::getCustomerBySSN($this->request->ssn)->getDoNotLoan();
				$dnl->set(
					ECash::getAgent()->AgentId,
					$this->request->do_not_loan_exp,
					$this->request->do_not_loan_category,
					$this->do_not_loan_other_reason
				);

				$comments = ECash::getApplicationById($this->request->application_id)->getComments();
				$comments->add(
					$this->request->do_not_loan_exp,
					ECash::getAgent()->AgentId,
					ECash_Application_Comments::TYPE_DNL
				);

				$agent = ECash::getAgent();
				$agent->getTracking()->add('dnl_set', $this->request->application_id);

				$loan_data = new Loan_Data(ECash::getServer());
				$data = $loan_data->Fetch_Loan_All($this->request->application_id);
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Add_Levels('overview','personal','view','general_info','view');

				break;

			case "remove_do_not_loan":
				ECash::getTransport()->Set_Levels('close_pop_up');

				$dnl = ECash::getCustomerBySSN($this->request->ssn_wk)->getDoNotLoan();
						
				if ($dnl->getByCompany(ECash::getCompany()->company_id))
				{
					$dnl->deactivate(ECash::getAgent()->AgentId, ECash::getCompany()->company_id);
				}
				
				$loan_data = new Loan_Data(ECash::getServer());
				$data = $loan_data->Fetch_Loan_All($this->request->application_id);
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Add_Levels('overview','personal','view','general_info','view');

				$comments = ECash::getApplicationById($this->request->application_id)->getComments();
				$comments->add(
					"DNL Removed",
					ECash::getAgent()->AgentId,
					ECash_Application_Comments::TYPE_DNL
				);

				$agent = ECash::getAgent();
				$agent->getTracking()->add('dnl_removed', $this->request->application_id);

				break;

			case "override_do_not_loan":
				ECash::getTransport()->Set_Levels('close_pop_up');

				$dnl = ECash::getCustomerBySSN($this->request->ssn)->getDoNotLoan();
				$dnl->setOverride(ECash::getAgent()->AgentId, ECash::getCompany()->company_id);

				$comments = ECash::getApplicationById($this->request->application_id)->getComments();
				$comments->add(
					"DNL Override Set",
					ECash::getAgent()->AgentId,
					ECash_Application_Comments::TYPE_DNL
				);

				$loan_data = new Loan_Data(ECash::getServer());
				$data = $loan_data->Fetch_Loan_All($this->request->application_id);
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Add_Levels('overview','personal','view','general_info','view');

				$agent = ECash::getAgent();
				$agent->getTracking()->add('dnl_override_set', $this->request->application_id);

				break;

			case "remove_override_do_not_loan":
				ECash::getTransport()->Set_Levels('close_pop_up');

				$dnl = ECash::getCustomerBySSN($this->request->ssn_wk)->getDoNotLoan();
				$dnl->clearOverride(ECash::getCompany()->company_id);

				$loan_data = new Loan_Data(ECash::getServer());
				$data = $loan_data->Fetch_Loan_All($this->request->application_id);

				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Add_Levels('overview','personal','view','general_info','view');

				$comments = ECash::getApplicationById($this->request->application_id)->getComments();
				$comments->add(
					"DNL Override Removed",
					ECash::getAgent()->AgentId,
					ECash_Application_Comments::TYPE_DNL
				);

				$agent = ECash::getAgent();
				$agent->getTracking()->add('dnl_override_removed', $this->request->application_id);

				break;

			case "get_dnl_audit_log":
				$application = ECash::getApplicationById($this->request->application_id);
				$ssn = $application->ssn;
				$dnl = new Do_Not_Loan(ECash::getMasterDb());
				$audit_log = $dnl->Get_DNL_Audit_Log($ssn);
				
				ECash::getTransport()->Set_Data( (object)(array('dnl_audit_log' => $audit_log)) );
				ECash::getTransport()->Set_Levels('popup','dnl_audit_log');
         		break;
         	case "get_dnl":
				$dnl = new Do_Not_Loan(ECash::getMasterDb());
         		$categories = $dnl->Get_Category_Info();
         		$name = $_REQUEST['name'];
				$ssn = $_REQUEST['ssn'];
				$company_id = $_REQUEST['company_id'];
				$ssn_wk = trim(str_replace('-', '', $ssn));

				$current_exists = $dnl->Does_SSN_In_Table_For_Company($ssn_wk, $company_id);
				$other_exists = $dnl->Does_SSN_In_Table_For_Other_Company($ssn_wk, $company_id);
				$override_exists = $dnl->Does_Override_Exists_For_Company($ssn_wk, $company_id);
				$dnl_info = $dnl->Get_DNL_Info($ssn_wk);
         		$categories = $dnl->Get_Category_Info();
         		
				ECash::getTransport()->Set_Data( (object)(array('name' => $name,'categories' => $categories ,'current_exists' => $current_exists, 'other_exists' => $other_exists, 'override_exists' => $override_exists, 'dnl_info' => $dnl_info,'ssn' => $this->request->ssn, 'application_id' => $this->request->application_id)) );
				ECash::getTransport()->Set_Levels('popup','dnl');
         		break;

			//[#40042] Login Counter & Lock
			case "get_login_lock":
				$flags = ECash::getApplicationById($this->request->application_id)->getContactFlags()->getAll();
				$checked = '';
				foreach($flags as $flag)
				{
					if($flag->field_name == 'login_lock')
						$checked = ' CHECKED';
				}
				ECash::getTransport()->Set_Data( (object)(array('application_id' => $this->request->application_id, 'login_locked_checked' => $checked)) );
				ECash::getTransport()->Set_Levels('popup','login_lock');
         		break;

			case "set_login_lock":
				ECash::getTransport()->Set_Levels('close_pop_up');
				if($this->request->login_lock != 'on')
				{
					//unset the flag
					ECash::getApplicationById($this->request->application_id)->getContactFlags()->clearAllByType('login_lock');
					//reset the counter to zero
					$lock = ECash::getFactory()->getModel('ApplicationLoginLock');
					$loaded = $lock->loadBy(array('application_id' => $this->request->application_id));
					if ($loaded)
                    {
	                    $lock->counter = 0;
	                    $lock->save();
                    }
				}
				$loan_data = new Loan_Data(ECash::getServer());
				$data = $loan_data->Fetch_Loan_All($this->request->application_id);
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Add_Levels('overview','personal','view','general_info','view');
				break;

			case "get_followup":
				// Time to create the dropdown list based on the type passed
				$opts = '
						 <option value="DATE">&lt;time&gt;</option>  // mantis:3144
						 <option value="5 minute">5 Minutes</option>
						 <option value="30 minute">30 minutes</option>
						 <option value="1 hour">1 Hour</option>
						 <option value="2 hour">2 Hours</option>
						 <option value="4 hour">4 Hours</option>
						 <option value="24 hour">24 Hours</option>
				';

				//[#42980] Allow followups on Saturday and/or Sunday
				//Get sat/sun business rules
				$business_rules = new ECash_BusinessRulesCache(ECash::getMasterDb());
				$followup_settings = $business_rules->Get_Rule_Set_Component_Parm_Values(ECash::getCompany()->name_short, 'weekend_followups');
				//default to FALSE if not set
				$allow_saturday_followups = isset($followup_settings['allow_saturday_followups']) && $followup_settings['allow_saturday_followups'] == 'Yes' ?
					'true' : 'false';
				$allow_sunday_followups = isset($followup_settings['allow_sunday_followups']) && $followup_settings['allow_sunday_followups'] == 'Yes' ?
					'true' : 'false';				
				
				ECash::getTransport()->Set_Data( (object)(array(
													  'followup_type' => $type,
													  'lower_followup_type' => strtolower($type),
													  'followup_opts' => $opts ,
													  'application_id' => $this->request->application_id,
													  'allow_saturday_followups' => $allow_saturday_followups,
													  'allow_sunday_followups' => $allow_sunday_followups,
													  )) );
				ECash::getTransport()->Set_Levels('popup','followup');
				break;

			case "receive_documents":
				$app = ECash::getApplicationById($this->request->application_id);
				$docs = $app->getDocuments();

				foreach($this->request->document_list as $id => $item)
				{
					$document_name = $item;
				}

				try
				{
					if($document = $docs->getByIDFromCondor(trim($this->request->archive_id), $this->request->docname_other, $document_name))
					{
						$document->recieved('fax', ECash::getAgent()->getAgentId(), isset($request->signature_status));
						$tiff_message = "<font color=\"green\"><b>Document found and updated</b></font>";
					}
					else
					{
						$tiff_message = "<font color=\"red\"><b>Document not found or Archive ID not numeric</b></font>";
					}
				}
				catch(Exception $e)
				{
					$tiff_message = "<font color=\"red\"><b>".$e->getMessage()."</b></font>";
				}

				$loan_data = new Loan_Data($this->server);
				$data = $loan_data->Fetch_Loan_All($this->request->application_id);
				if(isset($tiff_message))
					$data->tiff_message = $tiff_message;
				ECash::getTransport()->Set_Data($data);
				ECash::getTransport()->Add_Levels('overview','receive_documents','edit','documents','view');
				if (isset($this->funding)) 
				{
					$this->funding->Search_Dequeue();
				}
				break;

			//[#44204]
			case 'show_pdf':
				require_once(SERVER_CODE_DIR . 'show_pdf.php');
				$show_pdf = new Show_PDF($this->server, $this->request->archive_id, $this->request->attachment_key);
				try
				{
					ECash::getTransport()->Set_Data((object)(array('document' => $show_pdf->getDocument())));
				}
				catch(Exception $e)
				{
					ECash::getTransport()->Set_Data((object)(array('document' => $e->getMessage())));
				}
				ECash::getTransport()->Set_Levels('popup','show_document');
				break;

			case 'show_attachment':
				require_once(SERVER_CODE_DIR . 'show_attachment.php');
				$show_attachment = new Show_Attachment($this->server, $this->request->archive_id, $this->request->part_id);
				try
				{
					//kind of a hack. This will output via header(); echo; die();
					$show_attachment->getDocument();
					//ECash::getTransport()->Set_Data((object)(array('document' => $show_attachment->getDocument())));
				}
				catch(Exception $e)
				{
					ECash::getTransport()->Set_Data((object)(array('document' => $e->getMessage())));
				}
				ECash::getTransport()->Set_Levels('popup','show_attachment');
				break;

			case 'document_preview':
				require_once(SERVER_CODE_DIR . 'document_preview.php');
				$preview = new Document_Preview($this->server, $this->request->application_id, $this->request->document_id);
				try
				{
					ECash::getTransport()->Set_Data((object)(array('document' => $preview->getDocument())));
				}
				catch(Exception $e)
				{
					ECash::getTransport()->Set_Data((object)(array('document' => $e->getMessage())));
				}
				ECash::getTransport()->Set_Levels('popup','show_document');
				break;
			//END [#44204]

			case "shift_schedule":
				$this->edit->Shift_Schedule();
				break;

			case "manual_payment":
				$this->edit->Manual_Payment();
				break;

			case "save_wizard":
				$this->edit->Save_Wizard();
				break;

			case "save_payments":
				$this->edit->Save_Payments();
				break;

			case "save_application":
				$status = ECash::getApplicationById($this->request->application_id)->getStatus()->toArray();

				$strip_first_due_date = ($status['level2'] == "applicant" ||
										$status['level0'] == 'approved' ||
										$status['level0'] == 'funding_failed' ||
										 $status['level0'] == 'in_process') ? FALSE : TRUE;

				$this->edit->Save_Application(FALSE, $strip_first_due_date);
				break;

			case "save_employment":
				$this->edit->Save_Employment();
				$obj = $this->{$this->module_name};
				$obj->setupQueues($this);
				break;

			case "save_personal_references":
				$this->edit->Save_Personal_References();
				$obj = $this->{$this->module_name};
				$obj->setupQueues($this);
				break;

			case "save_personal":
				$this->edit->Save_Personal();
				$obj = $this->{$this->module_name};
				$obj->setupQueues($this);
				break;

			// For SSN Changes, send to the Review Queue
			case "to_action_queue" :
				$this->edit->Send_To_Action_Queue();
				break;

         	case "get_application_history":
			$application = ECash::getApplicationById($this->request->application_id);
			$queue_data = ECash::getFactory()->getData('Queues');

			$application_history_data = (object)(array(
				'application_history' => $application->getStatusHistory(),
				'queue_history' => $queue_data->getHistory($this->request->application_id),
				'call_history' => $call_history));
				ECash::getTransport()->Set_Data($application_history_data);
				ECash::getTransport()->Set_Levels('popup', 'application_history');
				break;

         	case "get_application_audit_log":
				$application = ECash::getApplicationById($this->request->application_id);
				$audit_log = $application->getAuditLog()->getAll();
			//[#46878] show fund requested & fund qualified in audit log to compare to fund actual
			ECash::getTransport()->Set_Data( (object)(array('application_audit_log' => $audit_log,
															'fund_requested' => $application->fund_requested,
															'fund_qualified' => $application->fund_qualified)) );
				ECash::getTransport()->Set_Levels('popup','application_audit_log');
			break;

         	case "get_application_flag_history":
         		require_once(SQL_LIB_DIR . '/app_flags.class.php');
				$flags = NEW Application_Flags($this->server,$this->request->application_id);
         		$application_flag_history = 	$flags->Get_Application_Flag_History();
         		ECash::getTransport()->Set_Data( (object)(array('application_flag_history' => $application_flag_history)) );
				ECash::getTransport()->Set_Levels('popup','application_flag_history');
         	break;
         	
         	case "get_payment_arrangement_history":
         		$payment_arrangement_history = Get_Payment_Arrangement_History($this->request->application_id);
				ECash::getTransport()->Set_Data( (object)(array('payment_arrangement_history' => $payment_arrangement_history)) );
				ECash::getTransport()->Set_Levels('popup','payment_arrangement_history');
         		break;

			case "ext_recovery_reversal":
				$this->loan_servicing->RecoveryReversal($this->request->action);
				ECash::getTransport()->Add_Levels('loan_servicing');
				break;

			case "chargeback":
			case "chargeback_reversal":
	//			$this->loan_servicing = new Loan_Servicing($this->server,$this->request,$request->mode);
				$this->loan_servicing = new Loan_Servicing($this->server,$this->request,$this->request->mode, $this);
				$this->loan_servicing->ChargeBack($this->request->action);
				ECash::getTransport()->Add_Levels('loan_servicing');
				break;

			case "execute_rollover":
				$renewal =  ECash::getFactory()->getRenewalClassByApplicationID($this->request->application_id);
				$rollover = $renewal->createRollover($this->request->application_id);
				$loan_data = new Loan_Data(ECash::getServer());
				$app_data = $loan_data->Fetch_Loan_All($this->request->application_id);

				ECash::getTransport()->Set_Data($app_data);
				if($rollover['success'])
				{
					ECash::getTransport()->Add_Levels('overview', 'schedule','view');
				}
				else 
				{
					ECash::getTransport()->Add_Levels('overview','application_info', 'view', 'general_info','view');	
				}
				
				break;

		   	/**
			 * For [#29877]
			 */
			case 'remove_fatal_ach':
				$this->edit->Remove_Fatal_ACH();
				$loan_data = new Loan_Data(ECash::getServer());
				$app_data = $loan_data->Fetch_Loan_All($this->request->application_id);
				ECash::getTransport()->Set_Data($app_data);
				ECash::getTransport()->Add_Levels('overview','application_info', 'view', 'general_info','view');	
				break;

			/**
			 * For [#29112]
			 */
			case 'resig':
				$this->edit->Re_ESig();
				$loan_data = new Loan_Data(ECash::getServer());
				$app_data = $loan_data->Fetch_Loan_All($this->request->application_id);
				ECash::getTransport()->Set_Data($app_data);
				ECash::getTransport()->Add_Levels('overview','application_info', 'view', 'general_info','view');	
				break;

			case "search":
				$num = $this->search->Search();
				if($num == 1)
				{
					ECash::getTransport()->Add_Levels('overview','loan_actions','view','general_info','view');
				}
				break;

			case "get_next_application_account_summary":
				$application_id = pull_from_automated_queue("Account Summary");
				if($application_id)
				{
					// Agent Tracking
					$agent = ECash::getAgent();
					$agent->getTracking()->add("account_summary", $application_id);

					$this->search->Show_Applicant($application_id);

					$payment = ECash::getFactory()->getModel('APIPayment');
					$payment->loadByFirstPayment($application_id);

					$tx_type_list = ECash::getFactory()->getReferenceList('TransactionType');

					/** @TODO this will change to transaction_type_id sometime */
					switch ($payment->event_type_id)
					{
						case $tx_type_list->toId(ECash_Transactions_TransactionType::TYPE_PAYOUT):
							$_SESSION['api_payment'] = $payment;
							$data = new stdClass();
							$data->javascript_on_load = 'VerifyPayout();';
							ECash::getTransport()->Set_Data($data);
							break;
							
						case $tx_type_list->toId(ECash_Transactions_TransactionType::TYPE_PAYDOWN):
							$_SESSION['api_payment'] = $payment;
							$data = new stdClass();
							$data->javascript_on_load = "if(confirm('Would you like to add a paydown to this application?')) OpenTransactionPopup('paydown', 'Add Paydown', 'customer_service');";
							ECash::getTransport()->Set_Data($data);
							break;
					}
					ECash::getTransport()->Add_Levels('overview','loan_actions','view','general_info','view');
				}
				else
				{
					if ($GLOBALS['queue_result_message'])
					{
						$duh = new stdClass;
						$duh->search_message = $GLOBALS['queue_result_message'];
						ECash::getTransport()->Set_Data($duh);
					}
					$this->search->Get_Last_Search($this->module_name, $this->request->mode);
				}
				break;

			case "modify_received_document":

				$document_query = ECash::getFactory()->getData('Document');
				$doc = $document_query->Get_Document_Log($_REQUEST['document_id']);
				$response = eCash_Document_DeliveryAPI_Condor::Prpc()->Get_History_By_Archive_Id($doc->archive_id);
				$doc->dispatch_history = $response;
				$doc->mode = $this->request->mode;
				ECash::getTransport()->Set_Data( (object)($doc) );
				ECash::getTransport()->Set_Levels('popup','modify_received_document');
				break;
				
			case 'personal_queue_pull':
			
				$agent = ECash::getAgent();
				$application_item = $agent->getQueue()->dequeue();
				$application_id = $application_item->RelatedId;	
				
				if (!empty($application_id))
				{
					$app = ECash::getApplicationById($application_id);
					$status = $app->getStatus();
		
					if ($status->level1 == 'underwriting' && $status->level2 == 'applicant') 
					{
						$new_status = array('follow_up', 'underwriting', 'applicant', '*root');
						$follow_up_type = 'underwriting';
					} 
					elseif ($status->level1 == 'verification' && $status->level2 == 'applicant') 
					{
						$new_status = array('follow_up', 'verification', 'applicant', '*root');
						$follow_up_type = 'verification';
					} 
					elseif ($status->level0 == 'amortization')
					{
						$follow_up_type = 'amortization';
					}
					elseif ($status->level1 == 'collections' || $status->level2 == 'collections')
					{
						$new_status = array('follow_up', 'contact', 'collections', 'customer', '*root');
						$follow_up_type = 'collections';
					}
					elseif ($status->level1 == 'servicing')
					{
						$new_status = false;
						$follow_up_type = 'servicing';
					}
					else 
					{
						$new_status = false;
						$follow_up_type = 'other';
					}
					
					$normalizer= new Date_Normalizer_1(new Date_BankHolidays_1());
					$date_expiration = $normalizer->advanceBusinessDays(time(), 2);	
					$application = ECash::getApplicationById($application_id);
					if ($follow_up_type == 'collections')
					{	
						$affiliations = $application->getAffiliations();
						$currentAffiliation = $affiliations->getCurrentAffiliation('collections', 'owner');
						if(!empty($currentAffiliation))
						{
							$agent = $currentAffiliation->getAgent();
							$affiliations->add($agent, 'collections', 'owner', $date_expiration);
						}
					}
					else
					{
						$affiliations = $application->getAffiliations();
						$currentAffiliation = $affiliations->getCurrentAffiliation('manual', 'owner');
						if(!empty($currentAffiliation))
						{
							$agent = $currentAffiliation->getAgent();
							$affiliations->add($agent, 'manual', 'owner', $date_expiration);
						}
					}
								
					// Update Queue Count to reflect queue pull
					$data = ECash::getTransport()->Get_Data();
					if(ECash::getTransport()->my_queue_count > 0)
					{
						ECash::getTransport()->my_queue_count = ECash::getTransport()->my_queue_count - 1;
					}	
					
					$search_action = "search_myapps_";
					$agent = ECash::getAgent();
					$agent->getTracking()->add($search_action, $application_id);

				}
				
				$this->request->criteria_type_1      = 'application_id';
				$this->request->search_deliminator_1 = 'is';
				$this->request->search_criteria_1    = $application_id;
				$this->request->criteria_type_2      = '';
				$this->request->search_deliminator_2 = 'is';
				$this->request->search_criteria_2    = '';
				$num = $this->search->Search(true);
				
				if("search" != ($last = array_pop(ECash::getTransport()->page_array)))
				{
					array_push(ECash::getTransport()->page_array,$last);
				}
				ECash::getTransport()->Add_Levels('overview','loan_actions','view','general_info','view');
				break;

			case "show_fraud_rules";
				$this->Add_Current_Level();
				break;
			case "add_fraud_rules";
				$this->Add_Current_Level();
				break;

			case 'check_ssn' :
				$this->edit->Customer_SSN_Change($this->request->customer_id);
				break;

			case 'customer_ssn_change' :
				$this->edit->Customer_SSN_Commit_Change();
				break;

			// For Customer-Centric Interface, retrieve basic app info
			// used for comparing a react to older apps under the same customer id
			case 'cust_intf_get_app_info' :
				$this->edit->Verify_React_Application();
				break;

			case 'react_verification' :
				$this->edit->React_Verification_Action();
				break;

			case "pbx_dial":

				try 
				{
					ECash::getTransport()->Set_Data( (object)(array('pbx_dial_number' => $dial_number)));

					require_once LIB_DIR . "/PBX/PBX.class.php";
					if(isset($this->request->contact_id)) 
					{
						eCash_PBX::LoggedDial($this->server, $this->request->dial_number, $this->request->contact_id, $this->request->agent_max_wait);

					} 
					else 
					{

						if(isset($this->request->add_contact) && $this->request->add_contact == 'true') 
						{

							$global_search = (!empty($this->request->contact_global_search));

							require_once LIB_DIR . "/Application/Contact.class.php";
							$contact = new eCash_Application_Contact(ECash::getMasterDb());
							$contact_id = $contact->addContact(
								$this->request->application_id,
								preg_replace('/\D/', '', $this->request->dial_number),
								$this->request->category,
								$this->request->type,
								'',
								$global_search);

							if(!$contact_id) 
							{
								throw new RuntimeException("PBX Error: Contact not added.");
							}

							eCash_PBX::LoggedDial($this->server, $this->request->dial_number, $contact_id, $this->request->agent_max_wait);

						} 
						else 
						{
							eCash_PBX::QuickDial($this->server, $this->request->dial_number, NULL, $this->request->agent_max_wait);
						}
					}

					ECash::getTransport()->Set_Data( (object)( array('pbx_dial_result' => 'Success')));
					$search_action = 'pbx_dial_success_'.str_replace(" ","_",$this->request->category);
				} 
				catch (Exception $e) 
				{
					$search_action = 'pbx_dial_fail_'.str_replace(" ","_",$this->request->category);
					ECash::getTransport()->Set_Data( (object)(array('pbx_dial_result' => "FAILURE: " . $e->getMessage())));
				}
				$agent = ECash::getAgent();
				$agent->getTracking()->add($search_action, $application_id);
				ECash::getTransport()->Set_Levels('popup','pbx_dial_result');

				break;

			case "add_fee":
				$loan_data = new Loan_Data(ECash::getServer());
				$loan_data->Add_Fee($this->request->application_id, $this->request->type);
				$app_data = $loan_data->Fetch_Loan_All($this->request->application_id);
				ECash::getTransport()->Set_Data($app_data);
				ECash::getTransport()->Add_Levels('overview', 'schedule','view');
				break;
			// Crossing fingers.
			case "renew_loan":
				renewLoan($this->request->application_id);

				$loan_data = new Loan_Data(ECash::getServer());
				$app_data = $loan_data->Fetch_Loan_All($this->request->application_id);
				ECash::getTransport()->Set_Data($app_data);
				ECash::getTransport()->Add_Levels('overview', 'schedule','view');
				break;

			//[#32997] moved from collections
			case "deceased_verified":
				$obj = $this->{$this->module_name};
				$obj->Deceased_Verification($this->request->application_id);
				break;
				
			case "deceased_unverified" :
				$obj = $this->{$this->module_name};
				$obj->Deceased_Notification($this->request->application_id);
			 	break;

       		case "show_search":
            default:
            	if ($this->Check_Action_Handlers()) 
            	{
          		 break;
            	}
            	else 
            	{
            		$this->search->Get_Last_Search($this->module_name, $this->request->mode);
                break;
                }

		}

		return;
	}

	public function Refresh_Pop_Up()
	{
		$loan_data = new Loan_Data($this->server);
		$data = $loan_data->Fetch_Loan_All($_SESSION['current_app']->application_id);
		ECash::getTransport()->Set_Data($data);


		if(isset($_SESSION["popup_display_list"]))
		{
			switch ($_SESSION["popup_display_list"]) 
			{
				case 'debt_consolidation':
					ECash::getTransport()->Add_Levels('overview', 'debt_consolidation','edit');
					break;
				case 'transaction_overview':
					ECash::getTransport()->Add_Levels('overview', 'schedule','view');
					break;
				case 'personal_info_edit':
					ECash::getTransport()->Add_Levels('overview', 'personal','edit');
					break;
				default:
					ECash::getTransport()->Add_Levels('overview','personal','view', 'general_info','view');
					break;
			}
			unset($_SESSION["popup_display_list"]);
			return;
		} 
		else 
		{
			switch($this->module_name)
			{
				
				case 'loan_servicing':
					ECash::getTransport()->Add_Levels('overview','application_info', 'view', 'general_info','view');
					break;
				case 'funding':
					if (isset($this->request->mode) && $this->request->mode == 'underwriting')
						ECash::getTransport()->Add_Levels('overview','application_info', 'view', 'general_info','view');
					else
						ECash::getTransport()->Add_Levels('overview','loan_actions', 'view', 'general_info','view');
					break;
				case 'fraud':
					
						//ECash::getTransport()->Set_Levels('application','fraud','watch');
						if (isset($_SESSION['previous_module']))
						{
							ECash::getTransport()->Set_Levels('application',$_SESSION['previous_module'],$_SESSION['previous_mode'],'overview','loan_actions','view','general_info','view');
							unset($_SESSION['previous_module']);	
							unset($_SESSION['previous_mode']);
						}
						else 
						{
							ECash::getTransport()->Set_Levels('application','fraud','watch','overview','loan_actions','view','general_info','view');
						}
					break;
				case 'collections':
					ECash::getTransport()->Add_Levels('overview','loan_actions','view','general_info','view');
					break;
				default:
					ECash::getTransport()->Add_Levels('overview','personal','view', 'general_info','view');
					break;
			}
		}
	}

	/**
	 * Send Confirmation Letter
	 *
	 */
	function SendConfirm()
	{
		ECash_Documents_AutoEmail::Send($_SESSION['current_app']->application_id, 'CONFIRMATION_LETTER');
	}

	function SendReactOffer()
	{
		ECash_Documents_AutoEmail::Send($_SESSION['current_app']->application_id, 'REACT_OFFER', null, true);
		
//		$loan_data = new Loan_Data($this->server);
//		$data = $loan_data->Fetch_Loan_All($_SESSION['current_app']->application_id);
//
//		$react['datalink'] = '';
//		$react['promo_id'] = '';
//		$react['promo_id'] = ECash::getConfig()->ECASH_APP_REACT_PROMOID;
//		$react['service_phone'] = ECash::getConfig()->COMPANY_SUPPORT_PHONE;
//		$react['datalink'] = ECash::getConfig()->COMPANY_DOMAIN;
//				
//		require_once eCash_Document_DIR . "/ApplicationData.class.php";
//		if (EXECUTION_MODE != 'LIVE')
//		{
//			$email_data['email_primary'] = ECash::getConfig()->DOCUMENT_TEST_EMAIL;
//			//Prepend 'rc.' to link so it works in non-live environments
//			$react['datalink'] = 'rc.' . $react['datalink'];
//		}
//		else
//		{
//			$email_data['email_primary'] = eCash_Document_ApplicationData::Get_Email($this->server, $_SESSION['current_app']->application_id);
//		}
//		
//		$email_data['name'] = $email_data['email_primary_name'] = ucfirst($data->name_first) . ' ' . ucfirst($data->name_last);
//		$email_data['FIRST_NAME'] = ucfirst($data->name_first);
//		$react['reckey'] = urlencode(base64_encode($_SESSION['current_app']->application_id));
//		$link = 'http://'. $react['datalink'] .'/?promo_id=' . $react['promo_id'] . '&promo_sub_code=agent_email_react&page=ent_cs_confirm_start&reckey=' . $react['reckey'];
//
//		$email_data['site_name_email'] = $email_data['site_name'] = $react['datalink'];
//		$email_data['link'] = $link;
//		$email_data['service_phone'] = $react['service_phone'];
//		$email_data['expire_date'] = date('F j, Y', strtotime('+2 weeks'));
//		//$email_data['react_info'] = array('ssn'=>$data->ssn,'dob'=>$data->dob);
//
//		$template = 'ECASH_REACT_EMAIL_OFFER';
//		$recipients = $email_data['email_primary'];
//
//		require_once(LIB_DIR . '/Mail.class.php');
//		eCash_Mail::sendMessage($template, $recipients, $email_data);
	}

      public function handle_actions($action) {
      }

      public function Register_Action_Handler($object, $method) {
              $this->callbacks[] = Array($object, $method);
      }

      public function Check_Action_Handlers() {
              foreach ($this->callbacks as $callback)
              {
                      if ($callback[0]->$callback[1]($this->request->action)) 
                      {
                              return true;
                      }
              }
              return false;
      }
}

?>
