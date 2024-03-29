<?php

require_once('Base.php');

/**
 * Execution Mode
 */
define('EXECUTION_MODE', 'RC');

/**
 * Enterprise - ENVIRONMENT SPECIFIC - Overrides General
 */
class AALM_Config_QA_AUTOMATED extends AALM_Config_Base
{
	protected function init()
 	{
 		parent::init();

		/**
		 * Mode - Used for the log file directory
		 */
		$this->configVariables['mode'] =  'aalm_qa_auto';

		/**
		 * Common Library Directories
		 */
		if (!defined('COMMON_LIB_DIR')) define('COMMON_LIB_DIR', '/virtualhosts/lib/');
		if (!defined('COMMON_LIB_ALT_DIR')) define('COMMON_LIB_ALT_DIR', '/virtualhosts/lib5/');
		if (!defined('LIBOLUTION_DIR')) define('LIBOLUTION_DIR', '/virtualhosts/libolution/');

		/**
		 * Database connections
		 */
		$this->configVariables['DB_HOST'] = 'db1.qa.tss';
		$this->configVariables['DB_NAME'] = 'ldb_generic';
		$this->configVariables['DB_USER'] = 'ecash';
		$this->configVariables['DB_PASS'] = 'lacosanostra';
		$this->configVariables['DB_PORT'] = 3308;

		$this->configVariables['SLAVE_DB_HOST'] = 'db1.qa.tss';
		$this->configVariables['SLAVE_DB_NAME'] = 'ldb_generic';
		$this->configVariables['SLAVE_DB_USER'] = 'ecash';
		$this->configVariables['SLAVE_DB_PASS'] = 'lacosanostra';
		$this->configVariables['SLAVE_DB_PORT'] = 3308;
		
		$this->configVariables['API_DB_HOST'] = 'db1.qa.tss';
		$this->configVariables['API_DB_NAME'] = 'ldb_generic';
		$this->configVariables['API_DB_USER'] = 'ecash';
		$this->configVariables['API_DB_PASS'] = 'lacosanostra';
		$this->configVariables['API_DB_PORT'] = 3308;
				

                /**
                 * Database connections
                 */
		$this->configVariables['DB_MASTER_CONFIG'] = new DB_MySQLConfig_1(
                        $this->configVariables['DB_HOST'],
                        $this->configVariables['DB_USER'] ,
                        $this->configVariables['DB_PASS'],
                        $this->configVariables['DB_NAME'],
                        $this->configVariables['DB_PORT']
                        );
		 $this->configVariables['DB_SLAVE_CONFIG'] = new DB_MySQLConfig_1(
                        $this->configVariables['SLAVE_DB_HOST'],
                        $this->configVariables['SLAVE_DB_USER'] ,
                        $this->configVariables['SLAVE_DB_PASS'],
                        $this->configVariables['SLAVE_DB_NAME'],
                        $this->configVariables['SLAVE_DB_PORT']
                        );
		 $this->configVariables['DB_API_CONFIG'] = new DB_MySQLConfig_1(
                        $this->configVariables['API_DB_HOST'],
                        $this->configVariables['API_DB_USER'] ,
                        $this->configVariables['API_DB_PASS'],
                        $this->configVariables['API_DB_NAME'],
                        $this->configVariables['API_DB_PORT']
                        );                        

		
		/**
		 * QuickChecks Return Files Directory
		 */
		if (!defined('QC_RETURN_FILE_DIR')) define('QC_RETURN_FILE_DIR', '/tmp/');

		$this->configVariables['FACTORY'] = ECash_Factory::getFactory($this->configVariables['CUSTOMER_CODE_DIR'], $this->configVariables['ENTERPRISE_PREFIX'], $this->configVariables['DB_MASTER_CONFIG']);

 	}
}
