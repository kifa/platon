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

    
    /*****************************************************************
    * HANDLE
    */
    
   protected function installModule() {
      
                    //$this->getResults($this->analytics, 'UA-42741537-1');
                   
                     
   }
   
   public function handleUninstallModule() {
       if($this->shopModel->isModuleActive('gapi')) {
           
            $this->shopModel->updateModuleStatus('gapi', 2);
           
            $this->shopModel->deleteShopInfo('gapiAPI');
            $this->shopModel->deleteShopInfo('gapiTOKEN');
            $this->shopModel->deleteShopInfo('gapiTRACKING');
           
       }
       else {
           $this->presenter->flashMessage('Module already uninstalled', 'alert');
       }
       $this->presenter->redirect('this');
   }

   
   public function actionOrder($orderInfo) {
       if($this->shopModel->isModuleActive('gapi')) {
           
           
       }
   }
   /***********************************************************************
     * RENDERY
     */
    
   public function renderAdmin() {
        
        $this->template->setFile(__DIR__ . '/gapiAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('gapi');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
        $this->template->render();
    }
    
    public function renderInstall() {

        $this->template->setFile(__DIR__ . '/gapiInstallModule.latte');
        
      if(!$this->shopModel->isModuleActive('gapi')) {  
        
        $gapi = new Birne\Gapi\Gapi();
        $gapi->setParent($this);
        $this->gapisession->token = NULL;
        $gapi->setGAPI($this->gapisession->token);
        
         if($gapi->getAnalytics() !== NULL){

                        $return = $gapi->getGapiParam();

                        try {
                        $this->shopModel->insertShopInfo('gapiAPI', $return[1]);
                        $this->shopModel->insertShopInfo('gapiTRACKING', '');
                        $this->shopModel->insertShopInfo('gapiTOKEN', $return[0]);
                        $this->shopModel->updateModuleStatus('gapi', 1);
                        $this->gapisession->token = $return[0]; }
                        catch (Exception $e) {
                            \Nette\Diagnostics\Debugger::log($e);
                        }
                        
                        //$this->presenter->redirect('SmartPanel:modules');
                    }
        

      }              
        $info = $this->shopModel->loadModuleByName('gapi');
      
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID;         
        $this->template->render();
    }

    
    public function renderSmartPanel() {

        $this->template->setFile(__DIR__ . '/gapiSmartPanel.latte');
        $code = $this->shopModel->getShopInfo('gapiAPI');
        $token = $this->shopModel->getShopInfo('gapiTOKEN');
        
        $gapi = new Birne\Gapi\Gapi();
        $gapi->setParent($this);

        $gapi->setGAPI($token, $code);
        $id = '74912256';
        
        $optParams = array(
                'dimensions' => 'ga:source',
                'max-results' => '10',
                'sort' => '-ga:visits');
        $metrics = 'ga:visits';
        
        $params = array(
            'ga:'. $id,
            '2012-03-03',
            '2013-03-03',
            $metrics,
            $optParams,
            );
        
        
        $optParams2 = array(
                'dimensions' => 'ga:productName',
                'max-results' => '10',
                'filters' => 'ga:productName=~CadoMotus Newspeed 90mm',
                'sort' => '-ga:itemRevenue');
        
        $metrics2 = 'ga:itemRevenue';
        
        $params2 = array(
            'ga:'. $id,
            '2012-03-03',
            '2013-03-03',
            $metrics2,
            $optParams2);
        
        
        $this->template->view = $gapi->respond($params);
        $this->template->view2 = $gapi->respond($params2);
        $this->template->render();
        
          
    }
    
    public function renderSmartPanelMini() {
         $this->template->setFile(__DIR__ . '/gapiSmartPanelMini.latte');
        $code = $this->shopModel->getShopInfo('gapiAPI');
        $token = $this->shopModel->getShopInfo('gapiTOKEN');
        
        $gapi = new Birne\Gapi\Gapi();
        $gapi->setParent($this);

        
        $gapi->setGAPI($token, $code);
        $id = '74912256';
        
        
        $today = date('Y-m-d');
        
        $optParams2 = array(
                'dimensions' => 'ga:productName',
                'max-results' => '5',
                'sort' => '-ga:itemRevenue');
        
        $metrics2 = 'ga:itemRevenue';
        
        $params2 = array(
            'ga:'. $id,
            '2013-01-01',
            $today,
            $metrics2,
            $optParams2);
        
        $products = $gapi->respond($params2);
        $this->template->products = $products;
      
        
        $optParams = array(
                 'dimensions' => 'ga:productCategory',
                'max-results' => '5',
                'sort' => '-ga:itemRevenue');
        
        $metrics = 'ga:itemRevenue';
        
        $params = array(
            'ga:'. $id,
            '2012-03-03',
            $today,
            $metrics,
            $optParams);
        
         
        $category = $gapi->respond($params);
        $this->template->category = $category;
        
        $optParams = array(
                'dimensions' => '',
                'max-results' => '5');
        
        $metrics = 'ga:itemRevenue';
        
        $params = array(
            'ga:'. $id,
            '2012-03-03',
            $today,
            $metrics,
            $optParams);
        
         
        $totalRevenue = $gapi->respond($params);
        $this->template->totalrevenue = $totalRevenue;
        
        
        $optParams = array(
                'dimensions' => '',
                'max-results' => '5');
        
        $metrics = 'ga:itemQuantity';
        
        $params = array(
            'ga:'. $id,
            '2012-03-03',
            $today,
            $metrics,
            $optParams);
        
         
        $items = $gapi->respond($params);
        $this->template->items = $items;
        
        
        $this->template->render();
    }


    public function renderProduct($productid) {
        $this->template->setFile(__DIR__ . '/gapiProduct.latte');
        $code = $this->shopModel->getShopInfo('gapiAPI');
        $token = $this->shopModel->getShopInfo('gapiTOKEN');
        
        $gapi = new Birne\Gapi\Gapi();
        $gapi->setParent($this);

        
        $gapi->setGAPI($token, $code);
        $id = '74912256';
        
        
        $today = date('Y-m-d');
        
        $optParams2 = array(
                'dimensions' => '',
                'max-results' => '10',
                'filters' => 'ga:productSku=='.$productid,
                'sort' => '-ga:itemRevenue');
        
        $metrics2 = 'ga:itemRevenue';
        
        $params2 = array(
            'ga:'. $id,
            '2012-03-03',
            $today,
            $metrics2,
            $optParams2);
        
        $revenue = $gapi->respond($params2);
        $this->template->revenue = $revenue;
      
        
        $optParams = array(
                'dimensions' => '',
                'max-results' => '5',
                'filters' => 'ga:productSku=='.$productid);
        
        $metrics = 'ga:itemsPerPurchase';
        
        $params = array(
            'ga:'. $id,
            '2012-03-03',
            $today,
            $metrics,
            $optParams);
        
         
        $purchases = $gapi->respond($params);
        $this->template->purchases = $purchases;
        
        $optParams = array(
                'dimensions' => '',
                'max-results' => '5',
                'filters' => 'ga:productSku=='.$productid);
        
        $metrics = 'ga:itemQuantity';
        
        $params = array(
            'ga:'. $id,
            '2012-03-03',
            $today,
            $metrics,
            $optParams);
        
         
        $totalSell = $gapi->respond($params);
        $this->template->totalsell = $totalSell;
        
        
        
        $this->template->render();
    }
}