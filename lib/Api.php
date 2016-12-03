<?php
namespace lib;
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
     * HTTP status code and descriptions
     */
    protected $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );

    /**
     * Custom API message
     */
    const RES_SUCESS = "Request completed successfully";
    const RES_FAILURE = "Errors while executing request";

    /**
     * @param $request
     * @param $method
     */
    public function __construct(Request $request)
    {
        $this->_setHeader();

        // ADD AUTHENTICATION LOGIC HERE
        // TODO
        // .....

        $this->_setParams($request); 
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
    protected function response($data,int $status = 200): string
    {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));

        return json_encode($data);
    }

    /**
     * Choose which implementation method to call
     * based on the http request
     * @return string|mixed|bool
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
    
    /**
     * Allow CORS and json content type
     */
    private function _setHeader()
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
    }

    /**
     * Set parameters from request URI
     * @param string $request 
     */
    private function _setParams(Request $request)
    {
        $this->args = explode('/', $request->getRequest());
        $this->api = $this->args[1];
        $this->version = $this->args[2];
        $this->endpoint = $this->args[3];
        $this->method = $request->getMethod();
    }

    /**
     * Handle status code and descriptions
     * @param $code
     * @return string
     */
    private function _requestStatus($code): string
    {
        return ($this->status[$code]) ?? $this->status[500];
    }
}