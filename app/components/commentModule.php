<?php

use Nette\Application\UI;

/*
 * Menu Control component
 */

class commentModule extends moduleControl {

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
    
    
   public function render($id) {
        $this->template->setFile(__DIR__ . '/commentModule.latte');
        $this->template->id = $id;
        $this->template->render();
    }
    
}
