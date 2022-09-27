<?php

namespace Incomaker\Api\Controller;

use Incomaker\Api\Data\Category;
use Incomaker\Api\Data\Categories;

/**
 * Description of CategoryController
 */
class CategoryController extends Controller {

    public function createNewCategory($categoryId) {
        return new Category($categoryId);
    }

    public function createNewCategories(Category $category = null) {
        return new Categories($category);
    }
    
    /**
     * Adds categories.
     * 
     * (createNewCategory and createNewCategories)
     * 
     * @param Categories $data
     * @return tresponse
     */
    public function addCategories(Categories $data) {
        return $this->curlPostQuery("segment/category/", "", json_encode(array("category" => $data->getArrayData())));
    }

    /**
     * Returns all categories
     * 
     * @return response
     */
    public function getCatgories() {
        return $this->curlGetQuery("category/");
    }

    /**
     * Updates category
     * 
     * (createNewCategory)
     * 
     * @param Category $category
     * @return response
     */
    public function updateCategory(Category $category) {      
        return $this->curlUpdateQuery("segment/category/", "", json_encode(array("category" => $category->getArrayData())));
    }

    /**
     * Deletes category by categoryId
     * 
     * @param type $categoryId
     * @return response
     */
    public function deleteCategory($categoryId) {
        return $this->curlDeleteQuery("segment/category/", "?categoryId=" . urlencode($categoryId));
    }

}
