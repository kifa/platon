<?php

use Nette\Application\UI;

/*
 * Menu Control component
 */

class MenuControl extends UI\Control {

    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    
       
     public function __construct($translator)
    {
        $this->translator = $translator;
        
    }
    
    public function createTemplate($class = NULL)
{
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
}
    
    public function render() {
        $this->template->setFile(__DIR__ . '/MenuControl.latte');
        
      //  $this->template->menuItems = $this->ShopModel->getMenu();
        $this->template->render();
    }

}
