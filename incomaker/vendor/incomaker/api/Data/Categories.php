<?php

namespace Incomaker\Api\Data;
/**
 * Description of Categories
 */
class Categories {

    private $data = array();

    public function addCategory(Category $category) {
        array_push($this->data, $category->getArrayData());
    }
    
    public function getArrayData() {
        return $this->data;
    }

    public function getData() {
        json_encode($this->getArrayData());
    }

}
