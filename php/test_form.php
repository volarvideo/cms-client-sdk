<?php
include('Volar.php');
$v = new Volar('934bf28ae6c5575b3bb6e3e94da47cde', 'JVJys$vZ-d8im2E:zLRO5UzWXd.A#V$i', 'local.platypus.com');

$endpoint = isset($_POST['endpoint']) ? $_POST['endpoint'] : 'api/client/broadcast';

?>
<table width="100%" border="1">
	<tr>
		<td valign="top" width="50%">
			<div style="width:704px; height:675px; overflow:auto;">
<?php
if(isset($_POST['params']) && is_array($_POST['params']))
{
	$params = array();
	foreach($_POST['params'] as $param_set)
	{
		if($param_set['key'])
			$params[$param_set['key']] = $param_set['value'];
	}

	if(count($params))
	{
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
}

?>
			</div>
		</td>
		<td valign="top" width="50%">

<form method="POST" action="">
	Endpoint:&nbsp;&nbsp;&nbsp;<input type="text" name="endpoint" value="<?php echo htmlspecialchars(stripslashes($endpoint)); ?>" style="width:300px;"><br /><br />
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
	<input type="submit" value="submit" />
</form>
		</td>
	</tr>
</table>