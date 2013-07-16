<?php

/*

CAREFUL WHEN RUNNING
Take a VERY LONG TIME (Leave running overnight)

*/

require("../Volar.php");
require("../test_config.php");
class BasicBroadcastTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	
	function testBroadcastRetrieval()
	{

		//generates arguments based on an array of integers passed by the test. 
		//1s are invalid data, 2s are expected to work.
		//Zero means that the argument will not be passed
		function paramDemux($seed)
		{
			//Expected indicates whether or not the request is expected to succeed
			//Empty indicates that no sites are expected to be returned
			$gen_params = array("expected" => true, "empty" => false);
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
					$gen_params["list"] = "grocery";
					break;
				case 2:
					$gen_params["list"] = "archived";
					break;
			}
			switch ($seed[2])
			{
				case 0:
					break;
				case 1:
					$gen_params["per_page"] = -1;
					break;
				case 2:
					$gen_params["per_page"] = 25;
					break;
			}
			switch ($seed[3])
			{
				case 0:
					break;
				case 1:
					$gen_params["section_id"] = -1;
					$gen_params["empty"] = true;
					break;
				case 2:
					$gen_params["section_id"] = 1;
					break;
			}
			switch ($seed[4])
			{
				case 0:
					break;
				case 1:
					$gen_params["playlist_id"] = -1;
					$gen_params["empty"] = true;
					break;
				case 2:
					$gen_params["playlist_id"] = 1;
					break;
			}
			switch ($seed[5])
			{
				case 0:
					break;
				case 1:
					$gen_params["id"] = -1;
					$gen_params["empty"] = true;
					break;
				case 2:
					$gen_params["id"] = 1;
					break;
			}
			switch ($seed[6])
			{
				case 0:
					break;
				case 1:
					$gen_params["title"] = "nobroadcastshere";
					$gen_params["empty"] = true;
					break;
				case 2:
					$gen_params["title"] = "Kevin_Interrupt_Archive";
					break;
			}
			switch ($seed[7])
			{
				case 0:
					break;
				case 1:
					$gen_params["autoplay"] = false;
					break;
				case 2:
					$gen_params["title"] = "true";
					break;
			}
			switch ($seed[8])
			{
				case 0:
					break;
				case 1:
					$gen_params["embed_width"] = -1;
					break;
				case 2:
					$gen_params["embed_width"] = "700";
					break;
			}
			switch ($seed[9])
			{
				case 0:
					break;
				case 1:
					$gen_params["before"] = "the end of time";
					break;
				case 2:
					$gen_params["before"] = "12/12/2013";
					break;
			}
			switch ($seed[10])
			{
				case 0:
					break;
				case 1:
					$gen_params["after"] = "the big bang";
					break;
				case 2:
					$gen_params["after"] = "01/01/2013";
					break;
			}
			switch ($seed[11])
			{
				case 0:
					break;
				case 1:
					$gen_params["sort_by"] = "coolness";
					break;
				case 2:
					$gen_params["sort_by"] = "status";
					break;
			}
			switch ($seed[12])
			{
				case 0:
					break;
				case 1:
					$gen_params["sort_dir"] = "however YOU feel";
					break;
				case 2:
					$gen_params["sort_dir"] = "desc";
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
		//Sorry for the lack of indentation, didn't want this file to be a mile wide
		for($a = 0; $a <= 2; $a++)
		for($b = 0; $b <= 2; $b++)
		for($c = 0; $c <= 2; $c++)
		for($d = 0; $d <= 2; $d++)
		for($e = 0; $e <= 2; $e++)
		for($f = 0; $f <= 2; $f++)
		for($g = 0; $g <= 2; $g++)
		for($h = 0; $h <= 2; $h++)
		for($i = 0; $i <= 2; $i++)
		for($j = 0; $j <= 2; $j++)
		for($k = 0; $k <= 2; $k++)
		for($l = 0; $l <= 2; $l++)
		for($m = 0; $m <= 2; $m++)({
			$param_seed = array($a, $b, $c, $d, $e, $f, $g, $h, $i, $j);
			$test_case = paramDemux($param_seed);
			if($test_case["expected"])
			{

				unset($test_case["expected"]);
				if($test_case["empty"])
				{
					unset($test_case["empty"]);
					$result = $v->broadcasts($test_case);
					$this->assertEmpty($result["broadcasts"], "Failure on case: ".$a.$b.$c.$d.$e.$f.$g.$h.$i.$j);
				}
				else
				{
					unset($test_case["empty"]);
					$this->assertInternalType("array", $v->broadcasts($test_case), "Failure on case: ".$a.$b.$c.$d.$e.$f.$g.$h.$i.$j);
				}
			}
			else
			{
				unset($test_case["expected"]);
				unset($test_case["empty"]);
				$this->assertFalse($v->broadcasts($test_case), "Failure on case: ".$a.$b.$c.$d.$e.$f.$g.$h.$i.$j);
			}
		}
	}
	
}
?>
