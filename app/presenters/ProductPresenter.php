<?php

use Nette\Forms\Form,
        Nette\Utils\Html;
/*
 * class ProductPresenter
 * ProductPresenter rendering product info, and catalog info
 */

class ProductPresenter extends BasePresenter {



    /*
     * @var productModel
     * @var categoryModel;
     */

    private $productModel;
    private $categoryModel;

    private $catId;


    protected function startup() {
        parent::startup();
        
        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;
        

        /* Kontrola přihlášení
         * 
         * if (!$this->getUser()->isLoggedIn()) {
          $this->redirect('Sign:in');
          } */
    }
    
 
    protected function createComponentProduct() {
        
        $control = new ProductControl();
        $control->setService($this->context->productModel);
        $control->setCategoryID($this->catId);
        return $control;
    }
 
    
    /*
     * Creating form for adding product
     */
    
    public function createComponentAddProductForm() {
        $addProduct = new Nette\Application\UI\Form;
        $addProduct->addGroup('AddProduct');
        $addProduct->addText('name', 'Name:')
                ->setRequired();
        $addProduct->addText('price', 'Price:')
                ->setRequired();
        $addProduct->addTextArea('desc', 'Description: ', 10)
                ->setRequired();
        $addProduct->addSubmit('add', 'Add Product');
        $addProduct->onSuccess[] = $this->addProductFormSubmitted;
        return $addProduct;
    }

    /*
     * Processing added product
     */
    
    public function addProductFormSubmitted($form) {
        //$values = $form->getValues();
        
        $this->productModel->insertProduct(
                15, //ID
                $form->values->name, //Name
                'producer', //Producer
                '1', //Album
                '11111', //Product Number
                $form->values->desc, //Description
                1, //Parametr Album
                '123456', //Ean
                '122', //QR
                'rok', //Warranty
                15, //Pieces
                1, //CatID
                2, //PriceID
                '', //Date of avail.
                '', //Date added
                1, //Documentation
                1 //Comment             
                
        );
        
        $this->redirect('Product:products',  1);
    }

    /*
     * renderProducts
     * @param ?
     * @param ? example: pozice počátečního znaku
       * @return string
     */

    public function renderProducts($id) {
        
        $this->catId = $id;
        $this->template->products = $this->productModel->loadCatalog($id);
        $this->template->category = $this->categoryModel->loadCategory($id); 
    }
    
    
     /*
     * renderProduct();
     * rendering Product with full info
      * * @param ?
* @param ? example: pozice počátečního znaku
* @return string
     */
    
    public function renderProduct($id) {
       
       $this->template->product = $this->productModel->loadProduct($id);
    }

    
    /*
     * renderDefault()
     * rendering default product catalog
     *  @param ?
* @param ? example: pozice počátečního znaku
* @return string
     */
    public function renderDefault()
	{
            $this->redirect('Products');
            $this->template->anyVariable = 'any value';
	}

}
?>
