<?php

use Nette\Application\UI,
    Nette\Forms\Form,
    Nette\Utils\Html;
/*
 * Component to render modal window
 */

class variantControl extends BaseControl {
    
    
     /* @var Gettext\translator */
    protected $translator;
    
    private $productModel;
    private $row;
    
    
    
    /* 
     *Settin Translator to implement localization
     * 
     * @param Nette\Gettext\translator
     * @return void
     */

    
   public function setTranslator($translator) {
        $this->translator = $translator;
    }
    
    public function setProduct($product) {
        $this->productModel = $product;
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


    protected function createComponentTrackingForm(){
       
        $trackingForm = new Nette\Application\UI\Form;
        $trackingForm->setTranslator($this->translator);
        $trackingForm->addText('user', 'Your email:')
                ->addRule(FORM::EMAIL)
                ->setRequired();
        $trackingForm->addText('order', 'Your order number:')
                ->setRequired();
        $trackingForm->addText('pass', 'Your secret code:')
                ->setRequired('Please enter your secret code from mail');
        $trackingForm->addSubmit('track', 'Track order')
                ->setAttribute('class', 'btn btn-primary')
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
    
    
    protected function createComponentEditPriceForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {

    
            $priceForm = new Nette\Application\UI\Form;
            $priceForm->setTranslator($this->translator);
            $priceForm->addText('price', 'Price:')
                    ->setDefaultValue($this->row['SellingPrice'])
                    ->setRequired()
                    ->setType('number')
                    ->addRule(FORM::FLOAT, 'This has to be a number.');
            $priceForm->addText('sale', 'Discount:')
                    ->setDefaultValue($this->row['SALE'])
                    ->addRule(FORM::FLOAT, 'This has to be a number.')
                    ->addRule(FORM::RANGE, 'It should be less then price', array(0, $this->row['SellingPrice']));
            $priceForm->addHidden('id', $this->row['ProductID']);
            $priceForm->addSubmit('edit', 'Save price')
                    ->setAttribute('class', 'btn btn-primary')
                    ->setHtmlId('priceSave')
                    ->setAttribute('data-loading-text', 'Saving...');
            $priceForm->onSuccess[] = $this->editPriceFormSubmitted;
            return $priceForm;
        }
    }

    public function editPriceFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {

            
            $this->productModel->updatePrice($form->values->id, $form->values->price, $form->values->sale);
            if($this->presenter->isAjax()){
                
                $this->presenter->invalidateControl('variants');
                $this->presenter->invalidateControl('script');
            }
            else {
            
            $this->redirect('this');
            }
        }
    }
    
    public function handleSetSale($id, $amount, $type){
        if ($this->presenter->getUser()->isInRole('admin')) {            
            $this->productModel->updateSale($id, $amount, $type);
            if($this->presenter->isAjax()) {
                $this->presenter->invalidateControl('variants');
                $this->presenter->invalidateControl('script');
            }
            else{
              $this->presenter->redirect('this');  
            }
        }
    }
    
    
    /*
     * Rendering component
     */

    public function render() {

        $this->template->setFile(__DIR__ . '/ModalControl.latte');
        $this->template->render();
    }
    
    public function renderVariant($variants, $shipping) {
        $this->template->setFile(__DIR__ . '/variantControl.latte');
    

     if ($this->presenter->getUser()->isInRole('admin')) {            
        $this['editPriceForm']->setDefaults(array( 
                            'price' => $variants['SellingPrice'],
                            'sale' => $variants['SALE'],
                            'id' => $variants['ProductID']));
     }
        
        $this->template->productVariant = $variants;
        $this->template->shippingPrice = $shipping;
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
