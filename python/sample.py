import volar, pprint, ConfigParser

# load settings
try:
	c = ConfigParser.ConfigParser()
	c.read('sample.cfg')	#note that this file is only for use with this script.  however, you can copy its contents and this code to use in your own scripts
	base_url = c.get('settings','base_url')
	api_key = c.get('settings','api_key')
	secret = c.get('settings','secret')
except:
	print "couldn't read settings.  please check the values in sample.cfg"
	exit(1);

v = volar.Volar(base_url = base_url, api_key = api_key, secret = secret)

print "-- SITES --"
response = v.sites()
if response == False:
	print v.error
	exit(1);
else:
	pprint.pprint(response)

site_slug = response['sites'][0]['slug']

print "-- BROADCASTS --"
response = v.broadcasts({'site':site_slug})
if response == False:
	print v.error
	exit(1);
else:
	pprint.pprint(response)

print "-- SECTIONS --"
response = v.sections({'site':site_slug})
if response == False:
	print v.error
	exit(1);
else:
	pprint.pprint(response)

print "-- PLAYLISTS --"
response = v.playlists({'site':site_slug})
if response == False:
	print v.error
	exit(1);
else:
	pprint.pprint(response)

