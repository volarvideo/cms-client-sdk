require 'rest-client'
require 'base64'
require 'digest/sha2'
require 'json'

class Volar
	attr_accessor :api_key
	attr_accessor :secret
	attr_accessor :base_url
	attr_accessor :secure
	attr_accessor :error

	def initialize(api_key, secret, base_url)
		@api_key = api_key
		@secret = secret
		@base_url = base_url 
		@secure = false
		@error = nil
	end

	def sites(params = {})

		results = self.request(route = 'api/client/info', method = 'GET', parameters = params)
		return results
	end

	def broadcasts(params = {})
		if params.has_key?('site') == false and params.has_key?('sites') == false
			@error = '"site" or "sites" parameter is required.'
			return false
		end  
		result = request(route = 'api/client/broadcast', method = '', parameters = params, post_body = nil)
		return result
	end 

	def broadcast_create(params = {})
		###################
		#####UNTESTED######
		###################

		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		params.delete('site')
		params = JSON.generate(params)
		puts params
		results = self.request(route = 'api/client/broadcast/create', method = 'POST', parameters = {'site' => site}, post_body = params)
		return results
	end 

	def broadcast_update(params = {})
		###################
		#####UNTESTED######
		###################
		
		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		params = params.to_json
		results = self.request(route = 'api/client/broadcast/update', method = 'POST', parameters = {'site' => site}, post_body = params)
		return results
	end

	def broadcast_delete(params = {})
		###################
		#####UNTESTED######
		###################
		
		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		params = params.to_json
		results = self.request(route = 'api/client/broadcast/delete', method = 'POST', parameters = {'site' => site}, post_body = params)
		return results
	end
	
	def broadcast_assign_playlist(params = {})
		###################
		#####UNTESTED######
		###################
		
		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		results = self.request(route = 'api/client/broadcast/assignplaylist', parameters = params)
		return results
	end

	def broadcast_remove_playlist(params = {})
		###################
		#####UNTESTED######
		###################
		
		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		results = self.request(route = 'api/client/broadcast/removeplaylist', parameters = params)
		return results
	end	

	def broadcast_poster(params = {}, file_path = '')
		###################
		#####UNTESTED######
		###################
		
		if file_path == ''
			result = self.request(route = 'api/client/broadcast/poster', method = 'GET', parameters = params)
		else
			post = {'api_poster' => File.new(file_path, 'rb')}
		end 
		result = request(route = 'api/client/broadcast/poster', method = 'POST', parameters = params, post_body = post)
		return result
	end 

	def broadcast_archive(params = {}, file_path = '')
		###################
		#####UNTESTED######
		###################
		
		if file_path == ''
			result = request(route = 'api/client/broadcast/archive', method = 'GET', parameters = params)
		else 
			post = {'archive' => File.new(file_path, 'rb')}
			result = request(route = 'api/client/broadcast/archive', method = 'POST', parameters = params, post_body = post)
		end
		return result
	end 

	def templates(params = {})
	
		site = params.fetch('site', nil)
		if site == nil
			@error = '"site" parameter is required'
			return false
		end
		result = request(route = 'api/client/template', method = '', parameters = params)
		return result
	end 

	def template_create(params = {})
		###################
		#####UNTESTED######
		###################
		
		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		params = params.to_json
		results = self.request(route = 'api/client/template/create', method = 'POST', parameters = {'site' => site}, post_body = params)
		return results
	end 

	def template_delete(params = {})
		###################
		#####UNTESTED######
		###################
		
		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		params = params.to_json
		results = self.request(route = 'api/client/template/delete', method = 'POST', parameters = {'site' => site}, post_body = params)
		return results
	end 

	def sections(params = {})


		if params.has_key?('site') == false and params.has_key?('sites') == false
			@error = '"site" or "sites" parameter is required.'
			return false
		end  
		result = request(route = 'api/client/section', method = '', parameters = params)
		return result
	end

	def playlists(params = {})
		###################
		#####UNTESTED######
		###################
		
		if params.has_key?('site') == false and params.has_key?('sites') == false
			@error = '"site" or "sites" parameter is required.'
			return false
		end  
		result = request(route = 'api/client/playlist', method = '', parameters = params)
		return result
	end

	def playlist_create(params = {})
		###################
		#####UNTESTED######
		###################
		
		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		params = params.to_json
		results = self.request(route = 'api/client/playlist/create', method = 'POST', parameters = {'site' => site}, post_body = params)
		return results
	end 
	
	def playlist_update(params = {})
		###################
		#####UNTESTED######
		###################
		
		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		params = params.to_json
		results = self.request(route = 'api/client/playlist/update', method = 'POST', parameters = {'site' => site}, post_body = params)
		return results
	end 

	def playlist_delete(params = {})
		###################
		#####UNTESTED######
		###################
		
		site = params.fetch('site', nil)
		if site == nil
			@error = 'site is required'
			return false
		end
		params = params.to_json
		results = self.request(route = 'api/client/playlist/delete', method = 'POST', parameters = {'site' => site}, post_body = params)
		return results
	end 

	def request(route, method = '', parameters = {}, post_body = nil)
		if method == ''
			method = 'GET'
		end
		
		transformed_params={}
		parameters.each do |key, val|
			if val.instance_of?(Hash)
				val.each do |vkey, vval|
					val.sort
					transformed_params[key + '[' + convert_val_to_str(vkey) + '['] = val
				end
			else
				transformed_params[key] = val
			end
		end

		transformed_params['api_key'] = @api_key
		signature = build_signature(route, method, transformed_params, post_body)
		#puts signature
		transformed_params['signature'] = signature
		url = '/' + route.chomp('/')
		
		if @secure
			url = 'https://' + @base_url + url 
		else 
			url = 'http://' + @base_url + url
		end 

		begin
			if method == 'GET'
				request = RestClient.get(url, {:params => transformed_params})
			else
				###################
				#####UNTESTED######
				###################
=begin
				data = {}
				files = nil
				if post_body != nil
					if post_body.kind_of?(String)
						data = post_body
					elsif post_body.kind_of?(Hash)
						post_body.each do |element|
							if element == 'files'
								files = post_body[element]
							else
								data[element] = post_body[element]
							end 
						end  
					end
				end 
				if data == {}
					data = nil
				end
=end 
				#puts post_body
				#puts transformed_params
				request = RestClient.post(url, post_body, {:nested => transformed_params})
			end
			return JSON.parse(request)
		rescue Exception => exc
			puts exc.message 
			puts exc.backtrace.inspect
			return false
		end

	end

	def build_signature(route, method = '', get_params = {}, post_body = nil)
		if method == ''
			method = 'GET'
		end 

		route = route.chomp('/').reverse.chomp('/').reverse
		get_params = get_params.sort { |a, b| a[0].to_s <=> b[0].to_s }
		get_params = (get_params.map { |param| param.join('=') }.join);


		method = method.upcase
	
		signature = @secret.to_s + method + route + get_params
		
		if post_body != nil and post_body.is_a?(String)
			signature += post_body
		end 
	
		
		signature=signature.force_encoding('us-ascii')
		sha256 = Digest::SHA2.new(256)
		signature = sha256.digest(signature)
		
		signature = Base64::encode64(signature)[0..42]
		
		signature.chomp!('=')
		return signature
	end 

	def convert_val_to_str(val)
		if val.is_a(Boolean)
			if val==true
				return '1'
			else 
				return '0'
			end
		else
			return val.to_s
		end 
	end

end

v=Volar.new(api_key='mZS1Q6EBuxesHCOThaVkIsJQoKevDdjD', secret='uPFC<fal^~?JM%v|3KT<f#SN$>K]e6C/', base_url='staging.platypusgranola.com')
result= v.broadcast_create(parameters={'site'=>'volar', 'title'=>'tjbroadcast', 'contact_name'=>'trjones', 'contact_phone'=>'555-555-5555', 'contact_sms'=>'555-555-5555', 'contact_email'=>'ajsdfkl@ajsdfkl.com'})
#puts result
#puts '\n\n\n'+v.error
=begin
v.each do |key, val|
	puts key
	puts val
end 
=end










