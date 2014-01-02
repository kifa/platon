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
    
    /* @var Translator */
    protected $translator;
    
    public $locale;
    
    protected function startup() {
        parent::startup();
        $salt = $this->shopModel->getShopInfo('Salt');
        $this->cart = $this->getSession('cart'.$salt);
    }
    
    public function injectTranslator(\Kdyby\Translation\Translator $translator) {
        parent::injectTranslator($translator);
        $this->translator = $translator;
    }
    
    public function injectShopModel(\ShopModel $shopModel) {
        parent::injectShopModel($shopModel);
        $this->shopModel = $shopModel;
    }
    
    public function injectProductModel(\ProductModel $productModel) {
        parent::injectProductModel($productModel);
        $this->productModel = $productModel;
    }
    
    public function injectOrderModel(\OrderModel $orderModel) {
        parent::injectOrderModel($orderModel);
        $this->orderModel = $orderModel;
    }

    public function injectUserModel(\UserModel $userModel) {
        parent::injectUserModel($userModel);
        $this->userModel = $userModel;
    }
    
    /*
     * Handle for removing item from Cart
     */

    public function createComponentCartControl() {
        $cart = new CartControl($this->shopModel, $this->productModel, 
                                $this->translator, $this->orderModel, $this->cart);
        $this->addComponent($cart, 'cartControl');
        return $cart;
    }

    public function actionCart($product, $amnt) {
     
       $row = $this->productModel->loadProduct($product);
       
       if (!$row || !$product) {
             if ($this->cart->numberItems > 0) {
                $this->setView('cart');
            } else {
                $this->setView('cartEmpty');
            }
        } else {
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
    }

    public function renderOrder() {
            
            if (!$this->cart->numberItems > 0) {
                $this->redirect('cartEmpty');
            }
            foreach ($this->cart->prd as $id => $amnt) {

                $amnt = $this->cart->prd[$id];
                $product2 = $this->productModel->loadProduct($id);

                $grandtotal = $amnt * $product2->FinalPrice;
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
            $this->template->orderForm = $this->createComponentOrderForm();
            $this->template->grandtotal = $grandtotal;
    
    }
    /*
     * Rendering Customer form in cart
     * 
     * @return $cartForm
     */

    protected function createComponentOrderForm() {
        $cartForm = new OrderForm();
        $cartForm->orderModel = $this->orderModel;
        $cartForm->onSuccess[] = $this->orderFormSubmitted;
        $cartForm->translator = $this->translator;
        return $cartForm;
    }

    public function orderFormSubmitted($form) {
        $total = 0;
        
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
                    $form->values->zip);
        } //USER == FALSE
        else {
                $this->userModel->insertUser(
                        $form->values->email,
                        $form->values->name,
                        $form->values->phone);

                $this->userModel->insertAddress(
                        $form->values->email,
                        $form->values->address,
                        $form->values->city,
                        $form->values->zip);
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
        try { $this->sendOrderDoneMail($orderid);
        } catch (Exception $e) {
              \Nette\Diagnostics\Debugger::log($e);
        }

        try { $this->sendAdminOrderDoneMail($orderid);
        } catch (Exception $e) {
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

    protected function createComponentAddNoteForm() {                       
        $addNote = new OrderNoteForm();
        $addNote->translator = $this->translator;
        $addNote->onSuccess[] = $this->addNoteFormSubmitted;
        return $addNote;
    }
    
    public function addNoteFormSubmitted($form) {
        $this->orderModel->addNote($form->values->orderID, $form->values->userName, $form->values->note);
           
        try { $this->sendNoteMail($form->values->orderID, $form->values->note);
        } catch (Exception $e) {
              \Nette\Diagnostics\Debugger::log($e);
        }
            
        $text = $this->translator->translate(' Note was sucessfully added!');
        $message = Html::el('span', $text);
        $e = Html::el('i')->class('icon-ok-sign left');
        $message->insert(0, $e);
        $this->flashMessage($message, 'alert alert-success');
        $this->redirect('this');
    }
    
    public function renderCartEmpty() {

    }

    public function actionOrderDone($orderid, $track) {
        $row = $this->orderModel->loadOrder($orderid);
        if($track !== md5($row->UsersID . $row->OrderID . $row->DateCreated)){
             $text = $this->translator->translate(' Your password is incorrect. Please try again.');
            $message = HTML::el('span', $text);
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-warning');
            $this->redirect('Homepage:default');
        } else {
            $noteForm = $this['addNoteForm'];
            $noteForm->setDefaults(array('orderID' => $orderid,
                                        'userName' => $row->UsersID));
        }
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
   
    protected function createComponentMail() {
        $mailControl = new mailControl($this->translator);
        return $mailControl;
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
        } else {
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
