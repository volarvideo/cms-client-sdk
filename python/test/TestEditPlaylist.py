import volar, pprint, ConfigParser, unittest


class TestEditPlaylist(unittest.TestCase):
	"""
	Tests the ability to create, delete, and update playlists through the API
	"""


	def setUp(self):
		# load settings
		c = ConfigParser.ConfigParser()
		c.read('sample.cfg')	#note that this file is only for use with this script.  however, you can copy its contents and this code to use in your own scripts
		base_url = c.get('settings','base_url')
		api_key = c.get('settings','api_key')
		secret = c.get('settings','secret')
		self.v = volar.Volar(base_url = base_url, api_key = api_key, secret = secret)

	def test_PlaylistCreateAndDelete(self):
		response = self.v.playlist_create({'site': 'volar'})
		self.assertFalse(response['success'], 'Received success by creating a playlist w/o title')

		response = self.v.playlist_create({'site': 'volar', 'title': 'api_test'})
		self.assertTrue(response['success'], 'Failed to create playlist')
		playlist_details = response['playlist']

		response = self.v.playlist_delete({'site': 'volar', 'id': playlist_details['id']})
		self.assertTrue(response['success'], 'Failed to delete playlist')

	def test_PlaylistUpdate(self):
		response = self.v.playlist_create({'site': 'volar', 'title': 'api_test'})
		self.assertTrue(response['success'], 'Failed to create playlist')
		playlist_details = response['playlist']

		response = self.v.playlist_update({'site': 'volar', 'id': playlist_details['id'], 'title': 'api_test_2'})
		pprint.pprint(response)
		self.assertTrue(response['success'], 'Failed to update title')

		response = self.v.playlist_update({'site': 'volar', 'id': playlist_details['id'], 'available': 'no'})
		self.assertTrue(response['success'], 'Failed to update availability')

		response = self.v.playlist_update({'site': 'volar', 'id': playlist_details['id'], 'description': 'API Playlist Testing'})
		self.assertTrue(response['success'], 'Failed to update description')

		response = self.v.playlist_update({'site': 'volar', 'id': playlist_details['id'], 'section_id': 2})
		self.assertTrue(response['success'], 'Failed to update section_id')

		response = self.v.playlist_delete({'site': 'volar', 'id': playlist_details['id']})
		self.assertTrue(response['success'], 'Failed to delete playlist')

if __name__ == '__main__':
	suite = unittest.TestLoader().loadTestsFromTestCase(TestEditPlaylist)
	unittest.TextTestRunner(verbosity = 2).run(suite)
