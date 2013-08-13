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

    private $categoryModel;
    private $productModel;
    private $blogModel;
    private $shopModel;




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
    
    
   public function render($img) {
        $this->template->setFile(__DIR__ . '/commentControl.latte');
       
       
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
        if($this->presenter->getUser()->isInRole('admin')){
        $news = $this->orderModel->loadUnreadOrdersCount($this->usertracking->date);
        $this->template->news = $news;
        }
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
