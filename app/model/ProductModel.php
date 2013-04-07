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
           return $this->getDB()->query('SELECT * FROM product JOIN price ON product.PriceID=price.PriceID JOIN photoalbum ON product.PhotoAlbumID=photoalbum.PhotoAlbumID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID WHERE Photo.CoverPhoto="1"');                   
        }
        else
        {          
           return $this->getDB()->query('SELECT * FROM product JOIN price ON product.PriceID=price.PriceID JOIN photoalbum ON product.PhotoAlbumID=photoalbum.PhotoAlbumID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID WHERE Photo.CoverPhoto="1" and Product.CategoryID=?',$id);
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
    public function insertProduct($name,$producer,$album,$prodnumber,
            $description,$parameters,$ean,$qr,$warranty,$pieces,$category,$price,
            $dataaval,$dateadded,$documentation,$comment)
    {
        $insert = array(
            'ProductID' => NULL,
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
    public function updateProduct($id,$name,$producer,$prodnumber,
            $description,$ean,$qr,$warranty,$pieces,$category,
            $dataaval,$documentation,$comment){
        $insert = array(

            'ProductName' => $name,
            'Producer' => $producer,
            //'PhotoAlbumID' => $album,
            'ProductNumber' => $prodnumber,
            'ProductDescription' => $description,
            //'ProductStatusID' => '',
            //'ParametersAlbumID' => $parameters,
            'ProductEAN' => $ean,
            'ProductQR' => $qr,
            'ProductWarranty' => $warranty,
            'PiecesAvailable' => $pieces,
            'CategoryID' => $category,
            //'PriceID' => $price,
            'DateOfAvailable' => $dataaval,
            //'ProductDateOfAdded' => $dateadded,
            'DocumentationID' => $documentation,
            'CommentID' => $comment
        );
        
        return $this->getTable('Product')->where('ProductID',$id)->update($insert);
    }

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
            $row = $this->getDB()->query('SELECT * FROM product JOIN photoalbum ON product.PhotoAlbumID=photoalbum.PhotoAlbumID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID WHERE Product.ProductID=?',$id); 
           // dump($row);
            return $row;
        }
    }
    
    public function insertPhotoAlbum($name, $desc) {
         $insert = array(
            'PhotoAlbumID' => NULL,
            'PhotoAlbumName' => $name,
            'PhotoAlbumDescription' => $desc
                );
         
           $this->getTable('photoalbum')->insert($insert);
           return $this->countPhotoAlbum();
    }
    /*
     * Count Albums
     */
    public function countPhotoAlbum() {
        return $this->getTable('photoalbum')->count();
    }
    /*
     * Load photo
     */
    public function loadPhoto($id){
        return $this->getTable('photo')->where('PhotoID',$id)->fetch();
    }
    
    public function deletePhoto($id) {
        return $this->getTable('photo')->where('PhotoID', $id)->delete();
    }

    /*
     * Insert Photo
     */
    
    public function insertPhoto($name, $albumID, $cover = null){
        $insert = array(
        'PhotoID' => NULL,
        'PhotoName' => $name,
        'PhotoURL' => $name,
        'PhotoAlbumID' => $albumID,
        'PhotoAltText' => 's4',
        'CoverPhoto' => $cover
        );
        
        return $this->getTable('photo')->insert($insert);
    }
    
    public function insertPrice($selling,$sale,$final){
        $insert = array(
            //'PriceID'=>
            //'BuyingPrice'=>
            'SellingPrice'=>$selling,
            'SALE'=>$sale,
            'FinalPrice'=>$final
            //'CurrencyID'=>
        );
                
        return $this->getTable('Price')->insert($insert);
    }
}