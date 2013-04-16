<?php

/**
 * Base presenter for eshop.
 * Presenting skeleton of shop - header - content link - footer
 * 
 * Rendering whole HEADER
 * Rendering whole FOOTER
 * Setting shop layout, css, scripts


 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {
    /*
     * @var shopModel
     * accessing info about shop like name, meta, shipping
     * 
     * @var categoryModel
     * accessing category model
     */

    private $shopModel;
    private $categoryModel;
    private $cart;

    
    public $backlink;
    
    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;

    
    protected function startup() {
        parent::startup();
        $this->shopModel = $this->context->shopModel;
        $this->categoryModel = $this->context->categoryModel;
        $this->cart = $this->getSession('cart');
    }

    /**
     * Inject translator
     * @param NetteTranslator\Gettext
     */
    public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }

   
    
    public function createTemplate($class = NULL) {
        $template = parent::createTemplate($class);

        // pokud není nastaven, použijeme defaultní z configu
        if (!isset($this->lang)) {
            $this->lang = $this->translator->getLang();
        }

        $this->translator->setLang($this->lang); // nastavíme jazyk
        $template->setTranslator($this->translator);

        return $template;
    }

    /*
     *  beforeRender()
     *  rendering info used on every page
     */

    public function beforeRender() {
        parent::beforeRender();

    }

    protected function createComponentBaseControl() {
            $base = new BaseControl();
            //$base->setTranslator($this->translator);
            return $base;
    }
    
    protected function createComponentMenu() {
        $menuControl = new MenuControl();
        $menuControl->setCart($this->cart);
        $menuControl->setCategory($this->categoryModel);
        $menuControl->setTranslator($this->translator);
        return $menuControl;
    }
    
    
    
    protected function createComponentModalControl() {
        $modalControl = new ModalControl();
        $modalControl->setTranslator($this->translator);
        return $modalControl;
    }
    
    
    
    
}