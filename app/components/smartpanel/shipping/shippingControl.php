<?php

use Nette\Application\UI,
    Nette\Forms\Form,
    Nette\Utils\Html;
use Nette\Http\Request;

/*
 * Component to render modal window
 */

class shippingControl extends BaseControl {
    /* @var Gettext\translator */

    protected $translator;
    protected $orderModel;
    private $row;

    public function __construct(\OrderModel $orderModel, \NetteTranslator\Gettext $translator) {
        parent::__construct();

        $this->orderModel = $orderModel;
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

    public function handleDelName($delid) {
       if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax()){            
               $content = $this->presenter->context->httpRequest->getPost('value');

               $this->orderModel->updateDeliveryName($delid, $content);

           }
           if(!$this->isControlInvalid()){           
               $this->presenter->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->presenter->sendPayload();
               $this->presenter->invalidateControl('shipping');       
           }
           else {
            $this->presenter->redirect('this');
           }
       }
    }
    
    public function handleDelDescription($delid) {
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax())
           {            
               $content = $this->presenter->context->httpRequest->getPost('value');

               $this->orderModel->updateDeliveryDescription($delid, $content);

           }
           if(!$this->isControlInvalid())
           {           
               $this->presenter->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->presenter->sendPayload();
               $this->presenter->invalidateControl('shipping');       
           }
           else {
            $this->presenter->redirect('this');
           }
       }
    }
    
    public function handleDelPrice($delid) {
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax()){            
               $content = $this->presenter->context->httpRequest->getPost('value');

               $this->orderModel->updateDeliveryPrice($delid, $content);

           }
           if(!$this->presenter->isControlInvalid()){           
               $this->presenter->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->presenter->sendPayload();
               $this->presenter->invalidateControl('shipping');       
           }
           else {
            $this->presenter->redirect('this');
           }
       }
    }
    
    public function handleDelStatus($delid) {
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax()){
                $content = $this->presenter->context->httpRequest->getPost('value');

                $this->orderModel->updateDeliveryStatus($delid, $content);
            }
            
            if(!$this->isControlInvalid()){           
               $this->presenter->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->presenter->sendPayload();
               $this->invalidateControl('shipping'); //invalidace snipetu
           }
           else {
            $this->presenter->redirect('this');
           }
        }
    }

        public function handleDelHigher($delid) {
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax()){
                $content = $this->presenter->context->httpRequest->getPost('value');

                $this->orderModel->updateHigherDelivery($delid, $content);
            }
            
            if(!$this->isControlInvalid()){           
               $this->presenter->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->presenter->sendPayload();
               $this->invalidateControl('shipping'); //invalidace snipetu
           }
           else {
            $this->presenter->redirect('this');
           }
        }
    }

    public function handleDelFF($delid) {
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax())
           {            
               $content = $this->presenter->context->httpRequest->getPost('value');

               $this->orderModel->updateDeliveryFreefrom($delid, $content);

           }
           if(!$this->isControlInvalid())
           {           
               $this->presenter->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->presenter->sendPayload();       
               $this->invalidateControl('shipping'); //invalidace snipetu

           }
           else {
            $this->presenter->redirect('this');
           }
       }
    }

    public function handleRemoveShipping($shipid) {
       if ($this->presenter->getUser()->isInRole('admin')) {
         
            //$this->orderModel->deleteDelivery($shipid);
           $this->orderModel->updateDeliveryStatus($shipid, 3);
           
           $text = $this->translator->translate('Shipping was archived.');
            $message = Html::el('span', ' '.$text);
            $e = Html::el('i')->class('icon-ok-sign left');
            $message->insert(0, ' ');
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');
            
            if($this->presenter->isAjax()) {
                $this->invalidateControl('shipping');
            }
            else{
                $this->presenter->redirect("this");
            }
     }
    }
    
    protected function createComponentAddShippingForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
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
        if ($this->presenter->getUser()->isInRole('admin')) {

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
   
    public function render() {

        $this->template->setFile(__DIR__ . '/templates/shippingList.latte');
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
        
        $this->template->render();
    }

    public function renderJs($shipping) {
        $this->template->setFile(__DIR__ . '/templates/shippingJs.latte');
        $this->template->deliver = $shipping;
        $status = array();

        foreach ($this->orderModel->loadStatuses('') as $key => $value) {
            $status[$key] = $value->StatusName;
        };

        $this->template->status = $status;
        $this->template->render();
    }
    
    public function renderForm() {
        $this->template->setFile(__DIR__ . '/templates/shippingForm.latte');
        $this->template->render();
    }

}
