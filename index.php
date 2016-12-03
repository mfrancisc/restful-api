<?php
/**
 * entry point for all API calls
 */

// load db configurations
require_once '.' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db.php';

// autoload apis
spl_autoload_register(function ($class) {
    $path = getcwd() . DIRECTORY_SEPARATOR . $class;
    $split = explode('\\', $path);
    $location = implode('/', $split) . '.php';
    include $location;
});

// group use declarations
use lib\{Request, posts, API};

// handle API call
try {

    // request info
    $request = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    $requestArray = explode('/', $request);

    // anonymous classes
    $Request = new class implements Request {
        private $request;
        private $method;
        public function setRequest(string $request)
        {
            $this->request = $request;
        }

        public function getRequest(): string
        {
            return $this->request;
        }

        public function setMethod(string $method)
        {
            $this->method = $method;
        }

        public function getMethod(): string
        {
            return $this->method;
        }


    };

    $Request->setRequest($request);
    $Request->setMethod($method);

    // try to load requested api es: api/v1/apiname
    $api = new posts($Request);
    echo $api->run();

// catch FATAL ERRORS
} catch (Throwable $t) {
    echo json_encode(array('error' => $t->getMessage()));
}