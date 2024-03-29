<?php
/**
 * VendorAPI_Blackbox_Rule_GreaterThan class file.
 *
 * @author Chris Barmonde <christopher.barmonde@sellingsource.com>
 */

/**
 * Checks if one value is greater than another value.
 *
 * @author Chris Barmonde <christopher.barmonde@sellingsource.com>
 */
class VendorAPI_Blackbox_Rule_GreaterThan extends VendorAPI_Blackbox_Rule
{
	/**
	 * Runs the Greater Than rule.
	 *
	 * @param Blackbox_Data $data Data to run validation checks on
	 * @param Blackbox_IStateData $state_data an IStateData object which contains the caller's (Blackbox_ITarget) state.
	 * 
	 * @return bool
	 */
	protected function runRule(Blackbox_Data $data, Blackbox_IStateData $state_data)
	{
		return ($this->getDataValue($data) > $this->getRuleValue());
	}
}

?>
