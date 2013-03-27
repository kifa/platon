<?php

use Nette\Application\UI;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class BaseControl extends UI\Control {
    
    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;


/*
    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function createTemplate($class = NULL)
{
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
}
    */
    
}
