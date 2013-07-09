<?php
require('../Volar.php');
require('../test_config.php');
class AdvBroadcastTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	function testDefaultDataTypes()
	{		
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$result = $v->broadcasts();
		$this->assertInternalType("string", $result["list"], "Incorrect Type Returned for list");
		$this->assertInternalType("string", $result["item_count"], "Incorrect Type Returned for item_count");
		$this->assertInternalType("int", $result["per_page"], "Incorrect Type Returned for per_page");
		$this->assertInternalType("int", $result["page"], "Incorrect Type Returned for page");
		$this->assertInternalType("int", $result["num_pages"], "Incorrect Type Returned for num_pages");
		$this->assertInternalType("string", $result["autoplay"], "Incorrect Type Returned for autoplay");
		$this->assertInternalType("int", $result["embed_width"], "Incorrect Type Returned for embed_width");
		$this->assertInternalType("string", $result["sort_by"], "Incorrect Type Returned for sort_by");
		$this->assertInternalType("string", $result["sort_dir"], "Incorrect Type Returned for sort_dir");
		$this->assertInternalType("null", $result["id"], "Incorrect Type Returned for id");
		$this->assertInternalType("null", $result["title"], "Incorrect Type Returned for title");
		$this->assertInternalType("null", $result["section_id"], "Incorrect Type Returned for section_id");
		$this->assertInternalType("null", $result["playlist_id"], "Incorrect Type Returned for playlist_id");
		$this->assertInternalType("null", $result["before"], "Incorrect Type Returned for before");
		$this->assertInternalType("null", $result["after"], "Incorrect Type Returned for after");
		//$this->assertInternalType("null", $result["site"], "Incorrect Type Returned for site");
		$this->assertInternalType("array", $result["broadcasts"], "Incorrect Type Returned for broadcasts");
		$this->assertInternalType("string", $result["broadcasts"][0]["id"], "Incorrect Type Returned for broadcasts[id]");
		$this->assertInternalType("string", $result["broadcasts"][0]["section_id"], "Incorrect Type Returned for broadcasts[section_id]");
		$this->assertInternalType("string", $result["broadcasts"][0]["title"], "Incorrect Type Returned for broadcasts[title]");
		$this->assertInternalType("string", $result["broadcasts"][0]["description"], "Incorrect Type Returned for broadcasts[description]");
		$this->assertInternalType("string", $result["broadcasts"][0]["date"], "Incorrect Type Returned for broadcasts[date]");
		$this->assertInternalType("string", $result["broadcasts"][0]["status"], "Incorrect Type Returned for broadcasts[status]");
		$this->assertInternalType("string", $result["broadcasts"][0]["embed_code"], "Incorrect Type Returned for broadcasts[embed_code]");
		$this->assertInternalType("string", $result["broadcasts"][0]["large_image"], "Incorrect Type Returned for broadcasts[large_image]");
		$this->assertInternalType("string", $result["broadcasts"][0]["medium_image"], "Incorrect Type Returned for broadcasts[medium_image]");
		$this->assertInternalType("string", $result["broadcasts"][0]["small_image"], "Incorrect Type Returned for broadcasts[small_image]");
	}

	/**
	 * @depends testDefaultDataTypes
	 */
	function testReturnedData()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);

		$params = array("site" => "volar",
				"list" => "archived",
				"page" => 1,
				"per_page" => 30,
				"section_id" => 1,
				"playlist_id" => 1,
				"id" => 495,
				"title" => "volar_archive",
				"autoplay" => true,
				"embed_width" => 700,
				"before" => "12/12/2013",
				"after" => "03/03/2013",
				"sort_by" => "description",
				"sort_dir" => "asc"
				);
		$result = $v->broadcasts($params);
		print_r($result);
		$this->assertEquals("archived", $result["list"], "Incorrect Value Returned For list");
		$this->assertEquals(30, $result["per_page"], "Incorrect Value Returned For per_page");
		//$this->assertEquals(1, $result["page"], "Incorrect Value Returned For page");
		$this->assertEquals(1, $result["section_id"], "Incorrect Value Returned For section_id");
		$this->assertEquals(1, $result["playlist_id"], "Incorrect Value Returned For playlist_id");
		$this->assertEquals(495, $result["id"], "Incorrect Value Returned For id");
		$this->assertEquals("volar_archive", $result["title"], "Incorrect Value Returned For title");
		$this->assertEquals(1, $result["autoplay"], "Incorrect Value Returned For autoplay");
		$this->assertEquals(700, $result["embed_width"], "Incorrect Value Returned For embed_width");
		$this->assertEquals("2013-03-03 00:00:00", $result["after"], "Incorrect Value Returned For after");
		$this->assertEquals("2013-12-12 00:00:00",$result["before"], "Incorrect Value Returned For before");
		$this->assertEquals("volar", $result["site"], "Incorrect Value Returned For site");
	}

	function testResponseCorrectness()
        {

                $v = new Volar($this->api_key, $this->secret_key, $this->server);

                $result = $v->broadcasts(array("site" => "volar", "id" => 495));
                $this->assertCount(1, $result["broadcasts"], "Found multiple broadcasts using one id");

		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "id", "sort_dir" => "ASC"));
		$this->assertGreaterThanOrEqual($result["broadcasts"][0]["id"], $result["broadcasts"][1]["id"], 
			"Broadcasts returned out of order: id ASC");

		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "id", "sort_dir" => "DESC"));
		$this->assertLessThanOrEqual($result["broadcasts"][0]["id"], $result["broadcasts"][1]["id"], 
			"Broadcasts returned out of order: id DESC");


		
		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "title", "sort_dir" => "ASC"));
		$this->assertGreaterThanOrEqual($result["broadcasts"][0]["title"], $result["broadcasts"][1]["title"], 
			"Broadcasts returned out of order: title ASC");
		
		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "title", "sort_dir" => "DESC"));
		$this->assertLessThanOrEqual($result["broadcasts"][0]["title"], $result["broadcasts"][1]["title"],
			"Broadcasts returned out of order: title DESC");



		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "status", "sort_dir" => "ASC"));
		$this->assertGreaterThanOrEqual($result["broadcasts"][0]["status"], $result["broadcasts"][1]["status"], 
			"Broadcasts returned out of order: status ASC");

		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "status", "sort_dir" => "DESC"));
		$this->assertLessThanOrEqual($result["broadcasts"][0]["status"], $result["broadcasts"][1]["status"], 
			"Broadcasts returned out of order: status DESC");



		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "date", "sort_dir" => "ASC"));
		$this->assertGreaterThanOrEqual($result["broadcasts"][0]["date"], $result["broadcasts"][1]["date"], 
			"Broadcasts returned out of order: date ASC");
		
		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "date", "sort_dir" => "DESC"));
		$this->assertLessThanOrEqual($result["broadcasts"][0]["date"], $result["broadcasts"][1]["date"],
			 "Broadcasts returned out of order: date DESC");


	
		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "description", "sort_dir" => "ASC"));
		$this->assertGreaterThanOrEqual($result["broadcasts"][0]["description"], $result["broadcasts"][1]["description"], 
			"Broadcasts returned out of order: description ASC");

		$result = $v->broadcasts(array("site" => "volar", "sort_by" => "description", "sort_dir" => "DESC"));
		$this->assertLessThanOrEqual($result["broadcasts"][0]["description"], $result["broadcasts"][1]["description"], 
			"Broadcasts returned out of order: description DESC");

	}


	function testPerPageBounds()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("per_page" => -1);
		$result = $v->broadcasts($params);
		$this->assertGreaterThanOrEqual(0, count($result["broadcasts"]));
		
		$params["per_page"] = 1;
		$result = $v->broadcasts($params);
		$this->assertLessThanOrEqual(1, count($result["broadcasts"]));

		$params["per_page"] = 61;
		$result = $v->broadcasts($params);
		$this->assertLessThanOrEqual(50, count($result["broadcasts"]));
	}

	function testSearches()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("title" => "Kevin");
		$result = $v->broadcasts($params);
		$this->assertGreaterThan(0, count($result["broadcasts"]));
	}
}
?>
