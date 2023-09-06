<?php

//ini_set('default_charset', "UTF-8");
header('Content-type: text/html; charset=UTF-8');

ob_start();

define('BOT_TOKEN', '1079939636:AAGwdMHmAo6b20FSj5ewwNTgVm42e6Qx0R0');
define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

function apiRequestWebhook($method, $parameters)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");
        return false;
    } //!is_string($method)
  
	 
    if (!$parameters) { 
        $parameters = array();
    } //!$parameters
    else if (!is_array($parameters)) {
        error_log("Parameters must be an array\n");
        return false;
    } //!is_array($parameters)
    
    $parameters["method"] = $method;
    
    header("Content-Type: application/json");
    echo json_encode($parameters);
    return true;
}

function exec_curl_request($handle)
{
    $response = curl_exec($handle);
    
    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl returned error $errno: $error\n");
        curl_close($handle);
        return false;
    } //$response === false
    
    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);
    
    if ($http_code >= 500) {
        // do not wat to DDOS server if something goes wrong
        sleep(10);
        return false;
    } //$http_code >= 500
    else if ($http_code != 200) {
        $response = json_decode($response, true);
        error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
        if ($http_code == 401) {
            throw new Exception('Invalid access token provided');
        } //$http_code == 401
        return false;
    } //$http_code != 200
    else {
        $response = json_decode($response, true);
        if (isset($response['description'])) {
            error_log("Request was successfull: {$response['description']}\n");
        } //isset($response['description'])
        $response = $response['result'];
    }
    
    return $response;
}

function apiRequest($method, $parameters)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");
        return false;
    } //!is_string($method)
    
    if (!$parameters) {
        $parameters = array();
    } //!$parameters
    else if (!is_array($parameters)) {
        error_log("Parameters must be an array\n");
        return false;
    } //!is_array($parameters)
    
    foreach ($parameters as $key => &$val) {
        // encoding to JSON array parameters, for example reply_markup
        if (!is_numeric($val) && !is_string($val)) {
            $val = json_encode($val);
        } //!is_numeric($val) && !is_string($val)
    } //$parameters as $key => &$val
    $url = API_URL . $method . '?' . http_build_query($parameters);
    
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    
    return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters)
{
    if (!is_string($method)) {
        error_log("Method name must be a string\n");
        return false;
    } //!is_string($method)
    
    if (!$parameters) {
        $parameters = array();
    } //!$parameters
    else if (!is_array($parameters)) {
        error_log("Parameters must be an array\n");
        return false;
    } //!is_array($parameters)
    
    $parameters["method"] = $method;
    
    $handle = curl_init(API_URL);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
    curl_setopt($handle, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
    ));
    
    return exec_curl_request($handle);
}

function processMessage($message)
{
    // process incoming message
    $message_id = $message['message_id'];
    $chat_id    = $message['chat']['id'];
	
    if (isset($message['text'])) {
        // incoming text message
        $text = $message['text'];
        
        if (strpos($text, "/start") === 0) {
            
		$param_usr['usr_id_tu'] =$chat_id;
		$param_usr['user_name_tel_tu']=$message['chat']['username'];
  //  save_usr_db_fun($param_usr);
	
   

define('WEBHOOK_URL', 'http://salmansiadat.ir/botcs50/');

if (php_sapi_name() == 'cli') {
    // if run from console, set or delete webhook
    apiRequest('setWebhook', array(
        'url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL
    ));
    exit;
} 


$content = file_get_contents("php://input");
$update  = json_decode($content, true);
  print_r($update);
  file_put_contents("log2", ob_get_clean());
if (!$update) {
    // receive wrong update, must not happen
    exit;
} //!$update

else if (isset($update["message"])) {

	
	
    processMessage($update["message"]);
    
    ///////////////////////////////////////
    
    file_put_contents("log", ob_get_clean());
} 
