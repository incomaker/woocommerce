<?php

namespace Incomaker\Api\Data;

/**
 * Product category library
 */
class Category {

    private $categoryId;
    private $categoryNames = array();
    private $parentCategoryId;

    function __construct($categoryId) {
        $this->categoryId = $categoryId;
    }

    /**
     * Adds new name to category
     * 
     * @param type $lang
     * @param type $name
     */
    public function addName($lang, $name) {
        $this->categoryNames[$lang] = $name;
    }

    /**
     * Deletes category name by language
     * 
     * @param type $lang
     */
    public function deleteName($lang) {
        unset($this->categoryNames[$lang]);
    }

    public function getArrayData() {
        $arrayData = array();
        if (!is_null($this->categoryId)) {
            $arrayData["categoryId"] = $this->categoryId;
        }
        if (!is_null($this->parentCategoryId)) {
            $arrayData["parentCategoryId"] = $this->parentCategoryId;
        }
        if (!empty($this->categoryNames)) {
            $arrayData["categoryNames"] = $this->categoryNames;
        }
        return $arrayData;
    }

    public function getData() {
        return json_encode($this->getArrayData());
    }

    function getCategoryId() {
        return $this->categoryId;
    }

    function getCategoryNames() {
        return $this->categoryNames;
    }

    function getParentCategoryId() {
        return $this->parentCategoryId;
    }

    function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    function setCategoryNames($categoryNames) {
        $this->categoryNames = $categoryNames;
    }

    function setParentCategoryId($parentCategoryId) {
        $this->parentCategoryId = $parentCategoryId;
    }

}
