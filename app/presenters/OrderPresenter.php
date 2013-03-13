<?php

use Nette\Forms\Form;

/**
 * Order presenter.
 */
class OrderPresenter extends BasePresenter {
    /*
     * @var OrderModel
     */

    private $orderModel;
    private $productModel;
    private $cart;
    private $c2;
    private $c3;

    protected function startup() {
        parent::startup();
        $this->orderModel = $this->context->orderModel;
        $this->productModel = $this->context->productModel;
        $this->cart = $this->getSession('cart');
    }

    /*
     * Action for removing item from Cart
     */

    public function actionRemoveItem($id) {
        unset($this->cart->prd[$id]);
        $this->cart->numberItems = Count($this->cart->prd);
        
        if ($this->cart->numberItems > 0) {
             $this->setView('Cart');
        } else {
            $this->setView('CartEmpty');
        }
    }

    public function actionCart($product, $amnt) {
        $row = $this->productModel->loadProduct($product);
        if (!$row || !$product) {
            $this->setView('CartEmpty');
        } else {
            if (isset($this->cart->prd[$product])) {
                $mnt = $this->cart->prd[$product];
                $mnt += $amnt;
                $this->cart->prd[$product] = $mnt;
            } else {
                $this->cart->prd[$product] = $amnt;
            }
            $this->cart->lastItem = $product;
            $this->cart->numberItems = Count($this->cart->prd);
            $this->setView('Cart');
        }
    }

    /*
     * renderCart rendering cart
     * including order form, payment, etc.
     * @row is used for "unknown" product id
     */

    public function renderCart() {

        $product = $this->cart->lastItem;
        
        if ($this->cart->numberItems > 0) {
            foreach ($this->cart->prd as $id => $amnt) {

                $amnt = $this->cart->prd[$id];
                $product2 = $this->productModel->loadProduct($id);

                $this->c2[$id][$amnt] = $product2;
         
            }
            $this->template->cart = $this->c2;

        } else {
            $this->setView('CartEmpty');
        }
    }

    protected function createComponentCartForm() {
        $cartForm = new Form();
        $cartForm->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $cartForm->addGroup('Delivery info');
        $cartForm->addText('name', 'Name:', 40, 100)
                ->addRule(Form::FILLED, 'Would you fill your name, please?');
        $cartForm->addText('phone', 'Phone:', 40, 100);
        $cartForm->addText('email', 'Email:', 40, 100)
                ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                ->addRule(Form::FILLED, 'Would you fill your name, please?');
        $cartForm->addGroup('Address');
        $cartForm->addText('address', 'Address:', 60, 100)
                ->addRule(Form::FILLED);
        $cartForm->addText('city', 'City:', 40, 100)
                ->addRule(Form::FILLED);
        $cartForm->addText('psc', 'PSC:', 40, 100)
                ->addRule(Form::FILLED);
        $cartForm->addSubmit('sendOrder', 'Checkout');
        return $cartForm;
    }

    protected function createComponentShippingForm() {

        $shippers = array(
            'cp' => 'Czech postal service',
            'dpd' => 'DPD'
        );
        $shippingForm = new Form();
        $shippingForm->addRadioList('shippers', 'Delivery options: ', $shippers);
        return $shippingForm;
    }

    /*
     * renderCartEmpty()
     * rendering empty cart
     */

    public function renderCartEmpty() {

        //$this->template->anyVariable = 'any value';
        
    }

    /*
     * renderOrderDone()
     * rendering Thank you for your order page
     */

    public function renderOrderDone() {
        
    }

    public function renderDefault() {
        $this->setView('Cart');
    }

}
