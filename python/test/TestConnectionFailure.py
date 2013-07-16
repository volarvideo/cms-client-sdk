import volar, pprint, ConfigParser, unittest


class TestConnectionFailure(unittest.TestCase):
	"""
	Tests the error results from the CMS when attempting to connect via invalid credentials.
	"""

	def setUp(self):
		# load settings
		c = ConfigParser.ConfigParser()
		c.read('sample.cfg')	#note that this file is only for use with this script.  however, you can copy its contents and this code to use in your own scripts
		base_url = c.get('settings','base_url')
		api_key = c.get('settings','api_key')
		secret = c.get('settings','secret')

		global v
		v = volar.Volar(base_url = base_url, api_key = api_key, secret = secret)
		global v_url_fail
		v_url_fail = volar.Volar(base_url = 'a.com', api_key = api_key, secret = secret)
		global v_key_fail
		v_key_fail = volar.Volar(base_url = base_url, api_key = 'a', secret = secret)
		global v_secret_fail
		v_secret_fail = volar.Volar(base_url = base_url, api_key = api_key, secret = 'a')

	#def test_ConnectionSuccess(self):
	#	response = self.v.sites()
	#	pprint.pprint(response)
	#	self.assertTrue(response != False, 'Connection To Sites Failed')
	#	 'volar' = response['sites'][0]['slug']

	#	response = self.v.broadcasts({'site': 'volar'})
	#	self.assertTrue(response != False, 'Connection to Broadcasts Failed')

	#	response = self.v.sections({'site': 'volar'})
	#	self.assertTrue(response != False, 'Connection to Sections Failed')

	#	response = self.v.playlists({'site': 'volar'})
	#	self.assertTrue(response != False, 'Connection to Playlists Failed')

	def test_ConnectionFailure_Sites(self):
		response = v_url_fail.sites()
		self.assertFalse(response, 'Invalid Connection Made to Sites: Using An Invalid URL')

		response = v_key_fail.sites()
		self.assertFalse(response, 'Invalid Connection Made to Sites: Using An Invalid Key')

		response = v_secret_fail.sites()
		self.assertFalse(response, 'Invalid Connection Made to Sites: Using An Invalid Secret')

	def test_ConnectionFailure_Broadcasts(self):

		response = v_url_fail.broadcasts({'site': 'volar'})
		self.assertFalse(response, 'Invalid Connection Made to Broadcasts: Using An Invalid URL')

		response = v_key_fail.broadcasts({'site': 'volar'})
		self.assertFalse(response, 'Invalid Connection Made to Broadcasts: Using An Invalid Key')

		response = v_secret_fail.broadcasts({'site': 'volar'})
		self.assertFalse(response, 'Invalid Connection Made to Broadcasts: Using An Invalid Secret')

		response = v.broadcasts()
		self.assertFalse(response, 'Invalid Connection Made to Broadcasts: No slug provided')

	def test_ConnectionFailure_Sections(self):

		response = v_url_fail.sections({'site': 'volar'})
		self.assertFalse(response, 'Invalid Connection Made to Sections: Using An Invalid URL')

		response = v_key_fail.sections({'site': 'volar'})
		self.assertFalse(response, 'Invalid Connection Made to Sections: Using An Invalid Key')

		response = v_secret_fail.sections({'site': 'volar'})
		self.assertFalse(response, 'Invalid Connection Made to Sections: Using An Invalid Secret')

		response = v.sections()
		self.assertFalse(response, 'Invalid Connection Made to Sections: No slug provided')

	def test_ConnectionFailure_Playlists(self):

		response = v_url_fail.playlists({'site': 'volar'})
		self.assertFalse(response, 'Invalid Connection Made to Playlists: Using An Invalid URL')

		response = v_key_fail.playlists({'site': 'volar'})
		self.assertFalse(response, 'Invalid Connection Made to Playlists: Using An Invalid Key')

		response = v_secret_fail.playlists({'site': 'volar'})
		self.assertFalse(response, 'Invalid Connection Made to Playlists: Using An Invalid Secret')

		response = v.playlists()
		self.assertFalse(response, 'Invalid Connection Made to Playlists: No slug provided')

if __name__ == '__main__':
	suite = unittest.TestLoader().loadTestsFromTestCase(TestConnectionFailure)
	unittest.TextTestRunner(verbosity = 2).run(suite)
