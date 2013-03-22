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
