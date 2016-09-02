<?php

/**
 * The cancel Packet
 *
 * @package ECashCra
 */
class ECashCra_Packet_CancelCLH extends ECashCra_Packet_UpdateCLH
{
	/**
	 * The type of packet
	 *
	 * @return string
	 */
	protected function getPacketType()
	{
		return 'cancel';
	}

	/**
	 * Creates the fund update specific part of the packet
	 *
	 * @param DOMDocument $xml
	 * @param DOMElement $data
	 * @return null
	 */
	protected function buildUpdateSection(DOMDocument $xml, DOMElement $data)
	{
		$data->appendChild($xml->createElement('CANCELLEDDATE', htmlentities($this->application->getCancelDate())));
	}
}
?>