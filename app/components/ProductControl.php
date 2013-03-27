<?php

use Nette\Application\UI;

/*
 * Component for product rendering
 * 
 * @autor Lukas
 */

class ProductControl extends BaseControl {
    
    /* @var ProductModel  */
    private $service;
    
    /* @var CategoryID */
    private $id;
    
    /* @var Gettext\translator */
    protected $translator;

    

    /**
     * Setting ProductModel service to access DB
     *
     * @param    ProductModel
     * @return   void
     */
    public function setService(ProductModel $service) {
        $this->service = $service;
    }
    
    /*
     * Setting current CategoryID to be able to
     * render products in category
     * 
     * @param int
     * @return void
     */

    public function setCategoryID($id) {
        $this->id = $id;
    }

    
    
    
    /* 
     *Settin Translator to implement localization
     * 
     * @param Nette\Gettext\translator
     * @return void
     */

    
   public function setTranslator($translator) {
        $this->translator = $translator;
    }
    
    /*
     * Create control template for localization
     * 
     * @param NULL
     * @return Translator template
     */

    public function createTemplate($class = NULL)
{
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);

    return $template;
}

    
    /*
     * Rendering component Product from ProductControl.latte
     */

    public function render() {

        $this->template->setFile(__DIR__ . '/ProductControl.latte');
        $this->template->products = $this->service->loadCatalog($this->id);
        $this->template->photos = $this->service->loadCoverPhoto();
        $this->template->render();
    }

}