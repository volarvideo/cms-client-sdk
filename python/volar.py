import hashlib, base64, requests, json

class Volar(object):
	"""
	SDK for interfacing with the Volar cms.  Allows pulling of lists as well
	as manipulation of records.  Requires an api user to be set up.  All
	requests (with the exception of the Volar.sites call) requires the 'site'
	parameter, and 'site' much match the slug value of a site that the given
	api user has access to.  Programmers can use the Volar.sites call to get
	this information.
	depends on the requests module:
		http://docs.python-requests.org/en/latest/user/install/#install
	"""

	def __init__(self, api_key, secret, base_url):
		self.api_key = api_key
		self.secret = secret
		self.base_url = base_url
		self.secure = False
		self.error = ''

	def sites(self, params = {}):
		"""
		gets list of sites

		@param dict params
			- optional -
			'list' : type of list.  allowed values are 'all', 'archived',
				'scheduled' or 'upcoming', 'upcoming_or_streaming',
				'streaming' or 'live'
			'page': current page of listings.  pages begin at '1'
			'per_page' : number of broadcasts to display per page
			'section_id' : id of section you wish to limit list to
			'playlist_id' : id of playlist you wish to limit list to
			'id' : id of site - useful if you only want to get details
				of a single site
			'slug' : slug of site.  useful for searches, as this accepts
				incomplete titles and returns all matches.
			'title' : title of site.  useful for searches, as this accepts
				incomplete titles and returns all matches.
			'sort_by' : data field to use to sort.  allowed fields are date,
				status, id, title, description
			'sort_dir' : direction of sort.  allowed values are 'asc'
				(ascending) and 'desc' (descending)
		@return false on failure, dict on success.  if failed, Volar.error can
			be used to get last error string
		"""

		return self.request(route = 'api/client/info', method = 'GET', params = params)

	def broadcasts(self, params = {}):
		"""
		gets list of broadcasts

		@param dict params
			- required -
			'site' : slug of site to filter to.
			- optional -
			'list' : type of list.  allowed values are 'all', 'archived', 
				'scheduled' or 'upcoming', 'upcoming_or_streaming',
				'streaming' or 'live'
			'page' : current page of listings.  pages begin at '1'
			'per_page' : number of broadcasts to display per page
			'section_id' : id of section you wish to limit list to
			'playlist_id' : id of playlist you wish to limit list to
			'id' : id of broadcast - useful if you only want to get details
				of a single broadcast
			'title' : title of broadcast.  useful for searches, as this
				accepts incomplete titles and returns all matches.
			'autoplay' : true or false.  defaults to false.  used in embed
				code to prevent player from immediately playing
			'embed_width' : width (in pixels) that embed should be.  defaults
				to 640
			'sort_by' : data field to use to sort.  allowed fields are date,
				status, id, title, description
			'sort_dir' : direction of sort.  allowed values are 'asc'
				(ascending) and 'desc' (descending)
		@return false on failure, dict on success.  if failed, Volar.error can
			be used to get last error string
		"""

		if('site' not in params):
			self.error = 'site is required'
			return False
		return self.request(route = 'api/client/broadcast', params = params)

	def broadcast_create(self, params = {}):
		"""
		create a new broadcast

		@param dict params
			- required -
			'title' : title of the new broadcast
			- optional -
			'description' : HTML formatted description of the broadcast.
			'status' : currently only supports 'scheduled' & 'upcoming'
			'timezone' : timezone of given date.  only timezones listed
				on http://php.net/manual/en/timezones.php are supported.
				defaults to UTC
			'date' : date (string) of broadcast event.  will be converted
				to UTC if the given timezone is given.  note that if the
				system cannot read the date, or if it isn't supplied, it
				will default it to the current date & time.
			'section_id' : id of the section that this broadcast should
				be assigned.  the Volar.sections() call can give you a
				list of available sections.  Defaults to a 'General' section
		@return dict
			{
				'success' : True or False depending on success
				...
				if 'success' == True:
					'broadcast' : dict containing broadcast information,
						including id of new broadcast
				else:
					'errors' : list of errors to give reason(s) for failure
			}
		"""
		site = params.pop('site', None)
		if site == None:
			self.error = 'site is required'
			return False

		params = json.dumps(params)
		return self.request(route = 'api/client/broadcast/create', method = 'POST', params = { 'site' : site }, post_body = params)

	def broadcast_update(self, params = {}):
		"""
		update existing broadcast

		@param dict params
			- required -
			'id' : id of broadcast you wish to update
			- optional -
			'title' : title of the new broadcast.  if supplied, CANNOT be
				blank
			'description' : HTML formatted description of the broadcast.
			'status' : currently only supports 'scheduled' & 'upcoming'
			'timezone' : timezone of given date.  only timezones listed
				on http://php.net/manual/en/timezones.php are supported.
				defaults to UTC
			'date' : date (string) of broadcast event.  will be converted
				to UTC if the given timezone is given.  note that if the
				system cannot read the date, or if it isn't supplied, it
				will default it to the current date & time.
			'section_id' : id of the section that this broadcast should
				be assigned.  the Volar.sections() call can give you a
				list of available sections.  Defaults to a 'General' section
		@return dict
			{
				'success' : True or False depending on success
				if 'success' == True:
					'broadcast' : dict containing broadcast information,
						including id of new broadcast
				else:
					'errors' : list of errors to give reason(s) for failure
			}
		"""
		site = params.pop('site', None)
		if site == None:
			self.error = 'site is required'
			return False

		params = json.dumps(params)
		return self.request(route = 'api/client/broadcast/update', method = 'POST', params = { 'site' : site }, post_body = params)

	def broadcast_delete(self, params = {}):
		"""
		delete a broadcast

		the only parameter (aside from 'site') that this function takes is 'id'
		"""
		site = params.pop('site', None)
		if site == None:
			self.error = 'site is required'
			return False

		params = json.dumps(params)
		return self.request(route = 'api/client/broadcast/delete', method = 'POST', params = { 'site' : site }, post_body = params)

	def broadcast_assign_playlist(self, params = {}):
		"""
		assign a broadcast to a playlist

		@params dict params
			'id' : id of broadcast
			'playlist_id' : id of playlist
		@return dict { 'success' : True }
		"""
		if('site' not in params):
			self.error = 'site is required'
			return False
		return self.request(route = 'api/client/broadcast/assignplaylist', params = params)

	def broadcast_remove_playlist(self, params = {}):
		"""
		remove a broadcast from a playlist

		@params dict params
			'id' : id of broadcast
			'playlist_id' : id of playlist
		@return dict { 'success' : True }
		"""
		if('site' not in params):
			self.error = 'site is required'
			return False
		return self.request(route = 'api/client/broadcast/removeplaylist', params = params)

	def broadcast_poster(self, params = {}, image_data = ''):
		self.error  = 'todo'
		return False

	def broadcast_archive(self, params = {}, file_path = '', filename = ''):
		"""
		archives a broadcast.

		@params
			dict params
				'id' : id of broadcast
			string file_path
				if supplied, this file is uploaded to the server and attached
				to the broadcast
			string filename
				if supplied & file_path is also given, the uploaded file's
				name is reported to Volar as this filename.  used for easing
				file upload passthrus.  if not supplied, the filename from
				file_path is used.
		@return dict
			{
				'success' : True or False depending on success
				if 'success' == True:
					'fileinfo' : dict containing information about the
					uploaded file (if there was a file uploaded)
				else:
					'errors' : list of errors to give reason(s) for failure
			}
		"""
		if file_path == '':
			return self.request(route = 'api/client/broadcast/archive', method = 'GET', params = params)
		else:
			if filename != '':
				post = {'files' : { 'archive': (filename, open(file_path, 'rb'))}}
			else:
				post = {'files' : { 'archive': open(file_path, 'rb')}}
			return self.request(route = 'api/client/broadcast/archive', method = 'POST', params = params, post_body = post)

	def sections(self, params = {}):
		"""
		gets list of sections

		@param dict params
			- required -
			'site' : slug of site to filter to.
			- optional -
			'page' : current page of listings.  pages begin at '1'
			'per_page' : number of broadcasts to display per page
			'broadcast_id' : id of broadcast you wish to limit list to.
				will always return 1
			'video_id' : id of video you wish to limit list to.  will always
				return 1.  note this is not fully supported yet.
			'id' : id of section - useful if you only want to get details of
				a single section
			'title' : title of section.  useful for searches, as this accepts
				incomplete titles and returns all matches.
			'sort_by' : data field to use to sort.  allowed fields are id,
				title
			'sort_dir' : direction of sort.  allowed values are 'asc'
				(ascending) and 'desc' (descending)
		@return false on failure, dict on success.  if failed, Volar.error can
			be used to get last error string
		"""

		if('site' not in params):
			self.error = 'site is required'
			return False

		return self.request(route = 'api/client/section', params = params)

	def playlists(self, params = {}):
		"""
		gets list of playlists

		@param dict params
			- required -
			'site' : slug of site to filter to.
			- optional -
			'page' : current page of listings.  pages begin at '1'
			'per_page' : number of broadcasts to display per page
			'broadcast_id' : id of broadcast you wish to limit list to.
			'video_id' : id of video you wish to limit list to.  note this is
				not fully supported yet.
			'section_id' : id of section you wish to limit list to
			'id' : id of playlist - useful if you only want to get details of
				a single playlist
			'title' : title of playlist.  useful for searches, as this accepts
				incomplete titles and returns all matches.
			'sort_by' : data field to use to sort.  allowed fields are id,
				title
			'sort_dir' : direction of sort.  allowed values are 'asc'
				(ascending) and 'desc' (descending)
		@return false on failure, dict on success.  if failed, Volar.error can
			be used to get last error string
		"""

		if('site' not in params):
			self.error = 'site is required'
			return False

		return self.request(route = 'api/client/playlist', params = params)


	def request(self, route, method = '', params = {}, post_body = None):
		if method == '':
			method = 'GET'
		params['api_key'] = self.api_key
		signature = self.build_signature(route, method, params, post_body)
		params['signature'] = signature

		url = '/' + route.strip('/')

		if self.secure:
			url = 'https://' + self.base_url + url
		else:
			url = 'http://' + self.base_url + url

		try:
			if method == 'GET':
				r = requests.get(url, params = params)
			else:
				data = {}
				files = None
				# see if there's file data
				if post_body is not None:
					if type(post_body) is str:
						data = post_body
					elif type(post_body) is dict:
						for i in post_body:
							if i == 'files':
								files = post_body[i]	#files are sent multipart
							else:
								data[i] = post_body[i]

				if data == {}:	#no data
					data = None

				r = requests.post(url, params = params, data = data, files = files)
			return json.loads(r.text)
		except Exception as e:
			self.error = "Request failed with following error: " + e.message
			return False

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
		return signature
