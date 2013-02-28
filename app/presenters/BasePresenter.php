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
     * zpřístupňuje informace o obchodu a jeho nastavení
     * 
     * @var categoryModel
     * přístup ke kategoriím pro načtení struktury menu atp
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
         *  Načítá informace, které se ukazují na každé stránce
         */
        
    public function beforeRender() {
        parent::beforeRender();
       // $this->template->infos = $this->shopModel->nactidata();
       // $this->template->categories = $this->categoryModel->nactidata();
    }
    
    
    

}
