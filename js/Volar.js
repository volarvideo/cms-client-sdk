var Volar = {
	'base_url' : 'vcloud.volarvideo.com',
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
			$.ajax({
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