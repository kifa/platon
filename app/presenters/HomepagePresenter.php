<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image,
    Nette\Templates\FileTemplate;
use Nette\Mail\Message;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    private $productModel;
    private $categoryModel;
    private $shopModel;
    
    protected $translator;

    protected function startup() {
        parent::startup();

        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;
        $this->shopModel = $this->context->shopModel;
        /* Kontrola přihlášení
         * 
         * if (!$this->getUser()->isInRole('admin')) {
          $this->redirect('Sign:in');
          } */
    }
    
    public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }

    
  
    
    public function renderDefault() {

         if ($this->getUser()->isInRole('admin')) {
            // load all products
        $this->template->products = $this->productModel->loadCatalogAdmin("");
        } else {
            // load published products
        $this->template->products = $this->productModel->loadCatalog("");
        }
       
        $layout = $this->shopModel->getShopInfo('HomepageLayout');
        
        $this->template->setFile( $this->context->parameters['appDir'] . '/templates/Homepage/'  . $layout . '.latte'); 
       
        $this->template->slider = 1;
        $this->template->category = $this->categoryModel->loadCategory("");
        $this->template->anyVariable = 'any value';
        
    }
    
    
    protected function createComponentContactForm() {
            $contactForm = new Nette\Application\UI\Form;
            $contactForm->setTranslator($this->translator);
           
           
            $contactForm->addText('email', 'Email:', 40, 100)
                ->setEmptyValue('@')
                ->addRule(Form::EMAIL, 'Would you fill your email, please?');
            $contactForm->addTextArea('note', 'What would you like to know:')
                    ->setRequired();
            $contactForm->addSubmit('send', 'Ask')
                    ->setAttribute('class', 'ajax span4 btn btn-primary btn-large')
                    ->setAttribute('data-loading-text', 'Asking...');
            $contactForm->onSuccess[] = $this->contactFormSubmitted;

            return $contactForm;
    }
    
    public function contactFormSubmitted(Form $form){
        $email = $form->values->email;
        $note = $form->values->note;
        
        if($this->isAjax()){
            
            $this->flashMessage('Great! We have received your question.', 'alert alert-info');
            $form->setValues(array(), TRUE);
            $this->invalidateControl('contact');  
            $this->invalidateControl('form'); 
            $this->invalidateControl('script');
            
        }
        else {
            $this->redirect('this');
        }
        
        try {
            $this->sendNoteMail($email, $note);
        }
        catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
        }
    }
   

    public function actionContact(){
    
    }
    
    public function handleEditContactTextHeading($staticID) {
        if($this->user->isInRole('admin')) {
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->shopModel->updateStaticText($staticID, 'StaticTextName', $content);
            }
            if(!$this->isControlInvalid('contactTextHeading')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('contactTextHeading');
            }
            else {
             $this->redirect('this');
            }
        }
           
    }
    
    public function handleEditContactText($staticID) {
        if($this->user->isInRole('admin')) {
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->shopModel->updateStaticText($staticID, 'StaticTextContent', $content);
            }
            if(!$this->isControlInvalid('contactText')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('contactText');
            }
            else {
             $this->redirect('this');
            }
        }
           
    }

    public function renderContact() {
        $this->template->anyVariable = 'any value';
        $this->template->contactText = $this->shopModel->loadStaticText(3);
        $this->template->companyName = $this->shopModel->getShopInfo('Name');
        $this->template->companyPhone = $this->shopModel->getShopInfo('ContactPhone');
        $this->template->companyMail = $this->shopModel->getShopInfo('ContactMail');
        $this->template->companyAddress = $this->shopModel->getShopInfo('CompanyAddress');
    }
    
    public function renderPhotos() {
        $this->template->Albums = $this->productModel->loadPhotoAlbum('');
    }
    
    public function renderPhoto($id) {
        $this->template->product = $this->productModel->loadProduct($id);
        $this->template->Albums = $this->productModel->loadPhotoAlbum($id);
    }

    
    protected function sendNoteMail($email, $note) {
        
            //$row = $this->orderModel->loadOrder($orderid);
            $template = new Nette\Templating\FileTemplate($this->context->parameters['appDir'] . '/templates/Email/contactMail.latte');
            $template->registerFilter(new Nette\Latte\Engine);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');
            $template->email = $email;
            $template->note = $note;
            
            $orderMail = $this->shopModel->getShopInfo('OrderMail');
            $shopName = $this->shopModel->getShopInfo('Name');
        
            $mailIT = new mailControl();
            $mailIT->sendSuperMail($orderMail, $shopName . ': Nový dotaz', $template, $email);
    }
}
