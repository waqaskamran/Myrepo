<?php
/**
 * Blackbox_Rule_Identical class file.
 *
 * @author Matt Piper <matt.piper@sellingsource.com>
 */

/**
 * Checks if one value is identical [ === ] to another value.
 * Used for type strict matching.
 *
 * @author Matt Piper <matt.piper@sellingsource.com>
 */
class OLPBlackbox_Rule_Identical extends OLPBlackbox_Rule implements OLPBlackbox_Factory_Legacy_IReusableRule
{
	/**
	 * Runs the Identical rule.
	 *
	 * @param Blackbox_Data $data Data to run validation checks on
	 * @param Blackbox_IStateData $state_data an IStateData object which contains the caller's (Blackbox_ITarget) state.
	 * 
	 * @return bool
	 */
	protected function runRule(Blackbox_Data $data, Blackbox_IStateData $state_data)
	{
		return ($this->getDataValue($data) === $this->getRuleValue());
	}
}

?>
