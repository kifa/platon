<?php

use Nette\Application\UI;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class BaseControl extends UI\Control {
    
    /** @persistent */
    public $locale;

    /** @var NetteTranslator\Gettext */
    protected $translator;

    protected function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->registerHelperLoader(callback($this->translator->createTemplateHelpers(), 'loader'));

    return $template;
    }
}
