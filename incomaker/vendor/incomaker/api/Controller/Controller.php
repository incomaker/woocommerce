<?php

namespace Incomaker\Api\Controller;

class Controller {

    protected $apiKey;
    protected $server;

    public function setupController($apiKey, $server) {
        $this->apiKey = $apiKey;
        $this->server = $server;
    }

    protected function formatGetRequest($arr) {
        $request = array();
        foreach ($arr as $key => $val) {
            if (!empty($val)) {
                array_push($request, $key . "=" . urlencode($val));
            }
        }
        return "?" . implode("&", $request);
    }
    
    private function startQuery($method, $query) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->server . $method . $this->apiKey . $query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        return $ch;        
    }

    private function finishQuery($ch) {

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));

        $response = curl_exec($ch);
        $curlErrno = curl_errno($ch);
        curl_close($ch);
        if ($curlErrno != 0) {
            $json = (object) ['error' => curl_strerror($curlErrno)];
        } else {
            $json = json_decode($response);
        }
        return $json;
    }

    protected function curlPostQuery($method, $query, $postFields) {

        $ch = $this->startQuery($method, $query);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        return $this->finishQuery($ch);
    }

    protected function curlGetQuery($method, $query = "") {

        $ch = $this->startQuery($method, $query);
        return $this->finishQuery($ch);
    }

    protected function curlPutQuery($method, $query, $postFields = "") {

        $ch = $this->startQuery($method, $query);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        if ($postFields != "") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        }
        return $this->finishQuery($ch);
    }

    protected function curlDeleteQuery($method, $query) {

        $ch = $this->startQuery($method, $query);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        return $this->finishQuery($ch);
    }
}