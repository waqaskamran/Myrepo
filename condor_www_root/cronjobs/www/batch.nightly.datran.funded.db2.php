<?php
	require_once('db2.1.php');
	require_once('debug.1.php');
	require_once('error.2.php');
	require_once('csv.1.php');	
	
	define('DEBUG_MODE', FALSE);
	
	// Main Object
	class Datran
	{
		// static //
		function Send($data_array,$debugmode=FALSE)
		{
			$data = array();
			$data['p'] = "SELFUN";
            $data['d'] = "http://www." . $data_array['URL'];
			$data['f'] = $data_array['FIRST_NAME'];
			$data['l'] = $data_array['LAST_NAME'];
			$data['e'] = $data_array['EMAIL'];
			$data['IP']= $data_array['IP_ADDRESS'];
			$data['a'] = $data_array['ADDRESS_1'];
            $data['c'] = $data_array['CITY'];
            $data['s'] = $data_array['STATE'];
            $data['z'] = $data_array['ZIP'];
            $data['t'] = $data_array['HOME_PHONE'];
			

					
			$vals = array();
			
			// format
			foreach ($data as $key=>$value) {
				$vals[] = $key."=".urlencode($value);
			}
			
			// our get string!!!1111one
			$get = "http://selsc.superautoresponders.com/c.aspx?" . join('&', $vals);
			
			// dont send this to datran, its a test!
			if ( $debugmode === FALSE )
				$response = file($get);
			else return $get;


			// Success! :D
			return TRUE;
		}
	}
	
	

	
	
	$total_count = 0;
	$count = 0;
	$day = strtotime("-4 days");
	
	
	$dstart = date("Y-m-d-00.00.00",$day);
	$dend = date("Y-m-d-23.59.59",$day);
	
	$company = array ("ufc","ucl","pcl","d1","ca");
	

	$fp = fopen("/tmp/dtrn_selfun_{$dstart}.csv","w");
	
	$CSV = new CSV(
			array(
			"forcequotes" => TRUE,
			"header" => array("FIRST_NAME","LAST_NAME","EMAIL","HOME_PHONE","ADDRESS_1","CITY","STATE","ZIP","IP_ADDRESS"),
			"stream" => $fp,
			"flush" => FALSE));
		

	
	$query = "
	select
		transaction.transaction_id trans_id,
		rtrim(c.name_first) first_name,
		rtrim(c.name_last) last_name,
		e.email_address email,
		p1.phone_number home_phone,
		a.street address_1,
		a.city city,
		st.name state,
		a.zip zip,
		transaction.ip_address ip_address,
		os.name url			
	from transaction
	join phone p1 on (transaction.active_home_phone_id=p1.phone_id)
	join customer c on (transaction.customer_id=c.customer_id)
	join email e on (transaction.active_email_id=e.email_id)
	join address a on (transaction.active_address_id=a.address_id)
	join state st on (a.state_id=st.state_id)
	join originating_source os on (transaction.originating_source_id=os.originating_source_id)
	where
		transaction.date_created between '$dstart' and '$dend'
		";	
	
	foreach ( $company as $cp )
	{
		
		$db2 = new Db2_1('olp',"web_$cp","{$cp}_web");
		error_2::Error_Test ($db2->Connect (), TRUE);		
			
			
		$result = $db2->Execute($query);
		error_2::Error_Test ($result, TRUE);
		
		
		print "\n{$cp}.result: " . $result->Num_Rows();
		
		$count = 0;
		while ( $obj = $result->Fetch_Object() )
		{
			$query2 = "
				select
					count(*) count
				from transaction_history th
				join transaction_sub_status tss ON (th.transaction_sub_status_id=tss.transaction_sub_status_id)
				where transaction_id={$obj->TRANS_ID}
				and tss.name in ('ACTIVE')";
			
			$result2 = $db2->Execute($query2);
			error_2::Error_Test($result2,TRUE);
			
			$o = $result2->Fetch_Object();
						
			if ($o->COUNT == 0)
				continue;
			
			$count++;
			print "\nSending to datran ... ";
			print "{$cp}.{$obj->TRANS_ID}.".($total_count+$count)." ... ";
			
			$CSV->recordFromArray ( (array)$obj );
			
			Datran::Send( (array)$obj, DEBUG_MODE);
			
			print "OK";
		}
		$total_count += $count;
	}

	
		
	$CSV->flush();
	print "\n\nTOTAL : $total_count \n";
	
	
	
					$message = new StdClass ();
					$message->text = "
					
					DataGrubber Run #		
					
					$total_count records were sent to datran
					
					 This is an automatic email generated by TSSDataGrubber; do not reply.  If you have any questions, please contact john.hargrove@thesellingsource.com. Thank you.
					";
					
							
			$email_port = 25;
			$email_url = "sellingsource.com";
			$email_s_name = "DataGrubber";
			$email_s_address = "data-grubber-noreply@thesellingsource.com";
			
			// Build Email Header
			$header = new StdClass ();
			$header->smtp_server = $email_smtp_server;
			$header->port = $email_port;
			$header->url = $email_url;
			$header->subject = "Funded to Datran - ".date("m-d-Y",strtotime("-1 day"));
			$header->sender_name = $email_s_name;
			$header->sender_address = $email_s_address;
				
		//email delivery
		// Create the Mail Object and Send the Mail	
		include_once("prpc/client.php");
		$mail = new prpc_client("prpc://smtp.2.soapdataserver.com/smtp.1.php");
			
		// Key Line - Create the mailing (Name of mailing, headers, scheduled date, scheduled time) DO NOT USE SCHEDULING!!!
		$mailing_id = $mail->CreateMailing ("datagrubber_run", $header, NULL, NULL);
	
		$r = new StdClass();
		$r->name= "john h.";
		$r->type="to";
		$r->address="john.hargrove@thesellingsource.com";
		
		$recipients = array();
		$recipients[] = $r;
	
		if ( !DEBUGMODE )
		{
			$r = new StdClass();
			$r->type="to";
			$r->name="Brian R.";
			$r->address="brian.rauch@thesellingsource.com";
			$recipients[] = $r;
		}
		
		
		
		// Key Line - Add the package to the mailing (mailing_id, array of recipients, message, array of attachments)
		$package_id = $mail->AddPackage ($mailing_id, $recipients, $message, NULL);

		// Key Line - Tell the server to process the mailing (send all emails)
		$result = $mail->SendMail ($mailing_id);
	
		// Debug Code - Use if you want to see the soap stuff
		// print_r ($mail->__get_wire ());
		echo " ... Report Mailing Id: ".$mailing_id."
";
		echo " ... Result: ".$result."\n";
		echo " ... Recipients: 
";		
	
?>
