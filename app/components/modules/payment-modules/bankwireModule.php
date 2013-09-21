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
class bankwireModule extends moduleControl {

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

    public function createTemplate($class = NULL) {
        $template = parent::createTemplate($class);
        $template->setTranslator($this->translator);
        // případně $this->translator přes konstrukt/inject

        return $template;
    }

    /*     * ***************************************************************
     * HANDLE
     */

    public function handleInstallModule() {

        
        try {
            if ($this->shopModel->isModuleActive('bankwire')) {
                $this->presenter->flashMessage('Module is already installed!', 'alert alert-success');
            } 
            else {
                $ID  = $this->orderModel->insertPayment('Bankwire', 0, 1);;
                $this->shopModel->insertShopInfo('bankwireID', $ID['PaymentID']);
                $this->shopModel->insertShopInfo('Account', '');
                $this->shopModel->updateModuleStatus('bankwire', 1);
                $this->presenter->flashMessage('Module installation OK!', 'alert alert-success');
            }
        }
        catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
        }
        $this->presenter->redirect('this');
    }

    public function handleUninstallModule() {
        if ($this->shopModel->isModuleActive('bankwire')) {
            $ID = $this->shopModel->getShopInfo('bankwireID');

            $this->orderModel->deletePayment($ID);
            $this->shopModel->updateModuleStatus('bankwire', 2);

            $this->shopModel->deleteShopInfo('bankwireID');
            $this->shopModel->deleteShopInfo('Account');

        } else {
            
        }
        $this->presenter->redirect('this');
    }

    
    
    public function actionOrder($orderInfo) {
         if($this->shopModel->isModuleActive('bankwire')) {
          
             $bankwireID = $this->shopModel->getShopInfo('bankwireID');
                 
           if ($orderInfo['Progress'] == 1 & $orderInfo['Payment'] == $bankwireID) {
               $this->sendStatusMail($orderInfo['OrderID']);
               
           }
        
        }
    }
    
    protected function sendStatusMail($orderid) {
       
        
             $row = $this->orderModel->loadOrder($orderid);
             $adminMail = $this->shopModel->getShopInfo('OrderMail');
             $shopName = $this->shopModel->getShopInfo('Name');
             $account = $this->shopModel->getShopInfo('Account');
             
            
            $template = new Nette\Templating\FileTemplate($this->presenter->context->parameters['appDir'] . '/templates/Email/bankwireStatus.latte');
            
          
            
            
            $template->registerFilter(new Nette\Latte\Engine);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');
            $template->orderId = $orderid;
            $template->variable = $orderid;
            $template->bankaccount = $account;
            $template->mailOrder = $row->UsersID;
            $template->total = $row->TotalPrice;
            $template->adminMail = $adminMail;
            $template->shopName = $shopName;

            
            $hash = md5($row->UsersID . $row->OrderID . $row->DateCreated);
            $args = array($row->OrderID, $hash);
            $template->link = $this->presenter->link('//Order:orderDone', $args);
            
      try {      
            $mailIT = new mailControl();
            
              
            $mailIT->sendSuperMail($row->UsersID, 'Informace k platbě ', $template, $adminMail);
        }
            catch (Exception $e) {
            \Nette\Diagnostics\Debugger::barDump($e);
            }
        
       
    }


    /*     * *********************************************************************
     * RENDERY
     */

    public function renderAdmin() {

        $this->template->setFile(__DIR__ . '/../simpleAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('bankwire');

        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID;
        $this->template->render();
    }

    public function renderInstall() {

        $this->template->setFile(__DIR__ . '/../simpleInstallModule.latte');

        $info = $this->shopModel->loadModuleByName('bankwire');

        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID;
        $this->template->render();
    }
    
    public function renderOrderAdmin() {

        $this->template->setFile(__DIR__ . '/../simpleAdminModule.latte');

        $info = $this->shopModel->loadModuleByName('bankwire');

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
        
        if($arrgs == 'orderAdmin') {
            $this->renderOrderAdmin();
        }
        
    }
    
}