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


    /*
     * renderCart rendering cart
     * including order form, payment, etc.
     */
    
    public function renderCart() {
        
        if (isset($cart)) {
        $this->template->cart = $cart;
        unset($cart->userName);
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
        $cart->userName = 'pepík';
    }


    /*
     * renderOrderDone()
     * rendering Thank you for your order page
     */
    
    public function renderOrderDone() {
        
    }

        public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

}
