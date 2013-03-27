<?php

use Nette\Application\UI;

/*
 * Component for product rendering
 * 
 * @autor Lukas
 */

class ProductControl extends BaseControl {

    private $service;
    private $id;
    
    protected $translator;

    

    /**
     * Vstříkne službu, kterou tato komponenta bude používat pro práci.
     *
     * @param    ProductModel
     * @return   void
     */
    public function setService(ProductModel $service) {
        $this->service = $service;
    }

    /*
     * Předává Category ID
     */

    public function setCategoryID($id) {
        $this->id = $id;
    }

    
    
    
    /* Set translator option
     * 
     */

    
   public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function createTemplate($class = NULL)
{
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
}

    /*
     * Vykreslí komponentu Product z makra ProductControl.latte
     */

    public function render() {

        $this->template->setFile(__DIR__ . '/ProductControl.latte');
        $this->template->products = $this->service->loadCatalog($this->id);
        $this->template->photos = $this->service->loadCoverPhoto();
        $this->template->render();
    }

}