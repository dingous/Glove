<?php
//die(utf8_decode("<marquee>TEMPORARIAMENTE INDISPONÃ�VEL. ACESSE NOVAMENTE EM ALGUMAS HORAS.</marquee>"));

if (@substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 'on');
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 30000000);



/*echo "<pre>";
print_r(ini_get_all());
echo "</pre>";*/



// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));



// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));





/** Zend_Application */
require_once 'Zend/Application.php'; 


//Lumine
require_once "Lumine_ApplicationContext.php";





// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
			
Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(false);	
