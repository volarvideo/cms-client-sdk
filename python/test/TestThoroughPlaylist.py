import volar, pprint, ConfigParser, unittest


class TestThoroughPlaylist(unittest.TestCase):
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

	def test_Playlists(self):
		param_seed = []
		
		response = self.v.playlists()
		self.assertFalse(response, 'Accessed playlists without a site')
		response = self.v.playlists({'site': 'notasite'})
		self.assertFalse(response, 'Accessed an invalid site, did not return false')
		response = self.v.playlists({'site': 'volar'})
		self.assertTrue(response != False, 'Could not access volar site')
		
		for i in range(0, 8):
			for j in range(0, 2):
				param_seed = [0]*9
				param_seed[i] = j

				params = {'site': 'volar', 'expected': True, 'empty': False}
				if param_seed[0] == 1:
					params['page'] = 'book'
				elif param_seed[0] == 2:
					params['page'] = '2'

				if param_seed[1] == 1:
					params['per_page'] = 'several'
				elif param_seed[1] == 2:
					params['per_page'] = 30

				if param_seed[2] == 1:
					params['broadcast_id'] = -6
					params['empty'] = True
				elif param_seed[2] == 2:
					params['broadcast_id'] = 495

				if param_seed[3] == 1:
					params['video_id'] = -9
					params['empty'] = True
				elif param_seed[3] == 2:
					params['video_id'] = 1

				if param_seed[4] == 1:
					params['section_id'] = -9
					params['empty'] = True
				elif param_seed[4] == 2:
					params['section_id'] = 1

				if param_seed[5] == 1:
					params['id'] = 'social security'
				elif param_seed[5] == 2:
					params['id'] = 1

				if param_seed[6] == 1:
					params['title'] = "This isn't the playlist you're looking for"
					params['empty'] = True
				elif param_seed[6] == 2:
					params['title'] = 'Kevin_Interrupt_Archive'

				if param_seed[7] == 1:
					params['sort_by'] = 'pizazz'
				elif param_seed[7] == 2:
					params['sort_by'] = 'status'

				if param_seed[8] == 1:
					params['sort_dir'] = 'north'
				elif param_seed[8] == 2:
					params['sort_dir'] = 'desc'

				if params['expected']:
					del params['expected']

					if params['empty']:
						del params['empty']
						response = self.v.playlists(params)
						self.assertEqual(0, len(response['playlists']), 'Case '+str(i)+':'+str(j)+': Expected no playlists, received playlists')

					else:
						del params['empty']
						response = self.v.playlists(params)
						self.assertTrue(len(response['playlists']) >= 1, 'Case '+str(i)+':'+str(j)+': Expected response, no playlists received')

				else:
					del params['expected']
					del params['empty']
					response = self.v.playlists(params)
					self.assertFalse(response['playlists'], 'Case '+str(i)+str(j)+': Expected api failure, information was received')

if __name__ == '__main__':
	suite = unittest.TestLoader().loadTestsFromTestCase(TestThoroughPlaylist)
	unittest.TextTestRunner(verbosity = 2).run(suite)
