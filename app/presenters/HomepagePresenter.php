<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
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
                    ->setAttribute('class', 'ajax btn btn-primary')
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
            $this->invalidateControl('content'); 
            
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

    public function renderContact() {
        $this->template->anyVariable = 'any value';
    }

    
    protected function sendNoteMail($email, $note) {
        
            //$row = $this->orderModel->loadOrder($orderid);
            $template = new Nette\Templating\FileTemplate($this->context->parameters['appDir'] . '/templates/Email/contactMail.latte');
            $template->registerFilter(new Nette\Latte\Engine);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');
            $template->email = $email;
            //$template->mailOrder = $row->UsersID;
            $template->note = $note;
            
            $mailIT = new mailControl();
            $mailIT->sendSuperMail('luk.danek@gmail.com', 'Nový dotaz', $template);
    }
}
