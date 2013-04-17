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
    public function loadCatalog($catID) {
        //$id = '2';
        //return $this->getTable('Product')->select('Product.*,Price.*')->where('CategoryID', $id);
        if($catID==''){
            //return $this->getTable('product')->select('product.ProductID, product.ProductName,
            //    product.ProductDescription,product.PhotoAlbumID,product.PiecesAvailable,price.FinalPrice,Photo.*');            
            return $this->getDB()->query('SELECT * FROM product JOIN price 
                ON price.ProductID=product.ProductID JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
                JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                WHERE Photo.CoverPhoto="1"');                              
            
            }
        else
        {  return $this->getDB()->query('SELECT * FROM product JOIN price ON 
            price.ProductID=product.ProductID JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
            JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
            WHERE Photo.CoverPhoto="1" and product.CategoryID=?',$catID);
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
        return $this->getDB()->query('SELECT * 
FROM product JOIN price ON product.ProductID=price.ProductID JOIN photoalbum ON
product.ProductID=photoalbum.ProductID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID
WHERE Product.ProductID=?',$id)->fetch();
        //return $this->getTable('Product')->select('Product.*,Price.*,PhotoAlbum.*,photo.*')->where('Product.ProductID',$id)->fetch()
    }

    /*
     * Insert Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */
    public function insertProduct($name,$price,$producer,$prodnumber,
            $description,$ean,$qr,$warranty,$pieces,$category,
            $dataaval,$comment)
    {
        $today = date("Y-m-d");
        
        $insert = array(
            'ProductID' => NULL,
            'ProductName' => $name,
            'Producer' => $producer,            
            'ProductNumber' => $prodnumber,
            'ProductDescription' => $description,
            //'ProductStatusID' => '',            
            'ProductEAN' => $ean,
            'ProductQR' => $qr,
            'ProductWarranty' => $warranty,
            'PiecesAvailable' => $pieces,
            'CategoryID' => $category,            
            'DateOfAvailable' => $dataaval,
            'ProductDateOfAdded' => $today,            
            'CommentID' => $comment
        );
        $row = $this->getTable('Product')->insert($insert);   
        $lastprodid = $row["ProductID"];
        
        $albumid = $this->insertPhotoAlbum($name, $description,$lastprodid);
        
        $this->insertPrice($lastprodid, $price);
        
        return array($lastprodid, $albumid);
        
    }
    /*
     * Update Product
     * @param id
     *      Parameter id mean id of product you want to update
     * @param value
     *      Parameter value is new value you want to update
     * @param update
     *      Parameter update determines which attribut you want to update.
     *      Possible values are
     *              name => update product name
     *              producer => update producer name
     *              pn => update product number
     *              description => update product description
     *              status => update product status
     *              ean => update product ean code
     *              qr => update product qr code
     *              warranty => update product warranty informations
     *              category => update product category
     *              available => update date when product will be available
     *              pieces => update number od pieces which are available
     *              comment => update first first
     * 
     * @return 
     *      Insert new informations to the database
     *  */
    public function updateProduct($id, $update, $value){
        
            $insert = array(
                $update => $value
                );        
        return $this->getTable('Product')->where('ProductID',$id)->update($insert);
    }
    
    public function updatePrice($id, $price, $discount){
        
            $final = $price - $discount;
        
            $insert = array(
                'SellingPrice' => $price,
                'FinalPrice' => $final,
                'SALE' => $discount
                );        
        return $this->getTable('Price')->where('ProductID',$id)->update($insert);
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
    
    public function hideProduct($id){
        $insert = array(
            'ProductStatusID' => 1
        );
        
        return $this->getTable('Product')->where('ProductID',$id)->update($insert);
    }
    
     public function showProduct($id){
        $insert = array(
            'ProductStatusID' => 2
        );
        return $this->getTable('Product')->where('ProductID',$id)->update($insert);
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
            $row = $this->getDB()->query('SELECT * FROM product JOIN photoalbum 
                ON product.ProductID=photoalbum.ProductID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                WHERE Product.ProductID=?',$id); 
           // dump($row);
            return $row;
        }
    }
    
    public function insertPhotoAlbum($name, $desc, $product) {
         $insert = array(
            'PhotoAlbumID' => NULL,             
            'PhotoAlbumName' => $name,
            'PhotoAlbumDescription' => $desc,
            'ProductID' => $product
            );
         
           $row = $this->getTable('photoalbum')->insert($insert);
           return $row["PhotoAlbumID"];
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

     public function coverPhoto($id) {
         $insert = array(
             'CoverPhoto' => 1
         );
            
        return $this->getTable('photo')->where('PhotoID', $id)->update($insert);
    }
    /*
     * Insert Photo
     */
    
    public function insertPhoto($name, $url, $albumID, $cover = null){
        $insert = array(
        'PhotoID' => NULL,
        'PhotoName' => $name,
        'PhotoURL' => $url,
        'PhotoAlbumID' => $albumID,
        'PhotoAltText' => $name,
        'CoverPhoto' => $cover
        );
        
        return $this->getTable('photo')->insert($insert);
    }
    
    public function insertPrice($product,$final,$sale = 0){
        $insert = array(
            //'PriceID'=>
            'ProductID'=>$product,
            //'BuyingPrice'=>
            //'SellingPrice'=>$selling,
            'SALE'=>$sale,
            'FinalPrice'=>$final
            //'CurrencyID'=>
        );
                
        return $this->getTable('Price')->insert($insert);
    }
    
    public function loadParameters($id){
        if ($id == null){
            return $this->getTable('parameters')->select('parameters.*,attrib.*,unit.*')
                ->fetchPairs('ParameterID');
        }
        else {
            return $this->getTable('parameters')->select('parameters.*,attrib.*,unit.*')
                ->where('ProductID',$id)->fetchPairs('ParameterID');
        }
    }

    public function insertParameter($product,$attribute,$value=null,$unit=null){
        $insert = array(
            'ParameterID' => NULL,
            'ProductID' => $product,
            'AttribID' => $attribute,
            'Val' => $value,
            'UnitID' => $unit
        );
        
        return $this->getTable('parameters')->insert($insert);
    }
    
    public function updateParameter($paramID,$value,$unit=NULL){
        $update = array(
          //  'Parameter' => $param,
            'Val' => $value,
            'UnitID' => $unit
        );
                
        return $this->getTable('parameters')->where('ParameterID',$paramID)->update($update);
    }
    
    public function deleteParameter($paramID){
        return $this->getTable('parameters')->where('ParameterID', $paramID)->delete();
    }
    
    public function loadAttribute($id){
        if($id == NULL){
            return $this->getTable('attrib');
        }
        else {
            return $this->getTable('attrib')->where('AttribID',$id);
        }
    }

    public function insertAttribute($name){
        
        $row = $this->getTable('attrib')->where('AttribName',$name)->fetch();
        
        if (!$row) {
        $insert = array(
            'AttribName' => $name
        );
        
        return $this->getTable('attrib')->insert($insert);
        }
        else {
            
            return $row->AttribID;
        }
    }
    
    public function updateAttribute($id,$name){
        $insert = array(
            'AttribName' => $name
        );
        
        return $this->getTable('attrib')->where('AttribID',$id)->update($insert);
    }
    
    public function loadUnit($id){
        if($id == NULL){
            return $this->getTable('unit')->fetchPairs('UnitID');
        }
        else {
            return $this->getTable('unit')->where('UnitID',$id);
        }
    }

    public function insertUnit($name,$short){
        $insert = array(
            'UnitName' => $name,
            'UnitShort' => $short
        );
        
        return $this->getTable('unit')->insert($insert);
    }
    
    public function updateUnit($id,$name,$short){
        $insert = array(
            'UnitName' => $name,
            'UnitShort' => $short
        );
        
        return $this->getTable('unit')->where('UnitID',$id)->update($insert);
    }
    
    
    /*
     * Insert Doc
     */
    
    public function insertDocumentation($name, $url, $productID, $desc = null){
        $insert = array(
        'DocumentID' => NULL,
        'DocumentName' => $name,
        'DocumentURL' => $url,
        'DocumentDescription' => $desc,
        'ProductID' => $productID
        );
        
        return $this->getTable('documentation')->insert($insert);
    }
    
    public function loadDocumentation($id){
        return $this->getTable('documentation')->where('ProductID',$id);
    }
    
    public function deleteDocumentation($id){
        return $this->getTable('documentation')->where('DocumentID',$id)->delete();
    }
    
    public function loadCoverPhoto($id){
        return $this->getTable('photo')->select('photo.PhotoURL, photoalbum.ProductID')->where('photoalbum.ProductID',$id)
                ->where('photo.CoverPhoto','1')->fetch();
    }

    public function updateCoverPhoto($product,$photo){
        $album = $this->getTable('photoalbum')->select('PhotoAlbumID')->where('ProductID',$product)->fetch();       
        $albumID = $album['PhotoAlbumID'];
        
        $unsetCover = array(
            'CoverPhoto' => 0
        );
        
        $setCover = array(
            'CoverPhoto' => 1
        );
        
        $this->getTable('photo')->where('PhotoAlbumID',$albumID)->where('CoverPhoto','1')->update($unsetCover);
       
        $this->getTable('photo')->where('PhotoID',$photo)->update($setCover);
    }
}
