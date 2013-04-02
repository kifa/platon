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
            //return $this->getTable('product')->select('product.ProductID, product.ProductName,
            //    product.ProductDescription,product.PhotoAlbumID,product.PiecesAvailable,price.FinalPrice,Photo.*');            
           return $this->getDB()->query('SELECT * FROM product JOIN price ON product.PriceID=price.PriceID JOIN photoalbum ON product.PhotoAlbumID=photoalbum.PhotoAlbumID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID');                   
        }
        else
        {          
           return $this->getDB()->query('SELECT * FROM product JOIN price ON product.PriceID=price.PriceID JOIN photoalbum ON product.PhotoAlbumID=photoalbum.PhotoAlbumID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID WHERE Product.CategoryID=?',$id);
            //return $this->getTable('product')->select('product.ProductID, product.ProductName, 
              //  product.ProductDescription,product.CategoryID,product.PhotoAlbumID,product.PiecesAvailable,price.FinalPrice,Photo.*')->where('CategoryID', $id);                    
        }
    }

    /*
     * Load 1 Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */

    public function loadProduct($id) {       
        return $this->getDB()->query('SELECT * FROM product JOIN price ON product.PriceID=price.PriceID JOIN photoalbum ON product.PhotoAlbumID=photoalbum.PhotoAlbumID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID WHERE Product.ProductID=?',$id)->fetch();
        //return $this->getTable('Product')->select('Product.*,Price.*,PhotoAlbum.*,photo.*')->where('Product.ProductID',$id)->fetch()
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
            //return $this->getTable('PhotoAlbum')->where('ProductID',$id);
            return $this->getDB()->query('SELECT * FROM product JOIN photoalbum ON product.PhotoAlbumID=photoalbum.PhotoAlbumID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID WHERE Product.ProductID=?',$id);
        }
    }
    
    /*
     * Load photo
     */
    public function loadPhoto($id){
        return $this->getTable('photo')->where('PhotoAlbumID',$id);
    }
    
    /*
     * Load title photo
     */
    public function loadCoverPhoto(){
        return $this->getTable('photo')->where('CoverPhoto', 1)->fetchPairs('PhotoAlbumID');
        //return $this->getTable('Product')->select('Product.ProductID,Product.PhotoAlbumID,Photo.PhotoAlbumID,Photo.PhotoID,Photo.PhotoURL')->fetchPairs('ProductID');
        
    }

        /*
     * Insert Photo
     */
    
        public function insertPhoto($name){
            $id = $this->countPhoto() + 1;
            $insert = array(
            'PhotoID' => $id,
            'PhotoName' => $name,
            'PhotoURL' => $name,
            'PhotoAlbumID' => 4,
            'PhotoAltText' => 's4',
            'CoverPhoto' => 1
                );
            return $this->getTable('photo')->insert($insert);
        }
    /*
     * Counting number of photo to generate ID
     * 
     */
        public function countPhoto(){
            return $this->getTable('photo')->count();
        }
}