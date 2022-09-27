<?php

namespace Incomaker\Api\Data;

/**
 * Description of OrderProducts
 */
class OrderProducts {

    private $products = array();

    public function addProduct(OrderProduct $data) {
        array_push($this->products, $data->getArrayData());
    }

    public function getProduct($index) {
        return $this->products[$index];
    }

    public function getArrayData() {
        return $this->products;
    }

    public function getData() {
        return json_encode(getArrayData());
    }

}
