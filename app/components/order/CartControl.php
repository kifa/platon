<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;

class CartControl extends BaseControl{
    
     /** @persistent */
    public $locale;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $categoryModel;
    private $productModel;
    private $shopModel;
    private $orderModel;

    private $cart;
    
    public function __construct(\ShopModel $shopModel, \ProductModel $productModel, 
                                \Kdyby\Translation\Translator $translator,
                                \OrderModel $orderModel,
                                $cart) {
        $this->shopModel = $shopModel;
        $this->productModel = $productModel;
        $this->translator = $translator;
        $this->orderModel = $orderModel;
        $this->cart = $cart;
    }
    
    public function handleRemoveItem($id)
    {
        if($id) {
        unset($this->cart->prd[$id]);
        $this->cart->graveItem = $id;
        $this->cart->numberItems = Count($this->cart->prd);
        }
        
        if ($this->cart->numberItems > 0) {

            $text = $this->translator->translate('Product was removed. Isn´t it pitty?!');
            $text2 = $this->translator->translate('Take it Back!');
            $el1 = Html::el('span', $text.' ');
            $el2 = Html::el('a', $text2)->href($this->link('graveItem!'));
            $el1->add($el2);
            $this->presenter->flashMessage($el1, 'alert alert-warning');
            
                if($this->presenter->isAjax()){
                    $this->redrawControl();   
                    $this->presenter->redrawControl('script');
                }
                else {
                    $this->presenter->redirect('this');
                }
           
        } else {
            $this->redirect('Order:cartEmpty');
        }
    }

    /*
     * Handle for adding amount of goods
     */

    public function handleAddAmount($id) {
        if($id) {
        $mnt = $this->cart->prd[$id];
        $mnt += 1;
        $this->cart->prd[$id] = $mnt;
                
            if($this->presenter->isAjax()){
                $this->redrawControl();
                $this->presenter->redrawControl('script');
            }
            else {
                $this->presenter->redirect('this');
            }
        }
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

                    if($this->presenter->isAjax()){
                       $this->redrawControl();
                    }
                    else {
                        $this->presenter->redirect('this');
                    }
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
        $this->actionCart($id, 1);
    }
    
    public function render() {
        $this->template->setFile(__DIR__ . '/templates/cart.latte');
        if ($this->cart->numberItems > 0) {
            foreach ($this->cart->prd as $id => $amnt) {

                $amnt = $this->cart->prd[$id];
                $product2 = $this->productModel->loadProduct($id);

                $c2[$id][$amnt] = $product2;
            }
        
            $this->template->cart = $c2;     
       }
       $this->template->render();

    }
    
    public function renderSummary() {
        $this->template->setFile(__DIR__ . '/templates/cartSummary.latte');
        if ($this->cart->numberItems > 0) {
            foreach ($this->cart->prd as $id => $amnt) {

                $amnt = $this->cart->prd[$id];
                $product2 = $this->productModel->loadProduct($id);

                $c2[$id][$amnt] = $product2;
            }
        
            $this->template->cart = $c2;     
       }
       $this->template->render();

    }
}
