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
    private $orderModel;

    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setCategory($cat) {
        $this->categoryModel = $cat;

    }
    
    public function setOrder($order) {
        $this->orderModel = $order;

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
    
    
   public function handleInstallModule($name) {
       $component = $this[$name];
       
       try{
       $component->installModule();
       
       }
       catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
        }
   }*/
   
   
   /************************************************************
    *               SHIPPING MODULE
    ***********************************************************/
   protected function createComponentZasilkovna() {
       
       $zasilkovna = new ZasilkovnaModule();
       $zasilkovna->setTranslator($this->translator);
       $zasilkovna->setShop($this->shopModel);
       $zasilkovna->setOrder($this->orderModel);
       return $zasilkovna;
   }
   
   protected function createComponentUlozenka() {
       
       $ulozenka = new UlozenkaModule();
       $ulozenka->setTranslator($this->translator);
       $ulozenka->setShop($this->shopModel);
       $ulozenka->setOrder($this->orderModel);
       return $ulozenka;
   }
   
   
   /************************************************************
    *               PAYMENT MODULE
    ***********************************************************/
   
   protected function createComponentCod() {
       
       $cod = new CashOnDeliveryModule();
       $cod->setTranslator($this->translator);
       $cod->setShop($this->shopModel);
       $cod->setOrder($this->orderModel);
       return $cod;
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
        
        $this->template->setFile(__DIR__ . '/listOfModules.latte');
        
        try { 
            foreach ($this->shopModel->loadModules('shipping') as $id => $component) {
                $comp = $this->createComponent($component->CompModuleName);
                
                $this->addComponent($comp, $component->CompModuleName);
                
            }
        }
        catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
        }
        
        $this->template->render();
    }
    
    public function renderPaymentModules() {
        
        $this->template->setFile(__DIR__ . '/listOfModules.latte');
        
        try { 
            foreach ($this->shopModel->loadModules('payment') as $id => $component) {
                $comp = $this->createComponent($component->CompModuleName);
                $this->addComponent($comp, $component->CompModuleName);
            }
        }
        catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
        }
        
        $this->template->render();
    }
    
}