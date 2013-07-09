import volar, pprint, ConfigParser, unittest


class TestAdvPlaylist(unittest.TestCase):
	"""
	Validates the playlist data returned via the volar.playlists() function for type and expected value.
	Also tests searching, sorting, and the bounds of pages
	"""

	def setUp(self):
		# load settings
		c = ConfigParser.ConfigParser()
		c.read('sample.cfg')	#note that self file is only for use with self script.  however, you can copy its contents and self code to use in your own scripts
		base_url = c.get('settings','base_url')
		api_key = c.get('settings','api_key')
		secret = c.get('settings','secret')
		self.v = volar.Volar(base_url = base_url, api_key = api_key, secret = secret)


	#Tests if all response data is producing the correct data types
	def test_DefaultDataTypes(self):
		response = self.v.playlists({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To playlists Failed')

		self.assertTrue(isinstance(response['item_count'], basestring), "Incorrect Type Returned for item_count (should be basestring)")
		self.assertTrue(isinstance(response['page'], int), "Incorrect Type Returned for page (should be int)")
		self.assertTrue(isinstance(response['per_page'], int), "Incorrect Type Returned for per_page (should be int)")
		self.assertTrue(isinstance(response['num_pages'], int), "Incorrect Type Returned for num_pages (should be int)")
		self.assertTrue(isinstance(response['sort_by'], basestring), "Incorrect Type Returned for sort_by (should be basestring)")
		self.assertTrue(isinstance(response['sort_dir'], basestring), "Incorrect Type Returned for sort_dir (should be basestring)")
		self.assertTrue(response['id'] == None, "Incorrect Type Returned for id (should be None)")
		self.assertTrue(response['broadcast_id'] == None, "Incorrect Type Returned for playlist_id (should be None)")
		self.assertTrue(response['title'] == None, "Incorrect Type Returned for title (should be None)")
		self.assertTrue(response['video_id'] == None, "Incorrect Type Returned for playlist_id (should be None)")
		self.assertTrue(isinstance(response['site'], basestring), "Incorrect Type Returned for site (should be basestring)")
		self.assertTrue(isinstance(response['playlists'][0]['id'], basestring), "Incorrect Type Returned for playlists[id] (should be int)")
		self.assertTrue(isinstance(response['playlists'][0]['section_id'], basestring), "Incorrect Type Returned for playlists[section_id] (should be int)")
		self.assertTrue(isinstance(response['playlists'][0]['title'], basestring), "Incorrect Type Returned for playlists[title] (should be basestring)")
		self.assertTrue(isinstance(response['playlists'][0]['description'], basestring), "Incorrect Type Returned for playlists[description] (should be basestring)")


	#Tests if the response is returning the correct data when modified by params
	def test_ReturnedData(self):
		params = ({'site': 'volar', 'page': 1, 'per_page': 30, 'broadcast_id': 495, 'section_id': 1, 'video_id': 1,
			'sort_by': 'id', 'sort_dir': 'DESC', 'title': 'volar_archive', 'id': 1})

		response = self.v.playlists({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To Playlists Failed')

		response = self.v.playlists({'site': 'volar', 'id': 1})
		self.assertTrue(len(response['playlists']) <= 1, 'Found multiple sites with one id')

		response = self.v.playlists(params)
		#self.assertEqual(params['page'], response['page'], 'Incorrect Value Returned for page')
		self.assertEqual(str(params['per_page']), str(response['per_page']), 'Incorrect Value Returned for per_page')
		self.assertEqual(params['sort_by'], response['sort_by'], 'Incorrect Value Returned for sort_by')
		self.assertEqual(params['sort_dir'], response['sort_dir'], 'Incorrect Value Returned for sort_dir')
		self.assertEqual(str(params['id']), str(response['id']), 'Incorrect Value Returned for id')
		self.assertEqual(str(params['broadcast_id']), str(response['broadcast_id']), 'Incorrect Value Returned for broadcast_id')
		self.assertEqual(str(params['section_id']), str(response['section_id']), 'Incorrect Value Returned for section_id')
		self.assertEqual(str(params['video_id']), str(response['video_id']), 'Incorrect Value Returned for video_id')
		self.assertEqual(params['title'], response['title'], 'Incorrect Value Returned for title')


	#Tests to make sure that ids are unique and that sorting is working correctly
	def test_ResponseCorrectness(self):
		#response = self.v.sites()
		#self.assertTrue(response != False, 'Connection To Sites Failed')
		#site_slug = response['sites'][0]['slug']

		response = self.v.playlists({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To Playlists Failed')

		response = self.v.playlists({'site': 'volar', 'id': 1})
		self.assertTrue(len(response['playlists']) <= 1, 'Found multiple playlists with one id')

		response = self.v.playlists({'site': 'volar', 'sort_by': 'id', 'sort_dir': 'ASC'})
		if len(response['playlists']) > 1:
			self.assertTrue(response['playlists'][0]['id'] <= response['playlists'][1]['id'], 'Playlists Returned Out Of Order: id ASC')
			response = self.v.playlists({'site': 'volar', 'sort_by': 'id', 'sort_dir': 'DESC'})
			self.assertTrue(response['playlists'][0]['id'] >= response['playlists'][1]['id'], 'Playlists Returned Out Of Order: id DESC')
		
			response = self.v.playlists({'site': 'volar', 'sort_by': 'title', 'sort_dir': 'ASC'})
			self.assertTrue(response['playlists'][0]['title'].lower() <= response['playlists'][1]['title'].lower(), 'Playlists Returned Out Of Order: title ASC')
			response = self.v.playlists({'site': 'volar', 'sort_by': 'title', 'sort_dir': 'DESC'})
			self.assertTrue(response['playlists'][0]['title'].lower() >= response['playlists'][1]['title'].lower(), 'Playlists Returned Out Of Order: title DESC')
		else:
			print('\nInsufficient Playlists to test ordering')


	#Tests to make sure that per_page causes the response to have 0 - 50 playlists
	def test_PerPageBounds(self):
		#response = self.v.sites()
		#self.assertTrue(response != False, 'Connection To Sites Failed')
		#site_slug = response['sites'][0]['slug']

		response = self.v.playlists({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To Playlists Failed')

		response = self.v.playlists({'site': 'volar', 'per_page': -1})
		self.assertTrue(len(response['playlists']) >= 0, 'Response array is acting really weird')

		response = self.v.playlists({'site': 'volar', 'per_page': 1})
		self.assertTrue(len(response['playlists']) <= 1, 'Page is too long, should be no longer than 1')

		response = self.v.playlists({'site': 'volar', 'per_page': 61})
		self.assertTrue(len(response['playlists']) <= 50, 'Page is too long, should be no longer than 50')


	#Tests search functions
	def test_Searches(self):
		#response = self.v.sites()
		#self.assertTrue(response != False, 'Connection To Sites Failed')
		#site_slug = response['sites'][0]['slug']

		response = self.v.playlists({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To Playlists Failed')

		response = self.v.playlists({'site': 'volar', 'title': 'play'})
		self.assertTrue(len(response['playlists']) >= 1, 'Search by title failed')



if __name__ == '__main__':
	suite = unittest.TestLoader().loadTestsFromTestCase(TestAdvPlaylist)
	unittest.TextTestRunner(verbosity = 2).run(suite)
