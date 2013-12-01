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
class CashOnDeliveryModule extends moduleControl {

    /** @persistent */
    public $locale;

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
            if ($this->shopModel->isModuleActive('cod')) {
                $this->presenter->flashMessage('Module is already installed!', 'alert alert-success');
            } 
            else {
                $ID  = $this->orderModel->insertPayment('Cash on delivery', 0, 1);;
                $this->shopModel->insertShopInfo('codID', $ID['PaymentID']);
                $this->shopModel->updateModuleStatus('cod', 1);
                $this->presenter->flashMessage('Module installation OK!', 'alert alert-success');
            }
        }
        catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
        }
        $this->presenter->redirect('this');
    }

    public function handleUninstallModule() {
        if ($this->shopModel->isModuleActive('cod')) {
            $ID = $this->shopModel->getShopInfo('codID');

            $this->orderModel->deletePayment($ID);
            $this->shopModel->updateModuleStatus('cod', 2);

            $this->shopModel->deleteShopInfo('codID');

        } else {
            
        }
        $this->presenter->redirect('this');
    }

    public function actionOrder($orderInfo) {
        
    }



    /*     * *********************************************************************
     * RENDERY
     */

    public function renderAdmin() {

        $this->template->setFile(__DIR__ . '/../simpleAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('cod');

        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID;
        $this->template->render();
    }

    public function renderInstall() {

        $this->template->setFile(__DIR__ . '/../simpleInstallModule.latte');

        $info = $this->shopModel->loadModuleByName('cod');

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