<?php

namespace Incomaker\Api\Data;

use Incomaker\Api\Data\Image;

/**
 * Description of Images
 */
class Images {

    private $data = array();

    public function addImage(Image $image) {
        array_push($this->data, $image->getArrayData());
    }

    public function getArrayData() {
        return $this->data;
    }

    public function getData() {
        json_encode($this->getArrayData());
    }

}
