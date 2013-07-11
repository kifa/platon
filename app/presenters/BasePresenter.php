<?php

use Nette\Utils\Html;
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
    private $productModel;
    private $blogModel;
    private $cart;

    private $orderModel;


    public $backlink;
    
    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;

    
    protected function startup() {
        parent::startup();
        $this->shopModel = $this->context->shopModel;
        $this->categoryModel = $this->context->categoryModel;
        $this->orderModel = $this->context->orderModel;
        $this->blogModel = $this->context->blogModel;
        $this->cart = $this->getSession('cart');
    }
    
    public function injectProductModel(ProductModel $productModel) {
        $this->productModel = $productModel;
    }
    
    public function handleAddToCart($product, $amnt) {
     
       $row = $this->productModel->loadProduct($product);
        if (!$row || !$product) {
             if ($this->cart->numberItems > 0) {
                $this->setView('Cart');
            } else {
                $this->setView('CartEmpty');
            }
        }
        else {
            if (isset($this->cart->prd[$product])) {
                $mnt = $this->cart->prd[$product];
                $mnt += $amnt;
                $this->cart->prd[$product] = $mnt;
            } else {
                $this->cart->prd[$product] = $amnt;
            }
            $this->cart->lastItem = $product;
            $this->cart->numberItems = Count($this->cart->prd);

            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' ' . $row->ProductName . ' was successfully added to your cart. ');
            $link = HTML::el('a', 'Proceed to checkout ')->href($this->link('Order:cart'))->class('btn btn-primary btn-small');
            $ico2 = HTML::el('i')->class('icon-arrow-right right');
            $message->insert(0, $ico);
            $message->insert(2, $link);
            $link->insert(2, $ico2);
            
            $this->flashMessage($message, 'alert alert-success');
           $this->redirect('this');
       
        }
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
       
        $this->template->shopName = $this->shopModel->getShopInfo('Name');
        $this->template->shopDescription = $this->shopModel->getShopInfo('Description');
        $this->template->shopLogo = $this->shopModel->getShopInfo('Logo');
        // set theme layout
        $this->setLayout($this->shopModel->getShopInfo('CatalogLayout'));
        
        if ($this->isAjax()) {
        $this->invalidateControl('flashMessages');
        }
        
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
        $menuControl->setProduct($this->productModel);
        $menuControl->setBlog($this->blogModel);
        $menuControl->setTranslator($this->translator);
        $menuControl->setShop($this->shopModel);
        return $menuControl;
    }
    
    
    
    protected function createComponentModalControl() {
        $modalControl = new ModalControl();
        $modalControl->setTranslator($this->translator);
        $modalControl->setService($this->orderModel);
        return $modalControl;
    }
    
    protected function createComponentProductEdit() {
        $productEditControl = new productEditControl();
        $productEditControl->setTranslator($this->translator);
        $productEditControl->setProduct($this->productModel);
        $productEditControl->setCategory($this->categoryModel);
        $productEditControl->setBlog($this->blogModel);
        return $productEditControl;
    }
    
    protected function createComponentProduct() {
        $productControl = new productControl();
        $productControl->setTranslator($this->translator);
        $productControl->setProduct($this->productModel);
        $productControl->setCategory($this->categoryModel);
        $productControl->setBlog($this->blogModel);
        return $productControl;
    }
    
    protected function createComponentMail() {
        $mailControl = new mailControl();
        $mailControl->setTranslator($this->translator);
        $mailControl->setProduct($this->productModel);
        $mailControl->setCategory($this->categoryModel);
        $mailControl->setBlog($this->blogModel);
        $mailControl->setShop($this->shopModel);
        return $mailControl;
    }
    
    
    protected function createComponentAddCategoryForm() {
        if ($this->getUser()->isInRole('admin')) {

            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);

            foreach ($this->categoryModel->loadCategoryListAdmin() as $id => $category) {
                $categories[$id] = $category->CategoryName;
            }
            $prompt = Html::el('option')->setText("-- No Parent --")->class('prompt');

            $addForm->addText('name', 'Name:')
                    ->setRequired();
            
            $addForm->addSelect('parent', 'Parent Category:', $categories)
                    ->setPrompt($prompt);
            
            $addForm->addSubmit('add', 'Create Category')
                    ->setAttribute('class', 'upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Creating...');
            $addForm->onSuccess[] = $this->addCategoryFormSubmitted;
            return $addForm;
        }
    }

    public function addCategoryFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            $row = $this->categoryModel->createCategory($form->values->name, NULL, $form->values->parent);
            $this->redirect('Product:products', $row);
        }
    }
       
    protected function createComponentAddStaticTextForm() {
        if ($this->getUser()->isInRole('admin')) {

            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);

            $addForm->addText('name', 'Name:')
                    ->setRequired();
          
            $addForm->addSubmit('add', 'Create')
                    ->setAttribute('class', 'upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Creating...');
            $addForm->onSuccess[] = $this->addStaticTextFormSubmitted;
            return $addForm;
        }
    }

    public function addStaticTextFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            $row = $this->shopModel->insertStaticText($form->values->name, '', 2);
            $this->productModel->insertPhotoAlbum($form->values->name, '', NULL, NULL, $row);
            $this->redirect('Blog:staticText', $row);
        }
    }
}