<?php

namespace Incomaker\Api\Controller;

use Incomaker\Data\Image;
use Incomaker\Data\Images;

/**
 * Description of ImageController
 */
class ImageController extends Controller {

    /**
     * Creates new Image object
     * 
     * @param type $imageId
     * @return Image object
     */
    public function createNewImage($imageId) {
        return new Image($imageId);
    }

    /**
     * Creates new Images object
     * 
     * @return Images object
     */
    public function createNewImages() {
        return new Images();
    }

    /**
     * Adds more than one image.
     * 
     * Supports batch job.
     * 
     * @param string $data
     * @param long $requestId
     * @return string
     */
    public function addImages(Images $data, $requestId = 0) {
        return $this->curlPostQuery("image/batch/", "?requestId=" . urlencode($requestId), json_encode(array("data" => $data->getArrayData())));
    }

    /**
     * Returns image meta data.
     * 
     * @param integer $offset
     * @param integer $limit
     * @return string
     */
    public function getImagesMetaData($offset, $limit) {
        return $this->curlGetQuery("image/meta/",  "?offset=" . $offset . "?limit=" . $limit);
    }

    /**
     * Returns image data. (BASE-64)
     * 
     * @param string $imageId
     * @return string
     */
    public function getImage($imageId) {
        return $this->curlGetQuery("image/data/",  "?imageId=" . urlencode($imageId));
    }

    /**
     * Updates image data.
     * 
     * @param string $imageId
     * @param string $imageType
     * @param string $imageName
     * @param string $imageContent
     * @return string
     */
    public function updateImage(Image $image) {
        return $this->curlPutQuery("image/", "", $image->getData());
    }

    /**
     * Deletes image
     * 
     * @param string $imageId
     * @return string
     */
    public function deleteImage($imageId) {
        return $this->curlDeleteQuery("image/", "?imageId=" . urlencode($imageId));
    }

}
