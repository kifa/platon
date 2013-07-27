<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of zasilkovnaControl
 *
 * @author Lukas
 */
class zasilkovnaControl extends moduleControl {
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $shopModel;

    public function setTranslator($translator) {
        $this->translator = $translator;
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
    
   protected function installModule() {
       if($this->shopModel->isModuleActive()) {
           $this->flashMessage('Module already installed.', 'alert alert-warning');
           $this->redirect('this');
       }
       else {
            if($this->shopModel->getShopInfo('API')) {
                $this->reloadXML();
            }   
            else {
                $this->flashMessage('Module installation OK, please ENTER your API key!', 'alert alert-warning');
                $this->redirect('this');
            }

       }
       $this->flashMessage('Module installation OK!', 'alert alert-success');
       $this->redirect('this');
       
   }
   
   protected function uninstallModule() {
       if($this->shopModel->isModuleActive()) {
           
       }
       else {
           
       }
   }

   public function updateXML() {
       if($this->shopModel->isModuleActive() && $this->shopModel->getShopInfo('API')) {
           $this->reloadXML();
       }
       else {
           $this->flashMessage('Please enter your API key', 'alert alert-warning');
       }
       
       $this->redirect('this');
   }

   protected function reloadXML() {
       try {
        $API = $this->shopModel->getShopInfo('API');
        $file = file_get_contents('http://www.zasilkovna.cz/api/v2/' . $API . '/branch.xml');
         
        $soubor = fopen($this->context->parameters['appDir'] . "/zasilkovna.xml", "a+");
        fwrite($soubor, $file);
        fclose($soubor);
        
        $xml = simplexml_load_file($soubor);
        
        foreach ($xml->branches->branch as $branch) {
            dump($branch->name);
            dump($branch->nameStreet);
        }
        
        }
        catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
                   $this->flashMessage('XML feed crashed. I´m so sorry.', 'alert alert-danger');
        }
   }
   
   /***********************************************************************
     * RENDERY
     */
    
   public function renderAdmin() {
        
        $this->template->setFile(__DIR__ . '/zasilkovnaAdminModule.latte');
        $this->template->render();
    }
}