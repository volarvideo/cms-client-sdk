<?php
require("../Volar.php");
require("../test_config.php");
class IndividualSectionTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	
	function testSectionRetrieval()
	{

		//generates arguments based on an array of integers passed by the test. 
		//1s are invalid data, 2s are expected to work.
		//Zero means that the argument will not be passed
		function paramDemux($seed)
		{
			//Expected indicates whether or not the request is expected to succeed
			//Empty indicates that no sites are expected to be returned
			$gen_params = array("expected" => true, "empty" => false);
			$gen_params["site"] = 'volar';
			switch ($seed[0])
			{
				/*case 0:
					$gen_params["expected"] = false;
					break;
				case 1:
					$gen_params["site"] = "notasite";
					$gen_params["expected"] = false;
					break;
				case 2:
					$gen_params["site"] = "volar";
					break;*/
			}
			switch ($seed[1])
			{
				case 0:
					break;
				case 1:
					$gen_params["page"] = -1;
					break;
				case 2:
					$gen_params["page"] = "1";
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
					$gen_params["broadcast_id"] = -1;
					$gen_params["empty"] = true;
					break;
				case 2:
					$gen_params["broadcast_id"] = 495;
					break;
			}
			switch ($seed[4])
			{
				case 0:
					break;
				case 1:
					$gen_params["video_id"] = -1;
					$gen_params["empty"] = true;
					break;
				case 2:
					$gen_params["video_id"] = 1;
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
					$gen_params["title"] = "Quidditch";
					$gen_params["empty"] = true;
					break;
				case 2:
					$gen_params["title"] = "Football";
					break;
			}
			switch ($seed[7])
			{
				case 0:
					break;
				case 1:
					$gen_params["sort_by"] = "length";
					break;
				case 2:
					$gen_params["sort_by"] = "id";
					break;
			}
			switch ($seed[8])
			{
				case 0:
					break;
				case 1:
					$gen_params["sort_dir"] = "north";
					break;
				case 2:
					$gen_params["sort_dir"] = "DESC";
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
		for($i = 0; $i <= 7; $i++)
			for($j = 0; $j <= 2; $j++){
				$param_seed = array();
				for($k = 0; $k <=7; $k++)
					$param_seed[$k] = 0;
				$param_seed[$i] = $j;
				$test_case = paramDemux($param_seed);
				if($test_case["expected"])
				{
					unset($test_case["expected"]);
					if($test_case["empty"])
					{
						unset($test_case["empty"]);
						$result = $v->broadcasts($test_case);
						$this->assertEmpty($result["sections"], "Failure on case: ".$i.", ".$j);
					}
					else
					{
						unset($test_case["empty"]);
						$this->assertInternalType("array", $v->sections($test_case), "Failure on case: ".$i.", ".$j);
					}
				}
				else
				{
					unset($test_case["expected"]);
					unset($test_case["empty"]);
					$this->assertFalse($v->sections($test_case), "Failure on case: ".$i.", ".$j);
				}
			}
	}
	
}
?>
