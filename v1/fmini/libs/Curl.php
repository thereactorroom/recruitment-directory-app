<?php

/**
 * Curl short summary.
 *
 * Curl description.
 *
 * @version 1.0
 * @author Johannes Ramothale <johannes@fusion>
 * 
 * HttpCode:
 * 200 = Ok
 * 0 = Api Offline: Error
 * Other = Api error: json()->detail
 */

final class Curl {

    protected $secured = true;
    protected $contentType = 'application/json';
    protected $header = [];
    protected $response;
    protected $error = "";
    protected $apiKey = "65bb623cebe5aec2e95d3a62f67c8b27f781b0eb1617aa837ccabe7d6f432729";

    function __construct(){
        // add default values
        $this->header[] = 'apiKey: ' . $this->apiKey;
    }

    public function __init($params = false){
        if($params){
            // set values from parameters
            if(array_key_exists('secured', $params)){
                $this->secured = $params['secured'];
            }
            if(array_key_exists('contenttype', $params)){
                $this->contentType = $params['contenttype'];
            }
            if(array_key_exists('apiKey', $params)){
                $this->apiKey = $params['apiKey'];
            } 
            
            // assign values to header
            $this->header[] = 'apiKey: ' . $this->apiKey;
            $this->header[] = 'Content-Type: ' . $this->contentType;
        }
    }

    public function raw(){
        if(isset($this->response)){
            return $this->response;
        }
        return false;
    }

    public function json(){
        if(isset($this->response)){
            return json_decode($this->response);
        }
        return false;
    }

    public function error(){
        if(!$this->response && isset($this->error)){
            return $this->error;
        }
        return false;
    }

    public function error_codes(){
        return [403, 400, 404, 500];
    }

    public function info(){
        if(isset($this->info)){
            return $this->info;
        }
        return false;
    }

    public function get($url, $return = false){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->secured);

        $this->response = curl_exec($ch);
        $this->info = curl_getinfo($ch);
        
        if($this->response === false){
            $this->error = curl_error($ch);
        }
        curl_close($ch);

        if($return){
            return json_decode($this->response);
        }
    }

    public function post($url, $payload){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);        
        curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->secured);
        
        $this->response = curl_exec($curl);
        $this->info = curl_getinfo($curl);
        
        if($this->response === false){
            $this->error = curl_error($curl);
        }
        curl_close($curl);
    }

    public function postJson($url, $payload){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);        
        curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->secured);
        
        $this->response = curl_exec($curl);
        $this->info = curl_getinfo($curl);
        
        if($this->response === false){
            $this->error = curl_error($curl);
        }
        curl_close($curl);
    }

}