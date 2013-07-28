<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of modulControl
 *
 * @author Lukas
 */

class moduleControl extends BaseControl{
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $categoryModel;
    private $productModel;
    private $blogModel;
    private $shopModel;

    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setCategory($cat) {
        $this->categoryModel = $cat;

    }
    
    public function setBlog($blog) {
        $this->blogModel = $blog;

    }
    
    public function setProduct($pro) {
        $this->productModel = $pro;
    }
    
    public function setShop($shop) {
        $this->shopModel = $shop;
    }
  
    public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
    }
    
     

   /*****************************************************************
    * HANDLE
    */
    
   public function handleInstallModule($name) {
       $component = $this->createComponent($name);
       $component->installModule();
       
   }
   
/*
   protected function createComponent($name) {
        try { 
            $component = $this[$name];
            $component->setTranslator($this->translator);
            $component->setShop($this->shopModel);
            
            return $component;
        }
        catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
        }
            return FALSE;
   }
*/

   protected function createComponentZasilkovna() {
       
       $zasilkovna = new ZasilkovnaControl();
       $zasilkovna->setTranslator($this->translator);
       $zasilkovna->setShop($this->shopModel);
       return $zasilkovna;
   }

   /***********************************************************************
     * RENDERY
     */
    
    public function renderProductMini($id) {
        $layout = $this->shopModel->getShopInfo('ProductMiniLayout');
       
        $this->template->setFile(__DIR__ . '/' . $layout . '.latte');    
        $this->template->product = $this->productModel->loadProduct($id);
        $this->template->render();
    }
    
    
    public function renderShippingModules() {
        
        $this->template->setFile(__DIR__ . '/shippingModules.latte');
        
        foreach ($this->shopModel->loadModule('') as $component) {
            $comp = $this->createComponent($component->ModuleName);
            $this->addComponent($comp, $component->ModuleName);
        }
        
        $this->template->render();
    }
    
}