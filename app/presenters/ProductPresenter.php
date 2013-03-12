<?php

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
        return $control;
    }
    /*
     * renderProducts
     * @param ?
    * @param ? example: pozice počátečního znaku
       * @return string
     */
    public function renderProducts() {
   
        $this->template->products = $this->productModel->loadCatalog('2');
    }
    
    
     /*
     * renderProduct();
     * rendering Product with full info
      * * @param ?
* @param ? example: pozice počátečního znaku
* @return string
     */
    
    public function renderProduct($id) {
       //$id = '1';
        $control = $this->getComponent('product');
        $this->template->control = $control;
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
		$this->template->anyVariable = 'any value';
	}

}
?>
