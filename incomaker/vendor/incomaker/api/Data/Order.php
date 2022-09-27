<?php

namespace Incomaker\Api\Data;

use Incomaker\Api\Data\OrderProduct;
use Incomaker\Api\Data\OrderProducts;

/**
 * Description of Order
 */
class Order {

    private $permId;
    private $orderId;
    private $contactId;
    private $timestampCreate;
    private $products;
    private $discount;
    private $paymentMethod;
    private $currency;

    function __construct($permId) {
        $this->permId = $permId;
        $this->products = new OrderProducts();
    }

    public function getArrayData() {
        $arrayData = array();
        if (!is_null($this->permId)) {
            $arrayData["permId"] = $this->permId;
        }
        if (!is_null($this->orderId)) {
            $arrayData["orderId"] = $this->orderId;
        }
        if (!is_null($this->contactId)) {
            $arrayData["contactId"] = $this->contactId;
        }
        if (!is_null($this->discount)) {
            $arrayData["discount"] = $this->discount;
        }
        if (!is_null($this->timestampCreate)) {
            $arrayData["timestampCreate"] = $this->timestampCreate;
        }
        if (!is_null($this->paymentMethod)) {
            $arrayData["paymentMethod"] = $this->paymentMethod;
        }
        if (!empty($this->products)) {
            $arrayData["products"] = $this->products->getArrayData();
        }
        return $arrayData;
    }

    public function getData() {
        return json_encode($this->getArrayData());
    }

    public function addProduct($productId, $quantity, $pricePerUnit, $vat) {
        $this->products->addProduct(new OrderProduct($productId, $quantity, $pricePerUnit, $vat));
    }

    function getPermId() {
        return $this->permId;
    }

    function getOrderId() {
        return $this->orderId;
    }

    function getContactId() {
        return $this->contactId;
    }

    function getTimestampCreate() {
        return $this->timestampCreate;
    }

    function getProducts() {
        return $this->products;
    }

    function getDiscount() {
        return $this->discount;
    }

    function getPaymentMethod() {
        return $this->paymentMethod;
    }

    function getCurrency() {
        return $this->currency;
    }

    function setPermId($permId) {
        $this->permId = $permId;
    }

    function setOrderId($orderId) {
        $this->orderId = $orderId;
    }

    function setContactId($contactId) {
        $this->contactId = $contactId;
    }

    function setTimestampCreate($timestampCreate) {
        $this->timestampCreate = $timestampCreate;
    }

    function setProducts($products) {
        $this->products = $products;
    }

    function setDiscount($discount) {
        $this->discount = $discount;
    }

    function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    function setCurrency($currency) {
        $this->currency = $currency;
    }

}
