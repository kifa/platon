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
    public $locale;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $shopModel;
    private $orderModel;
    
    private $view;
   
    public function __construct(\ShopModel $shopModel, \OrderModel $orderModel, 
            \Kdyby\Translation\Translator $translator) {
        $this->shopModel = $shopModel;
        $this->orderModel = $orderModel;
        $this->translator = $translator;
    }

    public function setView($view)
    {
        $this->view = $view;
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
           
    }
    
    public function sendMail($orderID) {
        $mail = $this->sendStatusMail($orderID);
             return $mail;
    }

        protected function sendStatusMail($orderid) {
       
        
            $row = $this->orderModel->loadOrder($orderid);
            $adminMail = $this->shopModel->getShopInfo('OrderMail');
            $shopName = $this->shopModel->getShopInfo('Name');
            $account = $this->shopModel->getShopInfo('Account'); 
            
            $template = new Nette\Templating\FileTemplate($this->presenter->context->parameters['appDir'] . '/templates/Email/bankwireStatus.latte');
                        
            $template->registerFilter(new Nette\Latte\Engine);
            $template->setTranslator($this->translator);
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

            $info = $this->translator->translate('Payment Information');
        try {  
            $mailIT = new mailControl($this->translator);
            $this->addComponent($mailIT, 'mail');
            
            $mailIT->sendSuperMail($row->UsersID, $info, $template, $adminMail);           
           
          }  catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
            }
            
         return TRUE;
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
    
    
    
    final public function render($arrgs) {
        
        if($arrgs == 'admin') {
            $this->renderAdmin();
        }
        
        if($arrgs == 'install') {
            $this->renderInstall();
        }        
    }
    
}