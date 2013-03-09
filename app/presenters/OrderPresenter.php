<?php

/**
 * Order presenter.
 */
class OrderPresenter extends BasePresenter
{

    
    /*
     * @var OrderModel
     */
    
    private $orderModel;
    private $cart;
    
    protected function startup() {
        parent::startup();
        $this->orderModel = $this->context->orderModel;
        $this->cart = $this->getSession('cart');
        
        
    }

     
    public function actionCart($produkt) {
        
        if (isset($this->cart)) {
            $this->setView('Cart');
        }
        else {
                $this->setView('CartEmpty');

        }
    }

    
    /*
     * renderCart rendering cart
     * including order form, payment, etc.
     */
    
    public function renderCart($produkt) {

        if (isset($produkt)) {
            
        $this->cart->userName = "super nový".$produkt;
        $this->template->cart = $this->cart;
       // unset($cart->userName);
        }
        
        else {
            $this->setView('CartEmpty');
        }
        
    }

    /*
     * renderCartEmpty()
     * rendering empty cart
     */    
    
    public function renderCartEmpty() {
            
        //$this->template->anyVariable = 'any value';
        $this->cart->userName = 'pepík';
    }


    /*
     * renderOrderDone()
     * rendering Thank you for your order page
     */
    
    public function renderOrderDone() {
        
    }

        public function renderDefault()
	{
		$this->setView('CartEmpty');
	}

}
