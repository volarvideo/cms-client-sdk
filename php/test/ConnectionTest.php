<?php
require('../Volar.php');
require('../test_config.php');
class ConnectionTest extends PHPUnit_Framework_TestCase {
	var $server = VOLAR_BASE_URL;
	var $api_key = VOLAR_API_KEY;
	var $secret_key = VOLAR_SECRET_KEY;

	function testConnectionSuccess()
	{
		$v = new Volar($this->api_key, $this->secret_key, $this->server);
		$this->assertInternalType('array', $v->sites());
		$this->assertInternalType('array', $v->timezones());
		$this->assertInternalType('array', $v->broadcasts(array("site" => "volar")));
		$this->assertInternalType('array', $v->sections(array("site" => "volar")));
		$this->assertInternalType('array', $v->playlists());
	}
	
	function testConnectionFailureSites()
	{
		$v = new Volar('a', $this->secret_key, $this->server);
		$this->assertFalse($v->sites());
		
		$v = new Volar($this->api_key, 'a', $this->server);
		$this->assertFalse($v->sites());

		$v = new Volar($this->api_key, $this->secret_key, 'a.com');
		$this->assertFalse($v->sites());
	}
	function testConnectionFailureTimezones()
	{
		$v = new Volar('a', $this->secret_key, $this->server);
		$this->assertFalse($v->timezones());
		
		$v = new Volar($this->api_key, 'a', $this->server);
		$this->assertFalse($v->timezones());

		$v = new Volar($this->api_key, $this->secret_key, 'a.com');
		$this->assertFalse($v->timezones());
	}
	function testConnectionFailureBroadcasts()
	{
		$v = new Volar('a', $this->secret_key, $this->server);
		$this->assertFalse($v->broadcasts());
		
		$v = new Volar($this->api_key, 'a', $this->server);
		$this->assertFalse($v->broadcasts());

		$v = new Volar($this->api_key, $this->secret_key, 'a.com');
		$this->assertFalse($v->broadcasts());
	}
	function testConnectionFailureSections()
	{
		$v = new Volar('a', $this->secret_key, $this->server);
		$this->assertFalse($v->sections(array("site" => "volar")));
		
		$v = new Volar($this->api_key, 'a', $this->server);
		$this->assertFalse($v->sections(array("site" => "volar")));

		$v = new Volar($this->api_key, $this->secret_key, 'a.com');
		$this->assertFalse($v->sections(array("site" => "volar")));
	}
	function testConnectionFailurePlaylists()
	{
		$v = new Volar('a', $this->secret_key, $this->server);
		$this->assertFalse($v->playlists());
		
		$v = new Volar($this->api_key, 'a', $this->server);
		$this->assertFalse($v->playlists());

		$v = new Volar($this->api_key, $this->secret_key, 'a.com');
		$this->assertFalse($v->playlists());
	}
}
?>
