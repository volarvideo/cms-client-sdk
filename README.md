cms-client-sdk
==============

php
---

The PHP SDK requires that the curl extension be installed.  if you don't have it installed and cannot get it installed using your hosting provider, you may need to do the requests through raw php code rather than through the SDK class.  Documentation on signing requests & making different requests is in the wiki for this repository.  Please note that if some requests you see in the code aren't documented in this file, the requests may not yet be available on the server side.

Additionally, the code requires that the json extension be enabled.  if you can't enable it, there are various json parsing libraries that you will have to work into the code (or declare the json_decode and json_encode functions, which are used throughout the code)

To use it, you will need to require the `Volar.php` class file in your scripts and instantiate the object by doing:

```php
$v = new Volar($api_key, $secret_key, $base_url);
```

You can then execute requests based on the documentation for each function (in the code).  to list all upcoming
broadcasts (for instance), you can do:

```php
$broadcasts = $v->broadcasts(array('list'=>'scheduled', 'site' => '<site slug>'));
```

If the function returns false, you can get what the error was by checking what $v->getError() returns.  There is also a $v->debug value you can grab that is set to what the last request URL was.  helpful if you need to see what is actually being sent to the server.

See `test.php` and `test_form.php` to see it in action.  Note that both of these files use the credentials as they are set in `test_config.php`.  There is also a sample site put together by one of our programmers included in the directory.

python
------
The Python SDK should be just as functional as the PHP code.  However, it requires the 'requests' python module, which can be gotten from http://docs.python-requests.org/en/latest/, as well as the json, base64, and hashlib modules (although those modules are typically already installed with python).  If you're unsure whether or not you have the modules already available, you can pull up the python interpreter on your server and importing them individually to see if you get errors.  Additionally, it has only been tested on Python 2.7, so if you have a version you need it to work on, help is needed for other python versions, and contributions are appreciated.

To use the python SDK, place the `volar.py` file somewhere where your other scripts can find it and do

```python
import volar
```

Then, you can instantiate the object:

```python
v = volar.Volar(base_url = given_base_url, api_key = given_api_key, secret = given_secret_key)
```

And you can then execute requests based on the documentation for each function (in the code).  to list all upcoming broadcasts (for instance), you can do:

```python
broadcasts = v.broadcasts({'site':'<site slug>', 'list': 'scheduled'})
```

If the function returns false, you can get the last error by checking the value of v.error.

ruby
----
At the moment, the Ruby SDK isn't yet complete.  Don't count on it working.  Contributions are welcome, and documentation on the requests are in the wiki for this repository