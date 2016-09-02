<?php

/*
	cronjob to sent zero-hour emails to olp paperless applicants
	meant to be run from the cmdline
*/


define('RC', FALSE);
define('DEBUG', RC || !(bool)preg_match('/ws0*[12]/', trim(`hostname`)));
// if DEBUG is set to true, user's email will be replaced with this one
///define('DEBUG_EMAIL',	'ryanf@sellingsource.com');
define('DEBUG_EMAIL',	'debug@tssmasterd.com');
// who to email when the script fails?
define('BAIL_NOTIFY',	'mikeg@sellingsource.com');
//define('BAIL_NOTIFY',	'bail@tssmasterd.com');

if (DEBUG)
{
	echo "DEBUG IS ON\n";
	sleep(1);
}

// this is required for security.3 to be able to decrypt passwords
define('PASSWORD_ENCRYPTION', 'ENCRYPT');

// Initial ini settings
ini_set('magic_quotes_runtime', 0);
ini_set('session.use_cookies', 0);

if (!DEBUG)
{
	// Make sure we keep running even if user aborts
	ignore_user_abort(TRUE);
}

// Let it run forever
set_time_limit(0);

// we need this early in case we need to bail!
require_once('/virtualhosts/lib/lib_mail.1.php');

// handle 
if (4 == $argc)
{
	define('OLE_EVENT',	$argv[1]);
	// how long after the signup should this go out? (in minutes)
	define('TIME_TARGET',	intval($argv[2]));
	// until how long after TIME_TARGET (in minutes) do we attempt to send this email?
	define('TIME_WINDOW',	intval($argv[3]));
}
else if (DEBUG)
{
	// define legit event to use for testing
	define('OLE_EVENT',	'OLP_PAPERLESS_2_HOUR_FOLLOWUP');
	define('TIME_TARGET',	120);
	define('TIME_WINDOW',	600);
}
else
{
	echo "Usage: {$argv[0]} <OLE_EVENT> <TIME_TARGET> <TIME_WINDOW>\n";
	bail("you're passing me an invalid argv of $argc elements: " . join(",", $argv) . ", what am i supposed to do?!");
}

if (DEBUG)
{
	echo "OLE_EVENT: '" . OLE_EVENT . "', TIME_TARGET: '" . TIME_TARGET . "', TIME_WINDOW: '" . TIME_WINDOW . "'\n";
}

// OLE_EVENT is checked by pulling it out of the database later on
assert(is_numeric(TIME_TARGET) && TIME_TARGET > 0);
assert(is_numeric(TIME_WINDOW) && TIME_WINDOW > 0);

if (DEBUG && !RC)
{
	// Connection information
	define('DB_HOST',	'localhost');
	define('DB_USER',	'root');
	define('DB_PASS',	'');
}
else
{
	define('DB_HOST',	'selsds001');
	define('DB_USER',	'sellingsource');
	define('DB_PASS',	'password');
}


// db where property_map lies
define('DB_MGMT',	'management');
define('OLE_SMTP_URL',	'prpc://smtp.2.soapdataserver.com/ole_smtp.1.php');
define('CONFIG_URL',	'prpc://config.1.soapdataserver.com/init_4');

// cache for config objects
$CONFIGS = array();

// we cheat by hardcoding this stuff. we could always pull property_id out of management
// however, it's unlikely to change; also, "db" can not always be guarenteed to be in the 
// for "olp_{property_short}_visitor", so just hardcode the shit

if (DEBUG && !RC)
{
	$companies = array(
	"PCL"	=> array(
		"db"	=> (RC ? "rc_" : "") . "olp_pcl_visitor"
		,"id"	=> 3018
	),
	);
}
else
{
	$companies = array(
	/*"CA"	=> array(
		"db"	=> (RC ? "rc_" : "") . "olp_ca_visitor"
		,"id"	=> 1581
	),*/
	"PCL"	=> array(
		"db"	=> (RC ? "rc_" : "") . "olp_pcl_visitor"
		,"id"	=> 3018
	),
	/*"UFC"	=> array(
		"db"	=> (RC ? "rc_" : "") . "olp_ufc_visitor",
		"id"	=> 17208
	),*/
	"UCL"	=> array(
		"db"	=> (RC ? "rc_" : "") . "olp_ucl_visitor",
		"id"	=> 1583
	),
	);
}

// require the rest of what we need
require_once('/virtualhosts/lib/error.2.php');
require_once('/virtualhosts/lib/debug.1.php');
require_once('/virtualhosts/lib/mysql.3.php');
require_once('/virtualhosts/lib/prpc/client.php');
require_once('/virtualhosts/lib/security.3.php');


// define functions

function bail($msg)
{
	$hostname = trim(`hostname`);
	$self = $_SERVER["SCRIPT_NAME"];
	$date = date("Y-m-d H:i:s");
	$body = "
$hostname:$self failed at $date with the message:
$msg
";
	Lib_Mail::mail(BAIL_NOTIFY, "$hostname:$self - $msg", $body);
	die($msg);
}

// only one site per property is "enterprise"
// this needs to go in a table for now it's ghetto!!!
function enterprise_site($property)
{
	$site = FALSE;
	switch (strtoupper($property))
	{
	case "PCL":
		$site['site_name'] = "oneclickcash.com"; 
		$site['name_view'] = "OneClickCash.com";
		break;
	case "UCL":
		$site['site_name'] = "unitedcashloans.com";
		$site['name_view'] = "UnitedCashLoans.com";
		break;
	case "CA":
		$site['site_name'] = "paydaycentral.com";
		$site['name_view'] = "PayDayCentral.com";
		break;
	case "USF":
		$site['site_name'] = "usfastcash.com";
		$site['name_view'] = "USFastCash.com";
		break;
	case "D1":
		$site['site_name'] = "500fastcash.com";
		$site['name_view'] = "500FastCash.com";
		break;
	}
	return $site;
}

function format_phone($phone)
{
	$phone = preg_replace('/\D+/', '', $phone);
	$phone = preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '\1-\2-\3', $phone);
	return $phone;
}

function fetch_config($license_key)
{
	global $CONFIGS;
	if (!array_key_exists($license_key, $CONFIGS))
	{
		$configurator = new Prpc_Client(CONFIG_URL);
		$CONFIGS[$license_key] = $configurator->Get_Init($license_key, "", "", "");
		if (DEBUG)
		{
			echo "fetched this config object for license key '$license_key': " .
				print_r($CONFIGS[$license_key], 1);
		}
	}
	assert(is_object($CONFIGS[$license_key]));
	return $CONFIGS[$license_key];
}

// fetch customer services phone# and fax# for a property
function fetch_support(&$sql, $license_key)
{
	$config = fetch_config($license_key);
	return array(
		"support_phone"		=> $config->support_phone
		,"collections_phone"	=> $config->collections_phone
		,"support_fax"		=> $config->support_fax
	);
}


function fetch_email_def(&$sql, &$property)
{

	// fetch info about the email in question
	$query = "
	SELECT
		email_id
		,ole_event
		,start_date
	FROM
		email_def
	WHERE
		ole_event = '" . OLE_EVENT . "'";
	
	$rs = $sql->Query($property["db"], $query, Debug_1::Trace_Code(__FILE__, __LINE__));
	Error_2::Error_Test($rs, TRUE);
	
	if ($sql->Row_Count($rs) == 0)
	{
		bail("OLE_EVENT '" . OLE_EVENT . "' does not exist in the email_def table!");
	}
	
	return $sql->Fetch_Array_Row($rs);

}

// sent is an array of application ids that were successfully sent mail
function record_sent(&$sql, &$property, &$def, &$sent)
{
	if (count($sent) == 0)
	{
		return;
	}

	$query = "
	INSERT INTO email_log (
		application_id
		,email_def_id
	) VALUES (" . join("," . $def["email_id"] . "),(", $sent) . "," . $def["email_id"] . ")";

	if (DEBUG)
	{
		echo "record_sent(" . $property["db"] . ") query: " . $query . "\n";
	}

	if (!DEBUG || RC)
	{
		$rs = $sql->Query(
			$property["db"]
			,$query
			,Debug_1::Trace_Code(__FILE__, __LINE__)
		);

		Error_2::Error_Test($rs, TRUE);
	}

}

function create_confirm_url(&$user)
{

	return "http://" . strtolower($user["site_name"]) . "/" . 
		"?page=ent_cs_login" . 
		"&application_id=" . urlencode($user["app_id"]) .
		"&property_short=" . urlencode($user["property_short"]);
}

function fetch_app_type()
{
	switch (OLE_EVENT)
	{
	case "OLP_PAPERLESS_2_HOUR_FOLLOWUP":
	case "OLP_PAPERLESS_4_HOUR_FOLLOWUP":
		// "i applied but haven't confirmed" status
		return "app.type = 'PROSPECT' AND stat_info.confirmed = 0";
		break;
	case "OLP_PAPERLESS_ACH_PROCESSED":
	case "OLP_PAPERLESS_THANK_YOU":
		// "funded" status... ugly
		return "app.type = 'CUSTOMER' AND app.status = 'APPROVED'";
		break;
	}
}

function create_template_data(&$user)
{

	// fetch phone/fax data for property
	$support = fetch_support($sql, $user["license_key"]);

	// associative array we send to OLE so it can populate the template
	// returns all data necessary to fill in all templates we support
	return array(
		// following two fields are required by the ole emailer
		"email_primary"		=> $user["email"]
		,"email_primary_name"	=> $user["name_first"] . " " . $user["name_last"]
		// here's the data for the template
		// name of the site they signed up under... 
		,"site_name"		=> $user["site_name"]
		,"name_view"		=> $user["name_view"]
		,"name"			=> $user["name_first"] . " " . $user["name_last"]
		,"applicationid"	=> $user["app_id"]
		// do this formatting in php because of the nice and clean number_format() function
		// formats to 2 decimal places, adds commas for numbers 1e3+
		,"amount"		=> '$' . number_format($user["amount"], 2)
		,"username"		=> $user["username"]
		,"password"		=> $user["password"]
		// url they need to click on to confirm loan
		,"confirm"		=> create_confirm_url($user)
		// estimated fund dats->legal_documente
		,"date"			=> $user["est_fund_date"]

		,"phone"		=> format_phone($support["support_phone"])
		,"fax"			=> format_phone($support["support_fax"])

	);
}


function fetch_suckers(&$sql, $property_short, &$property, &$email_def)
{
	$target = (DEBUG && !RC ? strtotime("2004/06/25 12:00:00") : time()) - (TIME_TARGET * 60);
	$earliest = date("YmdHis", $target);
	$latest = date("YmdHis", $target + (TIME_WINDOW * 60));

	if (DEBUG)
	{
		printf("current: %s, earliest: %s, latest: %s\n",
			date("Y-m-d H:i:s"),
			date("Y-m-d H:i:s", $target),
			date("Y-m-d H:i:s", $target + (TIME_WINDOW * 60))
		);
	}

	$query = "
	SELECT
		app.application_id	AS app_id
		,p.first_name		AS name_first
		,p.last_name		AS name_last 
		,p.email		AS email
		,DATE_FORMAT(ln.estimated_fund_date,'%W, %M %D %Y')	AS est_fund_date
		,ln.fund_amount		AS amount
		,acc.login		AS username
		,acc.hash_pass		AS hashed_password
		,ci.url			AS site_name
		,ci.license_key		AS license_key
		,'" . $property_short . "' AS property_short
	FROM
		application app
-- grab some basic personal info for the email
	JOIN
		personal p
	ON
		p.application_id = app.application_id
-- get info about their loan
	JOIN
		loan_note ln
	ON
		ln.application_id = app.application_id
	JOIN
		account acc
	ON
		acc.active_application_id = app.application_id
-- get info about the site they used
	JOIN
		campaign_info ci
	ON
		ci.application_id = app.application_id
-- must be left join, match may occur if there isn't a record at all
	LEFT JOIN
		email_log el
	ON
		el.application_id = app.application_id
	AND
		el.email_def_id = " . intval($email_def["email_id"]) . "
	LEFT JOIN
		stat_info
	ON
		stat_info.application_id = app.application_id
	WHERE
-- user is at the proper stage
		" . fetch_app_type() . "
	AND
-- ...user signed up within the window we're looking at
		app.created_date BETWEEN '" . $earliest . "' AND '" . $latest ."'
	AND
-- ...user has not been sent the message before
		el.application_id IS NULL
	";

	if (DEBUG)
	{
		echo "query for '$property_short' query: $query\n";
	}

	$rs = $sql->Query(
		$property["db"]
		,$query
		,Debug_1::Trace_Code(__FILE__, __LINE__)
	);

	Error_2::Error_Test($rs, TRUE);

	if (DEBUG)
	{
		echo "query returned " . $sql->Row_Count($rs) . " rows\n";
	}

	return $rs;

}



////////////////////////////// BEGIN MAIN LOGIC

// Build the sql object
$sql = new MySQL_3 ();

// Try the connection
$rs = $sql->Connect(
	'BOTH'
	,DB_HOST
	,DB_USER
	,DB_PASS
	,Debug_1::Trace_Code(__FILE__, __LINE__)
);

// die if we fail to connect
Error_2::Error_Test($rs, TRUE);

// define mailing class
$mailer = new Prpc_Client(OLE_SMTP_URL);

foreach ($companies as $property_short => $property)
{

	// create security object to decrypt passwords
	$security = new Security_3($sql, $property["db"], "account");

	// grab definitions for the email we'll be sending
	// this is *not* property-specific, however since there is no "global" olp db where
	// we can store things, we need to store a separate identical copy in each property's db,
	// and we need to know the property's db's name, so this gets done once per property. whew.
	$email_def = fetch_email_def($sql, $property);

	// keep track of all the emails we send for this property that succeed so we can record them afterwards
	$sent = array( TRUE => array(), FALSE => array() );

	// retreive enterprise site_name and name_view
	$enterprise = enterprise_site($property_short);
	
	$rs = fetch_suckers($sql, $property_short, $property, $email_def);

	//For each customer found process them
	while (FALSE !== ($user = $sql->Fetch_Array_Row($rs)))
	{
		if (DEBUG)
			echo "fetched user '" . $user["email"] . "\n";

		// decrypt password for sending
		if (DEBUG && !RC)
		{
			echo "user: " . print_r($user, 1);
			echo "unmangling password '" . $user["hashed_password"] . "'...\n";
		}

		$user["password"] = $security->_Un_Mangle_Password($user["hashed_password"]);

		if (DEBUG && !RC)
		{
			echo "password unmangled: " . print_r($user["password"], 1) . "\n";
		}
		$user['site_name'] = $enterprise['site_name'];
		$user['name_view'] = $enterprise['name_view'];
		
		$data = create_template_data($user);

		// if we're in debug mode, actually send to the user who's debugging
		if (DEBUG && !RC)
		{
			$data["email"] = $data["email_primary"] = DEBUG_EMAIL;
			echo "data for property '$property_short': " . print_r($data, 1) . "\n";
		}


		
		// send mail and append app_id to the result (TRUE|FALSE) array of sent
		// pass NULL property_id for "generic" -- or so days The Don
		$mail = $mailer->Ole_Send_Mail(OLE_EVENT, NULL, $data);

		if (DEBUG && !RC)
		{
			echo "mail results: $mail\n";
		}

		$sent[(bool)is_array($mail)][] = intval($user["app_id"]);

		// only send one record per property for testing
		if (DEBUG && !RC)
		{
			echo "sent for property '$property_short': $sent\n";
			//break;
		}
	}

	if (DEBUG)
	{
		echo "user is FALSE!, see?: "; var_dump($user); echo "\n";
		echo "result of send for '$property_short':" . print_r($sent, 1) . "\n";
	}

	// after we've run through an entire property's worth, record results
	record_sent($sql, $property, $email_def, $sent[TRUE]);

	$sql->Free_Result($rs);

}

// vim: set ts=8:

?>
