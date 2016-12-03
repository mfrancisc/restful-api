<?php
namespace lib;
/**
 * Request interface
 */
interface Request {
	public function setRequest(string $request);
    public function getRequest(): string;
    public function setMethod(string $method);
    public function getMethod(): string;
}