<?php

use Nette\Application\UI;

/*
 * Component for product rendering
 * 
 * @autor Lukas
 */

class ProductControl extends UI\Control {

    private $service;

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
     * Vykreslí komponentu Product z makra ProductControl.latte
     */
    public function render() {

        $this->template->setFile(__DIR__ . '/ProductControl.latte');
        $this->template->products = $this->service->loadCatalog('');
        $this->template->render();
    }

}