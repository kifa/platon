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
   
   
   

   /**************************************************************
    *           PRODUCT MODULE
    *
    ***********************************************************************/
     
    protected function createComponentComment() {
       
       $comment = new CommentModule();
       $comment->setTranslator($this->translator);
       $comment->setShop($this->shopModel);
       $comment->setOrder($this->orderModel);
       $comment->setCategory($this->categoryModel);
       $comment->setBlog($this->blogModel);
       $comment->setProduct($this->productModel);
       
       return $comment;
   }
    
   protected function createComponentDocument() {
       
       $document = new documentModule();
       $document->setTranslator($this->translator);
       $document->setShop($this->shopModel);
       $document->setOrder($this->orderModel);
       $document->setCategory($this->categoryModel);
       $document->setBlog($this->blogModel);
       $document->setProduct($this->productModel);
       
       return $document;
   }
   
   
   
   /*****************************************************
    *           ORDER MODULES
    */
   
   protected function createComponentHeureka() {
       
       $heureka = new HeurekaModule();
       $heureka->setTranslator($this->translator);
       $heureka->setShop($this->shopModel);
       $heureka->setOrder($this->orderModel);
       $heureka->setProduct($this->productModel);
       $heureka->setCategory($this->categoryModel);
       return $heureka;
   }
   
   
    /*******************************************************
     * RENDERY
     */
    
   
    
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
    
    public function renderProductModule($name, $id) {
        
        $this->template->setFile(__DIR__ . '/renderModule.latte');
        
        $component = $this->shopModel->loadModuleByName($name);

        if($component !== FALSE) {
             $comp = $this->createComponent($component->CompModuleName);
             $this->addComponent($comp, $component->CompModuleName);
             $this->template->comp = $comp;
                      
             
        }
        else {
            $text = $this->translator->translate('Module not available: ');
           $this->presenter->flashMessage($text . $name, 'alert alert-warning');
           //$this->presenter->redirect('this');
        }
        

        $this->template->id = $id;
        $this->template->render();
    }
    
    public function renderModules() {
        
        $this->template->setFile(__DIR__ . '/listOfModules.latte');
        
        $components = $this->shopModel->loadModules('');
        
        if ($components !== NULL) {
            try { 
                foreach ($components as $id => $component) {
                    $comp = $this->createComponent($component->CompModuleName);
                    $this->addComponent($comp, $component->CompModuleName);
                }
            }
            catch (Exception $e) {
                \Nette\Diagnostics\Debugger::log($e);
            }
        }
        else {
           
        }
        
        $this->template->render();
    }
    
    public function renderSmartPanelModule($name){
        
     
        $this->template->setFile(__DIR__ . '/renderSmartPanelModule.latte');
        
        $component = $this->shopModel->loadModuleByName($name);

        if($component !== FALSE) {
             $comp = $this->createComponent($component->CompModuleName);
             $this->addComponent($comp, $component->CompModuleName);
             $this->template->comp = $comp;
                      
             
        }
        else {
           $text = $this->translator->translate('Module not available: ');
           $this->template->comp = NULL;
           $this->presenter->flashMessage($text . $name, 'alert alert-warning');
           
            //$this->presenter->redirect('this');
        }
        
        $this->template->render();
    }
}