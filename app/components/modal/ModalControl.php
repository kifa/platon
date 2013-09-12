<?php

use Nette\Application\UI,
    Nette\Forms\Form,
    Nette\Utils\Html;
/*
 * Component to render modal window
 */

class ModalControl extends BaseControl {
    
    
     /* @var Gettext\translator */
    protected $translator;
    
    private $service;
    
    
    
    /* 
     *Settin Translator to implement localization
     * 
     * @param Nette\Gettext\translator
     * @return void
     */

    
   public function setTranslator($translator) {
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

    public function setService(OrderModel $service) {
        $this->service = $service;
    }

    protected function createComponentTrackingForm(){
       
        $trackingForm = new Nette\Application\UI\Form;
        $trackingForm->setTranslator($this->translator);
        $trackingForm->addText('user', 'Your email:')
                ->addRule(FORM::EMAIL)
                ->setRequired()
                ->setAttribute('class', 'form-control');
        $trackingForm->addText('order', 'Your order number:')
                ->setRequired()
                ->setAttribute('class', 'form-control');
        $trackingForm->addText('pass', 'Your secret code:')
                ->setRequired('Please enter your secret code from mail')
                ->setAttribute('class', 'form-control');
        $trackingForm->addSubmit('track', 'Track order')
                ->setAttribute('class', 'btn btn-success form-control')
                ->setHtmlId('track');
        $trackingForm->onSuccess[] = $this->trackingFormSubmitted;
        return $trackingForm;
    }
    
    public function trackingFormSubmitted($form) {
        $row = $this->service->loadOrder($form->values->order);
        $e = Html::el('i')->class('icon-warning-sign');
        
        if (!$row) {
            $text = $this->translator->translate(' Order not found. Please try again!');
            $message = HTML::el('span', $text);
            $message->insert(0, $e);
            $this->presenter->flashMessage($message, 'alert');
            $this->presenter->redirect('this');
        }
        elseif ($row->UsersID !== $form->values->user) {
            $text = $this->translator->translate(' Email not found. Please try again!');
            $message = HTML::el('span', $text);
            $message->insert(0, $e);
            $this->presenter->flashMessage($message, 'alert');
            $this->presenter->redirect('this');
        }
        elseif (md5($row->UsersID . $row->OrderID . $row->DateCreated ) !== $form->values->pass) {
            $text = $this->translator->translate(' Your password is incorrect. Please try again.');
            $message = HTML::el('span', $text);
            $message->insert(0, $e);
            $this->presenter->flashMessage($message, 'alert');
            $this->presenter->redirect('this');
        }
        else  {
            $this->presenter->redirect('Order:orderDone', $row->OrderID, $form->values->pass);
        }        
    }
    /*
     * Rendering component
     */

    public function render() {

        $this->template->setFile(__DIR__ . '/ModalControl.latte');
        $this->template->render();
    }
    
    public function renderInfo($id, $title, $content) {

        $this->template->setFile(__DIR__ . '/ModalControl.latte');
        $this->template->id = $id;
        $this->template->title = $title;
        $this->template->content = $content;
        $this->template->render();
    }
    
    public function renderGallery($id, $title, $content) {

        $this->template->setFile(__DIR__ . '/ModalGalleryControl.latte');
        $this->template->id = $id;
        $this->template->title = $title;
        $this->template->content = $content;
        $this->template->render();
    }
    
    public function renderTracking() {
        $this->template->setFile(__DIR__ . '/ModalTrackingControl.latte');
        $this->template->render();
    }
    
    
}
