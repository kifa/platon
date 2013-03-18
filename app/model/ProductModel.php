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
        //return $this->getTable('Product')->select('Product.*,Price.*')->where('CategoryID', $id);
        return $this->getTable('product')->select('product.ProductID, product.ProductName, 
            product.ProductDescription,product.CategoryID,price.FinalPrice')->where('CategoryID', $id);        
    }

    /*
     * Load 1 Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */

    public function loadProduct($id) {
        
    //return $this->getTable('Product')->where('ProductID', $id)->fetch();
      return $this->getTable('Product')->select('Product.*,Price.*')->where('Product.ProductID',$id)->fetch();
    }

    /*
     * Insert Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */
    public function insertProduct($id,$name,$producer,$album,$prodnumber,
            $description,$parameters,$ean,$qr,$warranty,$pieces,$category,$price,
            $dataaval,$dateadded,$documentation,$comment)
    {
        $insert = array(
            'ProductID' => $id,
            'ProductName' => $name,
            'Producer' => $producer,
            'PhotoAlbumID' => $album,
            'ProductNumber' => $prodnumber,
            'ProductDescription' => $description,
            'ParametersAlbumID' => $parameters,
            'ProductEAN' => $ean,
            'ProductQR' => $qr,
            'ProductWarranty' => $warranty,
            'PiecesAvailable' => $pieces,
            'CategoryID' => $category,
            'PriceID' => $price,
            'DateOfAvailable' => $dataaval,
            'ProductDateOfAdded' => $dateadded,
            'DocumentationID' => $documentation,
            'CommentID' => $comment
        );
        return $this->getTable('Product')->insert($insert);              
    }
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