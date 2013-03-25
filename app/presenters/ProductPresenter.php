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
        $addProduct->addHidden('catID', $this->catId);
        $addProduct->addGroup('AddProduct');
        $addProduct->addText('name', 'Name:')
                ->setRequired();
        $addProduct->addText('price', 'Price:')
                ->setRequired()
                ->addRule(FORM::FLOAT, 'It has to be a number!');
        $addProduct->addText('amount', 'Amount')
                ->setDefaultValue('1')
                ->addRule(FORM::INTEGER, 'It has to be a number!')
                ->setRequired();
        $addProduct->addButton('plusItem', '+');
        $addProduct->addButton('minusItem', '-');
        $addProduct->addTextArea('desc', 'Description: ', 10)
                ->setRequired();
        $addProduct->addText('producer', 'Producer: ')
                ->setDefaultValue('neuvedeno');
        $addProduct->addSubmit('add', 'Add Product');
        $addProduct->onSuccess[] = $this->addProductFormSubmitted;
        return $addProduct;
    }

    /*
     * Processing added product
     */
    
    public function addProductFormSubmitted($form) {
        //$values = $form->getValues();
        $id = $this->productModel->countProducts() + 1;
        
        $this->productModel->insertProduct(
                $id, //ID
                $form->values->name, //Name
                $form->values->producer, //Producer
                '1', //Album
                '11111', //Product Number
                $form->values->desc, //Description
                1, //Parametr Album
                '123456', //Ean
                '122', //QR
                'rok', //Warranty
                $form->values->amount, //Pieces
                $form->values->catID, //CatID
                2, //PriceID
                '', //Date of avail.
                '', //Date added
                1, //Documentation
                1 //Comment             
                
        );
        
        $this->redirect('Product:products', $form->values->catID);
    }


    /*
     * Handle for removing products 
     */
    
    public function handleDeleteProduct($id, $catID) {
      //  $this->productModel->removeProduct($id);
        $this->redirect('Product:products', $catID);
                
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
