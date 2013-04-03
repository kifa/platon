<?php

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    private $productModel;
    private $categoryModel;
    
    protected $translator;

    protected function startup() {
        parent::startup();

        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;

        /* Kontrola přihlášení
         * 
         * if (!$this->getUser()->isInRole('admin')) {
          $this->redirect('Sign:in');
          } */
    }
    
    public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }

    protected function createComponentProduct() {
        $control = new ProductControl();
        $control->setService($this->context->productModel);
        $control->setTranslator($this->translator);
        return $control;
    }
    
    
    public function renderDefault() {

        $this->template->products = $this->productModel->loadCatalog('');
        $this->template->category = $this->categoryModel->loadCategory("");
        $this->template->anyVariable = 'any value';
    }

}
