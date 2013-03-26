<?php

/**
 * Class ProductModel
 * ProductModel is used for manipulating and managing products.
 * CRUD operations, etc.
 * @author lukas
 */
class ProductModel extends Repository {

    /**
     * Load Product Catalog
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */
    public function loadCatalog($id) {
        //$id = '2';
        //return $this->getTable('Product')->select('Product.*,Price.*')->where('CategoryID', $id);
        if($id==''){
            return $this->getTable('product')->select('product.ProductID, product.ProductName,
                product.ProductDescription,product.PhotoAlbumID,product.PiecesAvailable,price.FinalPrice');            
        }
        else
        {
        return $this->getTable('product')->select('product.ProductID, product.ProductName, 
            product.ProductDescription,product.CategoryID,product.PhotoAlbumID,product.PiecesAvailable,price.FinalPrice')->where('CategoryID', $id);        
        }
    }

    /*
     * Load 1 Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */

    public function loadProduct($id) {
        
    //return $this->getTable('Product')->where('ProductID', $id)->fetch();
      return $this->getTable('Product')->select('Product.*,Price.*,PhotoAlbum.*')->where('Product.ProductID',$id)->fetch();
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
            //'ProductStatusID' => '',
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
     * Delete Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
    public function deleteProduct($id){
        return $this->getTable('Product')->where('ProductID',$id)->delete();
    }
    
    /*
     * Count number of product
     */
    public function countProducts()
    {
        return $this->getTable('product')->count();
    }
    
    /*
     * Load Photo Album
     */
    public function loadPhotoAlbum($id){
        if($id==''){
            return $this->getTable('PhotoAlbum');
        }
        else{
        return $this->getTable('PhotoAlbum')->where('PhotoAlbumID',$id);
        }
    }
    
    /*
     * Load Photos
     */
    public function loadPhoto($id){
        return $this->getTable('Photo')->where('PhotoAlbumID',$id);
    }
    
    /*
     * Load title photo
     */
    public function loadCoverPhoto(){
        return $this->getTable('Photo')->where('CoverPhoto',1)->fetchPairs('PhotoAlbumID');
        //return $this->getTable('Product')->select('Product.ProductID,Product.PhotoAlbumID,Photo.PhotoAlbumID,Photo.PhotoID,Photo.PhotoURL')->fetchPairs('ProductID');
        
    }

        /*
     * Insert Photo
     */
    
}