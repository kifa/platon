<?php

/*
 * Class OrderModel
 * OrderModel is used for managing orders.
 * CRUD operations.
 */

class OrderModel extends Authenticator {
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
     * Show one order
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
    public function loadOrder($id){
        return $this->getTable('order')->select('order.*,delivery.*,payment.*,orderstatus.*')->where('order.OrderID',$id)->fetch();
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
    public function insertOrder($id, $status, $user, $price, $pricetax, $created, 
            $lastchange, $finished, $delivery, $payment)
    {
            $insert =  array(
                'OrderID' => $id, //automaticky!
                'StatusID' => $status, //automaticky!
                'UserID' => $user,  //nepraktické, aby se pouzivalo "novak", "admin"
                'TotalPrice' => $price, //
                'TotalPriceTax' => $pricetax,
                'DateCreated' => $created,  //automaticky zde
                'DateOfLastChange' => $lastchange, //?
                'DateFinished' => $finished, //? spolu s předchozí řešit až v administraci obj.
                'DeliveryID' => $delivery,
                'PaymentMethodID' => $payment,
                'IP' => NULL,
                'SessionID' => NULL
            );
            return $this->getTable('order')->insert($insert);
            
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
    public function loadOrderStatus($id)
    {
        if($id==''){
            return $this->getTable('orderstatus');
        }
        else
        {
            return $this->getTable('orderstatus')->where('StatusID',$id);
        }
    }
    
    /*
     * Insert new order status
     */
    public function insertOrderStatus($id,$name,$description)
    {
        $insert = array(
            'OrderStatusID' => $id,
            'OrderStatusName' => $name,
            'OrderStatusDescription' => $description
        );
        
        return $this->getTable('orderstatus')->insert($insert);
    }

    /*
     * Load payment method for order
     */
    public function loadPaymentMethod($id){
        if($id==''){
            return $this->getTable('paymentmethod')->fetchPairs('PaymentMethodID');
        }
        else
        {
            return $this->getTable('paymentmethod')->select('paymentmethod.*,price.finalprice')
                    ->where('PaymentMethodID',$id);
        }
    }
    
    /*
     * Insert new payment method
     */
    public function insertPaymentMethod($id,$name,$price)
    {
        $insert = array(
            'PaymentMethodID' => $id,
            'PaymentMethodName' => $name,
            'PaymentPriceID' => $price
        );
                
        return $this->getTable('paymentmethod')->insert($insert);
    }
    
    /*
     * Load delivery method
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
     * Insert new delivery method
     */
    public function insertDelivery($id,$type,$description,$price,$free)
    {
        $insert = array(
            'DeliveryID' => $id,
            'TypeOfDelivery' => $type,
            'DeliveryDescription' => $description,
            'PriceID' => $price,
            'FreeFromPrice' => $free
        );
        
        return $this->getTable('delivery')->insert($insert);
    }
    
    
    public function countOrder()
    {
        return $this->getTable('order')->count();
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
    
    /*
     * Card session
     */
    
   
}

?>
