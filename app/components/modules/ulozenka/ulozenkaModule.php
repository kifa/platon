<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ulozenkaControl
 *
 * @author Lukas
 */
class ulozenkaModule extends moduleControl {
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $shopModel;
    private $orderModel;


    private $view;
    
    public function setView($view)
    {
        $this->view = $view;
    }
    
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
            
     
            $this->shopModel->insertShopInfo('ulozenkaAPI', $form->values->api);
            try {
                $this->installModule();
                $this->shopModel->updateModuleStatus('ulozenka', 1);
            }catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
            }          
            
            $this->presenter->redirect('this');
        }
    }

    
     protected function createComponentPriceForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $priceForm = new Nette\Application\UI\Form;
            $priceForm->setTranslator($this->translator);
            $priceForm->addText('price', 'Price:')
                    ->setType('number')
                    ->setRequired('Please set new price.')
                    ->setAttribute('class', 'span12');
            $priceForm->addText('freefrom', 'Free from:')
                    ->setType('number')
                    ->setAttribute('class', 'span12');
            $priceForm->addSubmit('set', 'Set new Ulozenka price')
                    ->setAttribute('class', 'btn-primary upl span12')
                    ->setAttribute('data-loading-text', 'Setting...');
            $priceForm->onSuccess[] = $this->priceFormSubmitted;
            return $priceForm;
        }
        
    }
    
    public function priceFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            
            $ulozenkaID = $this->shopModel->getShopInfo('ulozenkaID');
            
            $delivery = $this->orderModel->loadSubDelivery($ulozenkaID);
            try {
                foreach($delivery as $id => $delivery) {
                    $this->orderModel->updateDeliveryPrice($id, $form->values->price);
                    $this->orderModel->updateDeliveryFreeFrom($id, $form->values->freefrom);
                }
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
      /* if($this->shopModel->isModuleActive('ulozenka')) {
           $this->presenter->flashMessage('Module already installed.', 'alert alert-warning');
           return TRUE;
       }
       else {}*/
       
      if($this->shopModel->getShopInfo('ulozenkaAPI')) {
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
       if($this->shopModel->isModuleActive('ulozenka')) {
           $delid = $this->shopModel->getShopInfo('ulozenkaID');
           $delivery = $this->orderModel->loadDelivery('', 'active');
                      foreach($delivery as $id => $del) {
               $this->orderModel->deleteSubDelivery($delid);
           }
           
            $this->shopModel->updateModuleStatus('ulozenka', 2);
           
           $this->shopModel->deleteShopInfo('ulozenkaID');
           $this->shopModel->deleteShopInfo('ulozenkaAPI');
           
       }
       else {
           
       }
       $this->presenter->redirect('this');
   }

   public function handleUpdateXML() {
       if($this->shopModel->isModuleActive('ulozenka') && $this->shopModel->getShopInfo('ulozenkaAPI')) {
         //  $this->updateXML();
       }
       else {
           $this->flashMessage('Please enter your API key', 'alert alert-warning');
       }
       
       $this->presenter->redirect('this');
   }

   protected function reloadXML() {
       try {
        $API = $this->shopModel->getShopInfo('ulozenkaAPI');
        
        $file = file_get_contents('http://www.ulozenka.cz/partner/pobocky.php?key=' . $API);
         
        $soubor = fopen($this->presenter->context->parameters['appDir'] . "/ulozenka.xml", "w+");
        fwrite($soubor, $file);
        fclose($soubor);
        
        $soubor = $this->presenter->context->parameters['appDir'] . "/ulozenka.xml";  
        $xml = simplexml_load_file($soubor);
        
       $ulozenkaID = $this->orderModel->insertDelivery('Uloženka',
                                          39,
                                          'osobní převzetí v síti Uloženka.cz',
                                          NULL,
                                            1,
                                           NULL);
        
        $this->orderModel->updateHigherDelivery($ulozenkaID['DeliveryID'], $ulozenkaID['DeliveryID']);
        $this->shopModel->insertShopInfo('ulozenkaID', $ulozenkaID['DeliveryID']);
        foreach ($xml->pobocky as $branch) {

            $this->orderModel->insertDelivery($branch->nazev[0]->__toString(),
                                              49,
                                              $branch->ulice[0]->__toString(),
                                              NULL,
                                                1,
                                               $ulozenkaID['DeliveryID']);
        } 
        
        }
        catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
                   $this->presenter->flashMessage('XML feed crashed. I´m so sorry.', 'alert alert-danger');
        }
   }
   
    public function actionOrder($orderInfo) {
        
    }
   
   /***********************************************************************
     * RENDERY
     */
    
   public function renderAdmin() {
        
        $this->template->setFile(__DIR__ . '/ulozenkaAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('ulozenka');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
        $this->template->render();
    }
    
    public function renderInstall() {
        
        $this->template->setFile(__DIR__ . '/ulozenkaInstallModule.latte');
        
        $info = $this->shopModel->loadModuleByName('ulozenka');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
                
        $this->template->render();
    }
    
    final public function render($arrgs) {

        if($arrgs == 'admin') {
            $this->renderAdmin();
        }
        
        if($arrgs == 'install') {
            $this->renderInstall();
        }

    }
}