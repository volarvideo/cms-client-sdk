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

	def test_Broadcasts(self):
		param_seed = []
		
		response = self.v.broadcasts()
		self.assertFalse(response, 'Accessed broadcasts without a site')
		response = self.v.broadcasts({'site': 'fmeafea'})
		self.assertFalse(response, 'Accessed an invalid site, did not return false')
		response = self.v.broadcasts({'site': 'volar'})
		self.assertTrue(response != False, 'Could not access volar site')
		
		for i in range(0, 11):
			for j in range(0, 2):
				param_seed = [0]*12
				param_seed[i] = j

				params = {'site': 'volar', 'expected': True, 'empty': False}
				if param_seed[0] == 1:
					params['list'] = 'grocery'
				elif param_seed[0] == 2:
					params['list'] = 'archived'

				if param_seed[1] == 1:
					params['per_page'] = 'several'
				elif param_seed[1] == 2:
					params['per_page'] = 30

				if param_seed[2] == 1:
					params['section_id'] = -5
					params['empty'] = True
				elif param_seed[2] == 2:
					params['section_id'] = 1

				if param_seed[3] == 1:
					params['playlist_id'] = -9
					params['empty'] = True
				elif param_seed[3] == 2:
					params['playlist_id'] = 1

				if param_seed[4] == 1:
					params['id'] = 'social security'
				elif param_seed[4] == 2:
					params['id'] = 495

				if param_seed[5] == 1:
					params['title'] = "This isn't the broadcast you're looking for"
					params['empty'] = True
				elif param_seed[5] == 2:
					params['title'] = 'Kevin_Interrupt_Archive'

				if param_seed[6] == 1:
					params['autoplay'] = 'I guess'
				elif param_seed[6] == 2:
					params['autoplay'] = True

				if param_seed[7] == 1:
					params['embed_width'] = '-1'
				elif param_seed[7] == 2:
					params['embed_width'] = '640'

				if param_seed[8] == 1:
					params['before'] = 'tomorrow'
				elif param_seed[8] == 2:
					params['before'] = '12/12/2013'

				if param_seed[9] == 1:
					params['after'] = 'earth'
				elif param_seed[9] == 2:
					params['after'] = '03/03/2013'

				if param_seed[10] == 1:
					params['sort_by'] = 'pizazz'
				elif param_seed[10] == 2:
					params['sort_by'] = 'status'

				if param_seed[11] == 1:
					params['sort_dir'] = 'north'
				elif param_seed[11] == 2:
					params['sort_dir'] = 'desc'

				if params['expected']:
					del params['expected']

					if params['empty']:
						del params['empty']
						response = self.v.broadcasts(params)
						self.assertEqual(0, len(response['broadcasts']), 'Case '+str(i)+':'+str(j)+': Expected no broadcasts, received broadcasts')

					else:
						del params['empty']
						response = self.v.broadcasts(params)
						self.assertTrue(len(response['broadcasts']) >= 1, 'Case '+str(i)+':'+str(j)+': Expected response, no broadcasts received')

				else:
					del params['expected']
					del params['empty']
					response = self.v.broadcasts(params)
					self.assertFalse(response['broadcasts'], 'Case '+str(i)+str(j)+': Expected api failure, information was received')

if __name__ == '__main__':
	suite = unittest.TestLoader().loadTestsFromTestCase(TestThoroughBroadcast)
	unittest.TextTestRunner(verbosity = 2).run(suite)
