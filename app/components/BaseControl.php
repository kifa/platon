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

    
}
