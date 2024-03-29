<?php
/**
 * Database connection information for the OPM eCash companies.
 *
 * @author Adam Englander <adam.englander@sellingsource.com>
 */
class DBInfo_Enterprise_OPM
{
	/**
	 * Returns an array of the database information for the given company and mode.
	 *
	 * @param string $name_short the name short of the company to retrieve db info for
	 * @param string $mode the mode we're currently running in
	 * @return array
	 */
	public static function getDBInfo($name_short, $mode)
	{
		$db_config = new DB_Config();
		$server = $db_config->getLegacyDatabaseConfig('enterprise/commercial/opm', $mode);
		
		return $server;
	}
	
	
}
?>
