<?php

/**
 * Base presenter for eshop.
 * Presenting skeleton of shop - header - content link - footer
 * 
 * zobrazuje celé záhlaví
 * zobrazuje celé zápatí
 * nastavuje rozložení stránky


 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {
    /*
     * @var shopModel
     * accessing info about shop like name, meta, shipping
     * 
     * @var categoryModel
     * accessing category model
     */

    private $shopModel;
    private $categoryModel;
    
    protected function startup() {
        parent::startup();
        $this->shopModel = $this->context->shopModel;
        $this->categoryModel = $this->context->categoryModel;
        
        }


        /*
         *  beforeRender()
         *  rendering info used on every page
         */
        
    public function beforeRender() {
        parent::beforeRender();
       // $this->template->infos = $this->shopModel->nactidata();
       // $this->template->categories = $this->categoryModel->nactidata();
    }
    
    
    

}
