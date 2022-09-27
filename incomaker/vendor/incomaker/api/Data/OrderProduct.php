<?php

namespace Incomaker\Api\Data;

/**
 * Description of OrderProduct
 */
class OrderProduct {

    private $productId;
    private $quantity;
    private $pricePerUnit;
    private $vat;

    function __construct($productId, $quantity, $pricePerUnit, $vat) {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->pricePerUnit = $pricePerUnit;
        $this->vat = $vat;
    }

    public function getArrayData() {
        $arrayData = array();
        if (!is_null($this->productId)) {
            $arrayData["productId"] = $this->productId;
        }
        if (!is_null($this->quantity)) {
            $arrayData["quantity"] = $this->quantity;
        }
        if (!is_null($this->pricePerUnit)) {
            $arrayData["pricePerUnit"] = $this->pricePerUnit;
        }
        if (!is_null($this->vat)) {
            $arrayData["vat"] = $this->vat;
        }
        return $arrayData;
    }

    public function getData() {
        return json_encode($this->getArrayData());
    }

    function getProductId() {
        return $this->productId;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function getPricePerUnit() {
        return $this->pricePerUnit;
    }

    function getVat() {
        return $this->vat;
    }

    function setProductId($productId) {
        $this->productId = $productId;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    function setPricePerUnit($pricePerUnit) {
        $this->pricePerUnit = $pricePerUnit;
    }

    function setVat($vat) {
        $this->vat = $vat;
    }

}
