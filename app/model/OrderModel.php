<?php

/*
 * Class OrderModel
 * OrderModel is used for managing orders.
 * CRUD operations.
 */

class OrderModel extends Repository {
    /*
     * Show orderdetails
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
    public function loadOrderDetails($id){
        if($id==''){
            return $this->getTable('orderdetails')->fetch();
        }
        else
        {
            return $this->getTable('orderdetails')->where('orderID',$id)->fetch();
        }
    }
      
    /*
     * Show all orders
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */  
    public function loadOrders(){     
        return $this->getTable('orders')->select('orders.*,delivery.*,payment.*,users.*,status.*')
                ->order('orders.OrderID DESC')->fetchPairs('OrderID');
    }
    
    /*
     * Show one order
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
    public function loadOrder($id){
        return $this->getTable('orders')->select('orders.*,payment.*,delivery.*,users.*,status.*')->where('orders.OrderID',$id)->fetch();
    }
    
    public function loadOrderAddress($id){
        $user = $this->getTable('orders')->select('orders.UsersID')->where('OrderID',$id);
        
        return $this->getTable('address')->where('UsersID',$user)->fetch();
    }
    
     public function updateOrderAddress($id, $street, $zip, $city){
        $user = $this->getTable('orders')->select('orders.UsersID')->where('OrderID',$id);
        
        $update = array('Street' => $street,
                        'ZIPCode' => $zip,
                        'City' => $city);
        
        return $this->getTable('address')->where('UsersID',$user)->update($update);
    }
    
    
    public function updateOrderStreet($id, $street){
        $user = $this->getTable('orders')->select('orders.UsersID')->where('OrderID',$id)->fetch();                
        
        $update = array('Street' => $street);
        
        return $this->getTable('address')->where('UsersID',$user['UsersID'])->update($update);
    }
    
    public function updateOrderCity($id, $city){
        $user = $this->getTable('orders')->select('orders.UsersID')->where('OrderID',$id)->fetch();
        
        $update = array(
            'City' => $city
        );
        
        return $this->getTable('address')->where('UsersID',$user['UsersID'])->update($update);
    }

    public function updateOrderZIP($id, $zip){
        $user = $this->getTable('orders')->select('orders.UsersID')->where('OrderID',$id)->fetch();
        
        $update = array(
            'ZIPCode' => $zip
        );
        
        return $this->getTable('address')->where('UsersID',$user['UsersID'])->update($update);
    }

    /*
     * Show product in order
     */
    public function loadOrderProduct($id){
        //return $this->getTable('orderdetails')->select('orderdetails.* ,product.*')
          //      ->where('orderdetails.OrderID',$id);
          return $this->getDB()->query('SELECT * FROM orderdetails 
              JOIN product ON orderdetails.ProductID=product.ProductID 
              JOIN photoalbum ON product.ProductID=photoalbum.ProductID 
              JOIN photo ON photoalbum.PhotoAlbumID=photo.PhotoAlbumID 
              WHERE photo.CoverPhoto="1" and orderdetails.OrderID=?',$id);
    }
    
    
      public function loadOrderNotes($id){
        return $this->getTable('notes')->where('OrderID',$id)->fetchPairs('NotesID');
    }
    
    public function addNote($id, $name, $note) {
        
        $insert =  array(
            'OrderID' => $id,
            'NotesDate' => date('Y-m-d H:i:s'),
            'NotesName' => $name,
            'NotesDescription' => $note
        );
        return $this->getTable('notes')->insert($insert);
    }
    /*
     * Check and save order
     * @param ?
     * @param ? 
     * @return string
     */
    public function insertOrder($user, $price, $delivery, $payment, $note)
    {                             
            $today = date('Y-m-d H:i:s');
            
            $deliveryprice = $this->loadDeliveryPrice($delivery);            
            $paymentprice = $this->loadPaymentPrice($payment);
            $deliverypaymentprice = $deliveryprice + $paymentprice;
            
            $tax1 = $this->getTable('settings')->select('Value')->where('SettingName',"TAX")->fetch();
            $tax = $tax1['Value'];
            //settype($tax, 'float');
            $finaltax = $price * ($tax / 100);
            
            $pricetax = $price - $finaltax;
            
            $totalprice = $price + $deliverypaymentprice;

            $insert =  array(
                 //'OrderID' => $id, //automaticky!
                //'StatusID' => $status, //automaticky!
                'UsersID' => $user,  //nepraktické, aby se pouzivalo "novak", "admin"
                'ProductsPrice' => $price,
                'DeliveryPaymentPrice' => $deliverypaymentprice,
                'TaxPrice' => $finaltax, //
                'PriceWithoutTax' => $pricetax,
                'TotalPrice' => $totalprice,
                'DateCreated' => $today,  //automaticky presenter
                'DateOfLastChange' => $today, //pri vytvoreni stejne jako created
                //'DateFinished' => '', //? spolu s předchozí řešit až v administraci obj.
                'DeliveryID' => $delivery,
                'PaymentID' => $payment,
                'Note' => $note,
                'IP' => NULL,
                'SessionID' => NULL
            );
           
            return $this->getTable('orders')->insert($insert); /*$lastID =  $lastID['OrderID']; */
    }
    
    public function updateOrder($orderid, $shipping, $payment=NULL) {
        
        $today = date('Y-m-d H:i:s');
        
        if($payment!=NULL){
            $paymentPrice = $this->loadPaymentPrice($payment);
        }
        else{
            $payment = $this->getTable('orders')->select('PaymentID')->where('OrderID',$orderid)->fetch();
            $paymentPrice = $this->loadPaymentPrice($payment['PaymentID']);
        }
            
        $deliveryPrice = $this->loadDeliveryPrice($shipping);
       
        $deliveryPaymentPrice = $paymentPrice + $deliveryPrice;
        
        $productPrice = $this->loadOrder($orderid)->ProductsPrice;
        $total = $productPrice + $deliveryPaymentPrice;
        
        $insert = array(
                'DeliveryID' => $shipping,
                'PaymentID' => $payment,
                'DeliveryPaymentPrice' => $deliveryPaymentPrice,
                'TotalPrice' => $total,
                'DateOfLastChange' => $today
                );   
        
        return $this->getTable('orders')->where('OrderID',$orderid)->update($insert);
        
    }
    
    
    public function updateOrderProducts($orderid, $product, $newProduct, $delivery, $payment, $products) {
       
        $today = date('Y-m-d H:i:s');
        
        //recalculating order
        $deliveryPrice = $this->loadDeliveryPrice($delivery);
        $paymentPrice = $this->loadPaymentPrice($payment);
        $deliveryPaymentPrice = $paymentPrice + $deliveryPrice;
        
        $totalprod = $products + $newProduct;
        $totalprice = $products + $deliveryPaymentPrice + $newProduct;
                        
        //inserting order details - products
        $this->insertOrderDetails($orderid, $product, 1, $newProduct);
                            
        $tax1 = $this->getTable('settings')->select('Value')->where('SettingName',"TAX")->fetch();
        $tax = $tax1['Value'];
            //settype($tax, 'float');
        $finaltax = $totalprod * ($tax / 100);
            
        $pricetax = $totalprice - $finaltax;                    
        
        //updating order total info
        $update = array(
                'ProductsPrice' => $totalprod,
                'DeliveryPaymentPrice' => $deliveryPaymentPrice,
                'TaxPrice' => $finaltax, //
                'PriceWithoutTax' => $pricetax,
                'TotalPrice' => $totalprice,
                'DateOfLastChange' => $today, //pri vytvoreni stejne jako created
                'DeliveryID' => $delivery,
                'PaymentID' => $payment
                );   
        
        return $this->getTable('orders')->where('OrderID',$orderid)->update($update);
        
    }
    
    public function loadUnreadOrders(){
        return $this->getTable('orders')->select('orders.*,delivery.*,payment.*,users.*,status.*')
                ->where('orders.Read = 0')
                ->order('orders.OrderID DESC')->fetchPairs('OrderID');
    }

    public function updateOrderRead($orderid, $value){
        $update = array(
            'Read' => $value
        );
        
        return $this->getTable('orders')->where('OrderID',$orderid)->update($update);
    }

    public function loadUnreadOrdersCount($date){
        if ($date == NULL) {
            $date =  date('Y-m-d H:i:s');
        }
        return $this->getTable('orders')->where('DateCreated>',$date)->count();
    }

    public function removeOrderProducts($orderid, $product) {
        $price = $this->removeOrderDetail($orderid, $product);
        
        $order = $this->loadOrder($orderid);
        $total = $order->TotalPrice;
        $products = $order->ProductsPrice;
        
         //updating order total info
        $insert = array(
                'TotalPrice' => $total - $price,
                'ProductsPrice' => $products + $price
                );   
        
        return $this->getTable('orders')->where('OrderID',$orderid)->update($insert);
    }

    public function checkRemoveProduct($orderid) {
        return $this->getTable('orderdetails')->where('OrderID', $orderid)->count();
    }

    /*
     * Insert order details
     */
    public function insertOrderDetails($orderid, $product, $quantity, $unitprice) 
    {
        $insert = array(
          //  'OrderDetailsID' => $id,
            'OrderID' => $orderid,
            'ProductID' => $product,
            'Quantity' => $quantity,
            'UnitPrice' => $unitprice
        );    
        return $this->getTable('orderdetails')->insert($insert);
    }
    
    /* return price of deleted product */
    public function removeOrderDetail($orderid, $product) {
        
        $detail = $this->getTable('orderdetails')->where('OrderID', $orderid)->where('ProductID', $product)->fetch();
        $price = $detail->UnitPrice;
        $detail->delete();
        
        return $price;
        
    }
    /*
     * Load order statuses
     */
    public function loadStatus($id)
    {
        if($id==''){
            return $this->getTable('orderstatus')->order('StatusProgress')->fetchPairs('OrderStatusID');
        }
        else
        {
            return $this->getTable('orderstatus')->where('OrderStatusID',$id);
        }
    }
    
    /*
     * Insert new order status
     */
    public function insertStatus($id,$name,$description)
    {
        $insert = array(
            'OrderStatusID' => $id,
            'StatusName' => $name,
            'StatusDescription' => $description
        );
        
        return $this->getTable('orderstatus')->insert($insert);
    }

    /*
     * Load payment  for order
     */
    public function loadPayment($id, $switch=NULL){
        if($switch==NULL){
            if($id==''){
                return $this->getTable('payment')->select('payment.*, status.*')
                        ->where('status.StatusName = ? OR status.StatusName = ?','active','non-active')
                        ->fetchPairs('PaymentID');
            }
            else
            {
                return $this->getTable('payment.*, status.*')->where('PaymentID',$id)->fetch();
            }
        }
        elseif ($switch=='active') {
             if($id==''){
                return $this->getTable('payment')->select('payment.*, status.*')
                        ->where('status.StatusName',$switch)->fetchPairs('PaymentID');
            }
            else
            {
                return $this->getTable('payment.*, status.*')->where('PaymentID',$id)->fetch();
            }
        }
    }
    
    public function loadPaymentPrice($id){
        //return $this->getTable('payment')->select('PaymentPrice')->where('PaymentID',$id)->fetch();        
        $payment = $this->getTable('payment')->select('PaymentPrice')->where('PaymentID',$id)->fetch();
        return $payment['PaymentPrice'];
    }

    /*
     * Insert new payment method
     */
    public function insertPayment($name,$price,$status=NULL)
    {
        $insert = array(
            'PaymentName' => $name,
            'PaymentPrice' => $price,
            'StatusID' => $status
        );
                
        return $this->getTable('payment')->insert($insert);
    }
    
    public function updatePayment($id,$name,$price,$status=NULL)
    {
        $update = array(
            'PaymentName' => $name,
            'PaymentPrice' => $price,
            'StatusID' => $status
        );
                
        return $this->getTable('payment')->where('PaymentID',$id)->update($update);
    }
    
    public function updatePaymentName($id,$name)
    {
        $update = array(
            'PaymentName' => $name
        );
                
        return $this->getTable('payment')->where('PaymentID',$id)->update($update);
    }
    
    public function updatePaymentPrice($id,$name)
    {
        $update = array(
            'PaymentPrice' => $name
        );
                
        return $this->getTable('payment')->where('PaymentID',$id)->update($update);
    }
    
    public function updatePaymentStatus($id,$status){
        $update = array(
            'StatusID' => $status
        );
        
        return $this->getTable('payment')->where('PaymentID',$id)->update($update);
    }

        public function deletePayment($id){
            $update = array(
            'StatusID' => 3
        );
        return $this->getTable('payment')->where('PaymentID',$id)->update($update);
    }


    /*
     * Load delivery 
     */
    public function loadDelivery($id,$switch=NULL)
    {
        if($switch==NULL){
            if($id==''){
                return $this->getTable('delivery')->select('delivery.*, status.*')
                        ->where('status.StatusName = ? OR status.StatusName = ?','active', 'non-active')
                        ->fetchPairs('DeliveryID');
            }
            else
            {
                return $this->getTable('delivery')->select('delivery.*, status.*')->where('DeliveryID',$id)->fetch();
            }
        }
        elseif ($switch=='active') {
             if($id==''){
                return $this->getTable('delivery')->select('delivery.*, status.*')
                      //  ->where('delivery.HigherDelivery IS NULL')
                        ->where('status.StatusName',$switch)                        
                        ->fetchPairs('DeliveryID');
            }
            else
            {
                return $this->getTable('delivery.*, status.*')->where('DeliveryID',$id)->fetch();
            }
        }
    }
    
    public function loadParrrentDelivery($iddelivery){
        $row = $this->getTable('delivery')->select('HigherDelivery')->where('DeliveryID',$iddelivery);
        $higher = $row['HigherDelivery'];
        
        return $this->getTable('delivery')->where('DeliveryID',$higher);
    }

    public function loadDeliveryList(){
        return $this->getTable('delivery')
                ->where('status.StatusName = ? OR status.StatusName = ?','active', 'non-active')
                ->fetchPairs('DeliveryID');
    }

    public function loadSubDelivery($higher)
    {
        return $this->getTable('delivery')->select('delivery.*, status.*')
                ->where('delivery.HigherDelivery',$higher)
                ->where('status.StatusName = "active"')
                ->fetchPairs('DeliveryID');
    }
    
    public function loadDeliveryPrice($id){
        //return $this->getTable('delivery')->select('DeliveryPrice')->where('DeliveryID',$id)->fetch();
        $delivery = $this->getTable('delivery')->select('DeliveryPrice')->where('DeliveryID',$id)->fetch();
        return $delivery['DeliveryPrice'];
    }
    
    /*
     * Insert new delivery 
     */
    public function insertDelivery($name,$price,$description=NULL,$free=NULL, $status=NULL, $higher=NULL)
    {
        $insert = array(
            'DeliveryName' => $name,
            'DeliveryDescription' => $description,
            'DeliveryPrice' => $price,
            'FreeFromPrice' => $free,
            'StatusID' => $status,
            'HigherDelivery' => $higher
        );
        
        return $this->getTable('delivery')->insert($insert);
    }
    
    public function updateDeliveryName($id, $name)
    {
        $update = array(
            
            'DeliveryName' => $name
        );
        
        return $this->getTable('delivery')->where('DeliveryID', $id)->update($update);
    }
    
    public function updateDeliveryDescription($id, $description)
    {
        $update = array(
            'DeliveryDescription' => $description,
        );
        
        return $this->getTable('delivery')->where('DeliveryID', $id)->update($update);
    }
    
    public function updateDeliveryPrice($id, $price)
    {
        $update = array(
            'DeliveryPrice' => $price
        );
        
        return $this->getTable('delivery')->where('DeliveryID', $id)->update($update);
    }
    
    public function updateDeliveryFreefrom($id, $ffprice)
    {
        $update = array(
            'FreeFromPrice' => $ffprice
        );
        
        return $this->getTable('delivery')->where('DeliveryID', $id)->update($update);
    }
    
    public function updateDeliveryStatus($id, $statusID){
        $update = array(
            'StatusID' => $statusID
        );
        
        return $this->getTable('delivery')->where('DeliveryID', $id)->update($update);
                
    }
    
    public function updateHigherDelivery($id, $HigherDelID){
        $update = array(
            'HigherDelivery' => $HigherDelID
        );
        
        return $this->getTable('delivery')->where('DeliveryID', $id)->update($update);
                
    }

    public function deleteDelivery($id) {
        $update = array(
            "StatusID" => 3
        );
        return $this->getTable('delivery')->where('DeliveryID',$id)->update($update);
    }
    
    public function deleteSubDelivery($higher){
        $update = array(
            "StatusID" => 3
        );
        return $this->getTable('delivery')->where('HigherDelivery',$higher)->update($update);
    }

    public function countOrder(){
        return $this->getTable('orders')->count();
    }
    
    public function countOrderDetail()
    {
        return $this->getTable('orderdetails')->count();
    }
    
    public function countDelivery()
    {
        return $this->getTable('delivery')->count();
    }
    
    public function setStatus($orderid,$statusid){
        $update = array(
            'StatusID' => $statusid
        );
        
        return $this->getTable('orders')->where('OrderID',$orderid)->update($update);
    }
    
    public function loadStatuses($id){
        if($id==NULL){
            return $this->getTable('status')->fetchPairs('StatusID');
        }
        else{
            return $this->getTable('status')->where('StatusID',$id);
        }
    }


    /*
     * Change order status
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */
    

    /*
     * Generate emails
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */
    
   
    
   
}


