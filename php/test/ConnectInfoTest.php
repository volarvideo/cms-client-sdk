<?php
require('../Volar.php');
require('../test_config.php');
class ConnectInfoTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

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
