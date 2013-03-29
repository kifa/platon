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
        return $this->getTable('orders')->select('orders.*,delivery.*,payment.*,users.*,status.*')->fetchPairs('orders.OrderID');
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
    
    /*
     * Show product in order
     */
    public function loadOrderProduct($id){
        return $this->getTable('orderdetails')->select('orderdetails.* ,product.*')
                ->where('orderdetails.OrderID',$id);
    }
    /*
     * Check and save order
     * @param ?
     * @param ? 
     * @return string
     */
    public function insertOrder($id, $user, $price, $pricetax, $created, 
            $lastchange, $delivery, $payment)
    {
            $userid = $this->getTable('users')->select('UserID')->where('Login',$user);
        
            $insert =  array(
                'OrderID' => $id, //automaticky!
                //'StatusID' => $status, //automaticky!
                'UserID' => $userid,  //nepraktické, aby se pouzivalo "novak", "admin"
                'TotalPrice' => $price, //
                'TotalPriceTax' => $pricetax,
                'DateCreated' => $created,  //automaticky presenter
                'DateOfLastChange' => $lastchange, //pri vytvoreni stejne jako created
                //'DateFinished' => '', //? spolu s předchozí řešit až v administraci obj.
                'DeliveryID' => $delivery,
                'PaymentID' => $payment,
                'IP' => NULL,
                'SessionID' => NULL
            );
            return $this->getTable('orders')->insert($insert);
            
    }
    
    /*
     * Insert order details
     */
    public function insertOrderDetails($id, $orderid, $product, $quantity, $unitprice) 
    {
        $insert = array(
            'OrderDetailsID' => $id,
            'OrderID' => $orderid,
            'ProductID' => $product,
            'Quantity' => $quantity,
            'UnitPrice' => $unitprice
        );    
        return $this->getTable('orderdetails')->insert($insert);
    }
    
    /*
     * Load order statuses
     */
    public function loadStatus($id)
    {
        if($id==''){
            return $this->getTable('status');
        }
        else
        {
            return $this->getTable('status')->where('StatusID',$id);
        }
    }
    
    /*
     * Insert new order status
     */
    public function insertStatus($id,$name,$description)
    {
        $insert = array(
            'StatusID' => $id,
            'StatusName' => $name,
            'StatusDescription' => $description
        );
        
        return $this->getTable('Status')->insert($insert);
    }

    /*
     * Load payment  for order
     */
    public function loadPayment($id){
        if($id==''){
            return $this->getTable('payment')->fetchPairs('PaymentID');
        }
        else
        {
            return $this->getTable('payment')->select('payment.*,price.finalprice')
                    ->where('PaymentID',$id);
        }
    }
    
    /*
     * Insert new payment method
     */
    public function insertPayment($id,$name,$price)
    {
        $insert = array(
            'PaymentID' => $id,
            'PaymentName' => $name,
            'PaymentPrice' => $price
        );
                
        return $this->getTable('payment')->insert($insert);
    }
    
    /*
     * Load delivery 
     */
    public function loadDelivery($id)
    {
        if($id==''){
            return $this->getTable('delivery')->fetchPairs('DeliveryID');
        }
        else
        {
            return $this->getTable('delivery')->select('delivery.*,price.FinalPrice')->where('DeliveryID',$id);
        }
    }
    
    /*
     * Insert new delivery 
     */
    public function insertDelivery($id,$type,$description,$price,$free)
    {
        $insert = array(
            'DeliveryID' => $id,
            'DeliveryName' => $type,
            'DeliveryDescription' => $description,
            'DeliveryPrice' => $price,
            'FreeFromPrice' => $free
        );
        
        return $this->getTable('delivery')->insert($insert);
    }
    
    
    public function countOrder()
    {
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


