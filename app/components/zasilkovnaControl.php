<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;


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
    private $orderModel;
    
    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setShop($shop) {
        $this->shopModel = $shop;
    }
  
     public function setOrder($order) {
        $this->orderModel = $order;
    }
    
    public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
    }
    

    
    protected function createComponentInstallModule() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $installForm = new Nette\Application\UI\Form;
            $installForm->setTranslator($this->translator);
            $installForm->addText('api', 'API:')
                    ->setRequired('Please enter your API key.');
            $installForm->addSubmit('install', 'Install module')
                    ->setAttribute('class', 'btn-primary upl span2')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $installForm->onSuccess[] = $this->installModuleSubmitted;
            return $installForm;
        }
    }
    
    
    public function installModuleSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            
            $this->shopModel->insertShopInfo('zasilkovnaAPI', $form->values->api);
            $this->installModule();
            $this->shopModel->updateModuleStatus('zasilkovna', 1);
            $this->presenter->redirect('this');
        }
    }


   /*****************************************************************
    * HANDLE
    */
    
   protected function installModule() {
       if($this->shopModel->isModuleActive('zasilkovna')) {
           $this->flashMessage('Module already installed.', 'alert alert-warning');
           $this->redirect('this');
       }
       else {
            if($this->shopModel->getShopInfo('zasilkovnaAPI')) {
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
       if($this->shopModel->isModuleActive('zasilkovna') && $this->shopModel->getShopInfo('zasilkovnaAPI')) {
           $this->reloadXML();
       }
       else {
           $this->flashMessage('Please enter your API key', 'alert alert-warning');
       }
       
       $this->redirect('this');
   }

   protected function reloadXML() {
       try {
        $API = $this->shopModel->getShopInfo('zasilkovnaAPI');
        $file = file_get_contents('http://www.zasilkovna.cz/api/v2/' . $API . '/branch.xml');
         
        $soubor = fopen($this->context->parameters['appDir'] . "/zasilkovna.xml", "a+");
        fwrite($soubor, $file);
        fclose($soubor);
        
        $xml = simplexml_load_file($soubor);
        
        
        $zasilkovnaID = $this->orderModel->insertDelivery('Zásilkovna',
                                          49,
                                          'osobní převzetí v síti Zásilkovna.cz',
                                          NULL,
                                            1,
                                           NULL);
        
        $this->orderModel->updateHigherDelivery($zasilkovnaID['DeliveryID'], $zasilkovnaID['DeliveryID']);

        foreach ($xml->branches->branch as $branch) {
            $this->orderModel->insertDelivery($branch->name,
                                              49,
                                              $branch->special . ' - ' .$branch->place,
                                              NULL,
                                                1,
                                               $zasilkovnaID['DeliveryID']);
        }
        
        return TRUE;
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
    
    public function renderInstall() {
        
        $this->template->setFile(__DIR__ . '/zasilkovnaInstallModule.latte');
        
        $info = $this->shopModel->loadModuleByName('zasilkovna');
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
                
        $this->template->render();
    }
}