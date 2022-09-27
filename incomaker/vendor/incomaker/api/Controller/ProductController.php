<?php

namespace Incomaker\Api\Controller;

use Incomaker\Data\Product;
use Incomaker\Data\Products;

/**
 * Description of Products
 */
class ProductController extends Controller {

    /**
     * 
     * @param type $productId
     * @return Product
     */
    public function createProductObject($productId) {
        return new Product($productId);
    }

    /**
     * 
     * @return Products
     */
    public function createProductsObject() {
        return new Products();
    }

    /**
     * Add products
     * 
     * @param Products $products
     */
    public function addProducts(Products $products) {
        return $this->curlPostQuery("product/", "", $products->getData());
    }

    /**
     * Returns product by product Id
     * 
     * @param type $id
     * @return response
     */
    public function getProduct($id) {
        return $this->curlGetQuery("product/", "?productId=" . $id);
    }

    /**
     * Returns product IDs
     * 
     * @return response
     */
    public function getProductIds() {
        return $this->curlGetQuery("product/id/");
    }

    /**
     * Updates products
     * 
     * @param Products $product
     * @return response
     */
    public function updateProduct(Products $product) {
        return $this->curlUpdateQuery("product/", "", $product->getData());
    }

    /**
     * Delete product
     * 
     * @param type $id
     * @return response
     */
    public function deleteProduct($id) {
        return $this->curlDeleteQuery("product/", "?productId=" . $id);
    }

}
