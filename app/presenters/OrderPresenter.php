<?php

use Nette\Forms\Form;
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
    
    protected function createComponentCartForm() {
        $cartForm = new Form();
        $cartForm->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $cartForm->addGroup('Delivery info');
        $cartForm->addText('name', 'Name:', 40,100)
                ->addRule(Form::FILLED, 'Would you fill your name, please?');
        $cartForm->addText('phone', 'Phone:', 40,100);
        $cartForm->addText('email', 'Email:', 40,100)
                ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                ->addRule(Form::FILLED, 'Would you fill your name, please?');
        $cartForm->addGroup('Address');
        $cartForm->addText('address', 'Address:', 60,100)
               ->addRule(Form::FILLED);
        $cartForm->addText('city', 'City:', 40,100)
               ->addRule(Form::FILLED);
        $cartForm->addText('psc', 'PSC:', 40,100)
               ->addRule(Form::FILLED);
        $cartForm->addSubmit('sendOrder', 'Checkout');
        return $cartForm;
        
    }
    
    protected function createComponentShippingForm(){
        
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
