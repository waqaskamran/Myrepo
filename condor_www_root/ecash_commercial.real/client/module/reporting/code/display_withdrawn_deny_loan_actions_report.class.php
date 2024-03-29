<?php

/**
 * @package Reporting
 * @category Display
 */
class Withdrawn_Deny_Loan_Actions_Report extends Report_Parent
{
	public function __construct(ECash_Transport $transport, $module_name)
	{
		$this->report_title       = "Withdraw / Denied Loan Action Report";
		$this->column_names       = array( 
											'company_name'	=>	'Company',
											'application_id' => 'App ID',
						   'full_name'	    => 'Name', 
						   'ssn'	    => 'SSN',
		                                   'current_status' => 'Current Status',
		                                   'disposition'    => 'Disposition',
		                                   'agent'          => 'Agent',
		                                   'status'         => 'Status',
		                                   'date'           => 'Date' );
		$this->sort_columns       = array( 'application_id',
						   'full_name', 
						   'ssn',
		                                   'current_status',
		                                   'disposition',
		                                   'agent',
		                                   'status',
		                                   'date' );
		$this->link_columns       = array( 'application_id' => '?module=%%%module%%%&mode=%%%mode%%%&show_back_button=1&action=show_applicant&application_id=%%%application_id%%%' );
		$this->totals             = null;
		$this->date_dropdown      = Report_Parent::$DATE_DROPDOWN_RANGE;

		$this->column_format       = array('ssn'				=> self::FORMAT_SSN,
										   'date'				=> self::FORMAT_DATETIME );
		$this->ajax_reporting 	  = true;
		$this->loan_type          = true;
		                                   
		parent::__construct($transport, $module_name);
	}

	// Get rid of the empty totals line in the excel report
	protected function Get_Company_Total_Line($company_name, &$company_totals)
	{
		return;
	}

}

?>
