<?php

namespace Incomaker\Api\Data;

/**
 * Description of ProductLabels
 */
class ProductLabels {

    private $language;
    private $name;
    private $description;

    function __construct($language, $name, $description) {
        $this->language = $language;
        $this->name = $name;
        $this->description = $description;
    }

    public function getArrayData() {
        $arrayData = array();
        if (!is_null($this->language)) {
            $arrayData["language"] = $this->language;
        }
        if (!is_null($this->name)) {
            $arrayData["name"] = $this->name;
        }
        if (!is_null($this->description)) {
            $arrayData["description"] = $this->description;
        }
        return $arrayData;
    }

    public function getData() {
        return json_encode($this->getArrayData());
    }

    function getLanguage() {
        return $this->language;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function setLanguage($language) {
        $this->language = $language;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

}
