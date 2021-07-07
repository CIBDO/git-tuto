<?php
declare(strict_types=1);

use Phalcon\Escaper;
use Phalcon\Flash\Session as Flash;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Url as UrlResolver;

use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Session\Bag;

use Phalcon\Acl\Adapter\Memory;

use Phalcon\Crypt;
use Phalcon\Http\Response\Cookies;
use Phalcon\Db\Dialect\MySQL as SqlDialect;
use Phalcon\Di\FactoryDefault;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

$di->set('dispatcher', function () {
    $eventsManager = new EventsManager();

    /**
     * Check if the user is allowed to access certain action using the SecurityPlugin
     */
    $eventsManager->attach('dispatch:beforeExecuteRoute', new SecurityPlugin);

    /**
     * Handle exceptions and not-found exceptions using NotFoundPlugin
     */
    $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'path' => $config->application->cacheDir,
                'separator' => '_'
            ]);

            $compiler = $volt->getCompiler();
            $compiler->addFunction('is_a', 'is_a');
            $compiler->addFunction('in_array', 'in_array');
            $compiler->addFunction('strstr', 'strstr');
            $compiler->addFunction('substr', 'substr');
            $compiler->addFunction('strlen', 'strlen');
            $compiler->addFunction('sizeof', 'sizeof'); 
            $compiler->addFunction('count', 'count'); 
            $compiler->addFunction('strtotime', 'strtotime');
            $compiler->addFunction('date', 'date');
            $compiler->addFunction('number_format', 'number_format');
            $compiler->addFunction('isset', 'isset');

            return $volt;
        },
        '.phtml' => PhpEngine::class
    ]);

    return $view;
});
/**
 * Database connection is created based in the parameters defined in the configuration file
 */
/*
$di->set('db', function () use ($config) {

    $dialect = new SqlDialect();
    // Register a new function called DIALECT_GET_AGE
    $dialect->registerCustomFunction(
        'DIALECT_GET_AGE',
        function($dialect, $expression) {
            $arguments = $expression['arguments'];
            return sprintf(
                " TIMESTAMPDIFF(YEAR, (%s), CURDATE() ) ",
                $dialect->getSqlExpression($arguments[0])
             );
        }
    );

    $dbConfig = $config->get('database')->toArray();
    $dbConfig["dialectClass"] = $dialect;
    
    $dbClass = 'Phalcon\Db\Adapter\Pdo\\' . $dbConfig['adapter'];
    unset($dbConfig['adapter']);
    $connection = new $dbClass($dbConfig);
    if (isset($config->get('config')->logging->log_db_queries) && $di->get('config')->logging->log_db_queries == true) {
        $events_manager = new EventsManager();
        $db_listener = new DBListener();
        $events_manager->attach('db', $db_listener);
        $connection->setEventsManager($events_manager);
    }

    return $connection;
});
*/
$di->setShared('db', function () {
    $config = $this->getConfig();

    $dialect = new SqlDialect();
    // Register a new function called DIALECT_GET_AGE
    $dialect->registerCustomFunction(
        'DIALECT_GET_AGE',
        function($dialect, $expression) {
            $arguments = $expression['arguments'];
            return sprintf(
                " TIMESTAMPDIFF(YEAR, (%s), CURDATE() ) ",
                $dialect->getSqlExpression($arguments[0])
             );
        }
    );

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];
    $params["dialectClass"] = $dialect;

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});
/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('api', function () {
    $api = new Api($config->api->baseUri, $config->api->timeout);
    return $api;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    $escaper = new Escaper();
    $flash = new Flash($escaper);
    $flash->setImplicitFlush(true);
    $flash->setCssClasses([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);

    return $flash;
});

$di->setShared('sessionBag', function () {
    return new Bag('bag');
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $config = $this->getConfig();

    $session = new SessionManager(array(
            'uniqueId' => 'my_session_' . $config->application->id
    ));
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});


// Cookie manager
$di->set('cookies', function () {
    $cookies = new Cookies();

    $cookies->useEncryption(false);

    return $cookies;
});

$di->set('crypt', function () {
    $crypt = new Crypt();

    $crypt->setKey('#1dj8$=dp?.ak//j1V$'); // Use your own key!

    return $crypt;
});
$di->set("colorsPalette", function () {
    // Add Color palette
    $colorsPalette = array("#FF0F00", "#FF6600", "#FF9E01", "#FCD202", "#F8FF01", "#B0DE09", "#04D215", "#0D52D1", "#2A0CD0", "#8A0CCF" , "#CD0D74", "#71A4CB", "#367fa9", "#2169A0", "#F97723", "#1ec429", "#46CCF4");
    return $colorsPalette;
});

//Init logger from library logger.php class
//Logger::init($config->logging);

/**
 * Register a user component
 */
$di->set('elements', function () {
    return new Elements();
});
