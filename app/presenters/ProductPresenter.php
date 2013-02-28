<?php

use Nette;
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
     * načítá produkty pro zobrazení katalogu
     */
    public function renderProducts() {
        $this->template->products = $this->productModel->nactiProdukty();
    }
    
    
     /*
     * renderProduct();
     * načítá jeden produkt pro zobrazení zboží
     */
    
    public function renderProduct() {
        $this->template->product = $this->productModel->nactiProdukt();
    }


}
?>
