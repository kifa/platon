<?php

/**
 * Class ProductModel
 * ProductModel is used for manipulating and managing products.
 * CRUD operations, etc.
 * @author lukas
 */



class ProductModel extends Repository {

    /*
     * Load 1 Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     *  */

    public function loadProduct($id) {       
        /*return $this->getDB()->query('SELECT * FROM product 
            JOIN price ON product.ProductID=price.ProductID
            JOIN producer ON product.ProducerID=producer.ProducerID
            WHERE product.ProductID=?',$id)->fetch();        */        
        $row = $this->db
                ->SELECT('*')
                ->FROM('product')
                ->JOIN('price')->ON('product.ProductID = price.ProductID')
                ->JOIN('producer')->ON('product.ProducerID = producer.ProducerID')
                ->WHERE('product.ProductID = %i', $id)
                ->FETCH();
        
        return $row;
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
        $today = date('Y-m-d H:i:s');
        
        if($dataaval==''){
            $dataaval = '0000-00-00 00:00:00';
        };
        
        $insert = array(
            'ProductID' => NULL,
            'ProductName' => $name,
            'ProductSeoName' => $name,
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
        /*$row = $this->getTable('product')
                ->insert($insert);   */
        $row = $this->db
                ->INSERT('product', $insert);
        
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
            
        /*return $this->getTable('product')
                ->where('productID',$id)
                ->update($update);*/
            $row = $this->db
                    ->UPDATE('product', $update)
                    ->WHERE('ProductID = %i', $id);
            
            return $row;
    }
    
    public function updatePrice($id, $price, $sale){
        
        $final = $price - $sale;

        $update = array(
            'SellingPrice' => $price,
            'FinalPrice' => $final,
            'SALE' => $sale
            );        

        /*return $this->getTable('price')
                ->where('ProductID',$id)
                ->update($update);*/
        $row = $this->db
                ->UPDATE('price', $update)
                ->WHERE('ProductID = %i', $id);
        
        return $row;
    }

    public function updateSale($prodid,$sale,$type=NULL){
        if($type==NULL){
            /*$productprice = $this->getTable('price')
                    ->select('SellingPrice')
                    ->where('ProductID',$prodid)
                    ->fetch();            */
            $productprice = $this->db
                    ->SELECT('SellingPrice')
                    ->FROM('price')
                    ->WHERE('ProductID = %i', $prodid)
                    ->FETCH();
            
            $finalprice = $productprice['SellingPrice'] - $sale;
                        
            $update = array(
                'SALE' => $sale,
                'FinalPrice' => $finalprice
            );
        }
        elseif ($type=='percent') {
            /*$productprice = $this->getTable('price')
                    ->select('SellingPrice')
                    ->where('ProductID',$prodid)
                    ->fetch();           
             */
            $productprice = $this->db
                    ->SELECT('SellingPrice')
                    ->FROM('price')
                    ->WHERE('ProductID = %i', $prodid)
                    ->FETCH();
            
            $sale = $sale/100;
            
            $abssale = $productprice['SellingPrice'] * $sale;
            $finalprice = $productprice['SellingPrice'] - $abssale;
            
            $update = array(
                'SALE' => $abssale,
                'FinalPrice' => $finalprice                    
            );            
    }
                
        /*return $this->getTable('price')
                ->where('ProductID',$prodid)
                ->update($update);*/
        $row = $this->db
                ->UPDATE('price', $update)
                ->WHERE('ProductID = %i', $prodid);
    
        return $row;
    }

    public function decreaseProduct($id, $amnt) {
        /*$cur = $this->getTable('product')
                ->where('ProductID',$id)
                ->fetch()->PiecesAvailable;*/
        $cur = $this->db
                ->SELECT('*')
                ->FROM('product')
                ->WHERE('ProductID = %i', $id)
                ->FETCH()
                ->PiecesAvailable;
        
        $cur = $cur - $amnt;
        
        $update = array(
                'PiecesAvailable' => $cur
                );        
        
        /*return $this->getTable('product')
                ->where('ProductID',$id)
                ->update($update);*/
        $row = $this->db
                ->UPDATE('product', $update)
                ->WHERE('ProductID = %i', $id);
        
        return $row;
    }
    
    /*
     * Delete Product
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
    public function deleteProduct($id){
        $update = array(
            'ProductStatusID' => 4
        );
        
        /*return $this->getTable('product')
                ->where('ProductID',$id)
                ->update($insert);*/
        $row = $this->db
                ->UPDATE('product', $update)
                ->WHERE('ProductID = %i', $id);
        
        return $row;
    }
    
    public function deleteProducer($prodID){
        $update = array(
            'ProducerID' => 1
        );
        
        /*$this->getTable('product')
                ->where('ProducerID',$prodID)
                ->update($update);*/
             $this->db
                     ->UPDATE('product', $update)
                     ->WHERE('ProducerID = %i', $prodID);
        
        /*return $this->getTable('producer')
                ->where('ProducerID', $prodID)
                ->delete();*/
             $row = $this->db
                     ->DELETE('producer')
                     ->WHERE('ProducerID = %i', $prodID);
             
             return $row;
    }
    
    public function hideProduct($id){
        $update = array(
            'ProductStatusID' => 1
        );
        
        /*return $this->getTable('product')
                ->where('ProductID',$id)
                ->update($insert);*/
        $row = $this->db
                ->UPDATE('product', $update)
                ->WHERE('ProductID = %i', $id);
        
        return $row;
    }
    
     public function showProduct($id){
        $update = array(
            'ProductStatusID' => 2
        );
        
        /*return $this->getTable('product')
                ->where('ProductID',$id)
                ->update($insert);*/
        $row = $this->db
                ->UPDATE('product', $update)
                ->WHERE('ProductID = %i', $id);
        
        return $row;
    }
     
    /*
     * Load Photo Album
     */
    public function loadPhotoAlbum($id){
        if($id==''){
            /*$row = $this->getDB()->query('SELECT * FROM photoalbum
                JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID
                WHERE photo.CoverPhoto=1');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('photoalbum')
                    ->JOIN('photo')->ON('photoalbum.PhotoAlbumID = photo.PhotoAlbumID')
                    ->WHERE('photo.CoverPhoto = 1');
        }
        else{            
            /*$row = $this->getDB()->query('SELECT * FROM product JOIN photoalbum 
                ON product.ProductID=photoalbum.ProductID 
                JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
                WHERE product.ProductID=?',$id); */
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('product')
                    ->JOIN('photoalbum')->ON('product.ProductID = photoalbum.ProductID')
                    ->JOIN('photo')->ON('photoalbum.PhotoAlbumID = photo.PhotoAlbumID')
                    ->WHERE('product.ProductID = %i', $id);            
        }
        return $row;
    }
    
    public function loadPhotoAlbumID($id) {
        /*return $this->getTable('photoalbum')
                ->select('PhotoAlbumID')
                ->where('ProductID', $id)
                ->fetch();*/
        $row = $this->db
                ->SELECT('PhotoAlbumID')
                ->FROM('photoalbum')
                ->WHERE('ProductID = %i', $id)
                ->FETCH();
        
        return $row;
    }
    
    public function insertPhotoAlbum($name, $desc, $product = NULL, $blog = NULL, $static = NULL) {
        $insert = array(             
            'PhotoAlbumName' => $name,
            'PhotoAlbumDescription' => $desc,
            'ProductID' => $product,
            'BlogID' => $blog,
            'StaticTextID' => $static
        );
         
        /*$row = $this->getTable('photoalbum')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('photoalbum', $insert);
        
        return $row["PhotoAlbumID"];
    }
    
    /*
     * Load photo
     */
    public function loadPhoto($id){
        /*return $this->getTable('photo')
                ->where('PhotoID',$id)
                ->fetch();         
         */
        $row = $this->db
                ->SELECT('*')
                ->FROM('photo')
                ->WHERE('PhotoID = %i', $id)
                ->FETCH();
        
        return $row;
    }
    
    public function deletePhoto($id) {
        /*return $this->getTable('photo')
                ->where('PhotoID', $id)
                ->delete();*/
        $row = $this->db
                ->DELETE('photo')
                ->WHERE('PhotoID = $i', $id);
        
        return $row;
    }

    public function coverPhoto($id) {
         $update = array(
             'CoverPhoto' => 1
         );
            
        /*return $this->getTable('photo')
                ->where('PhotoID', $id)
                ->update($update);*/
         $row = $this->db
                 ->UPDATE('photo', $update)
                 ->WHERE('PhotoID = %i', $id);
         
         return $row;
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
        
        /*return $this->getTable('photo')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('photo', $insert);
        
        return $row;
    }
    
    public function insertPrice($product,$final,$sale = 0){
        $insert = array(
            'ProductID'=>$product,
            //'BuyingPrice'=>
            'SellingPrice'=>$final,
            'SALE'=>$sale,
            'FinalPrice'=>$final
            //'CurrencyID'=>
        );
                
        /*return $this->getTable('price')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('price', $insert);
        
        return $row;
    }
    
    public function loadParameters($id){
        if ($id == null){
            /*return $this->getTable('parameters')
                    ->select('parameters.*,attrib.*,unit.*')
                    ->fetchPairs('ParameterID');*/
            $row = $this->db
                    ->SELECT('parameters.*, attrib.*, unit.*')
                    ->FROM('parameters')
                    ->JOIN('attrib')->ON('attrib.AttribID = parameters.AttribID')
                    ->JOIN('unit')->ON('unit.UnitID = parameters.UnitID')
                    ->FETCHASSOC('ParameterID');
        }
        else {
            /*return $this->getTable('parameters')
                    ->select('parameters.*,attrib.*,unit.*')
                    ->where('ProductID',$id)->fetchPairs('ParameterID');*/
            $row = $this->db
                    ->SELECT('parameters.*, attrib.*, unit.*')
                    ->FROM('parameters')
                    ->JOIN('attrib')->ON('attrib.AttribID = parameters.AttribID')
                    ->JOIN('unit')->ON('unit.UnitID = parameters.UnitID')
                    ->WHERE('ProductID = %i', $id)
                    ->FETCHASSOC('ParameterID');
        }
        
        return $row;
    }

    public function insertParameter($product,$attribute,$value=null,$unit=null){
        $insert = array(
            'ParameterID' => NULL,
            'ProductID' => $product,
            'AttribID' => $attribute,
            'Val' => $value,
            'UnitID' => $unit
        );
        
        /*return $this->getTable('parameters')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('parameters', $insert);
        
        return $row;
    }
    
    public function updateParameter($paramID,$value,$unit=NULL){
        $update = array(
            'Val' => $value,
            'UnitID' => $unit
        );
                
        /*return $this->getTable('parameters')
                ->where('ParameterID',$paramID)
                ->update($update);*/
        $row = $this->db
                ->UPDATE('parameters', $update)
                ->WHERE('ParametersID = %i', $paramID);
                
        return $row;
    }
    
    public function deleteParameter($paramID){
        /*return $this->getTable('parameters')
                ->where('ParameterID', $paramID)
                ->delete();*/
        $row = $this->db
                ->DELETE('parameters')
                ->WHERE('ParameterID = %i', $paramID);
        
        return $row;
    }
    
    public function loadAttribute($id){
        if($id == NULL){
            /*return $this->getTable('attrib');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('attrib');
        }
        else {
            /*return $this->getTable('attrib')
                    ->where('AttribID',$id);*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('attrib')
                    ->WHERE('AttribID = %i', $id);
        }
        
        return $row;
    }

    public function insertAttribute($name){        
        /*$row = $this->getTable('attrib')
                ->where('AttribName',$name)
                ->fetch();*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('attrib')
                ->WHERE('AttribName = $s', $name)
                ->FETCH();
        
        if (!$row) {
            $insert = array(
                'AttribName' => $name
            );
            
            /*return $this->getTable('attrib')
                    ->insert($insert);*/
            return $this->db
                    ->INSERT('attrib', $insert);
        }
        else {            
            return $row->AttribID;
        }
    }
    
    public function updateAttribute($id,$name){
        $update = array(
            'AttribName' => $name
        );
        
        /*return $this->getTable('attrib')
                ->where('AttribID',$id)
                ->update($update);*/
        $row = $this->db
                ->UPDATE('attrib', $update)
                ->WHERE('AttribID = %i', $id);
        
        return $row;
    }
    
    public function loadUnit($id){
        if($id == NULL){
            /*return $this->getTable('unit')
                    ->fetchPairs('UnitID');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('unit')
                    ->FETCHASSOC('UnitID');
        }
        else {
            /*return $this->getTable('unit')
                    ->where('UnitID',$id);*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('unit')
                    ->WHERE('UnitID = %i', $id);
        }
        return $row;
    }

    public function insertUnit($name,$short){
        $insert = array(
            'UnitName' => $name,
            'UnitShort' => $short
        );
        
        /*return $this->getTable('unit')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('unit', $insert);
        
        return $row;
    }
    
    public function updateUnit($id,$name,$short){
        $insert = array(
            'UnitName' => $name,
            'UnitShort' => $short
        );
        
        /*return $this->getTable('unit')
                ->where('UnitID',$id)
                ->update($insert);*/
        $row = $this->db
                ->UPDATE('unit', $insert)
                ->WHERE('UnitID = %i', $id);
        
        return $row;
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
        
        /*return $this->getTable('documentation')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('documentation', $insert);
        
        return $row;
    }
    
    public function loadDocumentation($id){
        /*return $this->getTable('documentation')
                ->where('ProductID',$id);*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('documentation')
                ->WHERE('ProductID = %i', $id);
        
        return $row;
    }
    
    public function deleteDocumentation($id){
        /*return $this->getTable('documentation')
                ->where('DocumentID',$id)
                ->delete();*/
        $row = $this->db
                ->DELETE('documentation')
                ->WHERE('DocumentID = %i', $id);
        
        return $row;
    }
    
    public function loadCoverPhoto($id){
        /*return $this->getTable('photo')
                ->select('photo.PhotoURL, photoalbum.ProductID')
                ->where('photoalbum.ProductID',$id)
                ->where('photo.CoverPhoto','1')
                ->fetch();*/
        $row = $this->db
                ->SELECT('photo.PhotoURL, photoalbum.ProductID')
                ->FROM('photo')
                ->JOIN('photoalbum')->ON('photoalbum.PhotoAlbumID = photo.PhotoAlbumID')
                ->WHERE('photoalbum.ProductID = %i '
                        . 'AND photo.CoverPhoto = 1', $id)
                ->FETCH();               
                
        return $row;
    }

    public function updateCoverPhoto($product,$photo){
        /*$album = $this->getTable('photoalbum')
                ->select('PhotoAlbumID')
                ->where('ProductID',$product)
                ->fetch();       */
        $album = $this->db
                ->SELECT('PhotoAlbumID')
                ->FROM('photoalbum')
                ->WHERE('ProductID = %i', $product)
                ->FETCH();
        
        $albumID = $album['PhotoAlbumID'];
        
        $unsetCover = array(
            'CoverPhoto' => 0
        );
        
        $setCover = array(
            'CoverPhoto' => 1
        );
        
        /*$this->getTable('photo')
                ->where('PhotoAlbumID',$albumID)
                ->where('CoverPhoto','1')
                ->update($unsetCover);
       
        $this->getTable('photo')
                ->where('PhotoID',$photo)
                ->update($setCover);*/
        $this->db
                ->UPDATE('photo', $unsetCover)
                ->WHERE('CoverPhoto = 1 '
                        . 'AND PhotoAlbumID = %i', $albumID);
        
        $this->db
                ->UDPATE('photo', $setCover)
                ->WHERE('PhotoID = %i', $photo);
    }
    
    public function loadProducer($prodid){
        /*return $this->getTable('producer')
                ->where('ProducerID',$prodid)
                ->fetch();*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('producer')
                ->WHERE('ProducerID = %i', $prodid)
                ->FETCH();
        
        return $row;
    }
    
    public function loadProducers(){
        /*return $this->getTable('producer')
                ->fetchPairs('ProducerID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('producer')
                ->FETCHASSOC('ProducerID');
        
        return $row;
    }
    
    public function insertProducer($name, $desc = NULL){
        $insert = array(
            'ProducerName' => $name,
            'ProducerDescription' => $desc
        );
                
        /*return $this->getTable('producer')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('producer', $insert);
        
        return $row;
    }
    
    public function updateProducer($id, $value, $name){
        $update = array(
            $value => $name
        );
        
        /*return $this->getTable('producer')
                ->where('ProducerID',$id)
                ->update($update);*/
        $row = $this->db
                ->UPDATE('producer', $update)
                ->WHERE('ProducerID = %i', $id);
        
        return $row;
    }      
    
    public function loadCheapestDelivery(){
        /*$delivery = $this->getTable('delivery')
                ->select('delivery.DeliveryPrice, status.StatusName')
                ->where('DeliveryPrice != "0"')
                ->where('status.StatusName','active')  
                ->order('DeliveryPrice')
                ->fetchPairs('DeliveryPrice');*/
        $delivery = $this->db
                ->SELECT('delivery.DeliveryPrice, status.StatusName')
                ->FROM('delivery')
                ->JOIN('status')->ON('delivery.StatusID = status.StatusID')
                ->WHERE('DeliveryPrice != 0 '
                        . 'AND status.StatusName = %s', 'active')
                ->ORDERBY('DeliveryPrice')
                ->FETCHASSOC('DeliveryPrice');
        
        $price = reset($delivery);
        
        if($price) {
        $price = $price->DeliveryPrice;
    } 
        return $price;
    }        

    public function insertComment($title,$content,$author,$product,$previous=0){
        $today = date('Y-m-d H:i:s');
            
        $insert = array(
            'CommentTitle' => $title,
            'CommentContent' => $content,
            'DateOfAdded' => $today,
            'Author' => $author,
            'ProductID' => $product,
            'PreviousCommentID' => $previous
        );
        
        /*return $this->getTable('comment')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('comment', $insert);
        
        return $row;
    }
    
    public function loadProductComments($id){
        /*return $this->getTable('comment')
                ->where('ProductID',$id)
                ->fetchPairs('CommentID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('comment')
                ->WHERE('ProductID = %i', $id)
                ->FETCHASSOC('CommentID');
        
        return $row;
    }
    
    public function deleteComment($commentid){
        /*return $this->getTable('comment')
                ->where('CommentID',$commentid)
                ->delete();*/
        $row = $this->db
                ->DELETE('comment')
                ->WHERE('CommentID = %i', $commentid);
        
        return $row;
    }
    
    public function loadComments(){
        /*return $this->getTable('comment')
                ->fetchPairs('CommentID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('comment')
                ->FETCHASSOC('CommentID');
        
        return $row;
    }
    
    public function loadCommentsByDate(){
        /*return $this->getTable('comment')
                ->order('DateOfAdded DESC')
                ->fetchPairs('CommentID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('comment')
                ->ORDERBY('DafeOfAdded DESC')
                ->FETCHASSOC('CommentID');
        
        return $row;
    }
    
    public function loadUnreadCommentsCount($date){
        if ($date == NULL) {
            $date =  date('Y-m-d H:i:s');
        }
        
        /*return $this->getTable('comment')
                ->where('DateOfAdded>',$date)
                ->count();*/
        $row = $this->db
                ->COUNT('comment')
                ->WHERE('DateOfAdded >', $date);
        
        return $row;
    }
    
    public function loadVariantParams($id){
        /*return $this->getTable('productvariants')
                ->where('ProductID',$id);*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('productvariants')
                ->WHERE('ProductID = %i', $id);
        
        return $row;
    }
    
    public function loadProductVariants($id){        
        /*$row = $this->getTable('price')
                ->select('price.*, product.*')
                ->where('product.ProductVariants', $id)
                ->where('product.ProductStatusID!=', 0)
                ->fetchPairs('ProductID');        */
        $row = $this->db
                ->SELECT('price.*, product.*')
                ->FROM('price')
                ->JOIN('product')->ON('product.ProductID = price.ProductID')
                ->WHERE('product.ProductVariants = %i '
                        . 'AND product.ProductStatusID != 0', $id)
                ->FETCHASSOC('ProductID');
        
        if($row==FALSE){
            /*$row = $this->getTable('price')
                    ->select('price.*, product.*')
                    ->where('product.ProductID',$id)
                    ->fetchPairs('ProductID');*/
            $row = $this->db
                ->SELECT('price.*, product.*')
                ->FROM('price')
                ->JOIN('product')->ON('product.ProductID = price.ProductID')
                ->WHERE('product.ProductID = %i', $id)
                ->FETCHASSOC('ProductID');
        };                
        
        return $row;             
    }
            

    public function insertProductVariant($product, $name, $pieces, $price, $dataaval = NULL){
        $today = date('Y-m-d H:i:s');
        
        if($dataaval== NULL){
            $dataaval = '0000-00-00 00:00:00';
        };
        
        /*$originalProduct = $this->getTable('price')
                ->select('price.*, product.*')
                ->where('product.ProductID',$product)
                ->fetch();*/
        $originalProduct = $this->db
                ->SELECT('price.*, product.*')
                ->FROM('price')
                ->JOIN('product')->ON('product.ProductID = price.ProductID')
                ->WHERE('productProductID = %i', $product)
                ->FETCH();
        
        $insert = array(
            'ProductName' => $originalProduct['ProductName'],
            'ProductSeoName' => $originalProduct['ProductSeoName'],
            'ProductVariantName' => $name,
            'ProductVariants' => $product,
            'ProducerID' => $originalProduct['ProducerID'],            
            'ProductNumber' => $originalProduct['ProductNumber'],
            'ProductShort' => $originalProduct['ProductShort'],
            'ProductDescription' => $originalProduct['ProductDescription'],          
            'ProductEAN' => $originalProduct['ProductEAN'],
            'ProductQR' => $originalProduct['ProductQR'],
            'ProductWarranty' => $originalProduct['ProductWarranty'],
            'PiecesAvailable' => $pieces,
            'CategoryID' => $originalProduct['CategoryID'],            
            'DateOfAvailable' => $dataaval,
            'ProductDateOfAdded' => $today,            
        );
        
        /*$row = $this->getTable('product')
                ->insert($insert);   */
        $row = $this->db
                ->INSERT('product', $insert);
        
        $lastprodid = $row["ProductID"];        
        
        $this->insertPrice($lastprodid, $price);               
        
        return $lastprodid;
    }
    
    public function insertVariantParam($product,$name,$attribute,$value,$unit){
        $insert = array(
            'ProductID' => $product,
            'VariantName' => $name,
            'AttribID' => $attribute,
            'Val' => $value,
            'UnitID' => $unit
        );
        
        /*return $this->getTable('parameters')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('parameters', $insert);
        
        return $row;
    }

    public function loadTotalPieces($id){
        /*$variant = $this->getTable('product')
                ->select('ProductID')
                ->where('ProductVariants', $id)
                ->where('ProductStatusID!=', 0)
                ->fetch();*/
        $variant = $this->db
                ->SELECT('ProductID')
                ->FROM('product')
                ->WHERE('ProductStatusID != 0 '
                        . 'AND ProductVariants = %i', $id)
                ->FETCH();
                      
        if($variant != FALSE){
            /*$pieces = $this->getTable('product')                                
                    ->where('ProductVariants = ?', $id)
                    ->sum('PiecesAvailable');*/
            $pieces = $this->db
                    ->SELECT('SUM(PiecesAvailable)')
                    ->FROM('product')
                    ->WHERE('ProductVariants = %i', $id);
        }
        else{
            /*$pieces = $this->getTable('product')
                    ->select('PiecesAvailable')
                    ->where('ProductID', $id)->fetch();            */
            $pieces = $this->db
                    ->SELECT('PiecesAvailable')
                    ->FROM('product')
                    ->WHERE('ProductID = %i', $id)
                    ->FETCH();                        
            
            //$pieces = $pieces['PiecesAvailable'];
        }       
        
        return($pieces);
    }
    
    public function insertVideo($product=NULL, $blog=NULL, $statictext=NULL, $name, $link){
        $insert = array(
            'ProductID' => $product,
            'BlogID' => $blog,
            'StaticTextID' => $statictext,
            'VideoName' => $name,
            'VideoLink' => $link
        );
        
        /*return $this->getTable('video')
                ->insert($insert);*/
        $row = $this->db
                ->INSERT('video', $insert);
        
        return $row;
    }
    
    public function deleteVideo($videoID) {
        /*return $this->getTable('video')
                ->where('VideoID', $videoID)
                ->delete();*/
        
        $row = $this->db
                ->DELETE('video')
                ->WHERE('VideoID = %i', $videoID);
        
        return $row;
    }

    public function loadVideos(){
        /*return $this->getTable('video')
                ->fetchPairs('VideoID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('video')
                ->FETCHASSOC('VideoID');
        
        return $row;
    }

    public function loadVideo($id){
        /*return $this->getTable('video')
                ->where('VideoID', $id)
                ->fetch();*/
        
        $row = $this->db
                ->SELECT('*')
                ->FROM('video')
                ->WHERE('VideoID = %i', $id)
                ->FETCH();
        
        return $row;
    }
    
    public function loadProductVideo($id){
        /*return $this->getTable('video')
                ->where('ProductID', $id)
                ->fetchPairs('VideoID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('video')
                ->WHERE('ProductID = %i', $id)
                ->FETCHASSOC('VideoID');
        
        return $row;
    }
    
    public function loadBlogVideo($id){
        /*return $this->getTable('video')
                ->where('BlogID', $id)
                ->fetchPairs('VideoID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('video')
                ->WHERE('BlogID = %i', $id)
                ->FETCHASSOC('VideoID');
        
        return $row;
    }
    
    public function loadStaticTextVideo($id){
        /*return $this->getTable('video')
                ->where('StaticTextID', $id)
                ->fetchPairs('VideoID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('video')
                ->WHERE('StaticTextID = %i', $id)
                ->FETCHASSOC('VideoID');
        
        return $row;
    }
}