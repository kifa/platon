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
    private $category;



    public function setCart($cart) {
        $this->cart = $cart;
    }

    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setCategory($cat) {
        $this->category = $cat;

    }

    public function createTemplate($class = NULL)
{
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
}
    
    
    private function getBread($catID) {
        $list = $this->category->loadCategory($catID);
        $menu[$catID][$catID] = $list->CategoryName;
        
        while ($list->HigherCategoryID){
            $list = $this->category->loadCategory($list->HigherCategoryID);
            $menu[$catID][$list->CategoryID] = $list->CategoryName;
        }
        
        /* @var $menu array */
        $menu = array_reverse($menu[$catID], TRUE);
        return $menu;
        

    }

    public function renderAdmin() {
        if($this->parent->getUser()->isLoggedIn()){
        $this->template->setFile(__DIR__.'/MenuAdminControl.latte');
        $this->template->category = $this->category->loadCategoryList(); 
        $this->template->render();
        }
    }
    
    public function render() {
        $this->template->setFile(__DIR__ . '/MenuControl.latte');
        $this->template->cart = $this->cart->numberItems;
        $this->template->category = $this->category->loadCategoryList(); 
      //  $this->template->menuItems = $this->ShopModel->getMenu();
        $this->template->render();
    }
    
    public function renderBread($catID) {
        $this->template->setFile(__DIR__ . '/MenuBreadControl.latte');
        $this->template->category = $this->getBread($catID); 
        $this->template->render();
    }

}
