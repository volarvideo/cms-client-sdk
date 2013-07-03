<?php

/*

Tests creation, editing, and deletion of playlists through the VolarVideo CMS API
Note: Any failed tests results in an upcoming api_test playlist on the CMS
use Killplaylist.php to remove them

*/

require('../Volar.php');
require('../test_config.php');
class EditPlaylistTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	//change to false to save all generated playlists after testing
	var $DELETE_PLAYLISTS = true;

	function testPlaylistCreateAndDelete()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		/*$params = array();
		$result = $v->playlist_create($params);
		$this->assertFalse($result["success"]);
		*/
		$params["site"] = "volar";
		$result = $v->playlist_create($params);
		$this->assertFalse($result["success"]);

		//$params["site"] = "";
		$params["title"] = "api_test";
		//$result = $v->playlist_create($params);
		//$this->assertFalse($result["success"]);
		
		$params["site"] = "volar";
		$result = $v->playlist_create($params);
		$this->assertTrue($result["success"]);

		$playlist_details = $result["playlist"];

		/*$params = array();
		$result = $v->playlist_delete($params);
		$this->assertFalse($result["success"]);*/

		/*$params["site"] = "volar";
		$result = $v->playlist_delete($params);
		$this->assertFalse($result["success"]);*/

		//unset($params["site"]);
		$delete_params["id"] = $playlist_details["id"];
		//$result = $v->playlist_delete($params);
		//$this->assertFalse($result["success"]);
		
		$delete_params["site"] = "volar";
		$result = $v->playlist_delete($delete_params);
		$this->assertTrue($result["success"]);
	}

	function testPlaylistUpdate()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("site" => "volar", "title" => "api_test");
		$result = $v->playlist_create($params);
		$this->assertTrue($result["success"]);

		$playlist_details = $result["playlist"];
		
		$update_params = array("site" => "volar", "id" => $playlist_details["id"], "title" => "api_test_2");
		$result = $v->playlist_update($update_params);
		print_r($result);
		$this->assertTrue($result["success"], "Title Update Failed");
		$this->assertEquals($update_params["title"], $result["title"], "Title Not Updated");

		$update_params["available"] = "no";
		$result = $v->playlist_update($update_params);
		$this->assertTrue($result["success"], "Availablity Update Failed");

		$update_params["description"] = "API Playlist Testing";
		$result = $v->playlist_update($update_params);
		$this->assertTrue($result["success"], "Description Update Failed");
		$this->assertEquals($update_params["description"], $result["description"], "Descrption Not Updated");

		$update_params["section_id"] = 2;
		$result = $v->playlist_update($update_params);
		$this->assertTrue($result["success"], "Section Update Failed");
		$this->assertEquals($update_params["section_id"], $result["section_id"], "Section ID Not Updated");

		if($DELETE_PLAYLISTS)
		{
			$delete_params["id"] = $playlist_details["id"];
			$delete_params["site"] = "volar";
			$result = $v->playlist_delete($delete_params);
			$this->assertTrue($result["success"], "Playlist Deletion Failed");
		}
	}
}
?>
