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
        return $this->blogModel->loadPosts(100);
    }
    
    public function renderAdmin() {
        if($this->parent->getUser()->isLoggedIn()){
        $this->template->setFile(__DIR__.'/MenuAdminControl.latte');
        $this->template->category = $this->categoryModel->loadCategoryList(); 
        $this->template->render();
        }
    }
    
    

        public function render() {
        $this->template->setFile(__DIR__ . '/MenuControl.latte');
        $this->template->cart = $this->cart->numberItems;
        $this->template->category = $this->categoryModel->loadCategoryList(); 
        $this->template->producers = $this->productModel->loadProducers();
      //  $this->template->menuItems = $this->ShopModel->getMenu();
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
