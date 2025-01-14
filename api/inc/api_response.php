<?php

class api_response{

    private $data;
    private $available_methods = ['GET', 'POST'];

    //========================================================================================
    public function __construct()
    {
        $this -> data = [];
    }

    //========================================================================================
    public function check_method($method){
        // check if method is valid
        return in_array($method, $this -> available_methods);
    }

    //========================================================================================
    public function set_method($method){
        // sets the response method
        $this -> data['method'] = $method;
    }

    //========================================================================================
    public function get_method(){
        // returns the request method
        return $this -> data['method'];
    }

    //========================================================================================
    public function set_endpoint($endpoint){
        // sets the request endpoint
        $this -> data['endpoint'] = $endpoint;
    }

    //========================================================================================
    public function get_endpoint(){
        // returns the current request endpoint
        return $this -> data['endpoint'];
    }

    //========================================================================================
    public function api_request_error($message = ''){
        // api message output

        $data_error = [
            'status' => "ERROR",
            'message' => $message,
            'results' => null
        ];

        $this -> data['data'] = $data_error;
        $this -> send_response();
        die(1);
    }

    //========================================================================================
    public function send_api_status(){
        // send api status
        
        $this -> data['status'] = "SUCCESS";
        $this -> data['message'] = "API running Ok!";
        $this -> send_response();
    }

    //========================================================================================
    public function send_response(){
        // final response output
        header("Content-Type:application/json");
        echo json_encode($this -> data);
    }


    public function add_to_data($key, $value){
        $this -> data[$key] = $value;
    }
}