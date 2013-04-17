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

    public function actionSetStatus($orderID, $statusID) {
        $this->orderModel->setStatus($orderID, $statusID);
        $this->redirect('orderDetail', $orderID);
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

    /*
     * renderOrderDone()
     * rendering Thank you for your order page
     * 
     * @param int
     * @return void
     */

    public function renderOrderDetail($orderNo) {
        if (!$this->getUser()->isInRole('admin')) {
            $this->redirect('Sign:in');
        } else {
            $this->template->products = $this->orderModel->loadOrderProduct($orderNo);
            $this->template->order = $this->orderModel->loadOrder($orderNo);
            $this->template->statuses = $this->orderModel->loadStatus('');
            $this->template->address = $this->orderModel->loadOrderAddress($orderNo);
        }
    }

    /*
     * Render Payment
     */

    public function renderPayment() {

        $this->template->payments = $this->orderModel->loadPayment('');
    }

    /**************************************************************************/
    /*        Render Shipping method and settings           */
    /**********************************************************************/

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

    
    public function renderShipping() {
        
        $this->template->delivery = $this->orderModel->loadDelivery('');
        
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

}

