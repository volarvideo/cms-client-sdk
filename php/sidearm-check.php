<?php

include('Volar.php');
define('VOLAR_SITE', 'gtest');
$v = new Volar('JdCa2JEA28l65YwQdwaYynm2sJVmO5gw', 'So?1cd~Df?uOS[8DIElBUQWdC{*es%[I', 'vcloud.volarvideo.com');

$d = new DateTime('+7 days', new DateTimezone('UTC'));
$create_data = array(
	'title' => 'Test create',
	'date' => $d->format('Y-m-d H:i:s'),
	'timezone' => 'America/New_York',
	'site' => VOLAR_SITE,
	'contact_name' => '-',
	'contact_email' => 't@t.com',
	'contact_phone' => 'phone',
);

$broadcast = $v->broadcast_create($create_data);
if(!$broadcast['success'])
{
	echo "Create failed: ";
	print_r($broadcast);
	exit;
}
echo "==================\nBroadcast created with information:\n";
print_r($broadcast);
echo "\n==========================\n";

$broadcasts = $v->broadcasts(array('site'=> VOLAR_SITE));
if(count($broadcasts['broadcasts']) == 0)
{
	echo "no broadcasts in list.  bailing.\n";
	exit;
}

foreach($broadcasts['broadcasts'] as $b)
{
	if($b['id'] == $broadcast['broadcast']['id'])
	{
		echo "==================\nBroadcast found in active list:\n";
		print_r($b);
		echo "\n==========================\n";
	}
}

$update = array(
	'id' => $broadcast['broadcast']['id'],
	'site' => $broadcast['broadcast']['site'],
	'title' => "Updated ".date("Y-m-d H:i:s T"),
);

$updated_b = $v->broadcast_update($update);
if(!$updated_b['success'])
{
	echo "Update failed: ";
	print_r($updated_b);
	exit;
}

echo "Pulling again...\n";
$broadcasts = $v->broadcasts(array('site'=> VOLAR_SITE));
if(count($broadcasts['broadcasts']) == 0)
{
	echo "no broadcasts in list.  bailing.\n";
	exit;
}

foreach($broadcasts['broadcasts'] as $b)
{
	if($b['id'] == $broadcast['broadcast']['id'])
	{
		echo "Broadcast found in active list after update.  Title = {$b['title']}, should be {$updated_b['broadcast']['title']}\n";

		if($b['title'] != $updated_b['broadcast']['title'])
		{
			echo "Title has not been updated\n";
		}
	}
}

$v->broadcast_delete(array('site' => VOLAR_SITE, 'id' => $broadcast['broadcast']['id']));
sleep(1);
$broadcasts = $v->broadcasts(array('site'=> VOLAR_SITE));
if(count($broadcasts['broadcasts']) == 0)
{
	echo "no broadcasts in list.  bailing.\n";
	exit;
}

foreach($broadcasts['broadcasts'] as $b)
{
	if($b['id'] == $broadcast['broadcast']['id'])
	{
		echo "==================\nBroadcast found in active list, even though it should have been deleted:\n";
		print_r($b);
		echo "\n==========================\n";
	}
}

?>