require 'rubygems'
require 'rest-client'

response = RestClient.get 'http://localhost/~gaberankin/test/pythonupload'
puts response.to_s
