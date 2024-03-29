<?php

class VendorAPI_PreviousCustomer_Criteria_HomePhone extends VendorAPI_PreviousCustomer_Criteria_Abstract
{
	protected function getCriteriaMapping()
	{
		return array(
			'phone_home' => 'homePhone',
		);
	}

	protected function getIgnoredStatuses()
	{
		return array('settled', 'paid');
	}

	protected function overrideDoNotLoanLookup()
	{
		return TRUE;
	}
}

?>
