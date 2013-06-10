require 'rubygems'
require 'rest-client'
require 'base64'
require 'digest/sha2'
require 'json'

class Volar
	attr_accessor :api_key
	attr_accessor :secret_key
	attr_accessor :base_url
	attr_accessor :error
	attr_accessor :secure

	def initialize(api_key, secret_key, base_url)
		@api_key = api_key
		@secret_key = secret_key
		@base_url = base_url
		@error = nil
		@secure = false
	end

	# def request(route, type = '', params = {}, post_body = nil)
	# end



	def buildSignature(route, type = '', get_params = {}, post_body = nil)
		type = (type ? type : 'GET').to_s.upcase;
		get_params   = get_params.sort { |a, b| a[0].to_s <=> b[0].to_s }
		route.chomp!('/').reverse!.chomp!('/').reverse!	#i'm sure there's a not stupid way to do this.  if there isn't, then i am glad i'm not primarily a ruby programmer
		
		stringToSign = @secret_key.to_s + type.to_s + route.to_s + (get_params.map { |param| param.join('=') }.join);
		if post_body != nil and post_body.kind_of?(String)
			stringToSign += post_body
		end

		signature = Base64::encode64(Digest::SHA256.digest(stringToSign))[0..42]
		signature.chomp!('=')
		signature

		# if(!is_array($post_body))
		# 	$stringToSign .= $post_body;

		# $signature = base64_encode(hash('sha256', $stringToSign, true));
		# $signature = urlencode(substr($signature, 0, 43));
		# $signature = rtrim($signature, '=');

		# return $signature;
	end

end