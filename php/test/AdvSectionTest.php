<?php
require('../Volar.php');
require('../test_config.php');
class AdvSectionTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	function testDefaultDataTypes()
        {
                $v = new Volar($this->api_key, $this->secret_key, $this->server);

                $result = $v->sections(array("site" => "volar"));
                $this->assertInternalType("string", $result["item_count"], "Incorrect Type Returned for item_count");
                $this->assertInternalType("int", $result["per_page"], "Incorrect Type Returned for per_page");
                $this->assertInternalType("int", $result["page"], "Incorrect Type Returned for page");
                $this->assertInternalType("int", $result["num_pages"], "Incorrect Type Returned for num_pages");
                $this->assertInternalType("string", $result["sort_by"], "Incorrect Type Returned for sort_by");
                $this->assertInternalType("string", $result["sort_dir"], "Incorrect Type Returned for sort_dir");
                $this->assertInternalType("null", $result["id"], "Incorrect Type Returned for id");
                $this->assertInternalType("null", $result["title"], "Incorrect Type Returned for title");
                $this->assertInternalType("null", $result["broadcast_id"], "Incorrect Type Returned for broadcast_id");
                $this->assertInternalType("null", $result["video_id"], "Incorrect Type Returned for video_id");
                $this->assertInternalType("string", $result["site"], "Incorrect Type Returned for site");
                $this->assertInternalType("array", $result["sections"], "Incorrect Type Returned for sections");
                $this->assertInternalType("string", $result["sections"][0]["id"], "Incorrect Type Returned for sections[id]");
                $this->assertInternalType("string", $result["sections"][0]["title"], "Incorrect Type Returned for sections[title]");
                $this->assertInternalType("string", $result["sections"][0]["description"], "Incorrect Type Returned for sections[description]");
        }

	/**
         * @depends testDefaultDataTypes
         */
        function testReturnedData()
        {
                $v = new Volar($this->api_key, $this->secret_key, $this->server);

                $params = array("site" => "volar",
                                "page" => 1,
                                "per_page" => 30,
                                "broadcast_id" => 495,
                                "video_id" => 1,
                                "id" => 4,
                                "title" => "Football",
                                "sort_by" => "id",
                                "sort_dir" => "asc"
                                );
                $result = $v->sections($params);

                $this->assertEquals(30, $result["per_page"], "Incorrect Value Returned For per_page");
                //$this->assertEquals(1, $result["page"], "Incorrect Value Returned For page");
                $this->assertEquals(495, $result["broadcast_id"], "Incorrect Value Returned For broadcast_id");
                $this->assertEquals(1, $result["video_id"], "Incorrect Value Returned For video_id");
                $this->assertEquals(4, $result["id"], "Incorrect Value Returned For id");
                $this->assertEquals("Football", $result["title"], "Incorrect Value Returned For title");
                $this->assertEquals("volar", $result["site"], "Incorrect Value Returned For site");
		$this->assertEquals("id", $result["sort_by"], "Incorrect Value Returned For sort_by");
		$this->assertEquals("ASC", $result["sort_dir"], "Incorrect Value Returned For sort_dir");
        }

	function testDataCorrectness()
	{
        $v = new Volar($this->api_key, $this->secret_key, $this->server);

		$result = $v->sections(array("site" => "volar", "id" => 1);
		$this->assertLessThanOrEqual(1, count($result["sections"]), "More than 1 section found with 1 id");

		$result = $v->sections(array("site" => "volar", "sort_by" => "id", "sort_dir" => "ASC"));
        $this->assertGreaterThanOrEqual($result["sections"][0]["id"], $result["sections"][1]["id"],
            "Sections returned out of order: id ASC");

        $result = $v->sections(array("site" => "volar", "sort_by" => "id", "sort_dir" => "DESC"));
        $this->assertLessThanOrEqual($result["sections"][0]["id"], $result["sections"][1]["id"], 
            "Sections returned out of order: id DESC");


                
        $result = $v->sections(array("site" => "volar", "sort_by" => "title", "sort_dir" => "ASC"));
        $this->assertGreaterThanOrEqual(strtolower($result["sections"][0]["title"]), strtolower($result["sections"][1]["title"]), 
            "Sections returned out of order: title ASC");
                
        $result = $v->sections(array("site" => "volar", "sort_by" => "title", "sort_dir" => "DESC"));
        print_r($result);
		$this->assertLessThanOrEqual(strtolower($result["sections"][0]["title"]), strtolower($result["sections"][1]["title"]),
            "Sections returned out of order: title DESC");
	}

	function testPerPageBounds()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("per_page" => -1, "site" => "volar");
		$result = $v->sections($params);
		$this->assertGreaterThanOrEqual(0, count($result["sections"]));
		
		$params["per_page"] = 1;
		$result = $v->sections($params);
		$this->assertLessThanOrEqual(1, count($result["sections"]));

		$params["per_page"] = 61;
		$result = $v->sections($params);
		$this->assertLessThanOrEqual(50, count($result["sections"]));
	}

	function testSearches()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("site" => "volar", "title" => "ball");
		$result = $v->sections($params);
		$this->assertGreaterThan(0, count($result["sections"]));
	}
}
?>
