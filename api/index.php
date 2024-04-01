<?php

// dependeces

require_once(dirname(__FILE__) . '/inc/api_response.php');
require_once(dirname(__FILE__) . '/inc/config.php');
require_once(dirname(__FILE__) . '/inc/api_logic.php');
require_once(dirname(__FILE__) . '/inc/database.php');

// instatiate the api_class

$api_response = new api_response;


//check the method
if(!$api_response->check_method($_SERVER['REQUEST_METHOD'])){
    // send error response
    $api_response -> api_request_error("Invalid request method");
}

//========================================================================================
// set request method
$api_response -> set_method($_SERVER['REQUEST_METHOD']);
$params = null;
if($api_response -> get_method() == 'GET'){
    $api_response -> set_endpoint($_GET['endpoint']);
    $params = $_GET;
}else if($api_response -> get_method() == 'POST'){
    $api_response -> set_endpoint($_POST['endpoint']);
    $params = $_POST;
}


//========================================================================================
// prepare the api logic
$api_logic = new api_logic($api_response -> get_endpoint(), $params);

//---------------------------------
// check if request endpoint exists
if(!$api_logic -> endpoint_exists()){
    $api_response -> api_request_error("Inexistent endpoint: ". $api_response -> get_endpoint());
}

// request to the api logic
$result = $api_logic -> {$api_response -> get_endpoint()}(); // o que esta dentro de chaveta e o resultado, se por exemplo o endpoint do get fosse status, seria algo como: status(), dentro de chavetas e o resultado do metodo: status, e depois acrescentando o ();
$api_response -> add_to_data('data', $result); // Adiciona o retorno do endpoint dentro da chave data
$api_response -> send_response();
// $api_response -> send_api_status();


