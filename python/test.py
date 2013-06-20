import volar, pprint, ConfigParser

# load settings
try:
	c = ConfigParser.ConfigParser()
	c.read('test.cfg')
	base_url = c.get('settings','base_url')
	api_key = c.get('settings','api_key')
	secret = c.get('settings','secret')
except:
	print "couldn't read settings.  please check the values in test.cfg"
	exit(1);

v = volar.Volar(base_url = base_url, api_key = api_key, secret = secret)

print "-- SITES --"
response = v.sites()
if response == False:
	print v.error
else:
	pprint.pprint(response)

print "-- BROADCASTS --"
response = v.broadcasts({'site':'default'})
if response == False:
	print v.error
else:
	pprint.pprint(response)

print "-- SECTIONS --"
response = v.sections({'site':'default'})
if response == False:
	print v.error
else:
	pprint.pprint(response)

print "-- PLAYLISTS --"
response = v.playlists({'site':'default'})
if response == False:
	print v.error
else:
	pprint.pprint(response)

