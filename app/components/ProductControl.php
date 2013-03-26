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