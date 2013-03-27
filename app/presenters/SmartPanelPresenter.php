<?php

use Nette\Forms\Form,
    Nette\Utils\Html;

/*
 * SettingsPreseter is used for setting up shop etc
 * 
 */

class SmartPanelPresenter extends BasePresenter {
    /* @var OrderModel */

    private $orderModel;

    /* @var ProductModel */
    private $productModel;

    /* @var CategoryModel */
    private $categoryModel;

    /* @var ShopModel */
    private $shopModel;

    /* @var UserModel */
    private $userModel;

    protected function startup() {
        parent::startup();
        $this->orderModel = $this->context->orderModel;
        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;
        $this->shopModel = $this->context->shopModel;
        $this->userModel = $this->context->userModel;
    }
    
    
    /*
     * renderOrderDone()
     * rendering Thank you for your order page
     * 
     * @param int
     * @return void
     */

    public function renderOrderDetail ($orderNo) {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        } else {
        $this->template->products = $this->orderModel->loadOrderProduct($orderNo);
        $this->template->order = $this->orderModel->loadOrder($orderNo);

        }
    }

    /*
     * Render Payment
     */
    
    public function renderPayment() {
        
    }


    /*
     * Render Shipping method and settings
     */
    
    public function renderShipping() {
        
    }
    
    
    /*
     * Render Orders
     *
     * @param null
     * @return void
     */

    public function renderOrders() {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        } else {
            $this->template->orders = $this->orderModel->loadOrders();
        }
    }

    /*
     * Render default view of SmartPanel
     */

    public function renderDefault() {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        } else {
            $this->template->anyVariable = 'any value';
        }
    }

}

