<?php
require('../Volar.php');
require('../test_config.php');
class AdvAccountInfoTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	function testDefaultDataTypes()
	{	
		$v = new Volar($this->api_key, $this->secret_key, $this->server);

		$result = $v->sites();
		$this->assertInternalType("string", $result["item_count"], "Incorrect Type Returned for item_count (should be string)");
		$this->assertInternalType("int", $result["page"], "Incorrect Type Returned for page (should be int)");
		$this->assertInternalType("int", $result["per_page"], "Incorrect Type Returned for per_page (should be int)");
		$this->assertInternalType("string", $result["sort_by"], "Incorrect Type Returned for sort_by (should be string)");
		$this->assertInternalType("string", $result["sort_dir"], "Incorrect Type Returned for sort_dir (should be string)");
		$this->assertInternalType("null", $result["id"], "Incorrect Type Returned for id (should be null)");
		$this->assertInternalType("null", $result["slug"], "Incorrect Type Returned for slug (should be null)");
		$this->assertInternalType("null", $result["title"], "Incorrect Type Returned for title (should be null)");
		$this->assertInternalType("array", $result["sites"], "Incorrect Type Returned for sites (should be array)");
		$this->assertInternalType("int", $result["sites"][0]["id"], "Incorrect Type Returned for sites[id] (should be int)");
		$this->assertInternalType("string", $result["sites"][0]["slug"], "Incorrect Type Returned for sites[slug] (should be string)");
		$this->assertInternalType("string", $result["sites"][0]["title"], "Incorrect Type Returned for sites[title] (should be string)");

	}

	/**
	 * @depends testDefaultDataTypes
	 */
	function testReturnedData()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params =array("page" => 0,
				"per_page" => 30,
				"sort_by" => "title",
				"sort_dir" => "DESC",
				"id" => 2,
				"slug" => "volar",
				"title" => "Volar Video"
				);
		$result = $v->sites($params);

		$this->assertEquals($params["page"], $result["page"], "Incorrect value returned for page");
		$this->assertEquals($params["per_page"], $result["per_page"], "Incorrect value returned for per_page");
		$this->assertEquals($params["sort_by"], $result["sort_by"], "Incorrect value returned for sort_by");
		$this->assertEquals($params["sort_dir"], $result["sort_dir"], "Incorrect value returned for sort_dir");
		$this->assertEquals($params["id"], $result["id"], "Incorrect value returned for id");
		$this->assertEquals($params["slug"], $result["slug"], "Incorrect value returned for slug");
		$this->assertEquals($params["title"], $result["title"], "Incorrect value returned for title");
	}

	function testResponseCorrectness()
	{
		
		$v = new Volar($this->api_key, $this->secret_key, $this->server);

		$result = $v->sites(array("id" => 1));
		$this->assertCount(1, $result["sites"], "Found multiple sites using one id");



		$result = $v->sites(array("site" => "volar", "sort_by" => "id", "sort_dir" => "ASC"));
                $this->assertGreaterThanOrEqual($result["sites"][0]["id"], $result["sites"][1]["id"],
                        "Sites returned out of order: id ASC");

                $result = $v->sites(array("site" => "volar", "sort_by" => "id", "sort_dir" => "DESC"));
                $this->assertLessThanOrEqual($result["sites"][0]["id"], $result["sites"][1]["id"], 
                        "Sites returned out of order: id DESC");


                
                $result = $v->sites(array("site" => "volar", "sort_by" => "title", "sort_dir" => "ASC"));
                $this->assertGreaterThanOrEqual(strtolower($result["sites"][0]["title"]), strtolower($result["sites"][1]["title"]), 
                        "Sites returned out of order: title ASC");
                
                $result = $v->sites(array("site" => "volar", "sort_by" => "title", "sort_dir" => "DESC"));
                $this->assertLessThanOrEqual(strtolower($result["sites"][0]["title"]), strtolower($result["sites"][1]["title"]),
                        "Sites returned out of order: title DESC");



                $result = $v->sites(array("site" => "volar", "sort_by" => "status", "sort_dir" => "ASC"));
                $this->assertGreaterThanOrEqual($result["sites"][0]["status"], $result["sites"][1]["status"], 
                        "Sites returned out of order: status ASC");

                $result = $v->sites(array("site" => "volar", "sort_by" => "status", "sort_dir" => "DESC"));
                $this->assertLessThanOrEqual($result["sites"][0]["status"], $result["sites"][1]["status"], 
                        "Sites returned out of order: status DESC");
	}

	function testPerPageBounds()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("per_page" => -1);
		$result = $v->sites($params);
		$this->assertGreaterThanOrEqual(0, count($result["sites"]), "per_page done goofed bad on this one");
		
		$params["per_page"] = 1;
		$result = $v->sites($params);
		$this->assertLessThanOrEqual(1, count($result["sites"]), "Page is too long, should be no longer than 1.");

		$params["per_page"] = 61;
		$result = $v->sites($params);
		$this->assertLessThanOrEqual(50, count($result["sites"]), "Page is too long, should be no longer than 50");
	}

	function testSearches()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("slug" => "vol");
		$result = $v->sites($params);
		//print_r($result);
		$this->assertGreaterThan(0, count($result["sites"]), "Unable to search by slug");

		$params = array("title" => "Vid");
		$result = $v->sites($params);
		//print_r($result);
		$this->assertGreaterThan(0, count($result["sites"]), "Unable to search by title");
	}
}
?>
