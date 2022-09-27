<?php

namespace Incomaker\Api\Data;

use Incomaker\Api\Data\ProductLabels;

/**
 * Description of Product
 */
class Product {

    private $productId;
    private $imageUrl;
    private $videoUrl;
    private $categories = array();
    private $tags = array();
    private $price;
    private $tax;
    private $purchase;
    private $unit;
    private $brand;
    private $ean;
    private $labels = array();
    private $condition;
    private $url;
    private $stock;
    private $active;
    private $variantId;
    private $parameters = array();
    private $availability;
    private $delivery;
    
    function __construct($productId) {
        $this->productId = $productId;
    }

    public function getArrayData() {
        $arrayData = array();

        if (!is_null($this->productId)) {
            $arrayData["productId"] = $this->productId;
        }
        if (!is_null($this->imageUrl)) {
            $arrayData["imageUrl"] = $this->imageUrl;
        }
        if (!is_null($this->videoUrl)) {
            $arrayData["videoUrl"] = $this->videoUrl;
        }
        if (!is_null($this->categories)) {
            $arrayData["categories"] = $this->categories;
        }
        if (!is_null($this->tags)) {
            $arrayData["tags"] = $this->tags;
        }
        if (!is_null($this->price)) {
            $arrayData["price"] = $this->price;
        }
        if (!is_null($this->tax)) {
            $arrayData["tax"] = $this->tax;
        }
        if (!is_null($this->purchase)) {
            $arrayData["purchase"] = $this->purchase;
        }
        if (!is_null($this->unit)) {
            $arrayData["unit"] = $this->unit;
        }
        if (!is_null($this->brand)) {
            $arrayData["brand"] = $this->brand;
        }
        if (!is_null($this->ean)) {
            $arrayData["ean"] = $this->ean;
        }
        if (!is_null($this->labels)) {
            $arrayData["labels"] = $this->labels;
        }
        if (!is_null($this->condition)) {
            $arrayData["condition"] = $this->condition;
        }
        if (!is_null($this->url)) {
            $arrayData["url"] = $this->url;
        }
        if (!is_null($this->stock)) {
            $arrayData["stock"] = $this->stock;
        }
        if (!is_null($this->active)) {
            $arrayData["active"] = $this->active;
        }
        if (!is_null($this->variantId)) {
            $arrayData["variantId"] = $this->variantId;
        }
        if (!is_null($this->parameters)) {
            $arrayData["parameters"] = $this->parameters;
        }
        if (!is_null($this->availability)) {
            $arrayData["availability"] = $this->availability;
        }
        if (!is_null($this->delivery)) {
            $arrayData["delivery"] = $this->delivery;
        }
        return $arrayData;
    }

    public function getData() {
        return json_encode($this->getArrayData());
    }

    function getProductId() {
        return $this->productId;
    }

    function getImageUrl() {
        return $this->imageUrl;
    }

    function getVideoUrl() {
        return $this->imageUrl;
    }

    function getCategories() {
        return $this->categories;
    }

    function getTags() {
        return $this->tags;
    }

    function getPrice() {
        return $this->price;
    }

    function getTax() {
        return $this->tax;
    }

    function getPurchase() {
        return $this->purchase;
    }

    function getUnit() {
        return $this->unit;
    }

    function getBrand() {
        return $this->brand;
    }

    function getEan() {
        return $this->ean;
    }

    function getLabels() {
        return $this->labels;
    }

    function getCondition() {
        return $this->condition;
    }

    function getUrl() {
        return $this->url;
    }

    function getStock() {
        return $this->stock;
    }

    function getActive() {
        return $this->active;
    }

    function getVariantId() {
        return $this->variantId;
    }

    function getParameters() {
        return $this->parameters;
    }

    function getAvailability() {
        return $this->availability;
    }

    function getDelivery() {
        return $this->delivery;
    }

    function setProductId($productId) {
        $this->productId = $productId;
    }

    function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
    }

    function setVideoUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
    }

    public function addCategory($category) {
        array_push($this->category, $category);
    }

    public function deleteCategory($category) {
        if (($key = array_search($category, $this->category)) !== false) {
            unset($this->category[$key]);
        }
    }

    public function addTags($tags) {
        array_push($this->tags, $tags);
    }

    public function deleteTags($tags) {
        if (($key = array_search($tags, $this->tags)) !== false) {
            unset($this->tags[$key]);
        }
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setTax($tax) {
        $this->tax = $tax;
    }

    function setPurchase($finalPrice) {
        $this->purchase = $purchase;
    }

    function setUnit($unit) {
        $this->unit = $unit;
    }

    function setBrand($brand) {
        $this->brand = $brand;
    }

    function setEan($ean) {
        $this->ean = $ean;
    }

    public function addLabels($language, $name, $description) {
        $dsc = new ProductLabels($language, $name, $description);
        array_push($this->labels, $dsc->getArrayData());
    }

    function setCondition($condition) {
        $this->condition = $condition;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setStock($stock) {
        $this->stock = $stock;
    }

    function setActive($active) {
        $this->active = $active;
    }

    public function addParameters($parameters) {
        array_push($this->parameters, $parameters);
    }

    public function deleteParameters($parameters) {
        if (($key = array_search($parameters, $this->parameters)) !== false) {
            unset($this->parameters[$key]);
        }
    }

    function setAvailability($availability) {
        $this->availability = $availability;
    }

    function setDelivery($delivery) {
        $this->delivery = $delivery;
    }

}
