<?php
include('Volar.php');
include('test_config.php');

header('Content-Type: text/plain');

$v = new Volar(VOLAR_API_KEY, VOLAR_SECRET_KEY, VOLAR_BASE_URL);

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
