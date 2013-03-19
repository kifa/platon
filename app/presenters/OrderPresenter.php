<?php

use Nette\Forms\Form,
 Nette\Utils\Html;

/**
 * Order presenter.
 */
class OrderPresenter extends BasePresenter {
    /*
     * @var OrderModel
     */

    private $orderModel;
    private $productModel;
    private $orderNo;
    private $cart;
    private $c2;


    protected function startup() {
        parent::startup();
        $this->orderModel = $this->context->orderModel;
        $this->productModel = $this->context->productModel;
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
            $el1->add( $el2 );
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
        
        if($mnt > 0){
        $this->cart->prd[$id] = $mnt;
        $this->presenter->redirect('this');

        }
        else {
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


    /*
     * Action for pre-view cart processing
     * 
     * 
     * 
     * 
     */

    public function actionCart($product, $amnt) {
        $row = $this->productModel->loadProduct($product);
        if (!$row || !$product) {
            if ($this->cart->numberItems > 0 ) {
                 $this->setView('Cart');
            }  else {
            $this->setView('CartEmpty');
            }
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
            $this->redirect('Order:cart');
        }
    }

    /*
     * renderCart rendering cart
     * including order form, payment, etc.
     * @row is used for "unknown" product id
     */

    public function renderCart() {

        //$product = $this->cart->lastItem;

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

    /*
     * Rendering Customer form in cart
     * 
     * @return $cartForm
     */
    protected function createComponentCartForm()
    {
        
        $shippers = array();
        $payment = array();
        
        foreach ($this->orderModel->loadDelivery('') as $key => $value) {
            $shippers[$key] = $value->DeliveryName; 
        };
        
        foreach ($this->orderModel->loadPaymentMethod('') as $key => $value) {
            $payment[$key] = $value->PaymentMethodName;
        };
        
       // $ico = Html::el('i', 'class=""'); 
        
        $cartForm = new Nette\Application\UI\Form;
        $cartForm->addProtection('Vypršel časový limit, odešlete formulář znovu');
        $cartForm->addGroup('Delivery info')
                ->setOption('container', 'div class="span5"');
        $cartForm->addText('name', 'Name:', 40, 100)
                ->addRule(Form::FILLED, 'Would you fill your name, please?');
        $cartForm->addText('phone', 'Phone:', 40, 100);
        $cartForm->addText('email', 'Email:', 40, 100)
                 ->setEmptyValue('@')
                ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                ->addRule(Form::FILLED, 'Would you fill your name, please?');
        $cartForm->addGroup('Address')
                   ->setOption('container', 'div class="span5"');
        $cartForm->addText('address', 'Address:', 60, 100)
                ->addRule(Form::FILLED);
        $cartForm->addText('city', 'City:', 40, 100)
                ->addRule(Form::FILLED);
        $cartForm->addText('psc', 'PSC:', 40, 100)
                ->addRule(Form::FILLED);
        $cartForm->addGroup('Shipping')
                ->setOption('container', 'div class="span5"');
        $cartForm->addRadioList('shippers','', $shippers)
                ->setAttribute('class', '.span1 radio')
                ->setRequired('Please select Shipping method');
        $cartForm->addGroup('Payment')
                ->setOption('container', 'div class="span5"');
        $cartForm->addRadioList('payment','', $payment)
                ->setAttribute('class', '.span1 radio')
                ->setRequired('Please select Payment method');;
        $cartForm->addGroup('Terms')
                ->setOption('container', 'div class="span5"');
        $cartForm->addCheckbox('terms', 'I accept Terms and condition.')
                ->setAttribute('class', 'checkbox inline')
                ->setRequired()
                ->setDefaultValue('TRUE')
                ->addRule(Form::FILLED, 'In order to continue checkout, you have to agree with Term.');
        $cartForm->addGroup('Checkout')
                ->setOption('container', 'div class="span5"');
        $cartForm->addSubmit('send', 'Checkout here!')
                ->setAttribute('class', 'btn btn-warning btn-large');
        $cartForm->onSuccess[] = $this->cartFormSubmitted;
        return $cartForm;
    }

    /*
     * Getting values from CartForm
     */
    public function cartFormSubmitted($form)
    {
       // $order = $form->getValues();
   
        $total = 0;
        foreach ($this->cart->prd as $id => $amnt){

                $price = $this->productModel->loadProduct($id)->FinalPrice;
                $amnt = $this->cart->prd[$id];
             
                  $total += $price * $amnt;
            }
            $this->orderNo = $this->orderModel->countOrder() + 1;

        $this->orderModel->insertOrder(
                                $this->orderNo,
                                1,
                                "novak",
                                $total,
                                89,
                                88,
                                99,
                                FALSE,
                                $form->values->shippers,
                                $form->values->payment

                                );
       
        $cislo = $this->orderModel->countOrderDetail() + 1;
        
        
        foreach ($this->cart->prd as $id => $amnt){

                $price = $this->productModel->loadProduct($id)->FinalPrice;
                $amnt = $this->cart->prd[$id];
                $this->orderModel->insertOrderDetails($cislo, $this->orderNo, $id, $amnt, $price );
                        $cislo++;
            }

        $this->redirect('Order:orderDone');
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

    public function renderOrderDone($orderNo) {
        $orderNo = 1;
        $this->template->orderdetails = $this->orderModel->loadOrderDetails($orderNo);
        $this->template->products = $this->orderModel->loadOrderProduct($orderNo);
        $this->template->order = $this->orderModel->loadOrder($orderNo);
        
        dump($this->orderModel->loadOrder($orderNo));
        $this->flashMessage('Order sent.');
        unset($this->cart->prd);
        $this->cart->numberItems = 0;
        
    }

    
    public function renderOrders() {
        //$this->template->orders = $this->orderModel->loadOrders;
    }

    public function renderDefault() {
        $this->setView('Cart');
    }

}
