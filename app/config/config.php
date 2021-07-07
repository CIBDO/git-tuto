<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'target_db',
        'charset'     => 'utf8',
    ),
    'application' => array(
        'id' 			            => 1,
        'adminEmail'                => 'cofacoul@hotmail.fr',
        'stucture_name_config'      => 'Opsise Health',
        'appName'                   => 'Opsise Health',
        'appShortName'              => 'Opsise Health',
        'transExterne'              => false,
        'appDir'                    => APP_PATH . '/',
        'controllersDir'            => APP_PATH . '/controllers/',
        'modelsDir'                 => APP_PATH . '/models/',
        'migrationsDir'             => APP_PATH . '/migrations/',
        'viewsDir'                  => APP_PATH . '/views/',
        'formsDir'                  => APP_PATH . '/forms/',
        'pluginsDir'                => APP_PATH . '/plugins/',
        'libraryDir'                => APP_PATH . '/library/',
        'cacheDir'                  => BASE_PATH . '/cache/',
        'baseUri'                   => '/',
        
    ),
    'logging' => array(
        'file'           	=> 'log/target.log',
        'level'          	=> 'DEBUG',
        'log_db_queries' 	=> 'yes',
        'syslog' 			=> 'LOG_USER',
    ),
    'activeModules' => [
        'modPharmacie'      => 1,
        'modLaboratoire'    => 1,
        'modImagerie'	    => 1,
        'modConsultation'   => 1,
        'modFinance'	    => 1,
    ],
    'oauth' => [
        'appId' => 1,
        'secret' => 'testsecret',
    ],
));
