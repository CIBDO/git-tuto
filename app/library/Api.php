<?php

require APP_PATH . "vendor/autoload.php";

use GuzzleHttp as GuzzleHttp;
use Phalcon\Config\Adapter\Ini as IniConfig;
use GuzzleHttp\Promise;

/**
 * Description of Api
 *
 * @author placan
 */
class Api {

    protected $client;
    protected $promise;
    public function __construct($uri, $timeOut) {
        $this->promise = array();
        //Get the current session
        $session = Phalcon\Di::getDefault()->getSession();
        $sessionUsr = $session->get('usr');
        $dateDeCo = $session->get('dateConnection');
        $this->client = new GuzzleHttp\Client([
            'base_uri' => $uri,
            'timeout' => $timeOut,
            'headers' => ['Authorization' => $sessionUsr['usrToken']]
        ]);
    }
    public function get($object, $params = []) {
        Logger::INFO("Send GET Method to API " . $this->client->getConfig('base_uri') . $object);
        return $this->client->request('GET', $object, ['http_errors' => false, 'future' => true, 'json' => $params]); //'verify' =>'C:\Workspace\bo-opsise\public\ca.pem' pour debug
    }

    public function post($object, $params = []) {
        Logger::INFO("Send POST Method to API " . $this->client->getConfig('base_uri') . $object);
        return $this->client->request('POST', $object, ['http_errors' => false, 'json' => $params]);
    }

    public function patch($object, $params = []) {
        Logger::INFO("Send PATCH Method to API " . $this->client->getConfig('base_uri') . $object);
        return $this->client->request('PATCH', $object, ['http_errors' => false, 'json' => $params]);
    }

    public function put($object, $params = []) {
        Logger::INFO("Send PUT Method to API " . $this->client->getConfig('base_uri') . $object);
        return $this->client->request('PUT', $object, ['http_errors' => false, 'json' => $params]);
    }

    public function delete($object, $params = []) {
        Logger::INFO("Send DELETE Method to API " . $this->client->getConfig('base_uri') . $object);
        return $this->client->request('DELETE', $object, ['http_errors' => false, 'json' => $params]);
    }



    /*
    *   Setter Promises
    */
    public function addPromises($object, $params = []){
        // Disable the HTTP errors to get them without exception thrown
        $this->promise [] = $this->client->getAsync($object, ['http_errors' => false, 'json' => $params]);
        return $this->promise;
    }

    public function getPromises(){
        return $this->promise;
    }

    /*
    *   Execute all promises of class, or custom promises in optional args
    *   @params promises ( optionnal )
    *   @return Array
    */

    public function execAllPromises($promises = ""){
        if($promises = ""){
            $results = Promise\unwrap($this->promise);
        }
        else
        {
            $results = Promise\unwrap($this->promise);
        }
        return $this->decodePromises($results);
    }

    protected function decodePromises($array){
        $results = [];
        foreach ($array as $key => $value) {
            $results[] = json_decode($value->getBody());
        }
        return $results;
    }



    public function getSomething($params = "",$promises = false, $shopList = null) {
        $strParam = "";

        if ($params != "") {
            $strParam = "/?" . http_build_query(array_filter($params));
        }

        $object = "pathurl" . $strParam;
        if($promises){
            $this->addPromises($object, $shopList);
            return;
        }
        $response = $this->get($object, $shopList);
        $res = json_decode($response->getBody());
        return $res;
    }

    public function getSomeOtherThings($id) {
        $object = "pathurl/" . $id;
        $response = $this->get($object);
        $res = json_decode($response->getBody());
        return $res;
    }
}
