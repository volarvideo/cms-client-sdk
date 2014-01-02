require '../volar.rb'
require 'parseconfig'
require "test/unit"


class TestEditBroadcast < Test::Unit::TestCase
	def setup
		config = ParseConfig.new('./sample.cfg')
		base = config['settings']['base_url']
		api = config['settings']['api_key']
		sec = config['settings']['secret']
		@v = Volar.new(api_key= api, secret= sec, base_url= base)
		@response=nil
		@broadcast=nil
		@playlist=nil
		@template=nil
		@testCategories={
			'Sites'=>[
				fetch_sites
			],
			'Broadcasts'=>[
				fetch_broadcasts,
				create_broadcast,
				update_broadcast,
				poster_broadcast,
				archive_broadcast,
				delete_broadcast
			],
			'Playlists'=>[
				create_broadcast,
				create_playlist,
				update_playlist,
				assign_playlist,
				remove_playlist,
				delete_playlist,
				delete_broadcast
			],
			'Templates'=>[
				fetch_templates,
				create_template,
				update_template,
				delete_template
			],
			'Sections'=>[
				fetch_sections
			]
		}
	end 

	def test_all		
		@testCategories.each do |category, test|
		end
	end

	def fetch_sites
		@response=@v.sites()
		assert(@response, "Failed to fetch sites.\n"+String(@response))
		if @response
			puts "\nSites fetched."
		end 
	end

	def fetch_broadcasts
		parameters={'site'=>'sdk-tests'}
		@response= @v.broadcasts(params=parameters)
		assert(@response, "Failed to fetch broadcasts.\n"+String(@response))
		if @response
			puts "Broadcasts fetched."
		end 
	end

	def create_broadcast
		parameters={'site'=>'sdk-tests', 'title'=> 'Ruby_SDK_Test', 'contact_name'=>'Ruby', 'contact_phone'=>'555-555-5555', 'contact_sms'=>'555-456-7890', 'contact_email'=>'yoda@starwars.com'}
		@response= @v.broadcast_create(params=parameters)
		assert(@response['success'], "Failed to create broadcast.\n"+String(@response))
		@broadcast=@response['broadcast']
		if @response['success']
			puts 'Broadcast created.'
		end 
		sleep(1)
	end

	def update_broadcast
		#Create_broadcast must be run before this in order to have a valid ID.
		puts "Updating broadcasts: "
		#update title
		parameters={'site'=>'sdk-tests', 'id'=>@broadcast['id'], 'title'=>'Title_Update'}
		@response= @v.broadcast_update(params=parameters)
		assert(@response['success'], "Failed to update broadcast title.\n"+String(@response))
		if @response['success']
			puts "\tTitle updated."
		end
		sleep(1)

		#update date
		parameters={'site'=>'sdk-tests', 'id'=>@broadcast['id'], 'date'=>'01/12/2013'}
		@response= @v.broadcast_update(params=parameters)
		assert(@response['success'], "Failed to update broadcast date.\n"+String(@response))
		if @response['success']
			puts "\tDate updated."
		end
		sleep(1)

		#update description
		parameters={'site'=>'sdk-tests', 'id'=>@broadcast['id'], 'description'=>'Ruby broadcast update.'}
		@response= @v.broadcast_update(params=parameters)
		assert(@response['success'], "Failed to update broadcast description.\n"+String(@response))
		if @response['success']
			puts "\tDescription updated."
		end
		sleep(1)

		#update section
		parameters={'site'=>'sdk-tests', 'id'=>@broadcast['id'], 'section_id'=>2}
		@response= @v.broadcast_update(params=parameters)
		assert(@response['success'], "Failed to update broadcast section_id.\n"+String(@response))
		if @response['success']
			puts "\tSection updated."
		end 
		sleep(1)

	end

	def poster_broadcast
		#Create_broadcast must be used before this in order to have a valid ID.
		parameters={'site'=> 'sdk-tests', 'id'=> @broadcast['id']}
		@response= @v.broadcast_poster(params=parameters, file_path='ruby-mini-logo.png')
		assert(@response['success'], "Failed to add a poster to the broadcast.\n"+String(@response))
		if @response
			puts "\tPoster uploaded to broadcast."
		end
		sleep(1)

	end

	def archive_broadcast
		#Create_broadcast must be used before this in order to have a valid ID.
		parameters={'site'=> 'sdk-tests', 'id'=> @broadcast['id']}
		@response= @v.broadcast_archive(params=parameters, file_path='test.mp4')
		assert(@response['success'], "Failed to archive the broadcast.\n"+String(@response))
		if @response
			puts "\tBroadcast archived."
		end	
		sleep(1)

	end

	def delete_broadcast
		#Create_broadcast must be used before this in order to have a valid ID.
		parameters={'site'=>'sdk-tests', 'id'=>@broadcast['id']}
		@response= @v.broadcast_delete(params=parameters)
		assert(@response['success'], "Failed to delete broadcast.\n"+String(@response))
		@broadcast=nil
		if @response['success']
			puts 'Broadcast deleted.'
		end
		sleep(1)

	end

	def fetch_playlists
		parameters={'site'=>'sdk-tests'}
		@response= @v.playlists(params=parameters)
		assert(@v.response, "Failed to fetch playlists.\n"+String(@response))
		if @response
			puts "Playlists fetched."
		end 
		sleep(1)

	end

	def create_playlist
		parameters={'site'=>'sdk-tests', 'title'=>'Ruby_SDK_Playlist'}
		@response= @v.playlist_create(params=parameters)
		assert(@response['success'], "Failed to create a playlist.\n"+String(@response))
		@playlist=@response['playlist']
		if @response['success']
			puts "Playlist created."
		end
		sleep(1)

	end

	def update_playlist
		#Create_playlist must be run prior to using this function.
		puts "Updating playlist: "

		#Update title
		parameters={'site'=>'sdk-tests', 'id'=>@playlist['id'], 'title'=>'Playlist_Title_Update'}
		@response= @v.playlist_update(params=parameters)
		assert(@response['success'], "Failed to update playlist title.\n"+String(@response))
		if @response['success']
			puts "\tTitle updated."
		end
		sleep(1)


		#Update description
		parameters={'site'=>'sdk-tests', 'id'=>@playlist['id'], 'description'=>'Playlist description update.'}
		@response= @v.playlist_update(params=parameters)
		assert(@response['success'], "Failed to update playlist title.\n"+String(@response))
		if @response['success']
			puts "\tDescription updated."
		end	
		sleep(1)

	end

	def assign_playlist
		#Create_broadcast and create_playlist must be used before this function.
		parameters={'site'=>'sdk-tests', 'id'=>@broadcast['id'], 'playlist_id'=>@playlist['id']}
		@response= @v.broadcast_assign_playlist(params=parameters)
		assert(@response['success'], "Failed to assign a broadcast to a playlist.\n"+String(@response))
		if @response['success']
			puts "Broadcast assigned to a playlist."
		end
		sleep(1)

	end

	def remove_playlist
		#Create_broadcast and create_playlist must be used before this function.
		parameters={'site'=>'sdk-tests', 'id'=>@broadcast['id'], 'playlist_id'=>@playlist['id']}
		@response= @v.broadcast_remove_playlist(params=parameters)
		assert(@response['success'], "Failed to remove a broadcast from a playlist.\n"+String(@response))
		if @response['success']
			puts "Broadcast removed from a playlist."
		end
		sleep(1)

	end

	def delete_playlist
		#Create_playlist must be run prior to using this function.
		parameters={'site'=>'sdk-tests', 'id'=>@playlist['id']}
		@response= @v.playlist_delete(params=parameters)
		assert(@response['success'], "Failed to delete playlist.\n"+String(@response))
		@playlist=nil
		if @response['success']
			puts "Playlist deleted."
		end 
		sleep(1)

	end

	def fetch_templates
		parameters={'site'=>'sdk-tests'}
		@response= @v.templates(params=parameters)
		assert(@response, "Failed to fetch templates.\n"+String(@response))
		if @response
			puts "Templates fetched."
		end 
		sleep(1)
	end

	def create_template
		parameters={'site'=>'sdk-tests', 'title'=>'Ruby_SDK_Template', 'data'=>[{'title'=> 'Temp title', 'type'=>'single-line'}]}
		@response= @v.template_create(params=parameters)
		assert(@response['success'], "Failed to create template.\n"+String(@response))
		@template= @response['template']
		if @response['success']
			puts "Template created."
		end
	end

	def update_template
		#Create_template must be run prior to using this function.
		puts "Updating templates: "
		
		#Update title
		parameters={'site'=>'sdk-tests', 'id'=>@template['id'], 'title'=>'Ruby_Template_Update'}
		@response= @v.template_update(params=parameters)
		assert(@response['success'], "Failed to update template title.\n"+String(@response))
		if @response['success']
			puts "\tTitle updated."
		end 

		#Update template
		parameters={'site'=>'sdk-tests', 'id'=>@template['id'], 'description'=>'Ruby description update.'}
		@response= @v.template_update(params=parameters)
		assert(@response['success'], "Failed to update template description.\n"+String(@response))
		if @response['success']
			puts "\tDescription updated."
		end 
	end

	def delete_template
		#Create_template must be run prior to using this function.
		parameters={'site'=>'sdk-tests', 'id'=>@template['id']}
		@response= @v.template_delete(params=parameters)
		assert(@response['success'], "Failed to delete template.\n"+String(@response))
		if @response['success']
			puts "Template deleted."
		end
	end

	def fetch_sections
		parameters={'site'=>'sdk-tests'}
		@response= @v.sections(params=parameters)
		assert(@response, "Failed to fetch sections.\n"+String(@response))
		if @response
			puts "Sections fetched."
		end
	end 
end 



