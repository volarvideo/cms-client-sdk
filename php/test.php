<?php
include('Volar.php');

header('Content-Type: text/plain');


//$v = new Volar('934bf28ae6c5575b3bb6e3e94da47cde', 'JVJys$vZ-d8im2E:zLRO5UzWXd.A#V$i', 'local.platypus.com');
$v = new Volar('eaZjI371TFPdFj2XmZMB7wYDxlVzwX1w', '}*wqFSun%&6l1%n457\mHh^PKCBI*V?3', 'master.platypusgranola.com');

echo $v->buildSignature('api/client/info')."\n";
if(!$res = $v->request('api/client/info'))
{
	echo $v->getError()."\n";
}
else
{
	foreach($res['sites'] as $site)
	{
		echo "=============\n";
		if(!$list = $v->request('api/client/broadcast/index', 'get', array('site' => $site['slug'])))
		{
			echo $v->getError()."\n";
		}
		else
		{
			var_dump($list);
		}

		echo "=============\n";
			var_dump($v->debug);
		echo "\n=============\n";
	}
}




// echo "=============\n";
// 	var_dump($v->debug);
// echo "\n=============\n";

?>