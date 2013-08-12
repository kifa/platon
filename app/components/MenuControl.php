<?php

use Nette\Application\UI;

/*
 * Menu Control component
 */

class MenuControl extends BaseControl {

    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $cart;
    private $categoryModel;
    private $productModel;
    private $blogModel;
    private $shopModel;
    private $usertracking;
    private $orderModel;



    public function setCart($cart) {
        $this->cart = $cart;
    }

    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setCategory($cat) {
        $this->categoryModel = $cat;

    }
    
    public function setBlog($blog) {
        $this->blogModel = $blog;

    }
    
    public function setProduct($pro) {
        $this->productModel = $pro;
    }
    
    public function setUserTracking($usertracking) {
        $this->usertracking = $usertracking;
    }

    public function setOrder($order) {
        $this->orderModel = $order;
    }

    public function setShop($shop) {
        $this->shopModel = $shop;
    }
    
    public function createTemplate($class = NULL)
{
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
}
    
    
    private function getBread($catID) {
        $list = $this->categoryModel->loadCategory($catID);
        $menu[$catID][$catID] = $list->CategoryName;
        
        while ($list->HigherCategoryID){
            $list = $this->categoryModel->loadCategory($list->HigherCategoryID);
            $menu[$catID][$list->CategoryID] = $list->CategoryName;
        }
        
        /* @var $menu array */
        $menu = array_reverse($menu[$catID], TRUE);
        return $menu;
        

    }

    private function loadStaticMenu() {
         if($this->parent->getUser()->isInRole('admin')){
             return $this->shopModel->loadStaticText(''); 
         }
         else {
             return $this->shopModel->loadActiveStaticText(''); 
         }
       
    }
    
    public function handleSetShopInfo($layout, $id) {
        if ($this->parent->getUser()->isInRole('admin')) {   
              $this->shopModel->setShopInfo($layout, $id);
                 $this->redirect('this');
          }
    }
    
    public function renderAdmin() {
        if($this->parent->getUser()->isInRole('admin')){
        $this->template->setFile(__DIR__.'/MenuAdminControl.latte');
        $this->template->category = $this->categoryModel->loadCategoryListAdmin(); 
        $this->template->render();
        }
    }
    
    

    public function render($img) {
        $this->template->setFile(__DIR__ . '/MenuControl.latte');
        $this->template->cart = $this->cart->numberItems;
       
        if($this->parent->getUser()->isInRole('admin')){
            $this->template->category = $this->categoryModel->loadCategoryListAdmin(); 
        }
        else {
            $this->template->category = $this->categoryModel->loadCategoryList();  
        }
        $this->template->producers = $this->productModel->loadProducers();
      //  $this->template->menuItems = $this->ShopModel->getMenu();
        $this->template->img = $img;
        $this->template->menu = $this->loadStaticMenu();
        $this->template->lang = $this->lang;
        $this->template->render();
    }
    
    public function renderBread($catID, $id=null) {
        $this->template->setFile(__DIR__ . '/MenuBreadControl.latte');
        $this->template->category = $this->getBread($catID);
        if ($id) {
        $this->template->product = $this->productModel->loadProduct($id);
        }
        else {
           $this->template->product = null;  
        }
        $this->template->render();
    }
    
    public function renderBreadBlog($catID, $id=null) {
        $this->template->setFile(__DIR__ . '/MenuBreadBlogControl.latte');
        $this->template->category = $this->getBread($catID);
        if ($id) {
        $this->template->blog = $this->blogModel->loadPost($id);
        }
        else {
           $this->template->blog = null;  
        }
        $this->template->render();
    }
    
    
    public function renderTop() {
        $this->template->setFile(__DIR__ . '/MenuTopControl.latte');
        $this->template->cart = $this->cart->numberItems;
        $news = $this->orderModel->loadUnreadOrdersCount($this->usertracking->date);
        
        $this->template->news = $news;
        $this->template->render();
    }
    
    public function renderFooter() {
        $this->template->setFile(__DIR__ . '/MenuFooterControl.latte');
        $this->template->menu = $this->loadStaticMenu();
        $this->template->render();
    }

    public function renderSide() {
        $this->template->setFile(__DIR__ . '/MenuSideControl.latte');
        $this->template->menu = $this->loadStaticMenu();
        $this->template->render();
    }
}
