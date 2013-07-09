import volar, pprint, ConfigParser, unittest


class TestAdvBroadcast(unittest.TestCase):
	"""
	Validates the broadcast data returned via the volar.broadcasts() function for type and expected value.
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

		#site_response = self.v.sites()
		#self.assertTrue(site_response != False, 'Connection To Sites Failed')
		#pprint.pprint(site_response)
		#global 'volar'
		#'volar' = site_response["sites"][0]['slug']


	#Tests if all response data is producing the correct data types
	def test_DefaultDataTypes(self):
		response = self.v.broadcasts({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To Broadcasts Failed')

		self.assertTrue(isinstance(response['list'], basestring), "Incorrect Type Returned for list (should be basestring)")
		self.assertTrue(isinstance(response['item_count'], basestring), "Incorrect Type Returned for item_count (should be basestring)")
		self.assertTrue(isinstance(response['page'], int), "Incorrect Type Returned for page (should be int)")
		self.assertTrue(isinstance(response['per_page'], int), "Incorrect Type Returned for per_page (should be int)")
		self.assertTrue(isinstance(response['num_pages'], int), "Incorrect Type Returned for num_pages (should be int)")
		self.assertTrue(isinstance(response['autoplay'], basestring), "Incorrect Type Returned for autoplay (should be basestring)")
		self.assertTrue(isinstance(response['embed_width'], int), "Incorrect Type Returned for embed_width (should be int)")
		self.assertTrue(isinstance(response['sort_by'], basestring), "Incorrect Type Returned for sort_by (should be basestring)")
		self.assertTrue(isinstance(response['sort_dir'], basestring), "Incorrect Type Returned for sort_dir (should be basestring)")
		self.assertTrue(response['id'] == None, "Incorrect Type Returned for id (should be None)")
		self.assertTrue(response['section_id'] == None, "Incorrect Type Returned for section_id (should be None)")
		self.assertTrue(response['title'] == None, "Incorrect Type Returned for title (should be None)")
		self.assertTrue(response['playlist_id'] == None, "Incorrect Type Returned for playlist_id (should be None)")
		self.assertTrue(response['before'] == None, "Incorrect Type Returned for before (should be None)")
		self.assertTrue(response['after'] == None, "Incorrect Type Returned for after (should be None)")
		self.assertTrue(isinstance(response['broadcasts'][0]['id'], basestring), "Incorrect Type Returned for broadcasts[id] (should be int)")
		self.assertTrue(isinstance(response['broadcasts'][0]['section_id'], basestring), "Incorrect Type Returned for broadcasts[section_id] (should be int)")
		self.assertTrue(isinstance(response['broadcasts'][0]['title'], basestring), "Incorrect Type Returned for broadcasts[title] (should be basestring)")
		self.assertTrue(isinstance(response['broadcasts'][0]['description'], basestring), "Incorrect Type Returned for broadcasts[description] (should be basestring)")
		self.assertTrue(isinstance(response['broadcasts'][0]['date'], basestring), "Incorrect Type Returned for broadcasts[date] (should be basestring)")
		self.assertTrue(isinstance(response['broadcasts'][0]['status'], basestring), "Incorrect Type Returned for broadcasts[status] (should be basestring)")
		self.assertTrue(isinstance(response['broadcasts'][0]['embed_code'], basestring), "Incorrect Type Returned for broadcasts[embed_code] (should be basestring)")
		if response['broadcasts'][0]['large_image'] != False:
			self.assertTrue(isinstance(response['broadcasts'][0]['large_image'], basestring), "Incorrect Type Returned for broadcasts[large_image] (should be basestring)")
			self.assertTrue(isinstance(response['broadcasts'][0]['medium_image'], basestring), "Incorrect Type Returned for broadcasts[medium_image] (should be basestring)")
			self.assertTrue(isinstance(response['broadcasts'][0]['small_image'], basestring), "Incorrect Type Returned for broadcasts[small_image] (should be basestring)")
		else:
			print('\nNo Image uploaded for test broadcast')


	#Tests if the response is returning the correct data when modified by params
	def test_ReturnedData(self):
		params = ({'site': 'volar', 'list': 'archived', 'page': 1, 'per_page': 30, 'section_id': 1, 'playlist_id': 1, 'id': 495, 'sort_by': 'title', 'sort_dir': 'DESC',
			'title': 'volar_archive', 'autoplay': 1, 'embed_width': 700, 'before': '12/12/2013', 'after': '03/03/2013'})
		#response = self.v.sites()
		#self.assertTrue(response != False, 'Connection To Sites Failed')
		#'volar' = response['sites'][0]['slug']

		response = self.v.broadcasts({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To Broadcasts Failed')

		response = self.v.broadcasts({'site': 'volar', 'id': 495})
		self.assertTrue(len(response['broadcasts']) <= 1, 'Found multiple broadcasts with one id')

		response = self.v.broadcasts(params)
		#self.assertEqual(params['page'], response['page'], 'Incorrect Value Returned for page')
		self.assertEqual(str(params['per_page']), str(response['per_page']), 'Incorrect Value Returned for per_page')
		self.assertEqual(params['sort_by'], response['sort_by'], 'Incorrect Value Returned for sort_by')
		self.assertEqual(params['sort_dir'], response['sort_dir'], 'Incorrect Value Returned for sort_dir')
		self.assertEqual(str(params['id']), str(response['id']), 'Incorrect Value Returned for id')
		self.assertEqual(params['title'], response['title'], 'Incorrect Value Returned for title')
		self.assertEqual(str(params['autoplay']), str(response['autoplay']), 'Incorrect Value Returned for autoplay')
		self.assertEqual('2013-03-03 00:00:00', response['after'], 'Incorrect Value Returned for after')
		self.assertEqual('2013-12-12 00:00:00', response['before'], 'Incorrect Value Returned for before')


	#Tests to make sure that ids are unique and that sorting is working correctly
	def test_ResponseCorrectness(self):
		#response = self.v.sites()
		#self.assertTrue(response != False, 'Connection To Sites Failed')
		#'volar' = response['sites'][0]['slug']

		response = self.v.broadcasts({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To Broadcasts Failed')

		response = self.v.broadcasts({'site': 'volar', 'id': 495})
		self.assertTrue(len(response['broadcasts']) <= 1, 'Found multiple sites with one id')

		response = self.v.broadcasts({'site': 'volar', 'sort_by': 'id', 'sort_dir': 'ASC'})
		if len(response['broadcasts']) > 1:
			self.assertTrue(response['broadcasts'][0]['id'] <= response['broadcasts'][1]['id'], 'Broadcasts Returned Out Of Order: id ASC')
			response = self.v.broadcasts({'site': 'volar', 'sort_by': 'id', 'sort_dir': 'DESC'})
			self.assertTrue(response['broadcasts'][0]['id'] >= response['broadcasts'][1]['id'], 'Broadcasts Returned Out Of Order: id DESC')
		
			response = self.v.broadcasts({'site': 'volar', 'sort_by': 'title', 'sort_dir': 'ASC'})
			self.assertTrue(response['broadcasts'][0]['title'].lower() <= response['broadcasts'][1]['title'].lower(), 'Broadcasts Returned Out Of Order: title ASC')
			response = self.v.broadcasts({'site': 'volar', 'sort_by': 'title', 'sort_dir': 'DESC'})
			self.assertTrue(response['broadcasts'][0]['title'].lower() >= response['broadcasts'][1]['title'].lower(), 'Broadcasts Returned Out Of Order: title DESC')

			response = self.v.broadcasts({'site': 'volar', 'sort_by': 'status', 'sort_dir': 'ASC'})
			self.assertTrue(response['broadcasts'][0]['status'] <= response['broadcasts'][1]['status'], 'Broadcasts Returned Out Of Order: status ASC')
			response = self.v.broadcasts({'site': 'volar', 'sort_by': 'status', 'sort_dir': 'DESC'})
			self.assertTrue(response['broadcasts'][0]['status'] >= response['broadcasts'][1]['status'], 'Broadcasts Returned Out Of Order: status DESC')

			response = self.v.broadcasts({'site': 'volar', 'sort_by': 'date', 'sort_dir': 'ASC'})
			self.assertTrue(response['broadcasts'][0]['date'] <= response['broadcasts'][1]['date'], 'Broadcasts Returned Out Of Order: date ASC')
			response = self.v.broadcasts({'site': 'volar', 'sort_by': 'date', 'sort_dir': 'DESC'})
			self.assertTrue(response['broadcasts'][0]['date'] >= response['broadcasts'][1]['date'], 'Broadcasts Returned Out Of Order: date DESC')

			if response['broadcasts'][0]['description'] != None and response['broadcasts'][1]['description'] != None:
				response = self.v.broadcasts({'site': 'volar', 'sort_by': 'description', 'sort_dir': 'ASC'})
				self.assertTrue(response['broadcasts'][0]['description'].lower() <= response['broadcasts'][1]['description'].lower(), 'Broadcasts Returned Out Of Order: description ASC')
				response = self.v.broadcasts({'site': 'volar', 'sort_by': 'description', 'sort_dir': 'DESC'})
				self.assertTrue(response['broadcasts'][0]['description'].lower() >= response['broadcasts'][1]['description'].lower(), 'Broadcasts Returned Out Of Order: description DESC')
			else:
				print('\nInsufficient Descriptions to test ordering')
		else:
			print('\nInsufficient Broadcasts to test ordering')


	#Tests to make sure that per_page causes the response to have 0 - 50 broadcasts
	def test_PerPageBounds(self):
		#response = self.v.sites()
		#self.assertTrue(response != False, 'Connection To Sites Failed')
		#'volar' = response['sites'][0]['slug']

		response = self.v.broadcasts({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To Broadcasts Failed')

		response = self.v.broadcasts({'site': 'volar', 'per_page': -1})
		self.assertTrue(len(response['broadcasts']) >= 0, 'Response array is acting really weird')

		response = self.v.broadcasts({'site': 'volar', 'per_page': 1})
		self.assertTrue(len(response['broadcasts']) <= 1, 'Page is too long, should be no longer than 1')

		response = self.v.broadcasts({'site': 'volar', 'per_page': 61})
		self.assertTrue(len(response['broadcasts']) <= 50, 'Page is too long, should be no longer than 50')


	#Tests search functions
	def test_Searches(self):
		#response = self.v.sites()
		#self.assertTrue(response != False, 'Connection To Sites Failed')
		#'volar' = response['sites'][0]['slug']

		response = self.v.broadcasts({'site': 'volar'})
		self.assertTrue(response != False, 'Connection To Broadcasts Failed')

		response = self.v.broadcasts({'site': 'volar', 'title': 'Kevin'})
		self.assertTrue(len(response['broadcasts']) >= 1, 'Search by title failed')



if __name__ == '__main__':
	suite = unittest.TestLoader().loadTestsFromTestCase(TestAdvBroadcast)
	unittest.TextTestRunner(verbosity = 2).run(suite)
