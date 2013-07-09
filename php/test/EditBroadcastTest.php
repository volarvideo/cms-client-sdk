<?php

/*

Tests creation, editing, and deletion of broadcasts through the VolarVideo CMS API
Note: Any failed tests results in an upcoming api_test broadcast on the CMS
use KillBroadcast.php to remove them

*/

require('../Volar.php');
require('../test_config.php');
class EditBroadcastTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	//change to false to save all generated broadcasts after testing
	var $DELETE_BROADCASTS = true;
	//File path for uploaded image
	var $POSTER_PATH = "/home/volar/Desktop/cms-client-sdk/php/test/pulp_fiction_lrg.jpg";

	function testBroadcastCreateAndDelete()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		/*$params = array();
		$result = $v->broadcast_create($params);
		$this->assertFalse($result["success"]);
		*/
		$params["site"] = "volar";
		$result = $v->broadcast_create($params);
		$this->assertFalse($result["success"]);

		//$params["site"] = "";
		$params["title"] = "api_test";
		//$result = $v->broadcast_create($params);
		//$this->assertFalse($result["success"]);
		
		$params["site"] = "volar";
		$result = $v->broadcast_create($params);
		$this->assertTrue($result["success"]);

		$broadcast_details = $result["broadcast"];

		/*$params = array();
		$result = $v->broadcast_delete($params);
		$this->assertFalse($result["success"]);*/

		/*$params["site"] = "volar";
		$result = $v->broadcast_delete($params);
		$this->assertFalse($result["success"]);*/

		//unset($params["site"]);
		$params["id"] = $broadcast_details["id"];
		//$result = $v->broadcast_delete($params);
		//$this->assertFalse($result["success"]);
		
		$params["site"] = "volar";
		$result = $v->broadcast_delete($params);
		$this->assertTrue($result["success"]);
	}

	function testBroadcastUpdate()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("site" => "volar", "title" => "api_test");
		$result = $v->broadcast_create($params);
		$this->assertTrue($result["success"]);

		$broadcast_details = $result["broadcast"];
		
		$update_params = array("site" => "volar", "id" => $broadcast_details["id"], "title" => "api_test_2");
		$result = $v->broadcast_update($update_params);
		print_r($result);
		$this->assertTrue($result["success"], "Title Update Failed");
		$this->assertEquals($update_params["title"], $result["title"], "Title Not Updated");

		$update_params["date"] = "03/03/2013";
		$result = $v->broadcast_update($update_params);
		$this->assertTrue($result["success"], "Date Update Failed");
		$this->assertEquals($update_params["date"], $result["date"], "Date Not Updated");

		$update_params["description"] = "API Broadcast Testing";
		$result = $v->broadcast_update($update_params);
		$this->assertTrue($result["success"], "Description Update Failed");
		$this->assertEquals($update_params["description"], $result["description"], "Descrption Not Updated");

		$update_params["section_id"] = 2;
		$result = $v->broadcast_update($update_params);
		$this->assertTrue($result["success"], "Section Update Failed");
		$this->assertEquals($update_params["section_id"], $result["section_id"], "Section ID Not Updated");

		if($DELETE_BROADCASTS)
		{
			$params["id"] = $broadcast_details["id"];
			$params["site"] = "volar";
			$result = $v->broadcast_delete($params);
			$this->assertTrue($result["success"], "Broadcast Deletion Failed");
		}
	}

	function testBroadcastArchive()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);

		$params = array("site" => "volar", "title" => "api_test");
		$result = $v->broadcast_create($params);
		$this->assertTrue($result["success"]);

		$broadcast_details = $result["broadcast"];

		$update_params = array("site" => "volar", "id" => $broadcast_details["id"]);
		$result = $v->broadcast_archive($update_params);
		$this->assertTrue($result["success"], "Archival Failed");

		if($DELETE_BROADCASTS)
		{
			$params["id"] = $broadcast_details["id"];
			$params["site"] = "volar";
			$result = $v->broadcast_delete($params);
			$this->assertTrue($result["success"], "Broadcast Deletion Failed");
		}
	}

	function testPlaylistAssignment()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("site" => "volar", "title" => "api_test");
		$result = $v->broadcast_create($params);
		$this->assertTrue($result["success"], "Broadcast Creation Failed");

		$broadcast_details = $result["broadcast"];

		$playlist_params = array("site" => "volar", 
					"id" => $broadcast_details["id"], 
					"playlist_id" => 1);
		$result = $v->broadcast_assign_playlist($playlist_params);
		$this->assertTrue($result["success"], "Playlist Assignment 1 Failed");

		$playlist_params["playlist_id"] = 4;
		$result = $v->broadcast_assign_playlist($playlist_params);
		$this->assertTrue($result["success"], "Playlist Assignment 2 Failed");

		$get_list_params = array("site" => "volar", "broadcast_id" => $broadcast_details["id"]);
		$result = $v->playlists($get_list_params);
		$this->assertCount(2, $result["playlists"], "Broadcast not added to playlists");

		$result = $v->broadcast_remove_playlist($playlist_params);
		$this->assertTrue($result["success"], "Failure to Remove Broadcast from Playlist 2");
		$playlist_params["playlist_id"] = 1;
		$result = $v->broadcast_remove_playlist($playlist_params);
		$this->assertTrue($result["success"], "Failure to Remove Broadcast from Playlist 1");
		
		$result = $v->playlists($get_list_params);
		$this->assertCount(0, $result["playlists"], "Broadcast not removed from playlists");

		if($DELETE_BROADCASTS)
		{
			$params["id"] = $broadcast_details["id"];
			$params["site"] = "volar";
			$result = $v->broadcast_delete($params);
			$this->assertTrue($result["success"], "Broadcast Deletion Failed");
		}
	}

	function testImageUpload()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		
		$params = array("site" => "volar", "title" => "api_test");
		$result = $v->broadcast_create($params);
		$this->assertTrue($result["success"], "Broadcast Creation Failed");

		$broadcast_details = $result["broadcast"];

		$image_params = array("site" => "volar", "id" => $broadcast_details["id"]);
		$result = $v->broadcast_poster($image_params, $POSTER_PATH, "pulpfiction.jpg");
		$this->assertTrue($result["success"], "Image Upload Failed");
		
		if($DELETE_BROADCASTS)
		{
			$params["id"] = $broadcast_details["id"];
			$params["site"] = "volar";
			$result = $v->broadcast_delete($params);
			$this->assertTrue($result["success"], "Broadcast Deletion Failed");
		}
	}
}
?>
