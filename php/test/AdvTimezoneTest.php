<?php
require('../Volar.php');
require('../test_config.php');
class AdvTimezoneTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	function testDefaultDataTypes()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$result = $v->timezones();
		$this->assertInternalType("array", $result["timezones"], "Incorrect Type Returned For timezones");
		$this->assertInternalType("string", $result["timezones"][0], "Incorrect Type Stored In timezones");
	}

	function testUTCCases()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("filter" => "America");
		$result = $v->timezones($params);
		$this->assertEquals("UTC", $result["timezones"][count($result["timezones"])-1], "UTC not in filtered set");
		
		$params = array("country" => "US");
		$result = $v->timezones($params);
		$this->assertNotEquals("UTC", $result["timezones"][count($result["timezones"])-1], "UTC in country set");

		$params = array("country" => "US", "filter" => "America");
		$result = $v->timezones($params);
		$this->assertNotEquals("UTC", $result["timezones"][count($result["timezones"])-1], "UTC in filtered country set");
	}
}
?>
