<?php

namespace Incomaker\Api\Data;

/**
 * Description of Image
 */
class Image {

    private $imageId;
    private $imageName;
    private $imageContent;

    function __construct($imageId = null) {
        $this->imageId = $imageId;
    }

    public function getArrayData() {
        $arrayData = array();
        if (!is_null($this->imageId)) {
            $arrayData["imageId"] = $this->imageId;
        }
        if (!is_null($this->imageName)) {
            $arrayData["imageName"] = $this->imageName;
        }
        if (!is_null($this->imageContent)) {
            $arrayData["imageContent"] = $this->imageContent;
        }
        return $arrayData;
    }

    public function getData() {
        return json_encode($this->getArrayData());
    }

    function getImageId() {
        return $this->imageId;
    }

    function getImageName() {
        return $this->imageName;
    }

    function getImageContent() {
        return $this->imageContent;
    }

    function setImageId($imageId) {
        $this->imageId = $imageId;
    }

    function setImageName($imageName) {
        $this->imageName = $imageName;
    }

    function setImageContent($imageContent) {
        $this->imageContent = $imageContent;
    }

}
