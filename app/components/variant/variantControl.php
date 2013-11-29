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
    
    
    public function __construct(\ProductModel $productModel, \GettextTranslator\Gettext $translator) {
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
                $this->presenter->invalidateControl('variantscript');
            }
            else{
              $this->presenter->redirect('this');  
            }
        }
    }
    
    
    public function handleEditProdVarTitle($id){
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax()){            
                $content = $_POST['value'];
                $this->productModel->updateProduct($id, 'ProductVariantName', $content);
            }
            if(!$this->isControlInvalid()){
                $this->presenter->payload->edit = $content;
                $this->presenter->sendPayload();
                //$this->invalidateControl('editProdVarTitle'.$id);
            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }
       
    public function handleEditProdAmount($id) {        
        if($this->presenter->getUser()->isInRole('admin')){ 
            if($this->presenter->isAjax())
            {            
                $content = $_POST['value'];

                $this->productModel->updateProduct($id, 'PiecesAvailable', $content);
               
            }
            if(!$this->isControlInvalid())
            {
                
                $this->presenter->payload->edit = $content;
                $this->presenter->sendPayload();
                $this->invalidateControl('editProdAmount');
                $this->presenter->invalidateControl('pageheader');
                 $this->presenter->invalidateControl('page-header');
                $this->invalidateControl();
            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }
    
    
    public function renderVariant($variants, $shipping) {
        $this->template->setFile(__DIR__ . '/templates/variantControl.latte');
    

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
    
    
    public function renderJs($variant) {
        $this->template->setFile(__DIR__ . '/templates/variantJs.latte');
        $this->template->productVariant = $variant;
        $this->template->render();
    }
    
    
}
