<?php
include('Volar.php');
include('test_config.php');

$v = new Volar(VOLAR_API_KEY, VOLAR_SECRET_KEY, VOLAR_BASE_URL);

$endpoint = isset($_POST['endpoint']) ? $_POST['endpoint'] : 'api/client/broadcast';
$list_of = isset($_POST['list_of']) ? $_POST['list_of'] : '';
?>
<table width="100%" border="1">
	<tr>
		<td valign="top" width="50%">
			<div style="width:704px; height:675px; overflow:auto;">
<?php
if(isset($_POST['action']))
{
	$params = array();
	if(isset($_POST['params']) && is_array($_POST['params']))
	{
		foreach($_POST['params'] as $param_set)
		{
			if($param_set['key'])
				$params[$param_set['key']] = $param_set['value'];
		}
	}

	if($list_of)
	{
		switch($list_of)
		{
			case 'broadcast_create':
			case 'broadcast_update':
			case 'broadcast_delete':
			case 'playlist_create':
			case 'playlist_update':
			case 'playlist_delete':
			case 'template_create':
			case 'template_update':
			case 'template_delete':
				$res = $v->{$list_of}($_POST['post_params']);
			break;
			case 'broadcast_archive':
			case 'broadcast_poster':			
				if($_FILES['uploaded']['name'])
				{
					$res = $v->{$list_of}($params, $_FILES['uploaded']['tmp_name'], $_FILES['uploaded']['name']);
				}
			break;
			default:
				$res = $v->{$list_of}($params);
			break;
		}
	}
	else
		$res = $v->request($endpoint, 'GET', $params);
	if($v->debug)
	{
		echo "<div  style='font-size:10px; padding:4px; background:#eee; width:100%;'>".nl2br(htmlspecialchars($v->debug))."</div>";
	}

	if(!$res)
	{
		echo $v->getError();
	}
	else
	{
		echo '<pre>'.htmlspecialchars(print_r($res, true)).'</pre>';
	}
}

?>
			</div>
		</td>
		<td valign="top" width="50%">

<form method="POST" action="" enctype="multipart/form-data">
	<input type="hidden" value="true" name="action" />
	<select name="list_of">
		<option value="">--</option>
		<option<?php echo $list_of == 'sites' ? ' selected' : ''; ?> value="sites">Sites</option>
		<optgroup label="Broadcasts">
			<option<?php echo $list_of == 'broadcasts' ? ' selected' : ''; ?> value="broadcasts">List of Broadcasts</option>
			<option<?php echo $list_of == 'broadcast_create' ? ' selected' : ''; ?> value="broadcast_create">  Create Broadcast</option>
			<option<?php echo $list_of == 'broadcast_update' ? ' selected' : ''; ?> value="broadcast_update">  Update Broadcast</option>
			<option<?php echo $list_of == 'broadcast_delete' ? ' selected' : ''; ?> value="broadcast_delete">  Delete Broadcast</option>
			<option<?php echo $list_of == 'broadcast_poster' ? ' selected' : ''; ?> value="broadcast_poster">  Upload Broadcast Poster</option>
			<option<?php echo $list_of == 'broadcast_archive' ? ' selected' : ''; ?> value="broadcast_archive">  Archive Broadcast</option>
		</optgroup>
		<option<?php echo $list_of == 'videos' ? ' selected' : ''; ?> value="videos">Videos</option>
		<option<?php echo $list_of == 'sections' ? ' selected' : ''; ?> value="sections">Sections</option>
		<optgroup label="Playlists">
			<option<?php echo $list_of == 'playlists' ? ' selected' : ''; ?> value="playlists">List of Playlists</option>
			<option<?php echo $list_of == 'playlist_create' ? ' selected' : ''; ?> value="playlist_create">Create Playlist</option>
			<option<?php echo $list_of == 'playlist_update' ? ' selected' : ''; ?> value="playlist_update">Update Playlist</option>
			<option<?php echo $list_of == 'playlist_delete' ? ' selected' : ''; ?> value="playlist_delete">Delete Playlist</option>
		</option>
		<optgroup label="Templates">
			<option<?php echo $list_of == 'templates' ? ' selected' : ''; ?> value="templates">List of Templates</option>
			<option<?php echo $list_of == 'template_create' ? ' selected' : ''; ?> value="template_create">Create Template</option>
			<option<?php echo $list_of == 'template_update' ? ' selected' : ''; ?> value="template_update">Update Template</option>
			<option<?php echo $list_of == 'template_delete' ? ' selected' : ''; ?> value="template_delete">Delete Template</option>
		</option>
	</select>
	Endpoint:&nbsp;&nbsp;&nbsp;<input type="text" name="endpoint" value="<?php echo htmlspecialchars(stripslashes($endpoint)); ?>" style="width:300px;"><br /><br />
	<textarea name="post_params" style="width:478px; height:147px;"><?php echo isset($_POST['post_params']) ? htmlspecialchars(stripslashes($_POST['post_params'])) : ''; ?></textarea>
	<table width="500">
		<tr>
			<th>key</th>
			<th>value</th>
		</tr>
		<?php
		for($i = 0; $i < 10; $i++)
		{
			$k = isset($_POST['params'][$i]['key']) ? htmlspecialchars(stripslashes($_POST['params'][$i]['key'])) : '';
			$v = isset($_POST['params'][$i]['value']) ? htmlspecialchars(stripslashes($_POST['params'][$i]['value'])) : '';
			?>
			<tr>
				<td><input type="text" value="<?php echo $k; ?>" name="params[<?php echo $i; ?>][key]" /></td>
				<td><input type="text" value="<?php echo $v; ?>" name="params[<?php echo $i; ?>][value]" /></td>
			</tr>
			<?php
		}
		?>
	</table>
	File upload: <input type="file" name="uploaded" /><br /><br />
	<input type="submit" value="submit" />
</form>
		</td>
	</tr>
</table>