<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
use Nette\Mail\Message;

use \PdfResponse;

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
    
    protected function createComponentMail() {
        $mailControl = new mailControl();
        $mailControl->setTranslator($this->translator);
        $mailControl->setProduct($this->productModel);
        $mailControl->setCategory($this->categoryModel);
        $mailControl->setBlog($this->blogModel);
        return $mailControl;
    }

    
     
    protected function createComponentGapiModule() {
       
       $gapi = new gapiModule();
       $gapi->setTranslator($this->translator);
       $gapi->setShop($this->shopModel);
       $gapi->setOrder($this->orderModel);
       $gapi->setProduct($this->productModel);
       $gapi->setCategory($this->categoryModel);
       $gapi->setGapi($this->gapisession);
       return $gapi;
   }
    
    public function handleSetStatus($orderid, $statusID, $name, $progress) {
        
            $this->orderModel->setStatus($orderid, $statusID);
            
            
            if($progress !== 10) {
                try {
                        $this->sendStatusMail($orderid, $name);
                    }
                catch (Exception $e) {
                       \Nette\Diagnostics\Debugger::log($e);
                }
            }
            
            $this->orderRow['Progress'] = $progress;

            $module = $this->createComponentModuleControl();
            $this->addComponent($module, 'module');
            $module->actionOrder($this->orderRow);
            
            
            $text = $this->translator->translate('Order status in now:');
            $message = Html::el('span', ' ' . $text . ' ' . $name);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
             if ($statusID !== 0) {
            $this->flashMessage($message, 'alert alert-success');
               }
            else {
                 $this->flashMessage($message, 'alert alert-danger');
            }
            
            if($this->isAjax()) {
                $this->invalidateControl('content');
                $this->invalidateControl('script');
                
            }
            else {  
                $this->redirect('this');
            }
            
    }
    
        
    protected function createComponentModuleControl() {
        $moduleControl = new moduleControl;
        $moduleControl->setTranslator($this->translator);
        $moduleControl->setProduct($this->productModel);
        $moduleControl->setCategory($this->categoryModel);
        $moduleControl->setShop($this->shopModel);
        $moduleControl->setOrder($this->orderModel);
        $moduleControl->setGapi($this->gapisession);
        
        return $moduleControl;
    
    }
    
    protected function createComponentAddProducerForm() {
        $prod = new Nette\Application\UI\Form;
        $prod->setTranslator($this->translator);
        $prod->addText('name', 'Brand name')
                ->setRequired()
                ->setAttribute('class', 'form-control');
        $prod->addTextArea('desc', 'Brand description')
                ->setAttribute('class', 'form-control');
        $prod->addSubmit('save', 'Add Brand')
                ->setAttribute('class', 'upl form-control btn btn-primary')
                ->setAttribute('data-loading-text', 'Adding...');
        $prod->onSuccess[] = $this->addProducerFormSubmitted;
        return $prod;
    }

    public function addProducerFormSubmitted($form) {
        
        $this->productModel->insertProducer($form->values->name, $form->values->desc);
        $this->redirect('this');
    }

        protected function createComponentPasswordForm() {
        $form = new Nette\Application\UI\Form;
        $form->setTranslator($this->translator);
        $form->addHidden('login', $this->getUser()->getIdentity()->id);
        $form->addPassword('newPassword', 'New password:', 30)
                ->addRule(Form::MIN_LENGTH, 'New password has to have %d letters.', 6);
        $form->addPassword('confirmPassword', 'New Password again:', 30)
                ->addRule(Form::FILLED, 'You have to add you password twice..')
                ->addRule(Form::EQUAL, 'Filled passwords has to match.', $form['newPassword']);
        $form->addSubmit('set', 'Change password');
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
            $text = $this->translator->translate('Your password was sucessfully changed.');
            $message = HTML::el('span', ' '.$text);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('SmartPanel:default');
        } catch (NS\AuthenticationException $e) {
            $form->addError('Entered password is invalid.');
        }
    }

    protected function createComponentNewUserForm() {
        $form = new Form();
        $form->setTranslator($this->translator);
        $form->addText('username', 'User name:', 10);
        $form->addText('name', 'Your name', 30);
        $form->addPassword('password', 'Password:', 30)
                ->addRule(Form::MIN_LENGTH, 'Nové heslo musí mí alespoň %d znaků', 6);
        $form->addPassword('confirmPassword', 'Heslo pro kontrolu', 30)
                ->addRule(Form::FILLED, 'Je nutné vyplnit!')
                ->addRule(Form::EQUAL, 'Zadaná hesla se musí shodovat', $form['password']);
        $form->addSubmit('add', 'Register');
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
            $this->flashMessage('You are registered. Now you can login.', 'alert alert-success');
            $this->redirect('Sign:in');
        } catch (NS\AuthenticationException $e) {
            $form->addError('Something is wrong. We apologise.');
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
            $text = $this->translator->translate('This order wasnt placed, yet. We are sorry.');
            $message = Html::el('span', ' '.$text);
            $e = Html::el('i')->class('icon-warning-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-warning');
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
    
    public function handleGenerateInvoice($orderid)
    {
        try { 
            
        $this->setLayout("pdfLayout");
        $template = $this->createTemplate()->setFile($this->context->parameters['appDir'] . "/templates/PDF/invoice.latte");
        $template->products = $this->orderModel->loadOrderProduct($orderid);
        $template->order = $this->orderModel->loadOrder($orderid);
        $template->address = $this->orderModel->loadOrderAddress($orderid);
        $template->productsInOrder = $this->productInOrder;
        $name = $this->shopModel->getShopInfo('Name');
        $template->companyName = $name;
        $template->shopLogo = $this->shopModel->getShopInfo('Logo');
        $template->companyPhone = $this->shopModel->getShopInfo('ContactPhone');
        $template->companyMail = $this->shopModel->getShopInfo('ContactMail');
        $template->companyAddress = $this->shopModel->getShopInfo('CompanyAddress');
        $prefix = $this->shopModel->getShopInfo('InvoicePrefix');
        $template->prefix = $prefix;
        $template->url = $this->context->parameters['wwwDir'];
       
        
        // Tip: In template to make a new page use <pagebreak>

        
        $pdf = new PdfResponse($template);
        $pdf->multiLanguage;
        
        }
        catch (Exception $e) {
                    \Nette\Diagnostics\Debugger::log($e);
            }
        // optional
        $pdf->documentTitle = 'invoice-' . $prefix . '' . $orderid; // creates filename 2012-06-30-my-super-title.pdf
        $pdf->pageFormat = "A4"; // wide format
        $pdf->getMPDF()->setFooter("|© ". $name . " |"); // footer
        
        //$pdf->save($this->context->parameters['wwwDir'] . "/generated/"); // as a filename $this->documentTitle will be used
        
        $pdf->setSaveMode(PdfResponse::DOWNLOAD);
        $this->sendResponse($pdf);
     
          
    }
    
    
    
    public function handleRemoveProduct($orderid, $product, $amount) {
        
        if ($this->productInOrder > 1) {
        $this->orderModel->removeOrderProducts($orderid, $product);
        $this->productModel->updateProduct($product, 'PiecesAvailable', $amount);
        
        $text = $this->translator->translate('Product was sucessfully removed.');
        $message = Html::el('span', ' '.$text);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
        }
        else {
            $message = Html::el('span', ' Cannot delete last product. Would you like to ');
            $cancel = Html::el('a', 'CANCEL ORDER')->href($this->link('setStatus!', $orderid, 0));
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $message->insert(2, $cancel);
            $this->flashMessage($message, 'alert alert-warning');
        }
        $this->redirect('this'); 
        
    
    }

    public function handleEditOrderShipping($orderid) {
        if (!$this->getUser()->isInRole('admin')) {
            
            if($this->isAjax()){
               $name = $_POST['id'];
               $content = $_POST['value'];
               $this->orderModel->updateOrder($orderid, $content);
               
           }
           if(!$this->isControlInvalid('shipping')){
               $this->payload->edit = $content;
               $this->sendPayload();
               $this->invalidateControl('shipping');
           }  
           
           else {
             $this->redirect('this');
            }      
        }
    }
    
    public function handleEditOrderPayment($orderid) {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('SmartPanel:');
        }
    }

    public function handleEditOrderPhone($userid, $name){
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->userModel->updateUser($userid, $name, $content);
            }
            if(!$this->isControlInvalid('orderPhone')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('orderPhone');
            }
            else {
             $this->redirect('this');
            }
        }
    }
    
     public function handleEditOrderName($userid, $phone){
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->userModel->updateUser($userid, $content, $phone);
            }
            if(!$this->isControlInvalid('orderName')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('orderName');
            }
            else {
             $this->redirect('this');
            }
        }
    }
    
    
    public function handleEditOrderStreet($orderid){
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->orderModel->updateOrderStreet($orderid,$content);
            }
            if(!$this->isControlInvalid('orderStreet')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('orderStreet');
            }
            else {
             $this->redirect('this');
            }
        }
    }
    
    public function handleEditOrderCity($orderid){
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->orderModel->updateOrderCity($orderid,$content);
            }
            if(!$this->isControlInvalid('orderCity')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('orderCity');
            }
            else {
             $this->redirect('this');
            }
        }
    }
    
    public function handleEditOrderZIP($orderid){
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->orderModel->updateOrderZIP($orderid,$content);
            }
            if(!$this->isControlInvalid('orderZIP')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('orderZIP');
            }
            else {
             $this->redirect('this');
            }
        }
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
        $editForm->setTranslator($this->translator);
        $editForm->addHidden('orderID', $this->orderRow['OrderID']);
        $editForm->addSelect('shipper', 'Shipping:', $shippers)
                ->setDefaultValue($this->orderRow['Shipping'])
                ->setAttribute('class', 'form-control');
        $editForm->addSelect('payment', 'Payment:', $payment)
            ->setDefaultValue($this->orderRow['Payment'])
                ->setAttribute('class', 'form-control');
        $editForm->addSubmit('edit', 'Edit info')
                    ->setAttribute('class', 'btn btn-success upl form-control')
                    ->setAttribute('data-loading-text', 'Editing...');
        $editForm->onSuccess[] = $this->editOrderInfoFormSubmitted;
        return $editForm;
    }

    public function editOrderInfoFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            
            $this->orderModel->updateOrder($form->values->orderID, $form->values->shipper, $form->values->payment);
            
            $text = $this->translator->translate('Order was sucessfully updated!');
            $message = Html::el('span', ' '.$text);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('this');
            
        }
        
    }
    
     protected function createComponentEditOrderAddressForm() {
        
                
        $editForm = new Nette\Application\UI\Form;
        $editForm->setTranslator($this->translator);
        $editForm->addHidden('orderID', $this->orderRow['OrderID']);
        $editForm->addText('street', 'Street:')
                ->setDefaultValue($this->orderAddress['Street'])
                ->setAttribute('class', 'form-control');
        $editForm->addText('zipcode', 'ZIP:')
                ->setDefaultValue($this->orderAddress['ZIPCode'])
                ->setAttribute('class', 'form-control');
        $editForm->addText('city', 'City:')
                ->setDefaultValue($this->orderAddress['City'])
                ->setAttribute('class', 'form-control');
        $editForm->addSubmit('edit', 'Edit address')
                    ->setAttribute('class', 'btn btn-success upl form-control')
                    ->setAttribute('data-loading-text', 'Editing...');
        $editForm->onSuccess[] = $this->editOrderAddressFormSubmitted;
        return $editForm;
    }

    public function editOrderAddressFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            
            $this->orderModel->updateOrderAddress($form->values->orderID, $form->values->street, $form->values->zipcode, $form->values->city);
            
            $text = $this->translator->translate('Order was sucessfully updated!');
            $message = Html::el('span', ' '.$text);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('this');
            
        }
        
    }
    
    
    
    protected function createComponentAddProductsForm() {
        
               
        $editProducts = new Nette\Application\UI\Form;
        $editProducts->setTranslator($this->translator);
        $editProducts->addHidden('orderID', $this->orderRow['OrderID']);
        $editProducts->addHidden('totalProducts', $this->orderRow['TotalProducts'] );
        
        foreach ($this->productModel->loadCatalogAdmin('') as $id => $product) {
            $products[$product->ProductID] = $product->ProductName;
           
          //  $editProducts->addHidden($product->ProductID, $product->FinalPrice);
         }
        $editProducts->addSelect('product', 'Select Product to add', $products)
                ->setRequired()
                ->setAttribute('class', 'form-control');
        
        $editProducts->addSubmit('add' , 'Add products')
                ->setAttribute('class', 'btn btn-success upl form-control')
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
            
             $text = $this->translator->translate('Product was sucessfully added!');
            $message = Html::el('span', ' '.$text);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('this');
         }
    }
    
    protected function createComponentAddNoteForm() {
        
               
        $editProducts = new Nette\Application\UI\Form;
        $editProducts->setTranslator($this->translator);
        $editProducts->addHidden('orderID', $this->orderRow['OrderID']);
        $editProducts->addHidden('userName', $this->getUser()->getId());
        $editProducts->addTextArea('note', 'Your Note:')
                ->setRequired()
                ->setAttribute('class', 'form-control');
        
        $editProducts->addSubmit('add' , 'Add note')
                ->setAttribute('class', 'btn-success upl form-control')
                    ->setAttribute('data-loading-text', 'Adding...');
        $editProducts->onSuccess[] = $this->addNoteFormSubmitted;
        return $editProducts;
                
        
    }
    
    public function addNoteFormSubmitted($form) {
         if ($this->getUser()->isInRole('admin')) {
          
            $this->orderModel->addNote($form->values->orderID, $form->values->userName, $form->values->note);
           
            try {
                    $this->sendNoteMail($form->values->orderID, $form->values->note);
                }
            catch (Exception $e) {
                   \Nette\Diagnostics\Debugger::log($e);
            }
             
            $text = $this->translator->translate('Note was sucessfully added!');
            $message = Html::el('span', ' '.$text);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('this');
         }
    }
    
    public function createComponentEditNote() {
        
    }

    public function renderOrderDetail($orderid) {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
            
            $order = $this->orderModel->loadOrder($orderid);
                      
            if($order->Read == 0 || $order->Read == NULL) { 
            $this->template->pros = $this->orderModel->loadOrderProduct($orderid);
            $this->orderModel->updateOrderRead($orderid, 1);
            }else {
            $this->template->pros = FALSE;
            }
            
            $this->template->products = $this->orderModel->loadOrderProduct($orderid);
            $this->template->order = $order;
            $this->template->statuses = $this->orderModel->loadStatus('');
            $this->template->address = $this->orderModel->loadOrderAddress($orderid);
            $this->template->notes = $this->orderModel->loadOrderNotes($orderid);
            $this->template->productsInOrder = $this->productInOrder;
            
            foreach ($this->orderModel->loadDelivery('') as $key => $value) {
         
            $shippers[$key] = $value->DeliveryName;
        };

            
            $this->template->delivery = $shippers;
            
        foreach ($this->orderModel->loadPayment('') as $key => $value) {
            $payment[$key] = $value->PaymentName;
        };
            $this->template->payment = $payment;
        
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
    
    public function handleDelName($delid) {
       if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
               $content = $_POST['value']; //odesílaná nová hodnota

               $this->orderModel->updateDeliveryName($delid, $content);

           }
           if(!$this->isControlInvalid('DelName'.$delid)){           
               $this->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->sendPayload();
               $this->invalidateControl('menu');       
               $this->invalidateControl('DelTitle'.$delid); //invalidace snipetu

           }
           else {
            $this->redirect('this');
           }
       }
    }
    
    public function handleDelDescription($delid) {
        if($this->getUser()->isInRole('admin')){
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
    }
    
    public function handleDelPrice($delid) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
               $content = $_POST['value']; //odesílaná nová hodnota

               $this->orderModel->updateDeliveryPrice($delid, $content);

           }
           if(!$this->isControlInvalid('DelPrice')){           
               $this->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->sendPayload();
               $this->invalidateControl('menu');       
               $this->invalidateControl('DelPrice'); //invalidace snipetu
           }
           else {
            $this->redirect('this');
           }
       }
    }
    
    public function handleDelStatus($delid) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){
                $content = $_POST['value']; //odesílaná nová hodnota

                $this->orderModel->updateDeliveryStatus($delid, $content);
            }
            
            if(!$this->isControlInvalid('DelStatus')){           
               $this->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->sendPayload();
               $this->invalidateControl('DelStatus'); //invalidace snipetu
           }
           else {
            $this->redirect('this');
           }
        }
    }

        public function handleDelHigher($delid) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){
                $content = $_POST['value']; //odesílaná nová hodnota

                $this->orderModel->updateHigherDelivery($delid, $content);
            }
            
            if(!$this->isControlInvalid('DelHigher')){           
               $this->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->sendPayload();
               $this->invalidateControl('DelHigher'); //invalidace snipetu
           }
           else {
            $this->redirect('this');
           }
        }
    }

    public function handleDelFF($delid) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax())
           {            
               $content = $_POST['value']; //odesílaná nová hodnota

               $this->orderModel->updateDeliveryFreefrom($delid, $content);

           }
           if(!$this->isControlInvalid('DelFF'))
           {           
               $this->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->sendPayload();       
               $this->invalidateControl('DelFF'); //invalidace snipetu

           }
           else {
            $this->redirect('this');
           }
       }
    }

    public function handleRemoveShipping($shipid) {
       if ($this->getUser()->isInRole('admin')) {
         
            //$this->orderModel->deleteDelivery($shipid);
           $this->orderModel->updateDeliveryStatus($shipid, 3);
           
           $text = $this->translator->translate('Shipping was archived.');
            $message = Html::el('span', ' '.$text);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, ' ');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
            
            if($this->isAjax()) {
                $this->invalidateControl('content');
                $this->invalidateControl('script');

                
            }
            else{
                $this->presenter->redirect("this");
            }
     }
    }
    
    protected function createComponentAddShippingForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);
            
            $addForm->addGroup('Create new shipping:');
            $addForm->addText('newShip', 'Shipping name:')
                    ->setRequired()
                    ->setAttribute('class', 'form-control');
            $addForm->addText('priceShip', 'Shipping price:')
                    ->setRequired()
                    ->addRule(FORM::FLOAT, 'This has to be a number')
                    ->setAttribute('class', 'form-control');
            $addForm->addText('descShip', 'Description:')
                    ->setAttribute('class', 'form-control');
            $addForm->addText('freeShip', 'Free from:')
                    ->setAttribute('class', 'form-control');
                    //->setDefaultValue(0)
                    //->addRule(FORM::FLOAT, 'This has to be a number.');
            $addForm->addSubmit('add', 'Add Shipping')
                    ->setAttribute('class', 'upl-add btn btn-success form-control')
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
                                              $form->values->freeShip,
                                                1);
            
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $text = $this->translator->translate('was added sucessfully to your shipping method.');
            $message = HTML::el('span', ' '.$text);
            $message->insert(0, ' ' . $form->values->newShip);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('this');
        }
    }

    protected function createComponentEditShippingForm($deliveryID){
         if ($this->getUser()->isInRole('admin')) { 
        $editShip = new Nette\Application\UI\Form;
        
        $editShip->setTranslator($this->translator);
        $editShip->addText('name', 'Name:')
                ->setDefaultValue($deliveryID['DeliveryName'])
                ->setRequired()
                ->setAttribute('class', 'form-control');
        $editShip->addText('desc', 'Description:')
                ->setDefaultValue($deliveryID['DeliveryDescription'])
                ->setAttribute('class', 'form-control');
        $editShip->addText('price', 'Price:')
                ->setDefaultValue($deliveryID['DeliveryPrice'])
                ->setAttribute('class', 'form-control');
        $editShip->addText('free', 'Free from:')
                ->setDefaultValue($deliveryID['FreeFromPrice'])
                ->setAttribute('class', 'form-control');
        $editShip->addHidden('deliveryID', $deliveryID['DeliveryID'] );
        $editShip->addSubmit('edit', 'Edit shipping')
                ->setAttribute('class', 'btn btn-success upl form-control')
                        ->setAttribute('data-loading-text', 'Saving...');
        $editShip->onSuccess[] = $this->editShippingSubmitted;
        return $editShip;
         }     
    }
    
    public function editShippingSubmitted($form) {
          if ($this->getUser()->isInRole('admin')) {
        $this->orderModel->updateDelivery($form->values->deliveryID, $form->values->name, $form->values->desc, $form->values->price, $form->values->free);
          
        $ico = HTML::el('i')->class('icon-ok-sign left');
        $text = $this->translator->translate('was sucessfully updated.');
            $message = HTML::el('span', ' '.$text);
            $message->insert(0, ' ' . $form->values->name);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('this');
          }
    }

    public function renderShipping() {
        
        $this->template->delivery = $this->orderModel->loadDelivery('');
        $status = array();
        
        foreach ($this->orderModel->loadStatuses('') as $key => $value) {
                $status[$key] = $value->StatusName;
            };
            
        
        $this->template->status = $status;
        
        foreach ($this->orderModel->loadDeliveryList('') as $key => $value){
            $list[$key] = $value->DeliveryName;
        }
        if(isset($list)){
        $this->template->deliveryList = $list;
        }
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
            
            $row = $this->orderModel->deletePayment($id);
            $text = $this->translator->translate('was removed.');
            $message = Html::el('span', ' '.$text);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, ' '. $row->PaymentName);
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
            
            if($this->isAjax()) {
                $this->invalidateControl('paymentName-'.$id);
                $this->invalidateControl('payment');
                $this->invalidateControl('content');
                
            }
            else {
            $this->redirect("this");
            }
       
        }
    }
    
    public function handleRemoveProd($prodID) {
       if ($this->getUser()->isInRole('admin')) {
            
            $row = $this->productModel->deleteProducer($prodID);
            $text = $this->translator->translate('was removed.');
            $message = Html::el('span', ' ' . $text . '.');
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, ' '. $row['ProducerName']);
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
            
            if($this->isAjax()) {
                $this->invalidateControl('prod');
                $this->invalidateControl('content');
                $this->invalidateControl('script');
            }
            else {
            $this->redirect("this");
            }
       
        }
    }
    
    protected function createComponentAddPaymentForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);

            $addForm->addGroup('Create new payment:');
            $addForm->addText('newPay', 'Payment name:')
                    ->setRequired()
                    ->setAttribute('class', 'form-control');
            $addForm->addText('pricePay', 'Payment price:')
                    ->setRequired()
                    ->setAttribute('class', 'form-control')
                    ->addRule(FORM::FLOAT, 'This has to be a number');
                         //->setDefaultValue(0)
                    //->addRule(FORM::FLOAT, 'This has to be a number.');
            $addForm->addSubmit('add', 'Add Payment')
                    ->setAttribute('class', 'upl-add btn btn-success form-control')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addForm->onSuccess[] = $this->addPaymentFormSubmitted;

            return $addForm;
        }
    }

    public function addPaymentFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

            $this->orderModel->insertPayment($form->values->newPay,
                                              $form->values->pricePay,
                                              1
                                              );
            
            $text = $this->translator->translate('was added sucessfully to your payment method.');
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' '.$text);
            $message->insert(0, ' ' . $form->values->newPay);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('this');
        }
    }

    protected function createComponentEditPaymentForm($paymentID){
         if ($this->getUser()->isInRole('admin')) { 
        $editPay = new Nette\Application\UI\Form;
        
        $editPay->setTranslator($this->translator);
        $editPay->addText('name', 'Name:')
                ->setDefaultValue($paymentID['PaymentName'])
                ->setRequired()
                ->setAttribute('class', 'form-control');
        $editPay->addText('price', 'Price:')
                ->setDefaultValue($paymentID['PaymentPrice'])
                ->setAttribute('class', 'form-control');
        $editPay->addHidden('paymentID', $paymentID['PaymentID'] );
        $editPay->addSubmit('edit', 'Edit payment')
                ->setAttribute('class', 'btn btn-success upl form-control')
                        ->setAttribute('data-loading-text', 'Saving...');
        $editPay->onSuccess[] = $this->editPaymentSubmitted;
        return $editPay;
         }     
    }
    
    public function editPaymentSubmitted($form) {
          if ($this->getUser()->isInRole('admin')) {
        $this->orderModel->updatePayment($form->values->paymentID, $form->values->name, $form->values->price);
          
        $ico = HTML::el('i')->class('icon-ok-sign left');
       $text = $this->translator->translate('was sucessfully updated.');
            $message = HTML::el('span', ' ' . $text);
            $message->insert(0, ' ' . $form->values->name);
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-success');
            $this->redirect('this');
          }
    }

    public function handleEditPaymentName($paymentID, $price) {
         if ($this->getUser()->isInRole('admin')) {
         
            
               
            if($this->isAjax()){
               //$name = $_POST['id'];
               $content = $_POST['value'];
               $this->orderModel->updatePaymentName($paymentID, $content);
               
               $text = $this->translator->translate('was sucessfully updated.');
               $ico = HTML::el('i')->class('icon-ok-sign left');
               $message = HTML::el('span', ' '.$text);
               $message->insert(0, ' ' . $content);
               $message->insert(0, $ico);
               $this->flashMessage($message, 'alert alert-success');
               
           }
           if(!$this->isControlInvalid('paymentName-'.$paymentID)){
               $this->payload->edit = $content;
               $this->sendPayload();
               $this->invalidateControl('paymentName-'.$paymentID);
               //$this->invalidateControl('flashMessages');

           }
            else {
                 $this->redirect('this');

            }
          }
    }
    
    public function handleEditPaymentPrice($paymentID, $name) {
         if ($this->getUser()->isInRole('admin')) {
         
           
               
            if($this->isAjax()){
               //$name = $_POST['id'];
               $content = $_POST['value'];
               
               $this->orderModel->updatePaymentPrice($paymentID, $content);
               $text = $this->translator->translate('was sucessfully updated.');
                $ico = HTML::el('i')->class('icon-ok-sign left');
               $message = HTML::el('span', ' '.$text);
               $message->insert(0, ' ' . $name);
               $message->insert(0, $ico);
               $this->flashMessage($message, 'alert alert-success');
               
           }
           if(!$this->isControlInvalid('paymentPrice-'.$paymentID)){
               $this->payload->edit = $content;
               $this->sendPayload();
               $this->invalidateControl('paymentPrice-'.$paymentID);
              // $this->invalidateControl('flashMessages');

           }
            else {
                 $this->redirect('this');

            }
          }
    }
    
    public function handleEditPaymentStatus($payid) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){
                $content = $_POST['value']; //odesílaná nová hodnota
                $this->orderModel->updatePaymentStatus($payid, $content);
            }
            
            if(!$this->isControlInvalid('PayStatus')){        
                $this->payload->edit = $content; //zaslání nové hodnoty do šablony
                $this->sendPayload();     
                $this->invalidateControl('PayStatus'); //invalidace snipetu
           }
           else {
            $this->redirect('this');
           }
        }
    }
    
    public function handleEditProducerName($prodID) {
         if ($this->getUser()->isInRole('admin')) {
    
            if($this->isAjax()){
               //$name = $_POST['id'];
               $content = $_POST['value'];
               $this->productModel->updateProducer($prodID,'ProducerName', $content);
               $text = $this->translator->translate('was sucessfully updated.');
               $ico = HTML::el('i')->class('icon-ok-sign left');
               $message = HTML::el('span', ' '.$text);
               $message->insert(0, ' ' . $content);
               $message->insert(0, $ico);
               $this->flashMessage($message, 'alert alert-success');
               
           }
           if(!$this->isControlInvalid('prodName-'.$prodID)){
               $this->payload->edit = $content;
               $this->sendPayload();
               $this->invalidateControl('prodName-'.$prodID);
   
           }
            else {
                 $this->redirect('this');
            }
          }
    }
    
    public function handleEditProducerDescription($prodID) {
         if ($this->getUser()->isInRole('admin')) {
    
            if($this->isAjax()){
               //$name = $_POST['id'];
               $content = $_POST['value'];
               $this->productModel->updateProducer($prodID,'ProducerDescription', $content);
               $text = $this->translator->translate('was sucessfully updated.');
               $ico = HTML::el('i')->class('icon-ok-sign left');
               $message = HTML::el('span', ' '.$text);
               $message->insert(0, ' ' . $content);
               $message->insert(0, $ico);
               $this->flashMessage($message, 'alert alert-success');
               
           }
           if(!$this->isControlInvalid('prodDesc-'.$prodID)){
               $this->payload->edit = $content;
               $this->sendPayload();
               $this->invalidateControl('prodDesc-'.$prodID);
   
           }
            else {
                 $this->redirect('this');
            }
          }
    }
    
    
    /*
     * Render Payment
     */

    public function renderPayment() {

        $this->template->payments = $this->orderModel->loadPayment('');
        
        $status = array();
        
        foreach ($this->orderModel->loadStatuses('') as $key => $value) {
                $status[$key] = $value->StatusName;
            };
            
        $this->template->status = $status;
    }
    
    public function renderProducers() {
        
        $this->template->prods = $this->productModel->loadProducers();
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
  
  
    protected function createComponentGrid($name) {
    $grid = new Grido\Grid($this, $name);
    $grid->setModel($this->productModel->loadCatalog(''));
    $grid->setTranslator($this->translator);
    $grid->setPrimaryKey('ProducerID');
    $grid->addColumn('ProducerName', 'Name', Column::TYPE_TEXT);
    $grid->addColumn('PiecesAvailable', 'PCS', Column::TYPE_TEXT);
    }
    
    
    
    public function renderWarehouse() {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
           
            $this->template->products = $this->productModel->loadCatalog('');
            $this->template->categories = $this->categoryModel->loadCategoryListAdmin();
            
        }
    }
    
    
    /*
     * Render default view of SmartPanel
     */
    
    public function handleRegenerateThumb() {
        foreach ($this->productModel->loadPhotoAlbum('') as $id => $product) {
            $sizes = $this->shopModel->loadPhotoSize();
            
            if ($product->PhotoAlbumID) {
                foreach ($this->productModel->loadPhotoAlbum($product->ProductID) as $id => $photo) {      
                    $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $product->PhotoAlbumID . '/';

                     
                    $image = Image::fromFile($imgUrl . $photo->PhotoURL);
                    $image->resize(null, $sizes['Large']->Value, Image::SHRINK_ONLY);

                    $imgUrl300 = $imgUrl . 'l-' . $photo->PhotoURL;
                    $image->save($imgUrl300);
                    
                    $image = Image::fromFile($imgUrl . $photo->PhotoURL);
                    $image->resize(null, $sizes['Medium']->Value, Image::SHRINK_ONLY);

                    $imgUrl150 = $imgUrl . 'm-' . $photo->PhotoURL;
                    $image->save($imgUrl150);

                    $image = Image::fromFile($imgUrl . $photo->PhotoURL);
                    $image->resize(null, $sizes['Small']->Value, Image::SHRINK_ONLY);

                    $imgUrl50 = $imgUrl . 's-' . $photo->PhotoURL;
                    $image->save($imgUrl50);
             
                }
            }
        }
        $text = $this->translator->translate('Thumbs sucessfully regenerated.');
        $this->flashMessage($text, 'alert alert-success');
        $this->presenter->redirect("this");
    }

    public function handleSetShopInfo($id) {
        if ($this->getUser()->isInRole('admin')) {   
            if($this->isAjax()){
               $name = $_POST['id'];
               $content = $_POST['value'];
               $this->shopModel->setShopInfoByID($id, $content);
                             
           }
           if(!$this->isControlInvalid('shopinfo')){
               $this->payload->edit = $content;
               $this->sendPayload();
               $this->invalidateControl('shopinfo');
               //$this->invalidateControl('flashMessages');

           }
            else {
                 $this->redirect('this');

            }
          }
    }
    
    protected function createComponentAddLogoForm() {
        
        $addLogo = new Nette\Application\UI\Form;
        $addLogo->setTranslator($this->translator);
        $addLogo->addUpload('logo', 'Select your logo')
                 ->addRule(FORM::IMAGE, 'Supported files are JPG, PNG a GIF')
                 ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024)
                ->setAttribute('class', 'form-control');
        $addLogo->addSubmit('upload', 'Upload')
                ->setAttribute('class', 'form-control');
        $addLogo->onSuccess[] = $this->addLogoFormSubmitted;
        return $addLogo;
    }
    
    public function addLogoFormSubmitted($form){
        if ($this->getUser()->isInRole('admin')) {
            
            if($form->values->logo->isOK()){
                
                $this->shopModel->setShopInfo('Logo', $form->values->logo->name);
                
                $logoURL = $this->context->parameters['wwwDir']  . '/images/logo/' . $form->values->logo->name;
                $form->values->logo->move($logoURL);
                
                $logo = Image::fromFile($logoURL);
                $logo->resize(null, 300, Image::SHRINK_ONLY);
                
                $logoUrl = $this->context->parameters['wwwDir'] . '/images/logo/300-' . $form->values->logo->name;
                $logo->save($logoUrl);  
                
                $logo->resize(null, 90, Image::SHRINK_ONLY);
                
                $logoUrl = $this->context->parameters['wwwDir'] . '/images/logo/90-' . $form->values->logo->name;
                $logo->save($logoUrl); 
            }
            
            $this->redirect('this');
        }
    }
    
    
        public function handleCRON() {
            try {
                $this->handleSitemap();
                $this->handleXMLFeed();
                $this->handleDownloadXML();
            }
            catch (Exception $e) {
                \Nette\Diagnostics\Debugger::log($e);
            }
        }

        public function handleSitemap() {
        try {
            $template = $this->createTemplate();
            $template->setFile($this->context->parameters['appDir'] . '/templates/components/sitemap.latte');
            $template->registerFilter(new Nette\Latte\Engine);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');

            $template->products = $this->productModel->loadCatalog("");
            $template->category = $this->categoryModel->loadCategory("");


            $text = $this->translator->translate('Sitemap sucessfully generated.');
            $template->save($this->context->parameters['wwwDir'] . '/sitemap.xml');
            $this->flashMessage($text, 'alert alert-success');
        }
        catch(Exception $e) {
            
                   \Nette\Diagnostics\Debugger::log($e);
                   $text = $this->translator->translate('Sitemap crashed. I´m so sorry.');
                   $this->flashMessage($text, 'alert alert-danger');

        }
        $this->redirect('this');
   
    }
    
    public function renderDefault() {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
            $this->usertracking->date = date("Y-m-d H:i:s");
            
            $this->template->usr = $this->getUser()->getIdentity();
            $this->template->orders = $this->orderModel->loadLatestOrders();
            $this->template->settings = $this->shopModel->getShopInfoPublic();
            $this->template->productNumber = $this->productModel->countProducts();
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
    
    
     public function renderModules() {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
                      
        }
    }

    
    protected function sendStatusMail($orderid, $name) {
        
            $row = $this->orderModel->loadOrder($orderid);
             $adminMail = $this->shopModel->getShopInfo('OrderMail');
             $shopName = $this->shopModel->getShopInfo('Name');

            $template = new Nette\Templating\FileTemplate($this->context->parameters['appDir'] . '/templates/Email/yourOrderStatus.latte');
            $template->registerFilter(new Nette\Latte\Engine);
            $template->setTranslator($this->translator);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');
            $template->orderId = $orderid;
            $template->mailOrder = $row->UsersID;
            $template->adminMail = $adminMail;
            $template->shopName = $shopName;
            $template->status = $name;
            $hash = md5($row->UsersID . $row->OrderID . $row->DateCreated);
            $args = array($row->OrderID, $hash);
            $template->link = $this->presenter->link('//Order:orderDone', $args);
            
            $sub = $this->translator->translate('Your order has new status');
            $mailIT = new mailControl();
            $mailIT->sendSuperMail($row->UsersID, $sub, $template, $adminMail);
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
            $template->note = $note;
            
            $sub = $this->translator->translate('Message about your order');
            $mailIT = new mailControl();
            $mailIT->sendSuperMail($row->UsersID, $sub, $template, $adminMail);
    }
}

