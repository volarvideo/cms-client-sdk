import volar, pprint, ConfigParser, unittest


class TestAdvAccountInfo(unittest.TestCase):
	"""
	Validates the site data returned via the volar.sites() function for type and expected value.
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



	def test_DefaultDataTypes(self):
		response = self.v.sites()
		self.assertTrue(response != False, 'Connection To Sites Failed')

		self.assertTrue(isinstance(response['item_count'], basestring), "Incorrect Type Returned for item_count (should be basestring)")
		self.assertTrue(isinstance(response['page'], int), "Incorrect Type Returned for page (should be int)")
		self.assertTrue(isinstance(response['per_page'], int), "Incorrect Type Returned for per_page (should be int)")
		self.assertTrue(isinstance(response['sort_by'], basestring), "Incorrect Type Returned for sort_by (should be basestring)")
		self.assertTrue(isinstance(response['sort_dir'], basestring), "Incorrect Type Returned for sort_dir (should be basestring)")
		self.assertTrue(response['id'] == None, "Incorrect Type Returned for id (should be None)")
		self.assertTrue(response['slug'] == None, "Incorrect Type Returned for slug (should be None)")
		self.assertTrue(response['title'] == None, "Incorrect Type Returned for title (should be None)")
		self.assertTrue(isinstance(response['sites'][0]['id'], int), "Incorrect Type Returned for sites[id] (should be int)")
		self.assertTrue(isinstance(response['sites'][0]['slug'], basestring), "Incorrect Type Returned for sites[slug] (should be basestring)")
		self.assertTrue(isinstance(response['sites'][0]['title'], basestring), "Incorrect Type Returned for sites[title] (should be basestring)")



	def test_ReturnedData(self):
		params = ({'page': 2, 'per_page': 30, 'sort_by': 'title', 'sort_dir': 'DESC',
			'id': 1, 'slug': 'volar', 'title': 'Volar Video'})
		response = self.v.sites(params)
		self.assertTrue(response != False, 'Connection To Sites Failed')

		self.assertEqual(1, response['page'], 'Incorrect Value Returned for page')
		self.assertEqual(str(params['per_page']), str(response['per_page']), 'Incorrect Value Returned for per_page')
		self.assertEqual(params['sort_by'], response['sort_by'], 'Incorrect Value Returned for sort_by')
		self.assertEqual(params['sort_dir'], response['sort_dir'], 'Incorrect Value Returned for sort_dir')
		self.assertEqual(str(params['id']), str(response['id']), 'Incorrect Value Returned for id')
		self.assertEqual(params['slug'], response['slug'], 'Incorrect Value Returned for slug')
		self.assertEqual(params['title'], response['title'], 'Incorrect Value Returned for title')



	def test_ResponseCorrectness(self):
		response = self.v.sites({'id': 1})
		self.assertTrue(len(response['sites']) <= 1, 'Found multiple sites with one id')

		response = self.v.sites({'site': 'volar', 'sort_by': 'id', 'sort_dir': 'ASC'})

		if len(response['sites']) >= 2:
			self.assertTrue(response['sites'][0]['id'] <= response['sites'][1]['id'], 'Sites Returned Out Of Order: id ASC')
			response = self.v.sites({'site': 'volar', 'sort_by': 'id', 'sort_dir': 'DESC'})
			self.assertTrue(response['sites'][0]['id'] <= response['sites'][1]['id'], 'Sites Returned Out Of Order: id DESC')
		
			response = self.v.sites({'site': 'volar', 'sort_by': 'title', 'sort_dir': 'ASC'})
			self.assertTrue(response['sites'][0]['title'].lower() <= response['sites'][1]['title'].lower(), 'Sites Returned Out Of Order: title ASC')
			response = self.v.sites({'site': 'volar', 'sort_by': 'title', 'sort_dir': 'DESC'})
			self.assertTrue(response['sites'][0]['title'].lower() <= response['sites'][1]['title'].lower(), 'Sites Returned Out Of Order: title DESC')

			response = self.v.sites({'site': 'volar', 'sort_by': 'status', 'sort_dir': 'ASC'})
			self.assertTrue(response['sites'][0]['status'] <= response['sites'][1]['status'], 'Sites Returned Out Of Order: status ASC')
			response = self.v.sites({'site': 'volar', 'sort_by': 'status', 'sort_dir': 'DESC'})
			self.assertTrue(response['sites'][0]['status'] <= response['sites'][1]['status'], 'Sites Returned Out Of Order: status DESC')
		else:
			print('\nInsufficient results to test response ordering')


	def test_PerPageBounds(self):
		response = self.v.sites({'per_page': -1})
		self.assertTrue(len(response['sites']) >= 0, 'Response array is acting really weird')

		response = self.v.sites({'per_page': 1})
		self.assertTrue(len(response['sites']) <= 1, 'Page is too long, should be no longer than 1')

		response = self.v.sites({'per_page': 61})
		self.assertTrue(len(response['sites']) <= 50, 'Page is too long, should be no longer than 50')



	def test_Searches(self):
	#	response = self.v.sites({'slug': 'vol'})
	#	self.assertTrue(len(response['sites']) == 1, 'Search by slug failed')

		response = self.v.sites({'title': 'Vid'})
		self.assertTrue(len(response['sites']) == 1, 'Seach by title failed')

if __name__ == '__main__':
	suite = unittest.TestLoader().loadTestsFromTestCase(TestAdvAccountInfo)
	unittest.TextTestRunner(verbosity = 2).run(suite)
