<?php

namespace Incomaker\Api\Data;

/**
 * Description of Products
 */
class Products {

    private $products = array();

    function __construct() {
        
    }

    public function addProduct(Product $data) {
        array_push($this->products, $data->getArrayData());
    }

    public function getProduct($index) {
        return $this->products[$index];
    }

    public function getArrayData() {
        return $this->products;
    }

    public function getData() {
        return json_encode($this->getArrayData());
    }

}
