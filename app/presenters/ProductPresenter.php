<?php

/*
 * class ProductPresenter
 * předkládá informace o produktech
 * předkládá informace o jednom produktu
 */

class ProductPresenter extends BasePresenter {



    /*
     * @var productModel
     * @var categoryModel;
     */

    private $productModel;
    private $categoryModel;


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
    
    
    /*
     * renderProducts();
     * rendering Product Catalog
     */
    public function renderProducts() {
     //   $this->template->products = $this->productModel->nactiProdukty();
    }
    
    
     /*
     * renderProduct();
     * rendering Product with full info
     */
    
    public function renderProduct() {
       // $this->template->product = $this->productModel->nactiProdukt();
    }

    public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

}
?>
