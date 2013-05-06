<?php
require('../Volar.php');
class ConnectInfoTest extends PHPUnit_Framework_TestCase {
	var $server = 'staging.platypusgranola.com';
	var $api_key = '8yBOLdFqu4tjyjY6Wjd2mwyjNIuC6jkW';
	var $secret_key = 'w}EzJJVC:SwJ_!zd%U:[IY<cCQh.|TRf';

	function testConnectSuccess()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		$result = $v->request('api/client/info');

		$this->assertInternalType('array', $result, var_export($result, true));
		$this->assertTrue($result['success']);
	}	

	function testConnectFailure()
	{
		//bad api key
		$v = new Volar('a'.$this->api_key, $this->secret_key, $this->server);
		$this->assertFalse($res = $v->request('api/client/info'));

		//bad secret key
		$v = new Volar($this->api_key,'a'. $this->secret_key, $this->server);
		$this->assertFalse($res = $v->request('api/client/info'));

		//bad host
		$v = new Volar($this->api_key, $this->secret_key, $this->server.'a');
		$this->assertFalse($res = $v->request('api/client/info'));

		//bad endpoint
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		$this->assertFalse($res = $v->request('api/client/blahblah'));

	}
}
?>
