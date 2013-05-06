import hashlib, base64, urllib, httplib, json

class Volar(object):
	def __init__(self, api_key, secret, base_url):
		self.api_key = api_key
		self.secret = secret
		self.base_url = base_url
		self.secure = False
		self.error = ''

	def request(self, route, method = '', params = {}, post_body = None):
		if method == '':
			method = 'GET'
		params['api_key'] = self.api_key
		signature = self.build_signature(route, method, params, post_body)

		url = '/' + route.strip('/');
		query_string = '';
		for key, value in params.iteritems():
			query_string += ('?' if query_string == '' else '&') + key + '=' + urllib.quote_plus(value);

		query_string += '&signature=' + signature;	#signature doesn't need to be urlencoded, as the buildSignature function does it for you.
		url += query_string

		if self.secure:
			http_connection = httplib.HTTPSConnection(self.base_url)
		else:
			http_connection = httplib.HTTPConnection(self.base_url)

		headers = {}
		if post_body is None:
			headers['Content-Length'] = 0

		http_connection.request(method, url, post_body, headers)

		response = http_connection.getresponse()
		data = response.read()
		http_connection.close()

		if response.status >= httplib.BAD_REQUEST:
			self.error = "Request failed with error code " + str(response.status)
			return False
		else:
			return json.loads(data)

	def build_signature(self, route, method = '', get_params = {}, post_body = None):
		if method == '':
			method = 'GET'

		signature = str(self.secret) + method.upper() + route.strip('/')

		for key, value in sorted(get_params.iteritems()):
			signature += key + '=' + str(value)

		signature = signature.encode('ascii')
		if type(post_body) is str:
			signature += post_body

		signature = base64.b64encode(hashlib.sha256(signature).digest())[0:43]
		signature = signature.rstrip('=')
		return urllib.quote_plus(signature)
