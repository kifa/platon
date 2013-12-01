<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;

/**
 * Description of productEditControl
 *
 * @author Lukas
 */
class visitedProductControl extends BaseControl{
    
     /** @persistent */
    public $locale;

    /** @var GettextTranslator\Gettext */
    protected $translator;
    private $productModel;

    public function __construct(\ProductModel $productModel, \Kdyby\Translation\Translator $translator) {
        $this->productModel = $productModel;
        $this->translator = $translator;
    }

        public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
    }
    
     
    
    public function render($visited) {
        $this->template->setFile(__DIR__ . '/templates/visitedProduct.latte');
        $this->template->product = $visited;
        $this->template->render();
    }
}