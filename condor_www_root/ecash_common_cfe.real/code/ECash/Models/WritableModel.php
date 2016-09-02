<?php

	/**
	 * @package Ecash.Models
	 */

        require_once 'libolution/DB/Models/WritableModel.1.php';

        abstract class ECash_Models_WritableModel extends DB_Models_WritableModel_1
        {
                const ALIAS_MASTER = 'ECASH_MASTER';
                const ALIAS_SLAVE = 'ECASH_SLAVE';


		public function setDatabaseInstance(DB_IConnection_1 $db)
		{
			$this->db = $db;
		}

		public function getDatabaseInstance($db_inst = DB_Models_DatabaseModel_1::DB_INST_WRITE)
		{
			return isset($this->override_db) ? $this->override_db : $this->db;
		}

		public function __set($name, $value)
		{
			$name_short = str_replace('_', '', $name);
			if(method_exists($this, 'set' . $name_short))
			{
				$this->{'set' . $name_short}($value);
			}
			else
			{
				parent::__set($name, $value);
			}
		}

		public function __get($name)
		{
			$name_short = str_replace('_', '', $name);
			if(method_exists($this, 'get' . $name_short))
			{
				return $this->{'get' . $name_short}();
			}
			else
			{
				return parent::__get($name);
			}
		}

		public function __isset($name)
		{
			$name_short = str_replace('_', '', $name);
			if(method_exists($this, 'get' . $name_short))
			{
				//this may be too simplistic
				return TRUE;
			}
			else
			{
				return parent::__isset($name);
			}
		}
		
		/**
		 * will delete the corresponding row in the database
		 * @throws Exception if the primary key isn't completely set
		 */
		public function delete()
		{

				$pk = array_intersect_key($this->getColumnData(), array_flip($this->getPrimaryKey()));
				if(count($pk) == count($this->getPrimaryKey())) 
				{
					$query = "
						DELETE FROM " . $this->getTableName() . "
						WHERE
							".implode(" = ? AND ", array_keys($pk))." = ?
					";
					$db = $this->getDatabaseInstance(self::DB_INST_WRITE);
					$st = $db->prepare($query);
					$st->execute(
						array_values($pk)
					);
				} 
				else 
				{
					throw new Exception("Attempting to perform a delete on an object with no primary key.");
				}
		}
	}
?>
