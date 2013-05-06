import volar

v = volar.Volar(base_url = 'local.platypus.com', api_key = '934bf28ae6c5575b3bb6e3e94da47cde', secret = 'JVJys$vZ-d8im2E:zLRO5UzWXd.A#V$i')
print v.build_signature(route = 'api/client/info')

response = v.request(route = 'api/client/info')
if response == False:
	print v.error
else:
	print response