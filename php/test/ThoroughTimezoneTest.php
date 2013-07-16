<?php
require("../Volar.php");
require("../test_config.php");
class BasicTimezoneTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	
	function testBasicTimezoneRetrieval()
	{

		//generates arguments based on an array of integers passed by the test. 
		//1s are invalid data, 2s are expected to work.
		//Zero means that the argument will not be passed
		function paramDemux($seed)
		{
			//Expected indicates whether or not the request is expected to succeed
			//Empty indicates that no results are expected to be returned
			//UTC indicates that UTC is the only expected timezone to be returned
			$gen_params = array("expected" => true, "empty" => false, "utc" => false);
			switch ($seed[0])
			{
				case 0:
					break;
				case 1:
					$gen_params["site"] = "notasite";
					$gen_params["expected"] = false;
					break;
				case 2:
					$gen_params["site"] = "volar";
					break;
			}
			switch ($seed[1])
			{
				case 0:
					break;
				case 1:
					$gen_params["filter"] = "Tatooine";
					$gen_params["utc"] = true;
					break;
				case 2:
					$gen_params["filter"] = "America";
					break;
			}
			switch ($seed[2])
			{
				case 0:
					break;
				case 1:
					$gen_params["country"] = "ZZ";
					$gen_params["utc"] = false;
					break;
				case 2:
					$gen_params["country"] = "US";
					$gen_params["utc"] = false;
					break;
			}
			return $gen_params;
		}

		//connection examples
		//$v = new Volar($this->api_key, $this->secret_key, $this->server);
		//$this->assertFalse($v->sites());
		//$this->assertInternalType("array", $v->sites());

		$v = new Volar($this->api_key, $this->secret_key, $this->server);

		//generate argument types. 0 = empty, 1 = invalid, 2 = valid
		//
		//Sorry for the lack of indentation, didn"t want this file to be a mile wide
		for($a = 0; $a <= 2; $a++)
		for($b = 0; $b <= 2; $b++)
		for($c = 0; $c <= 2; $c++){
			$param_seed = array($a, $b, $c);
			$test_case = paramDemux($param_seed);
			if($test_case["expected"])
			{
				unset($test_case["expected"]);
				if($test_case["empty"])
				{
					unset($test_case["empty"]);
					unset($test_case["utc"]);
					$result = $v->timezones($test_case);
					$this->assertEmpty($result["timezones"], "Failure on case: ".$a.$b.$c);
				}
				elseif($test_case["utc"])
				{
					unset($test_case["empty"]);
					unset($test_case["utc"]);
					$result = $v->timezones($test_case);
					$this->assertEquals(1, count($result["timezones"]), "Failure on case: ".$a.$b.$c);
				}
				else
				{
					unset($test_case["empty"]);
					unset($test_case["utc"]);
					$this->assertInternalType("array", $v->timezones($test_case), "Failure on case: ".$a.$b.$c);
				}
			}
			else
			{
				unset($test_case["expected"]);
				unset($test_case["empty"]);
				unset($test_case["utc"]);
				$this->assertFalse($v->timezones($test_case), "Failure on case: ".$a.$b.$c);
			}
		}
	}
	
}
?>
