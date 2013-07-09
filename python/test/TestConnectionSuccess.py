import volar, pprint, ConfigParser, unittest


class TestThoroughBroadcast(unittest.TestCase):
	"""
	Tests the ability to connect to the CMS via, hopefully, valid credentials.
	"""

	def setUp(self):
		# load settings
		c = ConfigParser.ConfigParser()
		c.read('sample.cfg')	#note that this file is only for use with this script.  however, you can copy its contents and this code to use in your own scripts
		base_url = c.get('settings','base_url')
		api_key = c.get('settings','api_key')
		secret = c.get('settings','secret')
		self.v = volar.Volar(base_url = base_url, api_key = api_key, secret = secret)

		

if __name__ == '__main__':
	suite = unittest.TestLoader().loadTestsFromTestCase(TestBroadcastThorough)
	unittest.TextTestRunner(verbosity = 2).run(suite)
