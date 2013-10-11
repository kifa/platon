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

    
    protected function createComponentEditPriceForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {

    
            $priceForm = new Nette\Application\UI\Form;
            $priceForm->setTranslator($this->translator);
            $priceForm->addText('price', 'Price:')
                    ->setDefaultValue($this->row['SellingPrice'])
                    ->setRequired()
                    ->setType('number')
                    ->addRule(FORM::FLOAT, 'This has to be a number.')
                    ->setAttribute('class', 'form-control');
            $priceForm->addText('sale', 'Discount:')
                    ->setAttribute('class', 'form-control')
                    ->setDefaultValue($this->row['SALE'])
                    ->addRule(FORM::FLOAT, 'This has to be a number.')
                    ->addRule(FORM::RANGE, 'It should be less then price', array(0, $this->row['SellingPrice']));
            $priceForm->addHidden('id', $this->row['ProductID']);
            $priceForm->addSubmit('edit', 'Save price')
                    ->setAttribute('class', 'btn btn-success form-control')
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
                 $this->presenter->invalidateControl('variantscript');
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
