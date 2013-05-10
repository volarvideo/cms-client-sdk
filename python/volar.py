import hashlib, base64, urllib, httplib, json

class Volar(object):
	def __init__(self, api_key, secret, base_url):
		self.api_key = api_key
		self.secret = secret
		self.base_url = base_url
		self.secure = False
		self.error = ''

	"""
	gets list of sites
	@param dict params
		recognized keys in params:
			- optional -
			'list'				type of list.  allowed values are 'all', 'archived', 'scheduled' or 'upcoming', 'upcoming_or_streaming', 'streaming' or 'live'
			'page'				current page of listings.  pages begin at '1'
			'per_page'			number of broadcasts to display per page
			'section_id'		id of section you wish to limit list to
			'playlist_id'		id of playlist you wish to limit list to
			'id'				id of site - useful if you only want to get details of a single site
			'slug'				slug of site.  useful for searches, as this accepts incomplete titles and returns all matches.
			'title'				title of site.  useful for searches, as this accepts incomplete titles and returns all matches.
			'sort_by'			data field to use to sort.  allowed fields are date, status, id, title, description
			'sort_dir'			direction of sort.  allowed values are 'asc' (ascending) and 'desc' (descending)
	@return false on failure, dict on success.  if failed, Volar.error can be used to get last error string
	"""
	def sites(self, params = {}):
		return self.request(route = 'api/client/info', method = 'GET', params = params)

	"""
	gets list of broadcasts
	@param dict params
		recognized keys in params:
			- required -
			'site'				slug of site to filter to.
			- optional -
			'list'				type of list.  allowed values are 'all', 'archived', 'scheduled' or 'upcoming', 'upcoming_or_streaming', 'streaming' or 'live'
			'page'				current page of listings.  pages begin at '1'
			'per_page'			number of broadcasts to display per page
			'section_id'		id of section you wish to limit list to
			'playlist_id'		id of playlist you wish to limit list to
			'id'				id of broadcast - useful if you only want to get details of a single broadcast
			'title'				title of broadcast.  useful for searches, as this accepts incomplete titles and returns all matches.
			'autoplay'			true or false.  defaults to false.  used in embed code to prevent player from immediately playing
			'embed_width'		width (in pixels) that embed should be.  defaults to 640
			'sort_by'			data field to use to sort.  allowed fields are date, status, id, title, description
			'sort_dir'			direction of sort.  allowed values are 'asc' (ascending) and 'desc' (descending)
	@return false on failure, dict on success.  if failed, Volar.error can be used to get last error string
	"""
	def broadcasts(self, params = {}):
		if('site' not in params):
			self.error = 'site is required';
			return False;

		return self.request(route = 'api/client/broadcast', params = params)

	"""
	gets list of sections
	@param dict params
		recognized keys in params:
			- required -
			'site'				slug of site to filter to.
			- optional -
			'page'				current page of listings.  pages begin at '1'
			'per_page'			number of broadcasts to display per page
			'broadcast_id'		id of broadcast you wish to limit list to.  will always return 1
			'video_id'			id of video you wish to limit list to.  will always return 1.  note this is not fully supported yet.
			'id'				id of section - useful if you only want to get details of a single section
			'title'				title of section.  useful for searches, as this accepts incomplete titles and returns all matches.
			'sort_by'			data field to use to sort.  allowed fields are id, title
			'sort_dir'			direction of sort.  allowed values are 'asc' (ascending) and 'desc' (descending)
	@return false on failure, dict on success.  if failed, Volar.error can be used to get last error string
	"""
	def sections(self, params = {}):
		if('site' not in params):
			self.error = 'site is required';
			return False;

		return self.request(route = 'api/client/section', params = params)

	"""
	gets list of playlists
	@param dict params
		recognized keys in params:
			- required -
			'site'				slug of site to filter to.
			- optional -
			'page'				current page of listings.  pages begin at '1'
			'per_page'			number of broadcasts to display per page
			'broadcast_id'		id of broadcast you wish to limit list to.
			'video_id'			id of video you wish to limit list to.  note this is not fully supported yet.
			'section_id'		id of section you wish to limit list to
			'id'				id of playlist - useful if you only want to get details of a single playlist
			'title'				title of playlist.  useful for searches, as this accepts incomplete titles and returns all matches.
			'sort_by'			data field to use to sort.  allowed fields are id, title
			'sort_dir'			direction of sort.  allowed values are 'asc' (ascending) and 'desc' (descending)
	@return false on failure, dict on success.  if failed, Volar.error can be used to get last error string
	"""
	def playlists(self, params = {}):
		if('site' not in params):
			self.error = 'site is required';
			return False;

		return self.request(route = 'api/client/playlist', params = params)


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
