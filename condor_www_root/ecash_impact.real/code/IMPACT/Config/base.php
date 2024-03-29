<?php

/**
 * This is only required if you're using eCash.  It adds paths to the
 * AutoLoader.
 */
if(defined('ECASH_WWW_DIR')) require_once ECASH_WWW_DIR . 'paths.php';

require_once(ECASH_COMMON_CODE_DIR . 'ECash/Config.php');

/**
 * ENTERPRISE - GENERAL
 */
class  IMPACT_Config_Base extends ECash_Config 
{
	protected function init()
 	{
		/**
		 * Enterprise Prefix
		 *
		 * Set to a string because OLP couldn't loop configs due to the constant CUSTOMER
		 * assigned to ENTERPRISE_PREFIX. GF #16842
		 */
		$this->configVariables['ENTERPRISE_PREFIX'] =  'IMPACT';
		$this->configVariables['CUSTOMER_BASE_DIR'] =  dirname(__FILE__) . "/../../../";
		$this->configVariables['CUSTOMER_CODE_DIR'] =  $this->configVariables['CUSTOMER_BASE_DIR'] . 'code/';

		/**
		 * @TODO These need to be removed from the code and the code set up to dynamically pull the values
		 */
		if(!defined('CUSTOMER')) define('CUSTOMER', $this->configVariables['ENTERPRISE_PREFIX']);
		if(!defined('CUST_DIR')) define('CUST_DIR', dirname(__FILE__) . "/../../../");
		if(!defined('CUSTOMER_LIB')) define('CUSTOMER_LIB', $this->configVariables['CUSTOMER_BASE_DIR'] . "legacy_customer_lib/");
 		
 		/**
		 * Paths to directories and files
		 */
		$this->configVariables['PDFLIB_LICENSE_FILE'] =  "/etc/pdflib/pdflib_licenses.txt";

		/**
		 * Default eCash user - From agent table
		 */
		$this->configVariables['DEFAULT_AGENT'] = 'ecash';
		$this->configVariables['DEFAULT_AGENT_ID'] =  1;

		/**
		 * Statistics database connection constants
		 */
		$this->configVariables['STAT_MYSQL_HOST'] =  'db101.ept.tss:3317';
		$this->configVariables['STAT_MYSQL_USER'] =  'sellingsource';
		$this->configVariables['STAT_MYSQL_PASS'] =  'password';
		/**
		 * Force Redirection to SSL
		 */
		$this->configVariables['FORCE_SSL_LOGIN'] = 'OFF';

		/**
		 * Master DOMAIN, used for requests that MUST be executed on the same server
		 * ie, ach batches, quick check batches, etc and should be a subdomain of 
		 * the load balanced domain.
		 */
		$this->configVariables['MASTER_DOMAIN'] = $_SERVER['HTTP_HOST'];
		$this->configVariables['LOAD_BALANCED_DOMAIN'] = $_SERVER['HTTP_HOST'];
		$this->configVariables['COOKIE_DOMAIN'] = $_SERVER['SERVER_NAME'];

		/**
		 * ACH Defaults
		 */
		// Used by ACH_Deem_Successful-- This should be a business rule!
		$this->configVariables['ACH_HOLD_DAYS'] = 3;
		$this->configVariables['ACH_TRANSPORT_TYPE'] = 'SFTP';
		$this->configVariables['ACH_PROCESSOR'] = 'IMPACT';
		$this->configVariables['ACH_BATCH_SERVER'] = 'ds68.tss';
		$this->configVariables['ACH_BATCH_SERVER_PORT'] = '22';
		$this->configVariables['ACH_BATCH_URL'] = '/home/sellsource/';
		$this->configVariables['ACH_BATCH_KEY'] = 'RC';
		$this->configVariables['ACH_BATCH_FORMAT'] = 'impact';
		$this->configVariables['ACH_RETURN_FORMAT'] = 'impact';
		
		$this->configVariables['ACH_USE_APP_ID_FOR_NAME'] = TRUE;
		$this->configVariables['DEFAULT_INVESTOR_GROUP'] = 'IG_CVL1';

		$this->configVariables['TAG_URL'] = '/home/sellsource/tag_info';

		/**
		 * Document / Copia Defaults
		 */
		$this->configVariables['DOC_REPRINT_URL'] = 'http://doc.1.edataserver.com/doc_gen.php';
		$this->configVariables['URL_PAYDATE_WIDGET'] = "http://paydates.widgets.edataserver.com/";

		/**
		 * New & React Apps
		 */
		$this->configVariables['NEW_APP_SITE'] = 'http://www.impactcashusa.com';
		$this->configVariables['ECASH_APP'] = 'http://rc.ecashapp.com/';
		$this->configVariables['REACT_SOAP_URL'] = 'http://rc.bfw.1.edataserver.com/cm_soap.php?wsdl';
		$this->configVariables['REACT_SOAP_KEY'] = '13eb55c3098ad6a6e18a3aadd90d1304'; // Unknown whether this needs to change per environment/company
		$this->configVariables['ECASH_APP_REACT_PROMOID'] = 27713; // Unknown whether this needs to change per environment/company

		/**
		 * Queue realted defaults
		 */
		$this->configVariables['LOCAL_EARLIEST_CALL_TIME'] = '8';
		$this->configVariables['LOCAL_LATEST_CALL_TIME'] = '20';
		$this->configVariables['QUEUE_RECYCLE_EXPIRED_WINDOW'] = '24 hour';
		$this->configVariables['DEFAULT_INCOMING_EMAIL_QUEUE'] = 'servicing_email_queue';
		/**
		 * Notification Recipients
		 */
		$this->configVariables['NOTIFICATION_ERROR_RECIPIENTS'] = 'brian.ronald@sellingsource.com';
		$this->configVariables['MANAGER_EMAIL_ADDRESS'] = 'brian.ronald@sellingsource.com';
		$this->configVariables['DOCUMENT_TEST_FAX'] = '';

		/**
		 * Features - Enable / Disable Switches
		 */
		$this->configVariables['MULTI_COMPANY_ENABLED'] = FALSE;
		$this->configVariables['MULTI_COMPANY_INCLUDE_ARCHIVE'] = FALSE;
		$this->configVariables['INVESTOR_GROUP_TAGGING_ENABLED'] = TRUE;
		$this->configVariables['DAILY_CASH_REPORT_ENABLED'] = FALSE;
		$this->configVariables['PBX_ENABLED'] = FALSE;
		$this->configVariables['USE_PRINT_MANAGER'] = FALSE;
		$this->configVariables['USE_SOAP_PREACT'] = FALSE;
		$this->configVariables['USE_QUICKCHECKS'] = TRUE;
		$this->configVariables['ALLOW_UNSIGNED_APPS'] = TRUE;
		$this->configVariables['HAS_LOANTYPE_RESTRICTION'] = FALSE;

		/**
		 * Other Defaults
		 */
		$this->configVariables['SYSTEM_NAME'] = 'ecash3_0';
		$this->configVariables['TIME_ZONE'] = 'US/Mountain';
		$this->configVariables['TICKET_PRIORITY'] = 4;
		$this->configVariables['CONVERSION_MODE'] = 'OTHER';
		$this->configVariables['MAX_SEARCH_DISPLAY_ROWS'] = 500;
		$this->configVariables['MAX_REPORT_DISPLAY_ROWS'] = 10000;
	}
}
