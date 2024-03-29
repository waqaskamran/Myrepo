<?php

/**
 * The previous customer check by email and DOB
 * @author Andrew Minerd <andrew.minerd@sellingsource.com>
 */
class OLPBlackbox_Enterprise_Generic_Rule_PreviousCustomer_EmailSSN extends OLPBlackbox_Enterprise_Generic_Rule_PreviousCustomer
{
	/**
	 * Gives a short name for the rule
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'EMAIL_SSN';
	}

	/**
	 * Indicates whether the rule has the proper information to run
	 *
	 * @param Blackbox_Data $data
	 * @param Blackbox_IStateData $state
	 * @return bool
	 */
	protected function canRun(Blackbox_Data $data, Blackbox_IStateData $state)
	{
		return isset($data->email_primary)
			&& isset($data->social_security_number)
			&& (strlen($data->social_security_number) === 9);
	}

	/**
	 * Gets the conditions for the ECash provider
	 *
	 * @param Blackbox_Data $data
	 * @return array
	 */
	protected function getECashConditions(Blackbox_Data $data)
	{
		return array(
			'email' => $data->email_primary,
			'ssn' => $data->social_security_number,
		);
	}

	/**
	 * Gets the conditions for the OLP provider
	 *
	 * @param Blackbox_Data $data
	 * @return unknown
	 */
	protected function getOLPConditions(Blackbox_Data $data)
	{
		return NULL;
	}
}

?>