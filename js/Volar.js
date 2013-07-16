/*! Volar v0.1 | (c) 2013 Volar Video, Inc.
//	contains 1 function that takes an object argument describing the request.
//	note that jquery is not required - calls to the Volar.broadcasts() function without
//		jquery installed will result in new script tags being inserted into your document
*/

var Volar = {
	'base_url' : 'vcloud.volarvideo.com',

	/**
	 *	gets list of broadcasts
	 *	@param object opts
	 *			recognized parameters in object:
	 *				- required -
	 *				'site'				slug of site to filter to.
	 *				'callback'			javascript function name that should be executed once the jsonp call is completed.
	 *										Actual function pointers are supported.
	 *				- optional -
	 *				'list'				type of list.  allowed values are 'all', 'archived', 'scheduled' or 'upcoming', 'upcoming_or_streaming', 'streaming' or 'live'
	 *				'page'				current page of listings.  pages begin at '1'
	 *				'per_page'			number of broadcasts to display per page
	 *				'section_id'		id of section you wish to limit list to
	 *				'playlist_id'		id of playlist you wish to limit list to
	 *				'id'				id of broadcast - useful if you only want to get details of a single broadcast
	 *				'title'				title of broadcast.  useful for searches, as this accepts incomplete titles and returns all matches.
	 *				'autoplay'			true or false.  defaults to false.  used in embed code to prevent player from immediately playing
	 *				'embed_width'		width (in pixels) that embed should be.  defaults to 640
	 *				'before' 			return broadcasts that occur before specified date.  can be a date string or integer timestamp.  note that date strings should be in standard formats.
	 *				'after' 			return broadcasts that occur after specified date.  can be a date string or integer timestamp.  note that date strings should be in standard formats.
	 *										note - if both before and after are included, broadcasts between the supplied dates are returned.
	 *				'sort_by'			data field to use to sort.  allowed fields are date, status, id, title, description
	 *				'sort_dir'			direction of sort.  allowed values are 'asc' (ascending) and 'desc' (descending)
	 *	Because the function operates on jsonp, the function supplied in the 'callback' argument is called with the returned data.
	 *	The function should be able to handle an object that is structured with the data as listed on 
	 *		https://github.com/volarvideo/cms-client-sdk/wiki/Creating-API-Connections-without-the-SDK-code#listing-broadcasts
	 */

	'broadcasts' : function(opts) {
		if(!('callback' in opts))
		{
			this.log('"callback" argument is required.')
			return;
		}
		if(!('site' in opts))
		{
			this.log('"site" argument is required.')
			return;
		}
		var api_url = ('https:' == document.location.protocol ? 'https://' : 'http://') + this.base_url + '/api/client/broadcast';
		opts['cache_breaker'] = Math.random();

		var fake = '';
		if(typeof opts['callback'] == 'function')
		{
			//function was passed.  since we have to translate it to a string, we have to stick it into a fake function name
			do
			{
				fake = 'Volar_' + Math.floor(Math.random() * 100000);
			}
			while((fake in window));
			window[fake] = function(data) {
				opts['callback'](data);
			}
		}

		if('jQuery' in window)
		{
			var data = {};
			for(i in opts)
			{
				if(i == 'callback' && fake != '')
				{
					data[i] = fake;
				}
				else
				{
					data[i] = opts[i];
				}
			}
			jQuery.ajax({
				// 'url' : 'http://local.platypus.com/api/client/broadcast',
				'url' : api_url,
				'data' : data,
				'dataType' : 'jsonp',
				'jsonpCallback' : fake ? fake : opts['callback']
			});
		}
		else
		{
			var args = '';
			for(i in opts)
			{
				if(i == 'callback' && fake != '')
				{
					args += (args == '' ? '?' : '&') + 'callback' + '=' + fake;
				}
				else
				{
					args += (args == '' ? '?' : '&') + i + '=' + opts[i];
				}
			}
			(function() {
				var script = document.createElement('script');
				script.type = 'text/javascript';
				script.async = true;
				script.src = api_url + args;
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(script, s);
				// s.parentNode.appendChild(script);
			})();
		}
	},
	'log' : function(message) {
		window.console && console.log(message);
	}
}