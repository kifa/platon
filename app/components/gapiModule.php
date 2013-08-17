<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;

use Birne\Gapi\Gapi;
use Birne\Gapi\Extension;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of zasilkovnaControl
 *
 * @author Lukas
 */
class gapiModule extends moduleControl {
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $shopModel;
    private $orderModel;
    private $categoryModel;
    private $productModel;

    private $analytic;
    private $gapisession;

    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setShop($shop) {
        $this->shopModel = $shop;
    }
  
     public function setOrder($order) {
        $this->orderModel = $order;
    }
    
    public function setCategory($cat) {
        $this->categoryModel = $cat;
    }
    
    public function setProduct($pro) {
        $this->productModel = $pro;
    }

    public function setGapi($gapisession) {
        $this->gapisession = $gapisession;
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
            $installForm->addText('api', 'KEY:')
                    ->setRequired('Please enter your Heureka Key.')
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
            if($this->shopModel->getShopInfo('heurekaKEY') == NULL) {
            $this->shopModel->insertShopInfo('heurekaKEY', $form->values->api);
                }
            try {
                $this->installModule();
                $this->shopModel->updateModuleStatus('ulozenka', 1);
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
       
       
      if($this->shopModel->getShopInfo('gapiAPI')) {
                try {
                    
                    
                    
                    $this->presenter->flashMessage('Module installation OK!', 'alert alert-success');
                    
                } catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
                }          
               }   
        else {
            $this->presenter->flashMessage('Module installation OK, please ENTER your API key!', 'alert alert-warning');
        }        
   }
   
   public function handleUninstallModule() {
       if($this->shopModel->isModuleActive('gapi')) {
           
            $this->shopModel->updateModuleStatus('gapi', 2);
           
            $this->shopModel->deleteShopInfo('gapiAPI');
           
       }
       else {
           $this->presenter->flashMessage('Module already uninstalled', 'alert');
       }
       $this->presenter->redirect('this');
   }

   
   public function actionOrder($orderid, $statusID) {
       if($this->shopModel->isModuleActive('gapi')) {
       }
   }
   /***********************************************************************
     * RENDERY
     */
    
   public function renderAdmin() {
        
        $this->template->setFile(__DIR__ . '/heurekaAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('heureka');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
        $this->template->key = $this->shopModel->getShopInfo('heurekaKEY');
        $this->template->render();
    }
    
    public function renderInstall() {
        
        $this->template->setFile(__DIR__ . '/heurekaInstallModule.latte');
        
        $info = $this->shopModel->loadModuleByName('heureka');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
                
        $this->template->render();
    }

    
    public function renderSmartPanel() {
        
        
        
        //$this->flflf();
        $this->template->setFile(__DIR__ . '/gapiSmartPanel.latte');
        $code = $this->shopModel->getShopInfo('gapiAPI');
        $token = $this->shopModel->getShopInfo('gapiTOKEN');
        
        $gapi = new Birne\Gapi\Gapi();
        $gapi->setParent($this);

        if($token == 'null') {
        $gapi->setGAPI( $this->gapisession->token);
        }
        else {
            $gapi->setGAPI($token, $code);
        }
        
        
        
        
        //$this->getResults($this->analytics, 'UA-42741537-1');
        if($gapi->getAnalytics() !== NULL){
        
/*            $return = $gapi->getGapiParam();

            $this->shopModel->setShopInfo('gapiAPI', $return[1]);
            $this->shopModel->setShopInfo('gapiTOKEN', $return[0]);
            $this->gapisession->token = $return[0];
  */          $id = '39033320';
            
            $this->template->view = $gapi->respond($id);
            
        }

        $this->template->render();
        
          
    }
    public function getResults($analytics, $profileId) {

            $optParams = array(
                'dimensions' => 'ga:source',
                'max-results' => '100');


            $metrics = 'ga:visits';

            return $analytics->data_ga->get(
               'ga:' . $profileId,
               '2012-03-03',
               '2013-03-03',
               $metrics);


        }
}