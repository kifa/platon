<?php

use Nette\Application\UI\Form;

class OrderForm extends BaseForm {
    
    public $orderModel;
    public $translator;


    public function build() {
        $shippers = array();
        $payment = array();
        $defaultShipping = NULL;
        $lowerShippers = NULL;
        

        foreach ($this->orderModel->loadDelivery('','active') as $key => $value) {
          //  $t = HTML::el('span', $value->DeliveryPrice)->class('text-info');
            if($value->HigherDelivery === NULL) {
            $shippers[$key] = $value->DeliveryName . ' | ' . $value->DeliveryPrice . ',-';
            
            if($defaultShipping == NULL) {
                $defaultShipping = $key;
            }
            
            }
            else {
                $lowerShippers[$value->HigherDelivery] = $value->HigherDelivery;
            }
        }
        
        foreach ($this->orderModel->loadPayment('','active') as $key => $value) {
            $payment[$key] = $value->PaymentName . ' | ' . $value->PaymentPrice .',-';
        }
        
      //  $this = new Nette\Application\UI\Form;
        $this->setTranslator($this->translator);

        $this->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $this->addGroup('Delivery info');
        $this->addText('name', 'order.form.name')
                ->addRule(Form::FILLED, 'Would you fill your name, please?')
                ->setAttribute('class', 'form-control');
        $this->addText('phone', 'Phone:')
                ->setAttribute('class', 'form-control');
        $this->addText('email', 'Email:')
                ->setEmptyValue('@')
                ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                ->addRule(Form::FILLED, 'Would you fill your name, please?')
                ->setAttribute('class', 'form-control');
        $this->addGroup('Address');
        $this->addText('address', 'Street:')
                ->addRule(Form::FILLED)
                ->setAttribute('class', 'form-control');
        $this->addText('city', 'City:')
                ->addRule(Form::FILLED)
                ->setAttribute('class', 'form-control');
        $this->addText('zip', 'ZIP:')
                ->addRule(Form::FILLED)
                ->setAttribute('class', 'form-control');
        $this->addGroup('Shipping');
        $this->addSelect('shippers', 'by post/delivery service', $shippers)
                ->setPrompt('-- select shipping --')
                ->setAttribute('class', 'form-control');
                //->setAttribute('class', ' radio')
              //  ->setRequired('Please select Shipping method');
        if(isset($lowerShippers)) {
           
            
            foreach($lowerShippers as $lower) {
                $lowerShippers2 = array();
                foreach($this->orderModel->loadSubDelivery($lower) as $key2 => $value2){
                   $lowerShippers2[$key2] = $value2->DeliveryName . ' | ' . $value2->DeliveryPrice . ',-';
                }   
            
        
        $this->addSelect('lowerShippers'.$lower, 'personal pick up', $lowerShippers2)
                      ->setAttribute('class', 'form-control');
              //  ->setAttribute('class', '.span1 radio')
              }
        
        }
        
        $this->addGroup('Payment');
        $this->addSelect('payment', '', $payment)
              //  ->setAttribute('class', '.span1 radio')
                ->setRequired('Please select Payment method')
                ->setAttribute('class', 'form-control');
        $this->addGroup('Terms');
        $this->addButton('termButton', 'Read Terms')
                ->setHtmlId('termsButton')
                ->setAttribute('class', 'btn btn-sm btn-success')
                ->setAttribute('data-toggle', 'modal')
                ->setAttribute('data-target', "#terms");
         $this->addGroup('Notes');
        $this->addTextArea('note', 'Note:')
                ->setAttribute('class', 'form-control');
        $this->addHidden('shipping','shipping')
                ->setDefaultValue($defaultShipping);
        $this->addGroup('Checkout');
        $this->addSubmit('send', 'Checkout')
                ->setAttribute('class', 'btn btn-danger btn-lg');
        
    }
}
