<?php

/**
 * ACH Batch Report
 * 
 * @package Reporting
 * @category Display
 */
class Achbatch_Report extends Report_Parent
{

	public function __construct(ECash_Transport $transport, $module_name)
	{

		$this->report_title       = "ACH Batch Report";

		$this->column_names       = array( 
				'report_date'                   	=> 'Date',
				'credit_num_attempted'         		=> '# Credits',
				'credit_total_attempted'       		=> '$ Credits',
				'debit_num_attempted'           	=> '# Debits',
				'debit_total_attempted'        		=> '$ Debits',
				'net_attempted'   					=> '# Net',
				'net_total'							=> '$ Net',
				'num_returned_actual_day'       	=> '# Returned',
				'total_returned_actual_day'     	=> '$ Returned',
				'coll_num_returned_actual_day'  	=> '# Returned Collections',
				'coll_total_returned_actual_day'	=> '$ Returned Collections',
				'net_after_returned'              	=> '$ Net after returns',
				'num_returned_adj_day'           	=> '# Returned (ach date)',
				'total_returned_adj_day'         	=> '$ Returned (ach date)',
				'coll_num_returned_adj_day'         => '# Returned Coll. (ach date)',
				'coll_total_returned_adj_day'       => '$ Returned Coll. (ach date)',				
				'net_after_returned_adj_day'        => '$ Net (ach date)',
		);
		$this->column_format = array( 
				'report_date' 						=> self::FORMAT_DATE,
				'credit_total_attempted' 			=> self::FORMAT_CURRENCY,
				'debit_total_attempted' 			=> self::FORMAT_CURRENCY,
				'net_total' 						=> self::FORMAT_CURRENCY,
				'total_returned_actual_day' 		=> self::FORMAT_CURRENCY,
				'net_after_returned' 				=> self::FORMAT_CURRENCY,
				'total_unauthorized' 				=> self::FORMAT_CURRENCY,
				'coll_total_returned_actual_day' 	=> self::FORMAT_CURRENCY,
				'coll_total_returned_adj_day' 		=> self::FORMAT_CURRENCY,
				'total_returned_adj_day' 			=> self::FORMAT_CURRENCY,
				'net_after_returned_adj_day'		=> self::FORMAT_CURRENCY,
		);


		$this->sort_columns = array( 
				'report_date',
				'credit_num_attempted',
				'credit_total_attempted',
				'debit_total_attempted',
				'debit_num_attempted',
				'net_attempted',
				'net_total',
				'num_returned_actual_day',
				'total_returned_actual_day',
				'coll_num_returned_actual_day',
				'coll_total_returned_actual_day',
				'net_after_returned',
				'num_returned_adj_day',
				'total_returned_adj_day',
				'coll_num_returned_adj_day',
				'coll_total_returned_adj_day',
				'net_after_returned_adj_day',										   
		);
		
		$this->link_columns       = array();
		$this->totals             = array(
			'company' =>
			array( 'credit_num_attempted'        		=> Report_Parent::$TOTAL_AS_SUM,
					'credit_total_attempted'       		=> Report_Parent::$TOTAL_AS_SUM,
					'debit_num_attempted' 		=> Report_Parent::$TOTAL_AS_SUM,
					'debit_total_attempted' 		=> Report_Parent::$TOTAL_AS_SUM,
					'net_attempted'          		=> Report_Parent::$TOTAL_AS_SUM,
					'net_total'                         => Report_Parent::$TOTAL_AS_SUM,
					'num_returned_actual_day'          		=> Report_Parent::$TOTAL_AS_SUM,
					'total_returned_actual_day'          		=> Report_Parent::$TOTAL_AS_SUM,
					'coll_num_returned_actual_day'	=> Report_Parent::$TOTAL_AS_SUM,
					'coll_total_returned_actual_day'	=> Report_Parent::$TOTAL_AS_SUM,
					'net_after_returned'       		=> Report_Parent::$TOTAL_AS_SUM,
					'num_returned_adj_day'          		=> Report_Parent::$TOTAL_AS_SUM,
					'total_returned_adj_day'          		=> Report_Parent::$TOTAL_AS_SUM,
					'coll_num_returned_adj_day'	=> Report_Parent::$TOTAL_AS_SUM,
					'coll_total_returned_adj_day'	=> Report_Parent::$TOTAL_AS_SUM,
					'net_after_returned_adj_day'       		=> Report_Parent::$TOTAL_AS_SUM,
					),

			'grand'   =>
			array( 'credit_num_attempted'        		=> Report_Parent::$TOTAL_AS_SUM,
					'credit_total_attempted'       		=> Report_Parent::$TOTAL_AS_SUM,
					'debit_num_attempted' 		=> Report_Parent::$TOTAL_AS_SUM,
					'debit_total_attempted' 		=> Report_Parent::$TOTAL_AS_SUM,
					'net_attempted'          		=> Report_Parent::$TOTAL_AS_SUM,
					'net_total'                         => Report_Parent::$TOTAL_AS_SUM,
					'num_returned_actual_day'          		=> Report_Parent::$TOTAL_AS_SUM,
					'total_returned_actual_day'          		=> Report_Parent::$TOTAL_AS_SUM,
					'coll_num_returned_actual_day'	=> Report_Parent::$TOTAL_AS_SUM,
					'coll_total_returned_actual_day'	=> Report_Parent::$TOTAL_AS_SUM,
					'net_after_returned'       		=> Report_Parent::$TOTAL_AS_SUM,
					'num_returned_adj_day'          		=> Report_Parent::$TOTAL_AS_SUM,
					'total_returned_adj_day'          		=> Report_Parent::$TOTAL_AS_SUM,
					'coll_num_returned_adj_day'	=> Report_Parent::$TOTAL_AS_SUM,
					'coll_total_returned_adj_day'	=> Report_Parent::$TOTAL_AS_SUM,
					'net_after_returned_adj_day'       		=> Report_Parent::$TOTAL_AS_SUM,
		));
		
				   //$this->report_table_height = 276;
		
		$this->totals_conditions   	= NULL;
		$this->date_dropdown       	= Report_Parent::$DATE_DROPDOWN_RANGE;
		$this->loan_type           	= TRUE;
		$this->download_file_name  	= NULL;
		$this->ajax_reporting 	  	= TRUE;
		
		parent::__construct($transport, $module_name);
	}

	protected function Format_Field( $name, $data, $totals = FALSE, $html = TRUE )
	{
		if ($data == NULL)
			return 0;
		else
			return parent::Format_Field( $name, $data, $totals, $html);
	}

}

?>
