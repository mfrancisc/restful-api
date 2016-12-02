<?php
/**
 * entry point for all API calls
 */

// load db configurations
require_once '.' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db.php';

// autoload function
function __autoload($classname)
{
    $filename = $classname . ".php";


    // default api path
    $filePath = 'lib' . DIRECTORY_SEPARATOR . $filename;
    if ( ! file_exists($filePath)) throw new Exception("No Api: " . $classname);

    require_once $filePath;
}

// handle API call
try {

    // parameters
    $request = $_SERVER['PATH_INFO'];
    $method = $_SERVER['REQUEST_METHOD'];
    $requestArray = explode('/', $request);

    // try to load requested api es: api/v1/apiname
    $api = new $requestArray[3]($request, $method);
    echo $api->run();

} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}