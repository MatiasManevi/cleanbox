<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class CI_Zend
{
	public function __construct($class = NULL)
	{

		ini_set('include_path',
		ini_get('include_path') . PATH_SEPARATOR . BASEPATH . 'libraries');

		if ($class)
		{
			require_once (string) $class . EXT;
			log_message('debug', "Zend Class $class Loaded");
		}
		else
		{
			log_message('debug', "Zend Class Initialized");
		}
	}

	public function load($class)
	{
		require_once (string) $class . EXT;
		log_message('debug', "Zend Class $class Loaded");
	}
}
//Fatal error: require_once() [function.require]: Failed opening required 'Zend/Gdata/Media.php' (include_path='.:/usr/local/lib/php-5.2.17/lib/php') in /hermes/bosweb/web206/b2067/ipg.sobrelawebcom/developer/goldgym/system/libraries/Zend/Gdata/YouTube.php on line 27
//Fatal error: require_once() [function.require]: Failed opening required 'Zend/Gdata/Media.php' (include_path='.:/usr/local/lib/php-5.2.17/lib/php:application/libraries') in /hermes/bosweb/web206/b2067/ipg.sobrelawebcom/developer/goldgym/system/libraries/Zend/Gdata/YouTube.php on line 27