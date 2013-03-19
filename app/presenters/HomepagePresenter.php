<?php

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

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
    
    public function renderDefault() {

        $this->template->products = $this->productModel->loadCatalog('');
        $this->template->anyVariable = 'any value';
    }

}
