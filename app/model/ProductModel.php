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
        
        //load only published products
        
        //$id = '2';
        //return $this->getTable('Product')->select('Product.*,Price.*')->where('CategoryID', $id);
        if($catID==''){
            //return $this->getTable('product')->select('product.ProductID, product.ProductName,
            //    product.ProductDescription,product.PhotoAlbumID,product.PiecesAvailable,price.FinalPrice,Photo.*');            
            return $this->getDB()->query('SELECT * FROM product JOIN price 
                ON price.ProductID=product.ProductID JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
                JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                WHERE photo.CoverPhoto="1" and product.ProductStatusID="2"');                              
            
            }
        else
        {  
            return $this->getDB()->query('SELECT * FROM product JOIN price ON 
            price.ProductID=product.ProductID JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
            JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
            WHERE photo.CoverPhoto="1" and (product.ProductStatusID="2" or product.ProductStatusID="3") and product.CategoryID=?',$catID);
            

            //return $this->getTable('product')->select('product.ProductID, product.ProductName, 
              //  product.ProductDescription,product.CategoryID,product.PhotoAlbumID,product.PiecesAvailable,price.FinalPrice,Photo.*')->where('CategoryID', $id);                    
        }
    }
    
     public function loadCatalogBrand($prodID) {
        
          return $this->getDB()->query('SELECT * FROM product JOIN price ON 
            price.ProductID=product.ProductID JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
            JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
            WHERE photo.CoverPhoto="1" and product.ProductStatusID="2" and product.ProducerID=?', $prodID);
            //return $this->getTable('product')->select('product.ProductID, product.ProductName, 
              //  product.ProductDescription,product.CategoryID,product.PhotoAlbumID,product.PiecesAvailable,price.FinalPrice,Photo.*')->where('CategoryID', $id);                    
        
    }
    
    public function loadCatalogAdmin($catID) {
        
        // load ALL products, even unpublished        
        if($catID==''){
            //return $this->getTable('product')->select('product.ProductID, product.ProductName,
            //    product.ProductDescription,product.PhotoAlbumID,product.PiecesAvailable,price.FinalPrice,Photo.*');            
            return $this->getDB()->query('SELECT * FROM product JOIN price 
                ON price.ProductID=product.ProductID JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
                JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                WHERE photo.CoverPhoto="1"');                              
            
            }
        else
        {  return $this->getDB()->query('SELECT * FROM product JOIN price ON 
            price.ProductID=product.ProductID JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
            JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
            WHERE photo.CoverPhoto="1" and product.CategoryID=?',$catID);
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
        return $this->getDB()->query('SELECT * FROM product 
            JOIN price ON product.ProductID=price.ProductID
            JOIN producer ON product.ProducerID=producer.ProducerID
            JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
            JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID
            WHERE product.ProductID=?',$id)->fetch();
        //return $this->getTable('Product')->select('Product.*,Price.*,PhotoAlbum.*,photo.*')->where('Product.ProductID',$id)->fetch()
    }

    /*
     * Insert Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */
    public function insertProduct($name,$price,$producer,$prodnumber,
            $short,$description,$ean,$qr,$warranty,$pieces,$category,
            $dataaval)
    {
        $today = date("Y-m-d");
        
        if($dataaval==''){
            $dataaval = '0000-00-00';
        };
        
        $insert = array(
            'ProductID' => NULL,
            'ProductName' => $name,
            'ProducerID' => $producer,            
            'ProductNumber' => $prodnumber,
            'ProductShort' => $short,
            'ProductDescription' => $description,
            //'ProductStatusID' => '',            
            'ProductEAN' => $ean,
            'ProductQR' => $qr,
            'ProductWarranty' => $warranty,
            'PiecesAvailable' => $pieces,
            'CategoryID' => $category,            
            'DateOfAvailable' => $dataaval,
            'ProductDateOfAdded' => $today,            
        );
        $row = $this->getTable('product')->insert($insert);   
        $lastprodid = $row["ProductID"];
        
        $albumid = $this->insertPhotoAlbum($name, $description,$lastprodid, null);
        
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
    * 
     * @return 
     *      Insert new informations to the database
     *  */
    public function updateProduct($id, $update, $value){
        
            $update = array(
                $update => $value
                );        
        return $this->getTable('Product')->where('productID',$id)->update($update);
    }
    
    public function updateProductName($id,$value){
        $update = array(
            'ProductName' => $value
        );
        return $this->getTable('product')->where('ProductID',$id)->update($update);
    }


    public function updatePrice($id, $price, $sale){
        
            $final = $price - $sale;
        
            $update = array(
                'SellingPrice' => $price,
                'FinalPrice' => $final,
                'SALE' => $sale
                );        
        return $this->getTable('price')->where('ProductID',$id)->update($update);
    }

    public function updateSale($prodid,$sale,$type=NULL){
        if($type==NULL){
            $productprice = $this->getTable('price')->select('SellingPrice')->where('ProductID',$prodid)->fetch();            
            
            $finalprice = $productprice['SellingPrice'] - $sale;
                        
            $update = array(
                'SALE' => $sale,
                'FinalPrice' => $finalprice
            );
        }
        elseif ($type=='percent') {
            $productprice = $this->getTable('price')->select('SellingPrice')->where('ProductID',$prodid)->fetch();            
            
            $sale = $sale/100;
            
            $abssale = $productprice['SellingPrice'] * $sale;
            $finalprice = $productprice['SellingPrice'] - $abssale;
            
            $update = array(
                'SALE' => $abssale,
                'FinalPrice' => $finalprice                    
            );            
    }
                
        return $this->getTable('price')->where('ProductID',$prodid)->update($update);
    }

        public function decreaseProduct($id, $amnt) {
        $cur = $this->getTable('product')->where('ProductID',$id)->fetch()->PiecesAvailable;
        $cur = $cur - $amnt;
        $update = array(
                'PiecesAvailable' => $cur
                );        
        return $this->getTable('product')->where('ProductID',$id)->update($update);
    }
    /*
     * Delete Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
    public function deleteProduct($id){
        return $this->getTable('product')->where('ProductID',$id)->delete();
    }
    
    public function hideProduct($id){
        $insert = array(
            'ProductStatusID' => 1
        );
        
        return $this->getTable('product')->where('ProductID',$id)->update($insert);
    }
    
     public function showProduct($id){
        $insert = array(
            'ProductStatusID' => 2
        );
        return $this->getTable('product')->where('ProductID',$id)->update($insert);
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
            //return $this->getTable('photoalbum')->select('photoalbum.*,photo.*')->where('photo.CoverPhoto',1)->fetchPairs('PhotoAlbumID');
            $row = $this->getDB()->query('SELECT * FROM photoalbum
                JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID
                WHERE photo.CoverPhoto=1');
            return $row;
        }
        else{
            //return $this->getTable('PhotoAlbum')->where('ProductID',$id);
            $row = $this->getDB()->query('SELECT * FROM product JOIN photoalbum 
                ON product.ProductID=photoalbum.ProductID JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                WHERE product.ProductID=?',$id); 
           // dump($row);
            return $row;
        }
    }
    
    public function insertPhotoAlbum($name, $desc, $product = NULL, $blog = NULL, $static = NULL) {
         $insert = array(             
            'PhotoAlbumName' => $name,
            'PhotoAlbumDescription' => $desc,
            'ProductID' => $product,
            'BlogID' => $blog,
            'StaticTextID' => $static
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
                
        return $this->getTable('price')->insert($insert);
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
    
    public function loadProducer($prodid){
        return $this->getTable('producer')->where('ProducerID',$prodid)->fetch();
    }
    
    public function loadProducers(){
        return $this->getTable('producer')->fetchPairs('ProducerID');
    }
    
    public function insertProducer($name){
        $insert = array(
            'ProducerName' => $name
        );
                
        return $this->getTable('producer')->insert($insert);
    }
    
    public function updateProducer($id,$name){
        $update = array(
            'ProducerName' => $name
        );
        
        return $this->getTable('producer')->where('ProducerID',$id)->update($update);
    }
    
    public function getData() {
        return $this->getTable('producer');
    }
    
    public function getCount() {
        return $this->getTable('producer')->count();
    }
    
    public function filter(array $condition) {
        return $this->getTable('producer')->where($condition);
    }
    
    public function limit($offset, $limit) {
        return $this->getTable('producer')->limit($limit, $offset);
    }
    
    public function sort(array $sorting) {
        return $this->getTable('producer')->where($sorting);
    }
    
    public function suggest($column, array $conditions) {
       return $this->getTable('producer')->select($columns)->where($condition);
    }           
    
    public function loadCheapestDelivery(){
        $delivery = $this->getTable('delivery')->select('DeliveryPrice')->where('DeliveryPrice != "0"')->order('DeliveryPrice')->fetchPairs('DeliveryPrice');
        $price = reset($delivery);
        $price = $price->DeliveryPrice;
        return $price;
    }        

    public function insertComment($title,$content,$author,$previous,$product){
        $today = date("Y-m-d");
        
        $insert = array(
            'CommentTitle' => $title,
            'CommentContent' => $content,
            'DateOfAdded' => $today,
            'Author' => $author,
            'PreviousCommentID' => $previous,
            'ProductID' => $product
        );
        
        return $this->getTable('comment')->insert($insert);
    }
    
    public function loadProductComments($idproduct){
        return $this->getTable('comment')->where('ProductID',$idproduct)->fetchPairs('CommentID');
    }
    
    public function deleteComment($idcomment){
        return $this->getTable('comment')->where('CommentID',$idcomment)->delete();
    }
    
    public function loadComments(){
        return $this->getTable('comment')->fetchPairs('CommentID');
    }
    
    public function loadCommentsByDate(){
        return $this->getTable('comment')->order('DateOfAdded DESC')->fetchPairs('CommentID');
    }
}
