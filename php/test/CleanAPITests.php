<?php

require('../Volar.php');
require('../test_config.php');
$server = VOLAR_BASE_URL;
$api_key = VOLAR_API_KEY;
$secret_key = VOLAR_SECRET_KEY;

$v = new Volar($api_key, $secret_key, $server);

/*foreach($argv as $argument)
{
$params = array("site" => "volar", "id" => $argument);
$result = $v->broadcast_delete($params);

if($result["success"])
	echo("Broadcast #".$argument." Deleted\n");
else
	echo("Failed to delete broadcast #".$argument."\n");
}*/
$params = array("site" => "volar", "title" => "api_test");
$result = $v->broadcasts($params);

foreach($result["broadcasts"] as $bcast)
{
	$delete_params = array("site" => "volar", "id" => $bcast["id"]);
	$result = $v->broadcast_delete($delete_params);
	if($result["success"])
		echo("Broadcast #".$bcast["id"]." Deleted\n");
	else
		echo("Failed to delete broadcast #".$bcast["id"]."\n");

}

$params = array("site" => "volar", "title" => "api_test_2");
$result = $v->broadcasts($params);

foreach($result["broadcasts"] as $bcast)
{
	$delete_params = array("site" => "volar", "id" => $bcast["id"]);
	$result = $v->broadcast_delete($delete_params);
	if($result["success"])
		echo("Broadcast #".$bcast["id"]." Deleted\n");
	else
		echo("Failed to delete broadcast #".$bcast["id"]."\n");

}

$params = array("site" => "volar", "title" => "api_test");
$result = $v->playlists($params);

foreach($result["playlists"] as $bcast)
{
	$delete_params = array("site" => "volar", "id" => $bcast["id"]);
	$result = $v->playlist_delete($delete_params);
	if($result["success"])
		echo("playlist #".$bcast["id"]." Deleted\n");
	else
		echo("Failed to delete playlist #".$bcast["id"]."\n");

}

$params = array("site" => "volar", "title" => "api_test_2");
$result = $v->playlists($params);

foreach($result["playlists"] as $bcast)
{
	$delete_params = array("site" => "volar", "id" => $bcast["id"]);
	$result = $v->playlist_delete($delete_params);
	if($result["success"])
		echo("Playlist #".$bcast["id"]." Deleted\n");
	else
		echo("Failed to delete playlist #".$bcast["id"]."\n");

}
?>
