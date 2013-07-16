import volar, pprint, ConfigParser

"""
Clears out test broadcasts and playlists created by TestEditBroadcast and TestEditPlaylist
"""

# load settings
c = ConfigParser.ConfigParser()
c.read('sample.cfg')	#note that this file is only for use with this script.  however, you can copy its contents and this code to use in your own scripts
base_url = c.get('settings','base_url')
api_key = c.get('settings','api_key')
secret = c.get('settings','secret')
v = volar.Volar(base_url = base_url, api_key = api_key, secret = secret)

response = v.broadcasts({'site': 'volar', 'title': 'api_test'})
for bcast in response['broadcasts']:
	response = v.broadcast_delete({'site': 'volar', 'id': bcast['id']})
	if (response['success']):
		print('Deleted Broadcast #'+bcast['id'])
	else:
		print('Failed to Delete Broadcast #'+bcast['id'])

response = v.broadcasts({'site': 'volar', 'title': 'api_test_2'})
for bcast in response['broadcasts']:
	response = v.broadcast_delete({'site': 'volar', 'id': bcast['id']})
	if (response['success']):
		print('Deleted Broadcast #'+bcast['id'])
	else:
		print('Failed to Delete Broadcast #'+bcast['id'])

response = v.playlists({'site': 'volar', 'title': 'api_test'})
for plist in response['playlists']:
	response = v.playlist_delete({'site': 'volar', 'id': plist['id']})
	if (response['success']):
		print('Deleted Playlist #'+plist['id'])
	else:
		print('Failed to Delete Playlist #'+plist['id'])

response = v.playlists({'site': 'volar', 'title': 'api_test_2'})
for plist in response['playlists']:
	response = v.playlist_delete({'site': 'volar', 'id': plist['id']})
	if (response['success']):
		print('Deleted Playlist #'+plist['id'])
	else:
		print('Failed to Delete Playlist #'+plist['id'])