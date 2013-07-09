import volar, pprint, ConfigParser, unittest


class TestEditBroadcast(unittest.TestCase):

	def setUp(self):
		# load settings
		c = ConfigParser.ConfigParser()
		c.read('sample.cfg')	#note that this file is only for use with this script.  however, you can copy its contents and this code to use in your own scripts
		base_url = c.get('settings','base_url')
		api_key = c.get('settings','api_key')
		secret = c.get('settings','secret')
		self.v = volar.Volar(base_url = base_url, api_key = api_key, secret = secret)

	def test_BroadcastCreateAndDelete(self):
		response = self.v.broadcast_create({'site': 'volar'})
		self.assertFalse(response['success'], 'Received success by creating a broadcast w/o title')

		response = self.v.broadcast_create({'site': 'volar', 'title': 'api_test'})
		self.assertTrue(response['success'], 'Failed to create broadcast')
		broadcast_details = response['broadcast']

		response = self.v.broadcast_delete({'site': 'volar', 'id': broadcast_details['id']})
		self.assertTrue(response['success'], 'Failed to delete broadcast')

	def test_BroadcastUpdate(self):
		response = self.v.broadcast_create({'site': 'volar', 'title': 'api_test'})
		self.assertTrue(response['success'], 'Failed to create broadcast')
		broadcast_details = response['broadcast']

		response = self.v.broadcast_update({'site': 'volar', 'id': broadcast_details['id'], 'title': 'api_test_2'})
		pprint.pprint(response)
		self.assertTrue(response['success'], 'Failed to update title')

		response = self.v.broadcast_update({'site': 'volar', 'id': broadcast_details['id'], 'date': '04/04/2013'})
		self.assertTrue(response['success'], 'Failed to update date')

		response = self.v.broadcast_update({'site': 'volar', 'id': broadcast_details['id'], 'description': 'API Broadcast Testing'})
		self.assertTrue(response['success'], 'Failed to update description')

		response = self.v.broadcast_update({'site': 'volar', 'id': broadcast_details['id'], 'section_id': 2})
		self.assertTrue(response['success'], 'Failed to update section_id')

		response = self.v.broadcast_delete({'site': 'volar', 'id': broadcast_details['id']})
		self.assertTrue(response['success'], 'Failed to delete broadcast')

	def test_BroadcastArchive(self):
		response = self.v.broadcast_create({'site': 'volar', 'title': 'api_test'})
		self.assertTrue(response['success'], 'Failed to create broadcast')
		broadcast_details = response['broadcast']

		response = self.v.broadcast_archive({'site': 'volar', 'id': broadcast_details['id']})
		self.assertTrue(response['success'], 'Archival failed')

		response = self.v.broadcast_delete({'site': 'volar', 'id': broadcast_details['id']})
		self.assertTrue(response['success'], 'Failed to delete broadcast')

	def test_PlaylistAssignment(self):
		response = self.v.broadcast_create({'site': 'volar', 'title': 'api_test'})
		self.assertTrue(response['success'], 'Failed to create broadcast')
		broadcast_details = response['broadcast']

		response = self.v.broadcast_assign_playlist({'site': 'volar', 'id': broadcast_details['id'], 'playlist_id': 1})
		self.assertTrue(response['success'], 'Failed to assign to playlist 1')
		
		response = self.v.broadcast_assign_playlist({'site': 'volar', 'id': broadcast_details['id'], 'playlist_id': 4})
		self.assertTrue(response['success'], 'Failed to assign to playlist 4')

		response = self.v.playlists({'site': 'volar', 'id': broadcast_details['id']})
		self.assertEqual(2, len(response['playlists']), 'Broadcast not added to both playlists')

		response = self.v.broadcast_remove_playlist({'site': 'volar', 'id': broadcast_details['id'], 'playlist_id': 1})
		self.assertTrue(response['success'], 'Failed to remove from playlist 1')

		response = self.v.broadcast_remove_playlist({'site': 'volar', 'id': broadcast_details['id'], 'playlist_id': 4})
		self.assertTrue(response['success'], 'Failed to remove from playlist 4')

		response = self.v.broadcast_delete({'site': 'volar', 'id': broadcast_details['id']})
		self.assertTrue(response['success'], 'Failed to delete broadcast')

	def test_ImageUpload(self):
		response = self.v.broadcast_create({'site': 'volar', 'title': 'api_test'})
		self.assertTrue(response['success'], 'Failed to create broadcast')
		broadcast_details = response['broadcast']

		response = self.v.broadcast_poster({'site': 'volar', 'id': broadcast_details['id']}, "/home/volar/Desktop/cms-client-sdk/python/test/pulp_fiction_lrg.jpg", "pulpfiction.jpg")
		self.assertTrue(response['success'], 'Failed to upload image')

		response = self.v.broadcast_delete({'site': 'volar', 'id': broadcast_details['id']})
		self.assertTrue(response['success'], 'Failed to delete broadcast')
	

if __name__ == '__main__':
	suite = unittest.TestLoader().loadTestsFromTestCase(TestEditBroadcast)
	unittest.TextTestRunner(verbosity = 2).run(suite)
