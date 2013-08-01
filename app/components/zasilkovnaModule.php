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
class zasilkovnaModule extends moduleControl {
    
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
                    ->setRequired('Please enter your API key.')
                    ->setAttribute('class', 'span12');
            $installForm->addSubmit('install', 'Install module')
                    ->setAttribute('class', 'btn-primary upl span12')
                    ->setAttribute('data-loading-text', 'Installing...');
            $installForm->onSuccess[] = $this->installModuleSubmitted;
            return $installForm;
        }
    }
    
    
    public function installModuleSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            
            $this->shopModel->insertShopInfo('zasilkovnaAPI', $form->values->api);
            try {
                $this->installModule();
                $this->shopModel->updateModuleStatus('zasilkovna', 1);
            }catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
            }          
            
            $this->presenter->redirect('this');
        }
    }


   /*****************************************************************
    * HANDLE
    */
    
   protected function installModule() {
      /* if($this->shopModel->isModuleActive('zasilkovna')) {
           $this->presenter->flashMessage('Module already installed.', 'alert alert-warning');
           return TRUE;
       }
       else {}*/
       
      if($this->shopModel->getShopInfo('zasilkovnaAPI')) {
                try {$this->reloadXML();
                     }catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
                }          
               }   
            else {
                $this->presenter->flashMessage('Module installation OK, please ENTER your API key!', 'alert alert-warning');
            }
       $this->presenter->flashMessage('Module installation OK!', 'alert alert-success');        
   }
   
   public function handleUninstallModule() {
       if($this->shopModel->isModuleActive('zasilkovna')) {
           $delid = $this->shopModel->getShopInfo('zasilkovnaID');
           $delivery = $this->orderModel->loadDelivery('', 'active');
                      foreach($delivery as $id => $del) {
               $this->orderModel->deleteSubDelivery($delid);
           }
           
            $this->shopModel->updateModuleStatus('zasilkovna', 2);
           
           $this->shopModel->deleteShopInfo('zasilkovnaID');
           $this->shopModel->deleteShopInfo('zasilkovnaAPI');
           
       }
       else {
           
       }
       $this->presenter->redirect('this');
   }

   public function handleUpdateXML() {
       if($this->shopModel->isModuleActive('zasilkovna') && $this->shopModel->getShopInfo('zasilkovnaAPI')) {
         //  $this->updateXML();
       }
       else {
           $this->flashMessage('Please enter your API key', 'alert alert-warning');
       }
       
       $this->presenter->redirect('this');
   }

   protected function reloadXML() {
       try {
        $API = $this->shopModel->getShopInfo('zasilkovnaAPI');
        $file = file_get_contents('http://www.zasilkovna.cz/api/v2/' . $API . '/branch.xml');
         
        $soubor = fopen($this->presenter->context->parameters['appDir'] . "/zasilkovna.xml", "w+");
        fwrite($soubor, $file);
        fclose($soubor);
        
        $soubor = $this->presenter->context->parameters['appDir'] . "/zasilkovna.xml";  
        $xml = simplexml_load_file($soubor);
        
       $zasilkovnaID = $this->orderModel->insertDelivery('Zásilkovna',
                                          49,
                                          'osobní převzetí v síti Zásilkovna.cz',
                                          NULL,
                                            1,
                                           NULL);
        
        $this->orderModel->updateHigherDelivery($zasilkovnaID['DeliveryID'], $zasilkovnaID['DeliveryID']);
        $this->shopModel->insertShopInfo('zasilkovnaID', $zasilkovnaID['DeliveryID']);
        foreach ($xml->branches->branch as $branch) {
            $name = $branch->country[0]->__toString() . ' - ' . $branch->name[0]->__toString();
            $this->orderModel->insertDelivery($name,
                                              49,
                                              $branch->place[0]->__toString(),
                                              NULL,
                                                1,
                                               $zasilkovnaID['DeliveryID']);
        } 
        
        }
        catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
                   $this->presenter->flashMessage('XML feed crashed. I´m so sorry.', 'alert alert-danger');
        }
   }
   
   /***********************************************************************
     * RENDERY
     */
    
   public function renderAdmin() {
        
        $this->template->setFile(__DIR__ . '/zasilkovnaAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('zasilkovna');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
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