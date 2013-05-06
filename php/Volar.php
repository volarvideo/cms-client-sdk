<?php

class Volar {
	public $api_key = null;
	public $secret = null;
	public $base_url = null;
	public $secure = false;
	public $debug = null;

	private $error = null;

	public function __construct($api_key, $secret, $base_url = 'vcloud.volarvideo.com')
	{
		$this->api_key = $api_key;
		$this->secret = $secret;
		$this->base_url = $base_url;
	}

	public function getError()
	{
		return $this->error;
	}

	/**
	 *	submits request to $base_url through $route
	 *	@param string 	$route		api uri path (not including base_url!)
	 *	@param string 	$type		type of request.  only GET and POST are supported.  if blank, GET is assumed
	 *	@param array 	$params		associative array containing the GET parameters for the request
	 *	@param mixed 	$post_body	either a string or an array for post requests.  only used if $type is POST.  if left null, an error will be returned
	 *	@return boolean indicating success or failure.  if failed, $volar->getError() can be used to get last error string
	 */
	public function request($route, $type = '', $params = array(), $post_body = null)
	{
		$type = strtoupper($type ? $type : 'GET');
		$params['api_key'] = $this->api_key;
		$signature = $this->buildSignature($route, $type, $params, $post_body);

		$url = ($this->secure ? 'https://' : 'http://').$this->base_url.'/'.trim($route, '/');
		$query_string = '';
		foreach($params as $key => $value)
		{
			$query_string .= ($query_string ? '&' : '?') .$key .'='. urlencode($value);
		}
		$query_string .= '&signature='.$signature;	//signature doesn't need to be urlencoded, as the buildSignature function does it for you.

		if(!$response = $this->execute($url.$query_string, $type, $post_body))
		{
			//error string should have already been set
			return false;
		}
		$this->debug = $url.$query_string."\n".$response;
		$json = json_decode($response, true);
		if(isset($json['error']) && !empty($json['error']))
		{
			$this->error = '('.$json['error']['code'].') '.$json['error']['message'];
			return false;
		}
		return $json;
	}

	/**
	 *	creates a signature
	 *	@param string $route		api uri path (not including base_url!)
	 *	@param string $type			type of request.  only GET and POST are supported.  if blank, GET is assumed
	 *	@param array $get_params	associative array containing the GET parameters for the request
	 *	@param mixed $post_body		either a string or an array for post requests.  only used if $type is POST, AND only used if a string
	 *	@return string urlencoded signature that should be used with requests
	 */
	public function buildSignature($route, $type = '', $get_params = array(), $post_body = '')
	{
		$type = strtoupper($type ? $type : 'GET');
		ksort($get_params);
		$stringToSign = $this->secret.$type.trim($route, '/');

		foreach($get_params as $key => $value)
		{
			$stringToSign .= $key.'='.$value;	//note that get_params are NOT urlencoded
		}

		if(!is_array($post_body))
			$stringToSign .= $post_body;

		$signature = base64_encode(hash('sha256', $stringToSign, true));
		$signature = urlencode(substr($signature, 0, 43));
		$signature = rtrim($signature, '=');

		return $signature;
	}

	public function execute($url, $type, $post_body, $content_type = '', $curl_options = array())
	{
		$type = strtoupper($type);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	//need the cURL request to come back with response so sdk code can handle it.
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);	//set request type
		if($content_type)
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $content_type);
		}
		if(is_array($post_body))
		{
			$post_fields = array();
			foreach($post_body as $key => $value)
			{
				$post_fields[] = $key.'='.urlencode($value);
			}
			$post_body = implode('&', $post_fields);
		}
		if(!empty($post_body) && $type == 'POST')
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
		}
		elseif($type == 'POST')	//$post_body is empty
		{
			$this->error = 'If type is POST, post_body is expected to be populated as an array or as a non-empty string';
			return false;
		}

		if(count($curl_options) > 0)
		{
			curl_setopt_array($ch, $curl_options);
		}

		$response = curl_exec($ch);
		if(!$response)
		{
			$error = curl_error($ch);
			curl_close($ch);
			$this->error = "cURL error: ".$error;
			return false;
		}

		curl_close($ch);

        return $response;
	}

}
?>