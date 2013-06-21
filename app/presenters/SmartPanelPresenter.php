<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/*
 * SettingsPreseter is used for setting up shop etc
 * 
 */

class SmartPanelPresenter extends BasePresenter {
    /* @var OrderModel */

    private $orderModel;

    /* @var ProductModel */
    private $productModel;

    /* @var CategoryModel */
    private $categoryModel;

    /* @var ShopModel */
    private $shopModel;

    /* @var UserModel */
    private $userModel;

    /* @var Translator */
    protected $translator;
    
    private $orderRow;
    
    private $productInOrder;
    
     private $orderAddress;

    protected function startup() {
        parent::startup();
        $this->orderModel = $this->context->orderModel;
        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;
        $this->shopModel = $this->context->shopModel;
        $this->userModel = $this->context->userModel;
    }

    /*
     * Action for setting OrderStatus
     */

    public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }

    public function handleSetStatus($orderid, $statusID, $name) {
        
           
            $this->orderModel->setStatus($orderid, $statusID);
        
            $message = Html::el('span', ' Order status in now: ' . $name);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
             if ($statusID !== 0) {
            $this->flashMessage($message, 'alert alert-info');
               }
            else {
                 $this->flashMessage($message, 'alert alert-error');
            }
                    
        $this->redirect('this');
    }

    protected function createComponentPasswordForm() {
        $form = new Nette\Application\UI\Form;
        $form->setRenderer(new BootstrapRenderer);
        $form->setTranslator($this->translator);
        $form->addHidden('login', $this->getUser()->getIdentity()->id);
        $form->addPassword('newPassword', 'Nové heslo:', 30)
                ->addRule(Form::MIN_LENGTH, 'Nové heslo musí mít alespoň %d znaků.', 6);
        $form->addPassword('confirmPassword', 'Potvrzení hesla:', 30)
                ->addRule(Form::FILLED, 'Nové heslo je nutné zadat ještě jednou pro potvrzení.')
                ->addRule(Form::EQUAL, 'Zadná hesla se musejí shodovat.', $form['newPassword']);
        $form->addSubmit('set', 'Změnit heslo');
        $form->onSuccess[] = $this->passwordFormSubmitted;
        return $form;
    }

    public function passwordFormSubmitted($form) {
        $values = $form->getValues();
        //$user = $form->getValues('login');

        try {
            // $this->authenticator->authenticate(array($user->getIdentity()->username, $values->oldPassword));
            $this->userModel->setPassword($values->login, $values->newPassword);
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' Your password was successfully changed.');
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-info');
            $this->redirect('SmartPanel:default');
        } catch (NS\AuthenticationException $e) {
            $form->addError('Zadané heslo není správné.');
        }
    }

    protected function createComponentNewUserForm() {
        $form = new Form();
        $form->setRenderer(new BootstrapRenderer);
        $form->setTranslator($this->translator);
        $form->addText('username', 'Uživatelské jméno:', 10);
        $form->addText('name', 'Vaše jméno', 30);
        $form->addPassword('password', 'Heslo:', 30)
                ->addRule(Form::MIN_LENGTH, 'Nové heslo musí mí alespoň %d znaků', 6);
        $form->addPassword('confirmPassword', 'Heslo pro kontrolu', 30)
                ->addRule(Form::FILLED, 'Je nutné vyplnit!')
                ->addRule(Form::EQUAL, 'Zadaná hesla se musí shodovat', $form['password']);
        $form->addSubmit('add', 'Zaregistrovat');
        $form->onSuccess[] = $this->newUserFormSubmitted;
        return $form;
    }

    /*
     * Submitting new users
     * 
     * @param Form
     */

    public function newUserFormSubmitted(Form $form) {
        $value = $form->getValues();
        try {
            $this->users->userAdd($value->name, $value->username, $value->password);
            $this->flashMessage('Jste zaregistrováni. Můžete se přihlásit', 'success');
            $this->redirect('Sign:in');
        } catch (NS\AuthenticationException $e) {
            $form->addError('Prostě nám to nejde');
        }
    }

    /*******************************************************************
     *              ORDER DETAIL
     *
     ********************************************************************/
    
    public function actionOrderDetail($orderid) {
        if (!$this->getUser()->isInRole('admin')) {
             $this->redirect('Sign:in');
        } else {
        
        $row = $this->orderModel->loadOrder($orderid);
        if (!$row) {
            $message = Html::el('span', ' This order wasnt placed, yet. Sorry.');
            $e = Html::el('i')->class('icon-warning-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert');
            $this->presenter->redirect('SmartPanel:Orders');
         }
            $this->orderRow = array('Shipping' => $row->DeliveryID,
                                    'Payment' => $row->PaymentID,
                                   'OrderID' => $row->OrderID,
                                   // 'Total' => $row->TotalPrice,
                                    'TotalProducts' => $row->ProductsPrice,
                                     'Note' => $row->Note);
            $adress = $this->orderModel->loadOrderAddress($orderid);
            $this->orderAddress = array('Street' => $adress->Street,
                                       'ZIPCode' => $adress->ZIPCode,
                                       'City' => $adress->City);
            
            $editAddress = $this['editOrderAddressForm'];
            $editForm = $this['editOrderInfoForm'];
            $this->productInOrder = $this->orderModel->checkRemoveProduct($orderid);
        }
        
    }
    
    public function handleRemoveProduct($orderid, $product, $amount) {
        
        if ($this->productInOrder > 1) {
        $this->orderModel->removeOrderProducts($orderid, $product);
        $this->productModel->updateProduct($product, 'PiecesAvailable', $amount);
        
        $message = Html::el('span', ' Product was sucessfully removed.');
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert');
        }
        else {
            $message = Html::el('span', ' Cannot delete last product. Would you like to ');
            $cancel = Html::el('a', 'CANCEL ORDER')->href($this->link('setStatus!', $orderid, 0));
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $message->insert(2, $cancel);
            $this->flashMessage($message, 'alert');
        }
        $this->redirect('this'); 
        
    
    }

    protected function createComponentEditOrderInfoForm() {
        
        $shippers = array();
        $payment = array();

        foreach ($this->orderModel->loadDelivery('') as $key => $value) {
          //  $t = HTML::el('span', $value->DeliveryPrice)->class('text-info');
            $shippers[$key] = $value->DeliveryName . ' | ' . $value->DeliveryPrice .',-';
        };

        foreach ($this->orderModel->loadPayment('') as $key => $value) {
            $payment[$key] = $value->PaymentName . ' | ' . $value->PaymentPrice.',-';
        };
        
        $editForm = new Nette\Application\UI\Form;
        $editForm->setRenderer(new BootstrapRenderer);
        $editForm->setTranslator($this->translator);
        $editForm->addHidden('orderID', $this->orderRow['OrderID']);
        $editForm->addSelect('shipper', 'Shipping:', $shippers)
                ->setDefaultValue($this->orderRow['Shipping']);
        $editForm->addSelect('payment', 'Payment:', $payment)
            ->setDefaultValue($this->orderRow['Payment']);
        $editForm->addSubmit('edit', 'Edit info')
                    ->setAttribute('class', 'btn-primary upl')
                    ->setAttribute('data-loading-text', 'Editing...');
        $editForm->onSuccess[] = $this->editOrderInfoFormSubmitted;
        return $editForm;
    }

    public function editOrderInfoFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            
            $this->orderModel->updateOrder($form->values->orderID, $form->values->shipper, $form->values->payment);
            
            $message = Html::el('span', ' Order was sucessfully updated!');
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert');
            $this->redirect('this');
            
        }
        
    }
    
     protected function createComponentEditOrderAddressForm() {
        
                
        $editForm = new Nette\Application\UI\Form;
        $editForm->setRenderer(new BootstrapRenderer);
        $editForm->setTranslator($this->translator);
        $editForm->addHidden('orderID', $this->orderRow['OrderID']);
        $editForm->addText('street', 'Street:')
                ->setDefaultValue($this->orderAddress['Street']);
        $editForm->addText('zipcode', 'ZIP:')
                ->setDefaultValue($this->orderAddress['ZIPCode']);
        $editForm->addText('city', 'City:')
                ->setDefaultValue($this->orderAddress['City']);
        $editForm->addSubmit('edit', 'Edit address')
                    ->setAttribute('class', 'btn-primary upl')
                    ->setAttribute('data-loading-text', 'Editing...');
        $editForm->onSuccess[] = $this->editOrderAddressFormSubmitted;
        return $editForm;
    }

    public function editOrderAddressFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            
            $this->orderModel->updateOrderAddress($form->values->orderID, $form->values->street, $form->values->zipcode, $form->values->city);
            
            $message = Html::el('span', ' Order address was sucessfully updated!');
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert');
            $this->redirect('this');
            
        }
        
    }
    
    
    
    protected function createComponentAddProductsForm() {
        
               
        $editProducts = new Nette\Application\UI\Form;
        $editProducts->setTranslator($this->translator);
       // $editProducts->setRenderer(new BootstrapRenderer);
        $editProducts->addHidden('orderID', $this->orderRow['OrderID']);
        $editProducts->addHidden('totalProducts', $this->orderRow['TotalProducts'] );
        
        foreach ($this->productModel->loadCatalog('') as $id => $product) {
            $products[$product->ProductID] = $product->ProductName;
           
            $editProducts->addHidden($product->ProductID, $product->FinalPrice);
         }
        $editProducts->addSelect('product', 'Select Product to add', $products)
                ->setRequired();
        
        $editProducts->addSubmit('add' , 'Add products')
                ->setAttribute('class', 'btn-primary upl')
                    ->setAttribute('data-loading-text', 'Adding...');
        $editProducts->onSuccess[] = $this->addProductsFormSubmitted;
        return $editProducts;
                
        
    }
    
    public function addProductsFormSubmitted($form) {
         if ($this->getUser()->isInRole('admin')) {
             $pID = $form->values->product;
          
             $this->orderModel->updateOrderProducts($form->values->orderID, $form->values->product,
                     $form->values->$pID,
                     $this->orderRow['Shipping'], 
                     $this->orderRow['Payment'],
                     $this->orderRow['TotalProducts']);
             
            $this->productModel->decreaseProduct($form->values->product, 1); 
             
            $message = Html::el('span', ' Product was sucessfully added!');
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert');
            $this->redirect('this');
         }
    }
    
    protected function createComponentAddNoteForm() {
        
               
        $editProducts = new Nette\Application\UI\Form;
        $editProducts->setTranslator($this->translator);
       // $editProducts->setRenderer(new BootstrapRenderer);
        $editProducts->addHidden('orderID', $this->orderRow['OrderID']);
        $editProducts->addHidden('userName', $this->getUser()->getId());
        $editProducts->addTextArea('note', 'Your Note:')
                ->setRequired();
        
        $editProducts->addSubmit('add' , 'Add note')
                ->setAttribute('class', 'btn-primary upl')
                    ->setAttribute('data-loading-text', 'Adding...');
        $editProducts->onSuccess[] = $this->addNoteFormSubmitted;
        return $editProducts;
                
        
    }
    
    public function addNoteFormSubmitted($form) {
         if ($this->getUser()->isInRole('admin')) {
          
            $this->orderModel->addNote($form->values->orderID, $form->values->userName, $form->values->note);
             
             
            $message = Html::el('span', ' Note was sucessfully added!');
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert');
            $this->redirect('this');
         }
    }
    
    public function createComponentEditNote() {
        
    }

    public function renderOrderDetail($orderid) {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
            $this->template->products = $this->orderModel->loadOrderProduct($orderid);
            $this->template->order = $this->orderModel->loadOrder($orderid);
            $this->template->statuses = $this->orderModel->loadStatus('');
            $this->template->address = $this->orderModel->loadOrderAddress($orderid);
            $this->template->notes = $this->orderModel->loadOrderNotes($orderid);
            $this->template->productsInOrder = $this->productInOrder;
                    
            $this->template->nextOrder = $this->orderModel->loadOrder($orderid+1);
        }
    }

    /**************************************************************************/
    /*        Render Shipping method and settings           */
    /**********************************************************************/

    public function actionShipping(){
          if ($this->getUser()->isInRole('admin')) {
        foreach ($this->orderModel->loadDelivery('') as $id => $deliver){
            $deliveryInfo = array(
                'DeliveryID' => $deliver->DeliveryID,
                'DeliveryName' => $deliver->DeliveryName,
                'DeliveryPrice' => $deliver->DeliveryPrice,
                'DeliveryDescription' => $deliver->DeliveryDescription,
                'FreeFromPrice' => $deliver->FreeFromPrice
            );
            $this['editShipping'.$deliver->DeliveryID] = $this->createComponentEditShippingForm($deliveryInfo);
        }
          }
        
    }
    
    public function handleDelTitle($delid) {
       
         if($this->isAjax())
        {            
            $content = $_POST['value']; //odesílaná nová hodnota

            $this->orderModel->updateDelivery($delid, $content);
           
        }
        if(!$this->isControlInvalid('DelTitle'))
        {           
            $this->payload->edit = $content; //zaslání nové hodnoty do šablony
            $this->sendPayload();
            $this->invalidateControl('menu');       
            $this->invalidateControl('DelTitle'); //invalidace snipetu
           
        }
        else {
         $this->redirect('this');
        }
    }

    public function handleDelDescription($delid) {
       
         if($this->isAjax())
        {            
            $content = $_POST['value']; //odesílaná nová hodnota

            $this->orderModel->updateDeliveryDescription($delid, $content);
           
        }
        if(!$this->isControlInvalid('DelDescription'))
        {           
            $this->payload->edit = $content; //zaslání nové hodnoty do šablony
            $this->sendPayload();
            $this->invalidateControl('menu');       
            $this->invalidateControl('DelDescription'); //invalidace snipetu
           
        }
        else {
         $this->redirect('this');
        }
    }
    
    public function handleDelPrice($delid) {
       
         if($this->isAjax())
        {            
            $content = $_POST['value']; //odesílaná nová hodnota

            $this->orderModel->updateDeliveryPrice($delid, $content);
           
        }
        if(!$this->isControlInvalid('DelPrice'))
        {           
            $this->payload->edit = $content; //zaslání nové hodnoty do šablony
            $this->sendPayload();
            $this->invalidateControl('menu');       
            $this->invalidateControl('DelPrice'); //invalidace snipetu
           
        }
        else {
         $this->redirect('this');
        }
    }
    
    public function handleDelFF($delid) {
       
         if($this->isAjax())
        {            
            $content = $_POST['value']; //odesílaná nová hodnota

            $this->orderModel->updateDeliveryFreefrom($delid, $content);
           
        }
        if(!$this->isControlInvalid('DelFF'))
        {           
            $this->payload->edit = $content; //zaslání nové hodnoty do šablony
            $this->sendPayload();
            $this->invalidateControl('menu');       
            $this->invalidateControl('DelFF'); //invalidace snipetu
           
        }
        else {
         $this->redirect('this');
        }
    }

    public function handleRemoveShip($id) {
       if ($this->getUser()->isInRole('admin')) {
         $row = $this->orderModel->loadDelivery($id);
         if($row) {       
            $this->orderModel->deleteDelivery($id);
            $message = Html::el('span', ' was removed.');
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, ' '. $row->DeliveryName);
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert');
            $this->presenter->redirect("this");
         }
         else {
             
             $this->flashMessage('This shipping cannot be removed.', 'alert');
                $this->presenter->redirect("this");
             
         }
        }
    }
    
    protected function createComponentAddShippingForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);
            $addForm->setRenderer(new BootstrapRenderer);

            $addForm->addGroup('Create new shipping:');
            $addForm->addText('newShip', 'Shipping name:')
                    ->setRequired();
            $addForm->addText('priceShip', 'Shipping price:')
                    ->setRequired()
                    ->addRule(FORM::FLOAT, 'This has to be a number');
            $addForm->addText('descShip', 'Description:');
            $addForm->addText('freeShip', 'Free from:');
                    //->setDefaultValue(0)
                    //->addRule(FORM::FLOAT, 'This has to be a number.');
            $addForm->addSubmit('add', 'Add Shipping')
                    ->setAttribute('class', 'upl-add btn btn-primary')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addForm->onSuccess[] = $this->addShippingFormSubmitted;

            return $addForm;
        }
    }

    public function addShippingFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

            $this->orderModel->insertDelivery($form->values->newShip,
                                              $form->values->priceShip,
                                              $form->values->descShip,
                                              $form->values->freeShip);
            
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' was added sucessfully to your shipping method.');
            $message->insert(0, ' ' . $form->values->newShip);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert success');
            $this->redirect('this');
        }
    }

    protected function createComponentEditShippingForm($deliveryID){
         if ($this->getUser()->isInRole('admin')) { 
        $editShip = new Nette\Application\UI\Form;
        
        $editShip->setTranslator($this->translator);
        $editShip->setRenderer(new BootstrapRenderer);
        $editShip->addText('name', 'Name:')
                ->setDefaultValue($deliveryID['DeliveryName'])
                ->setRequired();
        $editShip->addText('desc', 'Description:')
                ->setDefaultValue($deliveryID['DeliveryDescription']);
        $editShip->addText('price', 'Price:')
                ->setDefaultValue($deliveryID['DeliveryPrice']);
        $editShip->addText('free', 'Free from:')
                ->setDefaultValue($deliveryID['FreeFromPrice']);
        $editShip->addHidden('deliveryID', $deliveryID['DeliveryID'] );
        $editShip->addSubmit('edit', 'Edit shipping')
                ->setAttribute('class', 'btn btn-primary upl')
                        ->setAttribute('data-loading-text', 'Saving...');
        $editShip->onSuccess[] = $this->editShippingSubmitted;
        return $editShip;
         }     
    }
    
    public function editShippingSubmitted($form) {
          if ($this->getUser()->isInRole('admin')) {
        $this->orderModel->updateDelivery($form->values->deliveryID, $form->values->name, $form->values->desc, $form->values->price, $form->values->free);
          
        $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' was added sucessfully updates.');
            $message->insert(0, ' ' . $form->values->name);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert success');
            $this->redirect('this');
          }
    }

    public function renderShipping() {
        
        $this->template->delivery = $this->orderModel->loadDelivery('');
        
    }
    
    /**************************************************************************/
    /*        Render payment method and settings           */
    /**********************************************************************/
    public function actionPayment(){
          if ($this->getUser()->isInRole('admin')) {
        foreach ($this->orderModel->loadPayment('') as $id => $payment){
            $paymentInfo = array(
                'PaymentID' => $payment->PaymentID,
                'PaymentName' => $payment->PaymentName,
                'PaymentPrice' => $payment->PaymentPrice
            );
            $this['editPayment'.$payment->PaymentID] = $this->createComponentEditPaymentForm($paymentInfo);
        }
          }
        
    }
    
    public function handleRemovePay($id) {
       if ($this->getUser()->isInRole('admin')) {
         $row = $this->orderModel->loadPayment($id)->fetch();
         if($row) {       
            $this->orderModel->deletePayment($id);
            $message = Html::el('span', ' was removed.');
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, ' '. $row->PaymentName);
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert');
            $this->presenter->redirect("this");
         }
         else {
             
             $this->flashMessage('This payment cannot be removed.', 'alert');
                $this->presenter->redirect("this");
             
         }
        }
    }
    
    protected function createComponentAddPaymentForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);
            $addForm->setRenderer(new BootstrapRenderer);

            $addForm->addGroup('Create new payment:');
            $addForm->addText('newPay', 'Payment name:')
                    ->setRequired();
            $addForm->addText('pricePay', 'Payment price:')
                    ->setRequired()
                    ->addRule(FORM::FLOAT, 'This has to be a number');
                         //->setDefaultValue(0)
                    //->addRule(FORM::FLOAT, 'This has to be a number.');
            $addForm->addSubmit('add', 'Add Payment')
                    ->setAttribute('class', 'upl-add btn btn-primary')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addForm->onSuccess[] = $this->addPaymentFormSubmitted;

            return $addForm;
        }
    }

    public function addPaymentFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

            $this->orderModel->insertPayment($form->values->newPay,
                                              $form->values->pricePay
                                              );
            
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' was added sucessfully to your payment method.');
            $message->insert(0, ' ' . $form->values->newPay);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert success');
            $this->redirect('this');
        }
    }

    protected function createComponentEditPaymentForm($paymentID){
         if ($this->getUser()->isInRole('admin')) { 
        $editPay = new Nette\Application\UI\Form;
        
        $editPay->setTranslator($this->translator);
        $editPay->setRenderer(new BootstrapRenderer);
        $editPay->addText('name', 'Name:')
                ->setDefaultValue($paymentID['PaymentName'])
                ->setRequired();
        $editPay->addText('price', 'Price:')
                ->setDefaultValue($paymentID['PaymentPrice']);
        $editPay->addHidden('paymentID', $paymentID['PaymentID'] );
        $editPay->addSubmit('edit', 'Edit payment')
                ->setAttribute('class', 'btn btn-primary upl')
                        ->setAttribute('data-loading-text', 'Saving...');
        $editPay->onSuccess[] = $this->editPaymentSubmitted;
        return $editPay;
         }     
    }
    
    public function editPaymentSubmitted($form) {
          if ($this->getUser()->isInRole('admin')) {
        $this->orderModel->updatePayment($form->values->paymentID, $form->values->name, $form->values->price);
          
        $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' was added sucessfully updates.');
            $message->insert(0, ' ' . $form->values->name);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert success');
            $this->redirect('this');
          }
    }
    
    /*
     * Render Payment
     */

    public function renderPayment() {

        $this->template->payments = $this->orderModel->loadPayment('');
    }

    /*
     * Render Orders
     *
     * @param null
     * @return void
     */

    public function renderOrders() {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
            $this->template->orders = $this->orderModel->loadOrders();
        }
    }

    /**********************************************************************
     *                      WAREHOUSE
     *********************************************************************/
  
    public function renderWarehouse() {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
           
            $this->template->products = $this->productModel->loadCatalog('');
            
        }
    }
    
    
    /*
     * Render default view of SmartPanel
     */
    
    public function handleRegenerateThumb() {
        foreach ($this->productModel->loadCatalog('') as $id => $product) {
            if ($product->PhotoAlbumID) {
                foreach ($this->productModel->loadPhotoAlbum($product->ProductID) as $id => $photo) {      
                    $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $product->PhotoAlbumID . '/';

                    $image = Image::fromFile($imgUrl . $photo->PhotoURL);
                    $image->resize(null, 300, Image::SHRINK_ONLY);

                    $imgUrl300 = $imgUrl . '300-' . $photo->PhotoURL;
                    $image->save($imgUrl300);

                    $image = Image::fromFile($imgUrl . $photo->PhotoURL);
                    $image->resize(null, 50, Image::SHRINK_ONLY);

                    $imgUrl50 = $imgUrl . '50-' . $photo->PhotoURL;
                    $image->save($imgUrl50);
             
                }
            }
        }
        
        $this->flashMessage('Thumbs sucessfully regenerated.', 'alert');
        $this->presenter->redirect("this");
    }

    public function renderDefault() {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
            $this->template->usr = $this->getUser()->getIdentity();
            $this->template->ord = $this->orderModel->countOrder();
            $this->template->orders = $this->orderModel->loadOrders();
            $this->template->anyVariable = 'any value';
        }
    }
    
    /*********************************************************************
     *                  STATS
     ********************************************************************/
    
    public function renderStats() {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
            
        }
    }

}

