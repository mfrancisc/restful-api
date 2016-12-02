<?php
require './API.php';
try {
	$API = new API($_SERVER['PATH_INFO']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}