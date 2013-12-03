<?php

use Nette\Forms\Form,
    Nette\Utils\Html;
use Nette\Mail\Message;

/**
 * Order presenter.
 */
class OrderPresenter extends BasePresenter {
    
    /* @var OrderModel */
    private $orderModel;
    
    /* @var ProductModel */
    private $productModel;
    
    /* @var ShopModel */
    private $shopModel;
    
    /* @var UserModel */
    private $userModel;
    
    /* @var Session */
    private $cart;
    

    protected function startup() {
        parent::startup();
        $this->orderModel = $this->context->orderModel;
        $this->productModel = $this->context->productModel;
        $this->shopModel = $this->context->shopModel;
        $this->userModel = $this->context->userModel;
        $salt = $this->shopModel->getShopInfo('Salt');
        $this->cart = $this->getSession('cart'.$salt);
    }
        
    /*
     * Handle for removing item from Cart
     */

    public function handleRemoveItem($id) {
        unset($this->cart->prd[$id]);
        $this->cart->graveItem = $id;
        $this->cart->numberItems = Count($this->cart->prd);

        if ($this->cart->numberItems > 0) {

            $text = $this->translator->translate('Product was removed. Isn´t it pitty?!');
            $text2 = $this->translator->translate('Take it Back!');
            $el1 = Html::el('span', $text.' ');
            $el2 = Html::el('a', $text2)->href($this->link('graveItem!'));
            $el1->add($el2);
            $this->flashMessage($el1, 'alert alert-warning');
            
                if($this->isAjax()){
                    $this->invalidateControl('cartTable');   
                }
                else {
                    $this->presenter->redirect('this');
                }
           
        } else {
            $this->redirect('Order:cartEmpty');
        }
    }

    /*
     * Handle for adding amount of goods
     */

    public function handleAddAmount($id) {        
        $mnt = $this->cart->prd[$id];
        $mnt += 1;
        $this->cart->prd[$id] = $mnt;
                
            if($this->isAjax()){
                $this->invalidateControl('cartTable');   
            }
            else {
                $this->presenter->redirect('this');
            }
    }

    /*
     * Handle for removing amount of goods
     * 
     */

    public function handleRemoveAmount($id) {
            $mnt = $this->cart->prd[$id];
            $mnt -= 1;

            if ($mnt > 0) {
                $this->cart->prd[$id] = $mnt;

                    if($this->isAjax()){
                       $this->invalidateControl('cartTable');   
                     }
                    else {
                        $this->presenter->redirect('this');
                    }
                }
            else {
                $this->handleRemoveItem($id);
            }
    }

    /*
     * Handle for moving dead product back to cart
     */

    public function handleGraveItem() {
        $id = $this->cart->graveItem;
        $this->actionCart($id, "1");
    }


    public function actionCart($product, $amnt) {
     
       $row = $this->productModel->loadProduct($product);
       
        if (!$row || !$product) {
             if ($this->cart->numberItems > 0) {
                $this->setView('cart');
            } else {
                $this->setView('cartEmpty');
            }
        }
        else {
            if (isset($this->cart->prd[$product])) {
                $mnt = $this->cart->prd[$product];
                $mnt += $amnt;
                $this->cart->prd[$product] = $mnt;
            } else {
                $this->cart->prd[$product] = $amnt;
            }
            $this->cart->lastItem = $product;
            $this->cart->numberItems = Count($this->cart->prd);

            $ico = HTML::el('i')->class('icon-ok-sign left');
            $text = $this->translator->translate('was successfully added to your cart.');
            $message = HTML::el('span', ' ' . $row->ProductName . ' '.$text);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-success');
           $this->redirect('Order:cart');
       
        }
    }
   

    /*
     * renderCart rendering cart
     * including order form, payment, etc.
     * @row is used for "unknown" product id
     */

    public function renderCart() {

        if ($this->cart->numberItems > 0) {
            foreach ($this->cart->prd as $id => $amnt) {

                $amnt = $this->cart->prd[$id];
                $product2 = $this->productModel->loadProduct($id);

                $c2[$id][$amnt] = $product2;
            }

            $shippers = array();
            $payment = array();

            foreach ($this->orderModel->loadDelivery('','active') as $key => $value) {
                $shippers[$key] = $value->DeliveryPrice;
            };

            
            foreach ($this->orderModel->loadPayment('','active') as $key => $value) {
                $payment[$key] = $value->PaymentPrice;
            };
            
            $this->template->terms = $this->shopModel->loadStaticText(1);
            $this->template->shippers = $shippers;
            $this->template->payment = $payment;
            $this->template->cart = $c2;
            $this->template->cartForm = $this->createComponentCartForm();
           
        } else {
            $this->setView('CartEmpty');
        }
    }

    /*
     * Rendering Customer form in cart
     * 
     * @return $cartForm
     */

    protected function createComponentCartForm() {

        $shippers = array();
        $payment = array();
        $defaultShipping = NULL;
        $lowerShippers = NULL;

        foreach ($this->orderModel->loadDelivery('','active') as $key => $value) {
          //  $t = HTML::el('span', $value->DeliveryPrice)->class('text-info');
            if($value->HigherDelivery === NULL) {
            $shippers[$key] = $value->DeliveryName . ' | ' . $value->DeliveryPrice . ',-';
            
            if($defaultShipping == NULL) {
                $defaultShipping = $key;
            }
            
            }
            else {
                $lowerShippers[$value->HigherDelivery] = $value->HigherDelivery;
            }
        }
        
        foreach ($this->orderModel->loadPayment('','active') as $key => $value) {
            $payment[$key] = $value->PaymentName . ' | ' . $value->PaymentPrice .',-';
        }
        
        $cartForm = new Nette\Application\UI\Form;
        $cartForm->setTranslator($this->translator);

        $cartForm->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $cartForm->addGroup('Delivery info');
        $cartForm->addText('name', 'order.form.name')
                ->addRule(Form::FILLED, 'Would you fill your name, please?')
                ->setAttribute('class', 'form-control');
        $cartForm->addText('phone', 'Phone:')
                ->setAttribute('class', 'form-control');
        $cartForm->addText('email', 'Email:')
                ->setEmptyValue('@')
                ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                ->addRule(Form::FILLED, 'Would you fill your name, please?')
                ->setAttribute('class', 'form-control');
        $cartForm->addGroup('Address');
        $cartForm->addText('address', 'Street:')
                ->addRule(Form::FILLED)
                ->setAttribute('class', 'form-control');
        $cartForm->addText('city', 'City:')
                ->addRule(Form::FILLED)
                ->setAttribute('class', 'form-control');
        $cartForm->addText('zip', 'ZIP:')
                ->addRule(Form::FILLED)
                ->setAttribute('class', 'form-control');
        $cartForm->addGroup('Shipping');
        $cartForm->addSelect('shippers', 'by post/delivery service', $shippers)
                ->setPrompt('-- select shipping --')
                ->setAttribute('class', 'form-control');
                //->setAttribute('class', ' radio')
              //  ->setRequired('Please select Shipping method');
        if(isset($lowerShippers)) {
           
            
            foreach($lowerShippers as $lower) {
                $lowerShippers2 = array();
                foreach($this->orderModel->loadSubDelivery($lower) as $key2 => $value2){
                   $lowerShippers2[$key2] = $value2->DeliveryName . ' | ' . $value2->DeliveryPrice . ',-';
                }   
            
        
        $cartForm->addSelect('lowerShippers'.$lower, 'personal pick up', $lowerShippers2)
                      ->setAttribute('class', 'form-control');
              //  ->setAttribute('class', '.span1 radio')
              }
        $this->template->lowerShippers = $lowerShippers;
        }
        
        $cartForm->addGroup('Payment');
        $cartForm->addSelect('payment', '', $payment)
              //  ->setAttribute('class', '.span1 radio')
                ->setRequired('Please select Payment method')
                ->setAttribute('class', 'form-control');
        $cartForm->addGroup('Terms');
        $cartForm->addButton('termButton', 'Read Terms')
                ->setHtmlId('termsButton')
                ->setAttribute('class', 'btn btn-sm btn-success')
                ->setAttribute('data-toggle', 'modal')
                ->setAttribute('data-target', "#terms");
         $cartForm->addGroup('Notes');
        $cartForm->addTextArea('note', 'Note:')
                ->setAttribute('class', 'form-control');
        $cartForm->addHidden('shipping','shipping')
                ->setDefaultValue($defaultShipping);
        $cartForm->addGroup('Checkout');
        $cartForm->addSubmit('send', 'Checkout')
                ->setAttribute('class', 'btn btn-danger btn-lg');
        
        $cartForm->onSuccess[] = $this->cartFormSubmitted;
        return $cartForm;
    }

    
    protected function createComponentAddNoteForm() {
        
               
        $addNote = new Nette\Application\UI\Form;
        $addNote->setTranslator($this->translator);
        $addNote->addHidden('orderID', '');
        $addNote->addHidden('userName', '');
        $addNote->addTextArea('note', 'Your Note:')
                ->setRequired()
                ->setAttribute('class', 'form-control');  
        $addNote->addSubmit('add' , 'Add note')
                ->setAttribute('class', 'btn-primary upl')
                ->setAttribute('data-loading-text', 'Adding...');
        $addNote->onSuccess[] = $this->addNoteFormSubmitted;
        return $addNote;
                
        
    }
    
    public function addNoteFormSubmitted($form) {
         
          
            $this->orderModel->addNote($form->values->orderID, $form->values->userName, $form->values->note);
           
            try {
                    $this->sendNoteMail($form->values->orderID, $form->values->note);
                }
            catch (Exception $e) {
                   \Nette\Diagnostics\Debugger::log($e);
            }
            
            $text = $this->translator->translate(' Note was sucessfully added!');
            $message = Html::el('span', $text);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('this');
         
    }
    
    
    /*
     * Getting values from CartForm
     * 
     * @param Form, CartForm
     * @return void
     */

    public function cartFormSubmitted($form) {

        $total = 0;
        
        //$tax = $this->shopModel->getTax()->Value;
        //settype($tax, 'float');
        //$finalTax = $total * ($tax / 100);
        

        foreach ($this->cart->prd as $id => $amnt) {
            $price = $this->productModel->loadProduct($id)->FinalPrice;
            $amnt = $this->cart->prd[$id];
            $total += $price * $amnt;  
        }
        
                
        //USER == TRUE        
        if ($this->userModel->isUser($form->values->email)){

        $this->userModel->updateUser(
                    $form->values->email,
                    $form->values->name,
                    $form->values->phone
               );

        $this->userModel->updateAddress(
                    $form->values->email,
                    $form->values->address,
                    $form->values->city,
                    $form->values->zip
                );
        }
        //USER == FALSE
        else {
            $this->userModel->insertUser(
                    $form->values->email,
                    $form->values->name,
                    $form->values->phone
               );

            $this->userModel->insertAddress(
                    $form->values->email,
                    $form->values->address,
                    $form->values->city,
                    $form->values->zip
                );
        }
        
        //STEP 3 - insert order info, assign customer
        $order = $this->orderModel->insertOrder(
                $form->values->email,
                $total,
                $form->values->shipping,
                $form->values->payment,
                $form->values->note
        );

        $orderid = $order['OrderID'];
        

        //STEP 4 - insert Order Details and assign them to Order
        foreach ($this->cart->prd as $id => $amnt) {

            $price = $this->productModel->loadProduct($id)->FinalPrice;
            $amnt = $this->cart->prd[$id];
            $this->orderModel->insertOrderDetails(
                    $orderid,
                    $id,
                    $amnt,
                    $price);
           }
           
        //STEP 5 - redirect to Order Summary
           
           try {
                    $this->sendOrderDoneMail($orderid);
                }
            catch (Exception $e) {
                   \Nette\Diagnostics\Debugger::log($e);
            }
            
            try {
                    $this->sendAdminOrderDoneMail($orderid);
                }
            catch (Exception $e) {
                   \Nette\Diagnostics\Debugger::log($e);
            }
            foreach ($this->cart->prd as $id => $amnt) {

                $this->productModel->decreaseProduct($id, $this->cart->prd[$id]);
               }

            unset($this->cart->prd);
            $this->cart->numberItems = 0;
            
            $module = $this->createComponentModuleControl();
            $module->setParent($this);
            $orderInfo =  array('Shipping' => $form->values->shipping,
                                    'Payment' => $form->values->payment,
                                   'OrderID' => $orderid,
                                   // 'Total' => $row->TotalPrice,
                                    'TotalProducts' => $total,
                                     'Note' => $form->values->note,
                                    'Progress' => 1);
            
            $module->actionOrder($orderInfo);
            
            $track = md5($form->values->email . $orderid . $order->DateCreated);
        
            $this->redirect('Order:orderDone', $orderid, $track);
    }

    /*
     * Render for empty cart
     * rendering empty cart
     */

    public function renderCartEmpty() {

        //$this->template->anyVariable = 'any value';
    }

    /*
     * renderOrderDone()
     * rendering Thank you for your order page
     * 
     * @param int
     * @return void
     */
    
   

    public function actionOrderDone($orderid, $track) {
        $row = $this->orderModel->loadOrder($orderid);
        if($track !== md5($row->UsersID . $row->OrderID . $row->DateCreated)){
             $text = $this->translator->translate(' Your password is incorrect. Please try again.');
            $message = HTML::el('span', $text);
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-warning');
            $this->redirect('Homepage:default');
        }
        else {
            $noteForm = $this['addNoteForm'];
            $noteForm->setDefaults(array('orderID' => $orderid,
                                        'userName' => $row->UsersID));
                    
        }
    }
    
    protected function createComponentMail() {
        $mailControl = new mailControl();
        $mailControl->setTranslator($this->translator);
        $mailControl->setProduct($this->productModel);
        $mailControl->setCategory($this->categoryModel);
        $mailControl->setBlog($this->blogModel);
        return $mailControl;
    }

    public function renderOrderDone($orderid, $track) {

  
        $this->template->products = $this->orderModel->loadOrderProduct($orderid);
        $this->template->order = $this->orderModel->loadOrder($orderid);
        $this->template->statuses = $this->orderModel->loadStatus('');
        $this->template->address = $this->orderModel->loadOrderAddress($orderid);
        $this->template->notes = $this->orderModel->loadOrderNotes($orderid);

        $text = $this->translator->translate(' Order has been successfully sent.');

        $ico = HTML::el('i')->class('icon-ok-sign left');
        $message = HTML::el('span', $text);
        $message->insert(0, $ico);
        $this->flashMessage($message, 'alert alert-success');
        
       
            $this->template->track = 1;
        
    }
   
    /*
     * Render default page
     * 
     * @param null
     * @return void
     */

    public function actionDefault() {
        $this->setView('cart');
    }
    
    
    
    
    protected function sendOrderDoneMail($orderid) {
        
            $row = $this->orderModel->loadOrder($orderid);
            $adminMail = $this->shopModel->getShopInfo('OrderMail');
             $shopName = $this->shopModel->getShopInfo('Name');
            $template = new Nette\Templating\FileTemplate($this->context->parameters['appDir'] . '/templates/Email/yourOrderDone.latte');
            $template->registerFilter(new Nette\Latte\Engine);
            $template->setTranslator($this->translator);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');
            $template->orderId = $orderid;
            $template->mailOrder = $row->UsersID;
            $template->pass = md5($row->UsersID . $row->OrderID . $row->DateCreated);
            $template->adminMail = $adminMail;
            $template->shopName = $shopName;
            $hash = md5($row->UsersID . $row->OrderID . $row->DateCreated);
            $args = array($row->OrderID, $hash);
            $template->link = $this->presenter->link('//Order:orderDone', $args);
            
            $mailIT = new mailControl($this->translator);
            $mailIT->sendSuperMail($row->UsersID, 'Message about your order', $template, $adminMail);
    }
    
    protected function sendAdminOrderDoneMail($orderid) {
        
            $row = $this->orderModel->loadOrder($orderid);
            $adminMail = $this->shopModel->getShopInfo('OrderMail');
             $shopName = $this->shopModel->getShopInfo('Name');
            $template = new Nette\Templating\FileTemplate($this->context->parameters['appDir'] . '/templates/Email/adminOrderDone.latte');
            $template->registerFilter(new Nette\Latte\Engine);
            $template->setTranslator($this->translator);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');
            $template->orderId = $orderid;
            $template->customer = $row->Name;
            $template->mailOrder = $row->UsersID;
            $template->finalprice = $row->TotalPrice;
            
            $template->link = $this->presenter->link('//SmartPanel:orderDetail', $orderid);
            
            $template->adminMail = $adminMail;
            $template->shopName = $shopName;
            
            if($row->Note !== NULL) {
                $template->note = $row->Note;
            }
            else {
                $template->note = NULL;
            }
            
            $mailIT = new mailControl($this->translator);
            $mailIT->sendSuperMail($adminMail, 'New Order '.$orderid, $template, $adminMail);
    }
    
     protected function sendNoteMail($orderid, $note) {
        
            $row = $this->orderModel->loadOrder($orderid);
             $adminMail = $this->shopModel->getShopInfo('OrderMail');
             $shopName = $this->shopModel->getShopInfo('Name');
            $template = new Nette\Templating\FileTemplate($this->context->parameters['appDir'] . '/templates/Email/yourOrderNote.latte');
            $template->registerFilter(new Nette\Latte\Engine);
            $template->setTranslator($this->translator);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');
            $template->orderId = $orderid;
            $template->adminMail = $adminMail;
            $template->shopName = $shopName;
            $template->customer = $row->Name;
            $template->note = $note;
            
            $mailIT = new mailControl($this->translator);
            $mailIT->sendSuperMail($adminMail, 'Message about order', $template, $row->UserID);
    }

}
