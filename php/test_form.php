<?php
include('Volar.php');
$v = new Volar('934bf28ae6c5575b3bb6e3e94da47cde', 'JVJys$vZ-d8im2E:zLRO5UzWXd.A#V$i', 'local.platypus.com');
// $v = new Volar('8yBOLdFqu4tjyjY6Wjd2mwyjNIuC6jkW', 'w}EzJJVC:SwJ_!zd%U:[IY<cCQh.|TRf', 'staging.platypusgranola.com');
// $v = new Volar('eF3LHoviIC5K2q7LLRQI38APNiOfzqHV', 'qAd0XA-$O-|do}UJREeiz&)&ZpVzG5w(', 'vcloud.volarvideo.com');

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
				$res = $v->{$list_of}($_POST['post_params']);
			break;
			case 'broadcast_archive':
				if($_FILES['uploaded']['name'])
				{
					$res = $v->broadcast_archive($params, $_FILES['uploaded']['tmp_name'], $_FILES['uploaded']['name']);
				}
			break;
			case 'broadcast_poster':			
				if($_FILES['uploaded']['name'])
				{
					$params['file_name'] = $_FILES['uploaded']['name'];
					$res = $v->broadcast_poster($params, file_get_contents($_FILES['uploaded']['tmp_name']));
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
		<option<?php echo $list_of == 'broadcasts' ? ' selected' : ''; ?> value="broadcasts">Broadcasts</option>
		<option<?php echo $list_of == 'broadcast_create' ? ' selected' : ''; ?> value="broadcast_create">Create Broadcast</option>
		<option<?php echo $list_of == 'broadcast_update' ? ' selected' : ''; ?> value="broadcast_update">Update Broadcast</option>
		<option<?php echo $list_of == 'broadcast_delete' ? ' selected' : ''; ?> value="broadcast_delete">Delete Broadcast</option>
		<option<?php echo $list_of == 'broadcast_poster' ? ' selected' : ''; ?> value="broadcast_poster">Upload Broadcast Poster</option>
		<option<?php echo $list_of == 'broadcast_archive' ? ' selected' : ''; ?> value="broadcast_archive">Archive Broadcast</option>
		<option<?php echo $list_of == 'sections' ? ' selected' : ''; ?> value="sections">Sections</option>
		<option<?php echo $list_of == 'playlists' ? ' selected' : ''; ?> value="playlists">Playlists</option>
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