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
    private $blogModel;


    protected $translator;

    protected function startup() {
        parent::startup();

        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;
        $this->shopModel = $this->context->shopModel;
        $this->blogModel = $this->context->blogModel;
        /* Kontrola přihlášení
         * 
         * if (!$this->getUser()->isInRole('admin')) {
          $this->redirect('Sign:in');
          } */
    }
    
    public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }

    
    protected function createComponentAddVideoForm() {
        $videoForm = new Nette\Application\UI\Form;
        $videoForm->setTranslator($this->translator);
        $videoForm->addTextArea('video', 'Video embed code')
                ->setAttribute('class', 'form-control');
        $videoForm->addSubmit('add','Add video')
                ->setAttribute('class', 'btn btn-success form-control');
        $videoForm->onSuccess[] = $this->addVideoFormSubmitted;
        return $videoForm;   
    }
    
    public function addVideoFormSubmitted($form){
        if($this->getUser()->isInRole('admin')){
            $this->shopModel->setShopInfo('homepageVideo', $form->values->video);
            $this->redirect('this');
        }
    }
    
    

    
    public function actionDefault() {
         if($this->getUser()->isInRole('admin')){
             $this['addVideoForm'];
         }
    }

        public function renderDefault() {

        $this->template->products = $this->productModel->loadMainPage();
       
        $this->template->slider = 1;
        
        $this->template->sliderone = $this->shopModel->loadBannerByType('slider1');
        $this->template->slidertwo = $this->shopModel->loadBannerByType('slider2');
        $this->template->sliderthree = $this->shopModel->loadBannerByType('slider3');
        
        $this->template->bannerone = $this->shopModel->loadBannerByType('banner1');
        
        $this->template->bannertwo = $this->shopModel->loadBannerByType('banner2');
        $this->template->bannerthree = $this->shopModel->loadBannerByType('banner3');
        $this->template->bannerfour = $this->shopModel->loadBannerByType('banner4');
        
        $home = $this->shopModel->getShopInfo('HomepageLayout');
        $this->template->home = $home.'.latte';
        $this->template->category = $this->categoryModel->loadCategory("");
        $this->template->video = $this->shopModel->getShopInfo('homepageVideo');
        $this->template->anyVariable = 'any value';
        
    }
    
    
    public function renderSearch($query){
        $this->template->query = $query;
        if($query != NULL || $query != '') {
        $this->template->products = $this->productModel->search($query);
        $this->template->categories = $this->categoryModel->search($query);
        $this->template->posts = $this->blogModel->search($query);
        }
    }


    


    protected function createComponentContactForm() {
            $contactForm = new Nette\Application\UI\Form;
            $contactForm->setTranslator($this->translator);
           
           
            $contactForm->addText('email', 'Email:', 40, 100)
                ->setEmptyValue('@')
                ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                    ->setAttribute('class', 'form-control');
            $contactForm->addTextArea('note', 'What would you like to know:')
                    ->setRequired()
                    ->setAttribute('class', 'form-control');
            $contactForm->addSubmit('send', 'Ask')
                    ->setAttribute('class', 'ajax btn btn-success btn-lg form-control')
                    ->setAttribute('data-loading-text', 'Asking...');
            $contactForm->onSuccess[] = $this->contactFormSubmitted;

            return $contactForm;
    }
    
    public function contactFormSubmitted(Form $form){
        $email = $form->values->email;
        $note = $form->values->note;
        
        if($this->isAjax()){
            $text = $this->translator->translate('Great! We have received your question.');
            $this->flashMessage($text, 'alert alert-info');
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

            $adminMail = $this->shopModel->getShopInfo('OrderMail');
            $shopName = $this->shopModel->getShopInfo('Name');
            $template = new Nette\Templating\FileTemplate($this->context->parameters['appDir'] . '/templates/Email/contactMail.latte');
            $template->registerFilter(new Nette\Latte\Engine);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');
            $template->email = $email;
            $template->note = $note;
            $template->adminMail = $adminMail;
            $template->shopName = $shopName;
            
            $orderMail = $this->shopModel->getShopInfo('ContactMail');
            $shopName = $this->shopModel->getShopInfo('Name');
        
            $mailIT = new mailControl();
            $mailIT->sendSuperMail($orderMail, $shopName . ': New question', $template, $email);
    }
}
