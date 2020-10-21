<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\DI\FactoryDefault as DefaultDI;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Crypt;
use Phalcon\Http\Response\Cookies;
use Phalcon\Db\Dialect\MySQL as SqlDialect;


/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new DefaultDI();

/**
 * We register the events manager
 */
$di->set('dispatcher', function () {

    $eventsManager = new EventsManager;

    /**
     * Check if the user is allowed to access certain action using the SecurityPlugin
     */
    $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);

    /**
     * Handle exceptions and not-found exceptions using NotFoundPlugin
     */
    $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin);

    $dispatcher = new Dispatcher;
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
        $url = new UrlProvider();
        $url->setBaseUri($config->application->baseUri);
        return $url;
    });


$di->set('view', function () use ($config) {

    $view = new View();

    //$api = new Api($config->api->base_uri, $config->api->timeout);

    $view->setViewsDir(APP_PATH . $config->application->viewsDir);

    $view->registerEngines(array(
        ".volt" => 'volt'
    ));

    return $view;
});

/**
 * Setting up volt
 */
$di->set('volt', function ($view, $di) {

    $volt = new VoltEngine($view, $di);

    $volt->setOptions(array(
        "compiledPath" => APP_PATH . "cache/volt/"
    ));

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
    $compiler->addFunction('in_array', 'in_array');
    $compiler->addFunction('number_format', 'number_format');
    $compiler->addFunction('isset', 'isset');

    return $volt;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
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

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('api', function () use ($config) {
    $api = new Api($config->api->baseUri, $config->api->timeout);
    return $api;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaData();
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () use ($config) {
    $session = new Session(array(
            'uniqueId' => 'my_session_' . $config->application->id
        ));
    $session->start();
    return $session;
});

/**
 * Register the flash service with custom CSS classes
 */
$di->set("flash", function () {
    $flash = new FlashSession();
    return $flash;
});

/**
 * Register the flash service with custom CSS classes
 */
$di->set('flash', function () {
    return new FlashSession(array(
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ));
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
Logger::init($config->logging);

// Init config storing ( for access with $di in controllers )
$di->set('config', $config);


/**
 * Register a user component
 */
$di->set('elements', function () {
    return new Elements();
});
