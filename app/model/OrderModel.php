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
            /*return $this->getTable('orderdetails')
				->fetch();             
             */
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('orderdetails')
                    ->FETCH();
        }
        else
        {
            /*return $this->getTable('orderdetails')
				->where('orderID',$id)
				->fetch();*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('orderdetails')
                    ->WHERE('orderID = %i', $id)
                    ->FETCH();                    
        }
        return $row;
    }
      
    /*
     * Show all orders
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */  
    public function loadOrders(){     
        /*return $this->getTable('orders')
			->select('orders.*,delivery.*,payment.*,users.*,status.*')
			->order('orders.OrderID DESC')
			->fetchPairs('OrderID');*/
        $row = $this->db
                ->SELECT('orders.*, delivery.*, payment.*, users.*, status.*')
                ->FROM('orders')
                ->JOIN('delivery')->ON('delivery.DeliveryID = orders.DeliveryID')
                ->JOIN('payment')->ON('payment.PaymentID = orders.PaymentID')
                ->JOIN('users')->ON('users.UsersID = orders.UsersID')
                ->JOIN('status')->ON('status.StatusID = orders.StatusID')
                ->FETCHASSOC('OrderID');
        
        return $row;
    }
    
    /*
     * Show one order
     * @param ?
     * @param ? example: pozice počátečního znaku
     * @return string 
     */
    public function loadOrder($id){
        /*return $this->getTable('orders')
			->select('orders.*,payment.*,delivery.*,users.*,status.*')
			->where('orders.OrderID',$id)
			->fetch();*/
        $row = $this->db
                ->SELECT('orders.*, delivery.*, payment.*, users.*, status.*')
                ->FROM('orders')
                ->JOIN('delivery')->ON('delivery.DeliveryID = orders.DeliveryID')
                ->JOIN('payment')->ON('payment.PaymentID = orders.PaymentID')
                ->JOIN('users')->ON('users.UsersID = orders.UsersID')
                ->JOIN('status')->ON('status.StatusID = orders.StatusID')
                ->WHERE('orders.OrderID = %i', $id)
                ->FETCH();
        
        return $row;
    }
    
    public function loadOrderAddress($id){
        /*$user = $this->getTable('orders')
			->select('orders.UsersID')
			->where('OrderID',$id);
        
        return $this->getTable('address')
			->where('UsersID',$user)
			->fetch();*/
        $user = $this->db
                ->SELECT('UserID')
                ->FROM('orders')
                ->WHERE('OrderID = %i', $id);
        
        $row = $this->db
                ->SELECT('*')
                ->FROM('address')
                ->WHERE('UsersID = %s', $user)
                ->FETCH();
        
        return $row;
    }
    
     public function updateOrderAddress($id, $street, $zip, $city){
        /*$user = $this->getTable('orders')
			->select('orders.UsersID')
			->where('OrderID',$id);*/
        
        $user = $this->db
                ->SELECT('UserID')
                ->FROM('orders')
                ->WHERE('OrderID = %i', $id);
        
        $update = array('Street' => $street,
                        'ZIPCode' => $zip,
                        'City' => $city);
        
        /*return $this->getTable('address')
			->where('UsersID',$user)
			->update($update);*/
        $row = $this->db
                ->UPDATE('address', $update)
                ->WHERE('UsersID = %s', $user)
                ->EXECUTE();
        
        return $row;
    }
    
    
    public function updateOrderStreet($id, $street){
        /*$user = $this->getTable('orders')
			->select('orders.UsersID')
			->where('OrderID',$id)
			->fetch();           */     
        
        $user = $this->db
                ->SELECT('UserID')
                ->FROM('orders')
                ->WHERE('OrderID = %i', $id)
                ->FETCH();
        
        $update = array('Street' => $street);
        
        /*return $this->getTable('address')
			->where('UsersID',$user['UsersID'])
			->update($update);*/
        $row = $this->db
                ->UPDATE('address', $update)
                ->WHERE('UsersID = %s', $user['UsersID'])
                ->EXECUTE();

        return $row;
    }
    
    public function updateOrderCity($id, $city){
        /*$user = $this->getTable('orders')
			->select('orders.UsersID')
			->where('OrderID',$id)
			->fetch();*/
        $user = $this->db
                ->SELECT('UserID')
                ->FROM('orders')
                ->WHERE('OrderID = %i', $id)
                ->FETCH();
        
        $update = array(
            'City' => $city
        );
        
        /*return $this->getTable('address')
			->where('UsersID',$user['UsersID'])
			->update($update);*/
        $row = $this->db
                ->UPDATE('address', $update)
                ->WHERE('UsersID = %s', $user['UsersID'])
                ->EXECUTE();
        
        return $row;
    }

    public function updateOrderZIP($id, $zip){
        /*$user = $this->getTable('orders')
			->select('orders.UsersID')
			->where('OrderID',$id)
			->fetch();*/
        $user = $this->db
                ->SELECT('UserID')
                ->FROM('orders')
                ->WHERE('OrderID = %i', $id)
                ->FETCH();
        
        $update = array(
            'ZIPCode' => $zip
        );
        
        /*return $this->getTable('address')
			->where('UsersID',$user['UsersID'])
			->update($update);*/
       
        $row = $this->db
                ->UPDATE('address', $update)
                ->WHERE('UsersID = %s', $user['UsersID'])
                ->EXECUTE();
        
        return $row;                
    }

    /*
     * Show product in order
     */
    public function loadOrderProduct($id){        
          /*return $this->getDB()->query('
			SELECT * 
			FROM orderdetails 
			JOIN product ON orderdetails.ProductID=product.ProductID 
			WHERE orderdetails.OrderID=?',$id);*/
        
        $row = $this->db
                ->SELECT('*')
                ->FROM('orderdetails')
                ->JOIN('product')->ON('orderdetails.ProductID = product.ProductID')
                ->WHERE('orderdetails.OrderID = %i', $id);
        
        return $row;
    }
    
    
    public function loadOrderNotes($id){
        /*return $this->getTable('notes')
			->where('OrderID',$id)
			->fetchPairs('NotesID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('notes')
                ->WHERE('OrderID = %i', $id)
                ->FETCHASSOC('NotesID');
        
        return $row;
    }
    
    public function addNote($id, $name, $note) {
	$time = date('Y-m-d H:i:s');       
 
        $insert =  array(
            'OrderID' => $id,
            'NotesDate' => $time,
            'NotesName' => $name,
            'NotesDescription' => $note
        );
        /*return $this->getTable('notes')
			->insert($insert);
         */
        $row = $this->db
                ->INSERT('notes', $insert)
                ->EXECUTE();
        
        return $row;
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
            
            /*$tax1 = $this->getTable('settings')
				->select('Value')
				->where('SettingName',"TAX")
				->fetch();*/
            $tax1 = $this->db
                    ->SELECT('Value')
                    ->FROM('settings')
                    ->WHERE('SettingName = "TAX"')
                    ->FETCH();
				
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
                'IP' => NULL,
                'SessionID' => NULL
            );                      
			
			/*$row = $this->getTable('orders')
				->insert($insert);*/
                        $row = $this->db
                                ->INSERT('orders', $insert)
                                ->EXECUTE();
			
			$lastorderid = $row['OrderID'];
			
			if($note != ''){
				$this->addNote($lastorderid, $user, $note);
			}
			
			return $row;
    }
    
    public function updateOrder($orderid, $shipping, $payment=NULL) {
        
        $today = date('Y-m-d H:i:s');
        
        if($payment!=NULL){
            $paymentPrice = $this->loadPaymentPrice($payment);
        }
        else{
            /*$payment = $this->getTable('orders')
				->select('PaymentID')
				->where('OrderID',$orderid)
				->fetch();*/
            $payment = $this->db
                    ->SELECT('PaymentID')
                    ->FROM('orders')
                    ->WHERE('OrderID = %i', $orderid)
                    ->FETCH();
				
            $paymentPrice = $this->loadPaymentPrice($payment['PaymentID']);
        }
            
        $deliveryPrice = $this->loadDeliveryPrice($shipping);
       
        $deliveryPaymentPrice = $paymentPrice + $deliveryPrice;
        
        $productPrice = $this->loadOrder($orderid)->ProductsPrice;
        $total = $productPrice + $deliveryPaymentPrice;
        
        $update = array(
                'DeliveryID' => $shipping,
                'PaymentID' => $payment,
                'DeliveryPaymentPrice' => $deliveryPaymentPrice,
                'TotalPrice' => $total,
                'DateOfLastChange' => $today
                );   
        
        /*return $this->getTable('orders')
			->where('OrderID',$orderid)
			->update($update);*/
        $row = $this->db
                ->UPDATE('orders', $update)
                ->EXECUTE();
        
        return $row;        
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
                            
        /*$tax1 = $this->getTable('settings')
			->select('Value')
			->where('SettingName',"TAX")
			->fetch();*/
        $tax1 = $this->db
                    ->SELECT('Value')
                    ->FROM('settings')
                    ->WHERE('SettingName = "TAX"')
                    ->FETCH();
			
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
        
        /*return $this->getTable('orders')
			->where('OrderID',$orderid)
			->update($update);        */
        $row = $this->db
                ->UDPATE('orders', $update)
                ->WHERE('OrderID = %i', $orderid)
                ->EXECUTE();
        
        return $row;
    }
    
    public function loadUnreadOrders(){
        /*return $this->getTable('orders')
			->select('orders.*,delivery.*,payment.*,users.*,status.*')
			->where('orders.Read = 0')
			->order('orders.OrderID DESC')
			->fetchPairs('OrderID');*/
        $row = $this->db
                ->SELECT('orders.*, delivery.*, payment.*, users.*, status.*')
                ->FROM('orders')
                ->JOIN('delivery')->ON('delivery.DeliveryID = orders.DeliveryID')
                ->JOIN('payment')->ON('payment.PaymentID = orders.PaymentID')
                ->JOIN('users')->ON('users.UsersID = orders.UsersID')
                ->JOIN('status')->ON('status.StatusID = orders.StatusID')
                ->WHERE('orders.Read = 0')
                ->orderBy('orders.OrderID DESC')
                ->FETCHASSOC('OrderID');
                
        return $row;
    }

    public function loadLatestOrders(){
            /*return $this->getTable('orders')
                ->select('orders.*,delivery.*,payment.*,users.*,status.*')
                ->where('orders.StatusID != 3')
                ->order('orders.OrderID DESC')
                ->limit(10)
                ->fetchPairs('OrderID');*/
        $row = $this->db
                ->SELECT('orders.*, delivery.*, payment.*, users.*, status.*')
                ->FROM('orders')
                ->JOIN('delivery')->ON('delivery.DeliveryID = orders.DeliveryID')
                ->JOIN('payment')->ON('payment.PaymentID = orders.PaymentID')
                ->JOIN('users')->ON('users.UsersID = orders.UsersID')
                ->JOIN('status')->ON('status.StatusID = orders.StatusID')
                ->WHERE('orders.StatusID != 3')
                ->orderBy('orders.OrderID DESC')
                ->LIMIT(10)
                ->FETCHASSOC('OrderID');
        
        return $row;
}
	
    public function updateOrderRead($orderid, $value){
        $update = array(
            'Read' => $value
        );
        
        /*return $this->getTable('orders')
			->where('OrderID',$orderid)
			->update($update);*/
        $row = $this->db
                ->UPDATE('orders', $update)
                ->WHERE('OrderID', $orderid)
                ->EXECUTE();
        
        return $row;
    }

    public function loadUnreadOrdersCount($date){
        if ($date == NULL) {
            $date =  date('Y-m-d H:i:s');
        }
        /*return $this->getTable('orders')
			->where('DateCreated>',$date)
			->count();*/
        $row = $this->db
                ->COUNT('orders')
                ->WHERE('DateCreated > ', $date);
        
        return $row;
    }

    public function removeOrderProducts($orderid, $product) {
        $price = $this->removeOrderDetail($orderid, $product);
        
        $order = $this->loadOrder($orderid);
        $total = $order->TotalPrice;
        $products = $order->ProductsPrice;
        
         //updating order total info
        $update = array(
                'TotalPrice' => $total - $price,
                'ProductsPrice' => $products + $price
                );   
        
        /*return $this->getTable('orders')
			->where('OrderID',$orderid)
			->update($insert);*/
        $row = $this->db
                ->UPDATE('orders', $update)
                ->WHERE('OrderID = %i', $orderid)
                ->EXECUTE();
        
        return $row;
    }

    public function checkRemoveProduct($orderid) {
        /*return $this->getTable('orderdetails')
			->where('OrderID', $orderid)
			->count();*/
        $row = $this->db
                ->COUNT('orderdetails')
                ->WHERE('OrderID = %i', $orderid);
        
        return $row;
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
        /*return $this->getTable('orderdetails')->insert($insert);
         * 
         */
        
        $row = $this->db
                ->INSERT('orderdetails', $insert)
                ->EXECUTE();
        
        return $row;
    }
    
    /* return price of deleted product */
    public function removeOrderDetail($orderid, $product) {
        
        /*$detail = $this->getTable('orderdetails')
			->where('OrderID', $orderid)
			->where('ProductID', $product)
			->fetch();*/
        $detail = $this->db
                ->SELECT('*')
                ->FROM('orderdetails')
                ->WHERE('OrderID = %i '
                        . 'AND ProductID = %i', $orderid, $product)
                ->FETCH();
        
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
            /*return $this->getTable('orderstatus')
				->order('StatusProgress')
				->fetchPairs('OrderStatusID');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('orderstatus')
                    ->orderBy('StatusProgress')
                    ->FETCHASSOC('OrderStatusID');
        }
        else
        {
            /*return $this->getTable('orderstatus')
				->where('OrderStatusID',$id);*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('orderstatus')
                    ->WHERE('OrderStatusID = %i', $id);
        }
        
        return $row;
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
        
        /*return $this->getTable('orderstatus')
			->insert($insert);*/
        $row = $this->db
                ->INSERT('orderstatus', $insert)
                ->EXECUTE();
        
        return $row;
    }

    /*
     * Load payment  for order
     */
    public function loadPayment($id, $switch=NULL){
        if($switch==NULL){
            if($id==''){
                /*return $this->getTable('payment')
					->select('payment.*, status.*')
					->where('status.StatusName = ? OR status.StatusName = ?','active','non-active')
					->fetchPairs('PaymentID');
                 * 
                 */
                $row = $this->db
                        ->SELECT('payment.*, status.*')
                        ->FROM('payment')
                        ->JOIN('status')->ON('status.StatusID = payment.StatusID')
                        ->WHERE('status.StatusName = "active" '
                                . 'OR status.StatusName = "non-active"')
                        ->FETCHASSOC('PaymentID');
            }
            else
            {
                /*return $this->getTable('payment.*, status.*')
					->where('PaymentID',$id)
					->fetch();*/
                $row = $this->db
                        ->SELECT('payment.*, status.*')
                        ->FROM('payment')
                        ->JOIN('status')->ON('status.StatusID = payment.StatusID')
                        ->WHERE('payment.PaymentID = %i', $id)
                        ->FETCH();
            }
        }
        elseif ($switch=='active') {
             if($id==''){
                /*return $this->getTable('payment')
					->select('payment.*, status.*')
					->where('status.StatusName',$switch)
					->fetchPairs('PaymentID');*/
                $row = $this->db
                        ->SELECT('payment.*, status.*')
                        ->FROM('payment')
                        ->JOIN('status')->ON('status.StatusID = payment.StatusID')
                        ->WHERE('status.StatusName = %s', $switch)
                        ->FETCHASSOC('PaymentID');
            }
            else
            {
                /*return $this->getTable('payment.*, status.*')
					->where('PaymentID',$id)
					->fetch();*/
                $row = $this->db
                        ->SELECT('payment.*, status.*')
                        ->FROM('payment')
                        ->JOIN('status')->ON('status.StatusID = payment.StatusID')
                        ->WHERE('PaymentID = %i', $id)
                        ->FETCH();
            }
        }
        return $row;
    }
    
    public function loadPaymentPrice($id){      
        /*$payment = $this->getTable('payment')
			->select('PaymentPrice')
			->where('PaymentID',$id)
			->fetch();*/
        $payment = $this->db
                ->SELECT('PaymentPrice')
                ->FROM('payment')
                ->WHERE('PaymentID = %i', $id)
                ->FETCH();
	
        $row = $payment['PaymentPrice'];
        
        dump('$row');
        
        return $row;
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
                
        /*return $this->getTable('payment')
			->insert($insert);*/
        $row = $this->db
                ->INSERT('payment', $insert)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updatePayment($id,$name,$price,$status=NULL)
    {
        $update = array(
            'PaymentName' => $name,
            'PaymentPrice' => $price,
            'StatusID' => $status
        );
                
        /*return $this->getTable('payment')
			->where('PaymentID',$id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('payment', $update)
                ->WHERE('PaymentID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updatePaymentName($id,$name)
    {
        $update = array(
            'PaymentName' => $name
        );
                
        /*return $this->getTable('payment')
			->where('PaymentID',$id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('payment', $update)
                ->WHERE('PaymentID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updatePaymentPrice($id,$name)
    {
        $update = array(
            'PaymentPrice' => $name
        );
                
        /*return $this->getTable('payment')
			->where('PaymentID',$id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('payment', $update)
                ->WHERE('PaymentID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updatePaymentStatus($id,$status){
        $update = array(
            'StatusID' => $status
        );
        
        /*return $this->getTable('payment')
			->where('PaymentID',$id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('payment', $update)
                ->WHERE('PaymentID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }

    public function deletePayment($id){
            $update = array(
            'StatusID' => 3
        );
        /*return $this->getTable('payment')
			->where('PaymentID',$id)
			->update($update);*/
        $row = $this->db
            ->UPDATE('payment', $update)
            ->WHERE('PaymentID = %i', $id)
            ->EXECUTE();
        
        return $row;
    }


    /*
     * Load delivery 
     */
    public function loadDelivery($id,$switch=NULL)
    {
        if($switch==NULL){
            if($id==''){
                /*return $this->getTable('delivery')
					->select('delivery.*, status.*')
					->where('status.StatusName = ? OR status.StatusName = ?','active', 'non-active')
					->fetchPairs('DeliveryID');*/
                $row = $this->db
                        ->SELECT('delivery.*, status.*')
                        ->FROM('delivery')
                        ->JOIN('status')->ON('status.StatusID = delivery.StatusID')
                        ->WHERE('status.StatusName = "active" '
                                . 'OR status.StatusName = "non-active"')
                        ->FETCHASSOC('DeliveryID');                
            }
            else
            {
                /*return $this->getTable('delivery')
					->select('delivery.*, status.*')
					->where('DeliveryID',$id)
					->fetch();*/
                $row = $this->db
                        ->SELECT('delivery.*, status.*')
                        ->FROM('delivery')
                        ->JOIN('status')->ON('status.StatusID = delivery.StatusID')
                        ->WHERE('DeliveryID = %i', $id)
                        ->FETCH();
            }
        }
        elseif ($switch=='active') {
             if($id==''){
                /*return $this->getTable('delivery')
					->select('delivery.*, status.*')
				//  ->where('delivery.HigherDelivery IS NULL')
					->where('status.StatusName',$switch)                        
					->fetchPairs('DeliveryID');*/
                 $row = $this->db
                        ->SELECT('delivery.*, status.*')
                        ->FROM('delivery')
                        ->JOIN('status')->ON('status.StatusID = delivery.StatusID')
                        ->WHERE('delivery.HigherDelivery IS NULL '
                                . 'AND status.StatusName = %s', $switch)
                        ->FETCHASSOC('DeliveryID');
            }
            else
            {
                /*return $this->getTable('delivery.*, status.*')
					->where('DeliveryID',$id)
					->fetch();*/
                $row = $this->db
                        ->SELECT('delivery.*, status.*')
                        ->FROM('delivery')
                        ->JOIN('status')->ON('status.StatusID = delivery.StatusID')
                        ->WHERE('DeliveryID = %i', $id)
                        ->FETCH();
            }
        }
        return $row;
    }
    
    public function loadParentDelivery($iddelivery){
        /*$row = $this->getTable('delivery')
			->select('HigherDelivery')
			->where('DeliveryID',$iddelivery);*/
        $row = $this->db
                ->SELECT('HigherDelivery')
                ->FROM('delivery')
                ->WHERE('DeliveryID = %i', $iddelivery);
			
        $higher = $row['HigherDelivery'];
        
        /*return $this->getTable('delivery')
			->where('DeliveryID',$higher);*/
        $return = $this->db
                ->SELECT('*')
                ->FROM('delivery')
                ->WHERE('DeliveryID = %i', $higher);
        
        return $return;
    }

    public function loadDeliveryList(){
        /*return $this->getTable('delivery')
                ->where('status.StatusName = ? OR status.StatusName = ?','active', 'non-active')
                ->fetchPairs('DeliveryID');*/
        $return = $this->db
                ->SELECT('*')
                ->FROM('delivery')
                ->WHERE('status.StatusName = "active" OR status.StatusName = "non-active"')
                ->FETCHASSOC('DeliveryID');
        
        return $return;
    }

    public function loadSubDelivery($higher)
    {
        /*return $this->getTable('delivery')
			->select('delivery.*, status.*')
			->where('delivery.HigherDelivery',$higher)
			->where('status.StatusName = "active"')
			->fetchPairs('DeliveryID');*/
        $row = $this->db
            ->SELECT('delivery.*, status.*')
            ->FROM('delivery')
            ->JOIN('status')->ON('status.StatusID = delivery.StatusID')
            ->WHERE('status.StatusName = "active" '
                    . 'AND delivery.HigherDelivery = %i', $higher)                
            ->FETCHASSOC('DeliveryID');
    }
    
    public function loadDeliveryPrice($id){
        /*$delivery = $this->getTable('delivery')
			->select('DeliveryPrice')
			->where('DeliveryID',$id)
			->fetch();*/
        $delivery = $this->db
                ->SELECT('DeliveryPrice')
                ->FROM('delivery')
                ->WHERE('DeliveryID = %i', $id)
                ->FETCH();
        
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
        
        /*return $this->getTable('delivery')
			->insert($insert);*/
        $row = $this->db
                ->INSERT('delivery', $insert)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateDeliveryName($id, $name)
    {
        $update = array(            
            'DeliveryName' => $name
        );
        
        /*return $this->getTable('delivery')
			->where('DeliveryID', $id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('delivery', $update)
                ->WHERE('DeliveryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateDeliveryDescription($id, $description)
    {
        $update = array(
            'DeliveryDescription' => $description,
        );
        
        /*return $this->getTable('delivery')
			->where('DeliveryID', $id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('delivery', $update)
                ->WHERE('DeliveryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateDeliveryPrice($id, $price)
    {
        $update = array(
            'DeliveryPrice' => $price
        );
        
        /*return $this->getTable('delivery')
			->where('DeliveryID', $id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('delivery', $update)
                ->WHERE('DeliveryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateDeliveryFreefrom($id, $ffprice)
    {
        $update = array(
            'FreeFromPrice' => $ffprice
        );
        
        /*return $this->getTable('delivery')
			->where('DeliveryID', $id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('delivery', $update)
                ->WHERE('DeliveryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateDeliveryStatus($id, $statusID){
        $update = array(
            'StatusID' => $statusID
        );
        
        /*return $this->getTable('delivery')
			->where('DeliveryID', $id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('delivery', $update)
                ->WHERE('DeliveryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function updateHigherDelivery($id, $HigherDelID){
        $update = array(
            'HigherDelivery' => $HigherDelID
        );
        
        /*return $this->getTable('delivery')
			->where('DeliveryID', $id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('delivery', $update)
                ->WHERE('DeliveryID = %i', $id)
                ->EXECUTE();
        
        return $row;                
    }

    public function deleteDelivery($id) {
        $update = array(
            "StatusID" => 3
        );
        /*return $this->getTable('delivery')
			->where('DeliveryID',$id)
			->update($update);*/
        $row = $this->db
                ->UPDATE('delivery', $update)
                ->WHERE('DeliveryID = %i', $id)
                ->EXECUTE();
        
        return $row;
    }
    
    public function deleteSubDelivery($higher){
        $update = array(
            "StatusID" => 3
        );
        /*return $this->getTable('delivery')
			->where('HigherDelivery',$higher)
			->update($update);*/
        $row = $this->db
                ->UPDATE('delivery', $update)
                ->WHERE('HigherDelivery = %i', $higher)
                ->EXECUTE();
        
        return $row;
    }

    public function countOrder(){
        /*return $this->getTable('orders')
			->count();*/
        $row = $this->db
                ->COUNT('orders');
        
        return $row;        
    }
    
    public function countOrderDetail()
    {
        /*return $this->getTable('orderdetails')
			->count();*/
        $row = $this->db
                ->COUNT('orderdetails');
        
        return $row;
    }
    
    public function countDelivery()
    {
        /*return $this->getTable('delivery')
			->count();*/
        $row = $this->db
                ->COUNT('delivery');
        
        return $row;
    }
    
    public function setStatus($orderid,$statusid){
        $update = array(
            'StatusID' => $statusid
        );
        
        /*return $this->getTable('orders')
			->where('OrderID',$orderid)
			->update($update);*/
        $row = $this->db
                ->UPDATE('orders', $update)
                ->WHERE('OrderID = %i', $orderid)
                ->EXECUTE();
        
        return $row;
    }
    
    public function loadStatuses($id){
        if($id==NULL){
            /*return $this->getTable('status')
				->fetchPairs('StatusID');*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('status')
                    ->FETCHASSOC('StatusID');
        }
        else{
            /*return $this->getTable('status')
				->where('StatusID',$id);*/
            $row = $this->db
                    ->SELECT('*')
                    ->FROM('status')
                    ->WHERE('StatusID = %i', $id)
                    ->FETCHPAIRS('StatusID');
        }
        return $row;
    }

    public function search($query) {
        /*return $this->getTable('orders')
			->fetchPairs('OrderID');*/
        $row = $this->db
                ->SELECT('*')
                ->FROM('orders')
                ->FETCHASSOC('OrderID');
        
        return $row;
    }  
}