<?php

use Nette\Application\UI,
    Nette\Forms\Form,
    Nette\Utils\Html;
use Nette\Diagnostics;
use Nette\Http\Request;

/*
 * Component to render modal window
 */

class producerControl extends BaseControl {
    /* @var Gettext\translator */

    private $productModel;
    protected $translator;
    private $row;

    public function __construct(\ProductModel $productModel, \NetteTranslator\Gettext $translator) {
        parent::__construct();

        $this->productModel = $productModel;
        $this->translator = $translator;
    }

    /*
     * Create control template for localization
     * 
     * @param NULL
     * @return Translator template
     */

    public function createTemplate($class = NULL) {
        $template = parent::createTemplate($class);
        $template->setTranslator($this->translator);

        return $template;
    }

    
    public function handleEditProducerName($prodID) {
         if ($this->presenter->getUser()->isInRole('admin')) {
    
            if($this->presenter->isAjax()){
               //$name = $_POST['id'];
               $content = $this->presenter->context->httpRequest->getPost('value');
               $this->productModel->updateProducer($prodID,'ProducerName', $content);
               $text = $this->translator->translate('was sucessfully updated.');
               $ico = HTML::el('i')->class('icon-ok-sign left');
               $message = HTML::el('span', ' '.$text);
               $message->insert(0, ' ' . $content);
               $message->insert(0, $ico);
               $this->presenter->flashMessage($message, 'alert alert-success');
               
           }
           if(!$this->isControlInvalid()){
               $this->presenter->payload->edit = $content;
               $this->presenter->sendPayload();
               $this->invalidateControl('producer');
   
           }
            else {
                 $this->presenter->redirect('this');
            }
          }
    }
    
    public function handleEditProducerDescription($prodID) {
         if ($this->presenter->getUser()->isInRole('admin')) {
    
            if($this->presenter->isAjax()){
               //$name = $_POST['id'];
               $content = $this->presenter->context->httpRequest->getPost('value');
               $this->productModel->updateProducer($prodID,'ProducerDescription', $content);
               $text = $this->translator->translate('was sucessfully updated.');
               $ico = HTML::el('i')->class('icon-ok-sign left');
               $message = HTML::el('span', ' '.$text);
               $message->insert(0, ' ' . $content);
               $message->insert(0, $ico);
               $this->flashMessage($message, 'alert alert-success');
               
           }
           if(!$this->isControlInvalid()){
               $this->presenter->payload->edit = $content;
               $this->presenter->sendPayload();
               $this->presenter->invalidateControl('producer');
   
           }
            else {
                 $this->presenter->redirect('this');
            }
          }
    }
    
    public function handleRemoveProd($prodID) {
       if ($this->presenter->getUser()->isInRole('admin')) {

                
            $row = $this->productModel->deleteProducer($prodID);
            
            
            $text = $this->translator->translate('was removed.');
            $message = Html::el('span', ' ' . $text . '.');
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, ' '. $row['ProducerName']);
            $message->insert(0, $e);
            $this->presenter->flashMessage($message, 'alert alert-success');
            
            if($this->presenter->isAjax()) {
                $this->invalidateControl('producer');
                $this->invalidateControl('script');
            }
            else {
            $this->presenter->redirect("this");
            }
       
        }
    }
    
    
    
    protected function createComponentAddProducerForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
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
    }

    public function addProducerFormSubmitted($form) {
     if ($this->presenter->getUser()->isInRole('admin')) {   
        $this->productModel->insertProducer($form->values->name, $form->values->desc);
        $this->redirect('this');
     }
    }
  
    public function render() {

        $this->template->setFile(__DIR__ . '/templates/producerList.latte');
        $this->template->prods = $this->productModel->loadProducers();
        $this->template->render();
    }

    public function renderJs($producer) {
        $this->template->setFile(__DIR__ . '/templates/producerJs.latte');
        $this->template->prod = $producer;
        $this->template->render();
    }
    
    public function renderForm() {
        $this->template->setFile(__DIR__ . '/templates/producerForm.latte');
        $this->template->render();
    }

}
