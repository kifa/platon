<?php

/*
 * Class OrderModel
 * OrderModel is used for managing orders.
 * CRUD operations.
 */

class OrderModel extends Authenticator {
    /*
     * Show orders
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
    public function loadOrders(){
        return $this->getTable('order')->select('order.*,orderdetails.*');
        }

    /*
     * Show one order
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
    public function loadOrder($id){
        return $this->getTable('orderdetails')->select('orderdetails.*,order.*')
                ->where('orderdetails.OrderID',$id)->fetch();
    }
    /*
     * Check and save order
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string
     */
    public function insertOrder($id, $status, $user, $price, $pricetax, $created, 
            $lastchange, $finished, $delivery, $payment)
    {
            $insert=  array(
                'OrderID' => $id,
                'StatusID' => $status,
                'UserID' => $user,
                'TotalPrice' => $price,
                'TotalPriceTax' => $pricetax,
                'DateCreated' => $created,
                'DateOfLastChange' => $lastchange,
                'DateFinished' => $finished,
                'DeliveryID' => $delivery,
                'PaymentMethodID' => $payment,
                'IP' => NULL,
                'SessionID' => NULL
            );
            return $this->getTable('order')->insert($insert);
            
    }
    
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
