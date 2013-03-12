<?php

/**
 * Class ProductModel
 * ProductModel is used for manipulating and managing products.
 * CRUD operations, etc.
 * @author lukas
 */
class ProductModel extends Authenticator {

    /**
     * Load Product Catalog
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */
    public function loadCatalog($id) {
        //$id = '2';
        return $this->getTable('Product')->where('CategoryID', $id);
    }

    /*
     * Load 1 Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */

    public function loadProduct($id) {
        
        return $this->getTable('Product')->where('ProductID', $id)->fetch();
    }

    /*
     * Insert Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */


    /*
     * Update Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */


    /*
     * Delete Produkt
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
}