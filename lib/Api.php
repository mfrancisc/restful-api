<?php

/**
 * Class Api
 * Extend this class to create your APIs
 */
abstract class Api
{
    /**
     * the complete URI in array format
     */
    protected $args = Array();
    /**
     * the requested API in the URI. eg: /posts
     */
    protected $endpoint = '';
    /**
     * HTTP methods GET, POST, PUT or DELETE
     */
    protected $method = '';

    /**
     * @param $request
     * @param $method
     */
    public function __construct($request, $method)
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        // ADD AUTHENTICATION LOGIC HERE
        // TODO
        // .....

        $this->args = explode('/', $request);
        $this->api = $this->args[1];
        $this->version = $this->args[2];
        $this->endpoint = $this->args[3];
        $this->method = $method;
    }

    /**
     * Main method
     * @return string
     */
    public function run()
    {
        if (method_exists($this, $this->endpoint)) {
            return $this->response($this->{$this->endpoint}($this->args));
        }

        return $this->response("No endpoint: $this->endpoint", 404);
    }

    /****************************************
     * Implement the following methods
     * in your API
     * -------------------------------------/

    /**
     * @return mixed
     */
    abstract protected function delete();

    /**
     * @return mixed
     */
    abstract protected function update();

    /**
     * @return mixed
     */
    abstract protected function read();

    /**
     * @return mixed
     */
    abstract protected function create();
    /** -------------------------------------- */


    /**
     * Return the response
     * @param $data
     * @param int $status
     * @return string
     */
    protected function response($data, $status = 200)
    {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));

        return json_encode($data);
    }

    /**
     * Handle status code and descriptions
     * @param $code
     * @return mixed
     */
    private function _requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );

        return ($status[$code]) ? $status[$code] : $status[500];
    }

    /**
     * Choose which implementation method to call
     * based on the http request
     * @return string
     */
    protected function handleRequest()
    {
        switch ($this->method) {
            case 'DELETE':
                return $this->delete();
                break;

            case 'POST':
                return $this->update();
                break;

            case 'GET':
                return $this->read();
                break;

            case 'PUT':
                return $this->create();
                break;

            default:
                return $this->response('Invalid Method', 405);
                break;
        }
    }

    /**
     * Read parameters from std input
     * @return mixed
     */
    protected function readInput()
    {
        return json_decode(file_get_contents("php://input"));
    }
}