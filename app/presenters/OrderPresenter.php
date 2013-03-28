<?php

use Nette\Forms\Form,
    Nette\Utils\Html;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;

/**
 * Order presenter.
 */
class OrderPresenter extends BasePresenter {
    
    /* @var OrderModel */
    private $orderModel;
    
    /* @var ProductModel */
    private $productModel;
    
    /* @var ShopModel */
    private $shopModel;
    
    /* @var UserModel */
    private $userModel;
    
    /* @var int */
    private $orderNo;
    
    /* @var Session */
    private $cart;
    
    /* @var Session */
    private $c2;

    protected function startup() {
        parent::startup();
        $this->orderModel = $this->context->orderModel;
        $this->productModel = $this->context->productModel;
        $this->shopModel = $this->context->shopModel;
        $this->userModel = $this->context->userModel;
        $this->cart = $this->getSession('cart');
    }

    /*
     * Handle for removing item from Cart
     */

    public function handleRemoveItem($id) {
        unset($this->cart->prd[$id]);
        $this->cart->graveItem = $id;
        $this->cart->numberItems = Count($this->cart->prd);



        if ($this->cart->numberItems > 0) {

            $el1 = Html::el('span', 'Product was removed. Isn´t it pitty?! ');
            $el2 = Html::el('a', 'Take it Back!')->href($this->link('graveItem!'));
            $el1->add($el2);
            $this->flashMessage($el1, 'alert');
            $this->presenter->redirect("this");
        } else {
            $this->redirect('Order:cartEmpty');
        }
    }

    /*
     * Handle for adding amount of goods
     */

    public function handleAddAmount($id) {
        $mnt = $this->cart->prd[$id];
        $mnt += 1;
        $this->cart->prd[$id] = $mnt;

        $this->presenter->redirect('this');
    }

    /*
     * Handle for removing amount of goods
     * 
     */

    public function handleRemoveAmount($id) {
        $mnt = $this->cart->prd[$id];
        $mnt -= 1;

        if ($mnt > 0) {
            $this->cart->prd[$id] = $mnt;
            $this->presenter->redirect('this');
        } else {
            $this->handleRemoveItem($id);
        }
    }

    /*
     * Handle for moving dead product back to cart
     */

    public function handleGraveItem() {
        $id = $this->cart->graveItem;
        $this->actionCart($id, "1");
    }

    

    

    public function actionCart($product, $amnt) {
     
       $row = $this->productModel->loadProduct($product);
        if (!$row || !$product) {
             if ($this->cart->numberItems > 0) {
                $this->setView('Cart');
            } else {
                $this->setView('CartEmpty');
            }
        }
        else {
            if (isset($this->cart->prd[$product])) {
                $mnt = $this->cart->prd[$product];
                $mnt += $amnt;
                $this->cart->prd[$product] = $mnt;
            } else {
                $this->cart->prd[$product] = $amnt;
            }
            $this->cart->lastItem = $product;
            $this->cart->numberItems = Count($this->cart->prd);

            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' ' . $row->ProductName . ' was successfully added to your cart.');
            $message->insert(0, $ico);
            $this->flashMessage($message, 'alert alert-info');
           $this->redirect('Order:cart');
       
        }
    }
   

    /*
     * renderCart rendering cart
     * including order form, payment, etc.
     * @row is used for "unknown" product id
     */

    public function renderCart() {

        if ($this->cart->numberItems > 0) {
            foreach ($this->cart->prd as $id => $amnt) {

                $amnt = $this->cart->prd[$id];
                $product2 = $this->productModel->loadProduct($id);

                $this->c2[$id][$amnt] = $product2;
            }

            $shippers = array();
            $payment = array();

            foreach ($this->orderModel->loadDelivery('') as $key => $value) {
                $shippers[$key] = $value->DeliveryPrice;
            };

            foreach ($this->orderModel->loadPayment('') as $key => $value) {
                $payment[$key] = $value->PaymentPrice;
            };

            $this->template->shippers = $shippers;
            $this->template->payment = $payment;

            $this->template->cart = $this->c2;
            
            $this->template->cartForm = $this->createComponentCartForm();
           
        } else {
            $this->setView('CartEmpty');
        }
    }

    /*
     * Rendering Customer form in cart
     * 
     * @return $cartForm
     */

    protected function createComponentCartForm() {

        $shippers = array();
        $payment = array();

        foreach ($this->orderModel->loadDelivery('') as $key => $value) {
            $shippers[$key] = $value->DeliveryName;
        };

        foreach ($this->orderModel->loadPayment('') as $key => $value) {
            $payment[$key] = $value->PaymentName;
        };
        
        $cartForm = new Nette\Application\UI\Form;
        $cartForm->setRenderer(new BootstrapRenderer);
        $cartForm->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $cartForm->addGroup('Delivery info');
        $cartForm->addText('name', 'Name:', 40, 100)
                ->addRule(Form::FILLED, 'Would you fill your name, please?');
        $cartForm->addText('phone', 'Phone:', 40, 100);
        $cartForm->addText('email', 'Email:', 40, 100)
                ->setEmptyValue('@')
                ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                ->addRule(Form::FILLED, 'Would you fill your name, please?');
        $cartForm->addGroup('Address');
        $cartForm->addText('address', 'Address:', 60, 100)
                ->addRule(Form::FILLED);
        $cartForm->addText('city', 'City:', 40, 100)
                ->addRule(Form::FILLED);
        $cartForm->addText('psc', 'PSC:', 40, 100)
                ->addRule(Form::FILLED);
        $cartForm->addGroup('Shipping');
        $cartForm->addRadioList('shippers', '', $shippers)
                ->setAttribute('class', '.span1 radio')
                ->setRequired('Please select Shipping method');
        $cartForm->addGroup('Payment');
        $cartForm->addRadioList('payment', '', $payment)
                ->setAttribute('class', '.span1 radio')
                ->setRequired('Please select Payment method');
        $cartForm->addGroup('Terms');
        $cartForm->addCheckbox('terms', 'I accept Terms and condition.')
                ->setAttribute('class', 'checkbox inline')
                ->setRequired()
                ->setDefaultValue('TRUE')
                ->addRule(Form::FILLED, 'In order to continue checkout, you have to agree with Term.');
        $cartForm->addGroup('Checkout');
        $cartForm->addSubmit('send', 'Checkout here!')
                ->setAttribute('class', 'btn btn-warning btn-large');
        $cartForm->onSuccess[] = $this->cartFormSubmitted;
        return $cartForm;
    }

    /*
     * Getting values from CartForm
     * 
     * @param Form, CartForm
     * @return void
     */

    public function cartFormSubmitted($form) {

        $total = 0;
        $today = date("Y-m-d");

        foreach ($this->cart->prd as $id => $amnt) {

            $price = $this->productModel->loadProduct($id)->FinalPrice;
            
            $amnt = $this->cart->prd[$id];

            $total += $price * $amnt;
            
        }
        
        
        $this->orderNo = $this->orderModel->countOrder() + 1;

        $addressID = $this->userModel->countAddress() + 1;
        
        $this->userModel->insertAddress(
                    $addressID,
                    $form->values->address,
                    $form->values->city,
                    $form->values->psc
                    
                );
        
        
        
        $this->userModel->insertUser(
                    $form->values->email,
                    $form->values->name,
                    $form->values->phone,
                    $addressID
               );
        
        $tax = $this->shopModel->getTax()->value;
        settype($tax, "float");
        $finalTax = $total * ($tax / 100);
        
        
        $this->orderModel->insertOrder(
                $this->orderNo,
                $form->values->email,
                $total, $finalTax, $today, $today, $form->values->shippers, $form->values->payment
        );

        $cislo = $this->orderModel->countOrderDetail() + 1;


        foreach ($this->cart->prd as $id => $amnt) {

            $price = $this->productModel->loadProduct($id)->FinalPrice;
            $amnt = $this->cart->prd[$id];
            $this->orderModel->insertOrderDetails($cislo, $this->orderNo, $id, $amnt, $price);
            $cislo++;
        }

        $this->redirect('Order:orderDone', $this->orderNo);
    }

    /*
     * Render for empty cart
     * rendering empty cart
     */

    public function renderCartEmpty() {

        //$this->template->anyVariable = 'any value';
    }

    /*
     * renderOrderDone()
     * rendering Thank you for your order page
     * 
     * @param int
     * @return void
     */

    public function renderOrderDone($orderNo) {

        $this->template->products = $this->orderModel->loadOrderProduct($orderNo);
        $this->template->order = $this->orderModel->loadOrder($orderNo);

        $ico = HTML::el('i')->class('icon-ok-sign left');
        $message = HTML::el('span', ' Order has been successfully sent.');
        $message->insert(0, $ico);
        $this->flashMessage($message, 'alert alert-info');
        unset($this->cart->prd);
        $this->cart->numberItems = 0;
    }
   
    /*
     * Render default page
     * 
     * @param null
     * @return void
     */

    public function renderDefault() {
        $this->setView('Cart');
    }

}
