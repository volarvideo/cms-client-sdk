<?php
require('../Volar.php');
require('../test_config.php');
class AdvPlaylistTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

        function testDefaultDataTypes()
        {
                $v = new Volar($this->api_key, $this->secret_key, $this->server);

                $result = $v->playlists();
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
                $this->assertInternalType("array", $result["playlists"], "Incorrect Type Returned for playlists");
                $this->assertInternalType("string", $result["playlists"][0]["id"], "Incorrect Type Returned for playlists[id]");
                $this->assertInternalType("string", $result["playlists"][0]["section_id"], "Incorrect Type Returned for playlists[section_id]");
                $this->assertInternalType("string", $result["playlists"][0]["title"], "Incorrect Type Returned for playlists[title]");
                $this->assertInternalType("string", $result["playlists"][0]["description"], "Incorrect Type Returned for playlists[description]");
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
                                "section_id" => 1,
                                "broadcast_id" => 1,
				"video_id" => 1,
                                "id" => 495,
                                "title" => "volar_archive",
                                "sort_by" => "description",
                                "sort_dir" => "asc"
                                );
                $result = $v->playlists($params);

                $this->assertEquals(30, $result["per_page"], "Incorrect Value Returned For per_page");
                //$this->assertEquals(1, $result["page"], "Incorrect Value Returned For page");
                $this->assertEquals(1, $result["section_id"], "Incorrect Value Returned For section_id");
                $this->assertEquals(1, $result["broadcast_id"], "Incorrect Value Returned For broadcast_id");
                $this->assertEquals(1, $result["video_id"], "Incorrect Value Returned For video_id");
                $this->assertEquals(495, $result["id"], "Incorrect Value Returned For id");
                $this->assertEquals("volar_archive", $result["title"], "Incorrect Value Returned For title");
                $this->assertEquals("volar", $result["site"], "Incorrect Value Returned For site");
        }

	function testResponseCorrectness()
	{
                $v = new Volar($this->api_key, $this->secret_key, $this->server);

		$result = $v->playlists(array("site" => "volar", "id" => 1));
		$this->assertLessThanOrEqual(1, count($result["playlists"]), "More than one playlist found with one id");

		$result = $v->playlists(array("site" => "volar", "sort_by" => "id", "sort_dir" => "ASC"));
                $this->assertGreaterThanOrEqual($result["playlists"][0]["id"], $result["playlists"][1]["id"],
                        "Broadcasts returned out of order: id ASC");

                $result = $v->playlists(array("site" => "volar", "sort_by" => "id", "sort_dir" => "DESC"));
                $this->assertLessThanOrEqual($result["playlists"][0]["id"], $result["playlists"][1]["id"], 
                        "Broadcasts returned out of order: id DESC");


                
                $result = $v->playlists(array("site" => "volar", "sort_by" => "title", "sort_dir" => "ASC"));
                $this->assertGreaterThanOrEqual(strtolower($result["playlists"][0]["title"]), $result["playlists"][1]["title"], 
                        "Broadcasts returned out of order: title ASC");
                
                $result = $v->playlists(array("site" => "volar", "sort_by" => "title", "sort_dir" => "DESC"));
                $this->assertLessThanOrEqual($result["playlists"][0]["title"], $result["playlists"][1]["title"],
                        "Broadcasts returned out of order: title DESC");
	}

	function testPerPageBounds()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("per_page" => -1, "site" => "volar");
		$result = $v->playlists($params);
		print_r($result);
		$this->assertGreaterThanOrEqual(0, count($result["playlists"]));
		
		$params["per_page"] = 1;
		$result = $v->playlists($params);
		$this->assertLessThanOrEqual(1, count($result["playlists"]));

		$params["per_page"] = 61;
		$result = $v->playlists($params);
		$this->assertLessThanOrEqual(50, count($result["playlists"]));
	}

	function testSearches()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("title" => "play");
		$result = $v->playlists($params);
		$this->assertGreaterThan(0, count($result["playlists"]));
	}
}
?>
