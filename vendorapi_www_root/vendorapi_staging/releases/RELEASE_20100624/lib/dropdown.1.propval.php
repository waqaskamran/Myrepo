<?php

# written by pizza for mortgage-related sites... 
# previously, and still, currently, we have giant chunks of:
#
# <select name="dropdown">
# <option value="someval" <?php echo ($_POST["dropdown"] == "someval" ? " selected" : ""); ? >>title
# ...
# </select>
#
# a quite shite state of affairs; working on changing it

require_once("dropdown.1.php");

class Dropdown_PropVal extends Dropdown
{

	var $init_propval = true;

	function Dropdown_States()
	{
		// call parent constructor
		parent::Dropdown();
		// set defaults
		$this->_init();
	}

	function _init()
	{
		if (false === $this->init_propval)
			return;
		$this->init_propval = false;
		parent::_init();
		$this->key_vals = array(
			"25000" => "less than $25,000"
			,"27500" => "$25,000 - $29,999"
			,"32500" => "$30,000 - $34,999"
			,"37500" => "$35,000 - $39,999"
			,"42500" => "$40,000 - $44,999"
			,"47500" => "$45,000 - $49,999"
			,"52500" => "$50,000 - $54,999"
			,"57500" => "$55,000 - $59,999"
			,"62500" => "$60,000 - $64,999"
			,"67500" => "$65,000 - $69,999"
			,"72500" => "$70,000 - $74,999"
			,"77500" => "$75,000 - $79,999"
			,"82500" => "$80,000 - $84,999"
			,"87500" => "$85,000 - $89,999"
			,"92500" => "$90,000 - $94,999"
			,"97500" => "$95,000 - $99,999"
			,"102500" => "$100,000 - $104,999"
			,"107500" => "$105,000 - $109,999"
			,"112500" => "$110,000 - $114,999"
			,"117500" => "$115,000 - $119,999"
			,"122500" => "$120,000 - $124,999"
			,"127500" => "$125,000 - $129,999"
			,"132500" => "$130,000 - $134,999"
			,"137500" => "$135,000 - $139,999"
			,"142500" => "$140,000 - $144,999"
			,"147500" => "$145,000 - $149,999"
			,"152500" => "$150,000 - $154,999"
			,"157500" => "$155,000 - $159,999"
			,"162500" => "$160,000 - $164,999"
			,"167500" => "$165,000 - $169,999"
			,"172500" => "$170,000 - $174,999"
			,"177500" => "$175,000 - $179,999"
			,"182500" => "$180,000 - $184,999"
			,"187500" => "$185,000 - $189,999"
			,"192500" => "$190,000 - $194,999"
			,"197500" => "$195,000 - $199,999"
			,"205000" => "$200,000 - $209,999"
			,"215000" => "$210,000 - $219,999"
			,"225000" => "$220,000 - $229,999"
			,"235000" => "$230,000 - $239,999"
			,"245000" => "$240,000 - $249,999"
			,"255000" => "$250,000 - $259,999"
			,"265000" => "$260,000 - $269,999"
			,"275000" => "$270,000 - $279,999"
			,"285000" => "$280,000 - $289,999"
			,"295000" => "$290,000 - $299,999"
			,"305000" => "$300,000 - $309,999"
			,"315000" => "$310,000 - $319,999"
			,"325000" => "$320,000 - $329,999"
			,"335000" => "$330,000 - $339,999"
			,"345000" => "$340,000 - $349,999"
			,"355000" => "$350,000 - $359,999"
			,"365000" => "$360,000 - $369,999"
			,"375000" => "$370,000 - $379,999"
			,"385000" => "$380,000 - $389,999"
			,"395000" => "$390,000 - $399,999"
			,"410000" => "$400,000 - $419,999"
			,"430000" => "$420,000 - $439,999"
			,"450000" => "$440,000 - $459,999"
			,"470000" => "$460,000 - $479,999"
			,"490000" => "$480,000 - $499,999"
			,"510000" => "$500,000 - $519,999"
			,"530000" => "$520,000 - $539,999"
			,"550000" => "$540,000 - $559,999"
			,"570000" => "$560,000 - $579,999"
			,"590000" => "$580,000 - $599,999"
			,"610000" => "$600,000 - $619,999"
			,"630000" => "$620,000 - $639,999"
			,"650000" => "$640,000 - $659,999"
			,"670000" => "$660,000 - $679,999"
			,"690000" => "$680,000 - $699,999"
			,"710000" => "$700,000 - $719,999"
			,"730000" => "$720,000 - $739,999"
			,"750000" => "$740,000 - $759,999"
			,"770000" => "$760,000 - $779,999"
			,"790000" => "$780,000 - $799,999"
			,"800000" => "$800,000 or more"
		);

	}

}

?>
