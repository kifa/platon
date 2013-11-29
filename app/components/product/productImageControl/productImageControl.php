<?php


use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;


class productImageControl extends BaseControl {

    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    
     public function __construct(\ShopModel $shopModel, \ProductModel $productModel, 
                                \GettextTranslator\Gettext $translator) {
        $this->shopModel = $shopModel;
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
    
    public function renderCover() {
        $this->template->setFile(__DIR__.'/templates/cover.latte');
        $this->template->render();
    }
    
    public function renderGallery() {
        $this->template->setFile(__DIR__.'/templates/gallery.latte');
        $this->template->render();
    }
}