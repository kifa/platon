<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
use Nette\Utils\Finder;
 
/**
 * Base presenter for eshop.
 * Presenting skeleton of shop - header - content link - footer
 * 
 * Rendering whole HEADER
 * Rendering whole FOOTER
 * Setting shop layout, css, scripts
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

    private $shopModel;
    private $categoryModel;
    private $productModel;
    private $catalogModel;
    private $blogModel;
    private $userModel;
    private $cart;
    protected $usertracking;
    protected $gapisession;
    private $filter;
    private $orderModel;
    public $backlink;

    /** @persistent */
    public $locale;

    /** @var NetteTranslator\Gettext */
    protected $translator;

    protected function startup() {
        parent::startup();
        $salt = $this->shopModel->getShopInfo('Salt');
        $this->cart = $this->getSession('cart' . $salt);


        if (!isset($this->filter)) {
            $this->filter = $this->getSession('filter' . $salt);
            $this->filter->sort = NULL;
        }


        $salt = md5($this->getUser()->getId());
        $this->usertracking = $this->getSession('user' . $salt);

        if (!isset($this->gapisession)) {
            $this->gapisession = $this->getSession('gapitoken');
        }
        
        
//        $this->context->exchangeExtension->registerAsHelper($this->template);
    }

    public function injectProductModel(ProductModel $productModel) {
        $this->productModel = $productModel;
    }

    public function injectCategoryModel(CategoryModel $categoryModel) {
        $this->categoryModel = $categoryModel;
    }

    public function injectOrderModel(OrderModel $orderModel) {
        $this->orderModel = $orderModel;
    }

    public function injectBlogModel(BlogModel $blogModel) {
        $this->blogModel = $blogModel;
    }

    public function injectShopModel(ShopModel $shopModel) {
        $this->shopModel = $shopModel;
    }

    public function injectUserModel(UserModel $userModel) {
        $this->userModel = $userModel;
    }
    
    public function injectCatalogModel(CatalogModel $catalogModel) {
        $this->catalogModel = $catalogModel;
    }

    /**
     * Inject translator
     * @param NetteTranslator\Gettext
     */
    public function injectTranslator(\Kdyby\Translation\Translator $translator) {
        $this->translator = $translator;
    }

    public function createTemplate($class = NULL)
    {
        $template = parent::createTemplate($class);
        $template->registerHelperLoader(callback($this->translator->createTemplateHelpers(), 'loader'));

        return $template;
    }

    /*
     *  beforeRender()
     *  rendering info used on every page
     */

    public function beforeRender() {
        parent::beforeRender();

        $shopInfo = $this->shopModel->getShopSettings();

        $this->template->shopName = $shopInfo['Name'];
        $this->template->shopDescription = $shopInfo['Description'];
        $this->template->shopLogo = $shopInfo['Logo'];
        $this->template->GA = $shopInfo['GA'];
        $this->template->locale = $this->locale;

        $this->template->bannerone = $this->shopModel->loadBannerByType('banner1');

        // set theme layout
        $this->setLayout($shopInfo['ShopLayout']);

        $this->template->menuTop = $shopInfo['TopMenu'];
        $this->template->menuSide = $shopInfo['SideMenu'];
        $this->template->menuFooter = $shopInfo['FooterMenu'];
        $this->template->productMiniLayout = $shopInfo['ProductMiniLayout'];


        if ($this->isAjax()) {
            $this->invalidateControl('flashMessages');
        }
        
    }

    public function handleAddToCart($product, $amnt) {

        $row = $this->productModel->loadProduct($product);
        if (!$row || !$product) {
            if ($this->cart->numberItems > 0) {
                $this->setView('Cart');
            } else {
                $this->setView('CartEmpty');
            }
        } else {
            if (isset($this->cart->prd[$product])) {
                $mnt = $this->cart->prd[$product];
                $mnt += $amnt;
                $this->cart->prd[$product] = $mnt;
            } else {
                $this->cart->prd[$product] = $amnt;
            }
            $this->cart->lastItem = $product;
            $this->cart->numberItems = Count($this->cart->prd);

            $mess = ' was successfully added to your cart. ';
            $mess = $this->translator->translate($mess);
            $mess2 = 'Proceed to checkout ';
            $mess2 = $this->translator->translate($mess2);
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' ' . $row->ProductName . '' . $mess);

            $link = HTML::el('a', $mess2)->href($this->link('Order:cart'))->class('btn btn-danger btn-small');
            $ico2 = HTML::el('i')->class('icon-arrow-right right');
            $message->insert(0, $ico);
            $message->insert(2, $link);
            $link->insert(2, $ico2);

            $this->flashMessage($message, 'alert alert-success');

            if ($this->isAjax()) {
                $this->invalidateControl('cart');
                $this->invalidateControl('products');
                $this->invalidateControl('variants');
                $this->invalidateControl('script');
            } else {
                $this->redirect('this');
            }
        }
    }

    protected function createComponentBaseControl() 
    {
        $base = new BaseControl();
        $this->addComponent($base, 'baseControl');
        return $base;
    }

    public function createComponentCss() 
    {
        // připravíme seznam souborů
        // FileCollection v konstruktoru může dostat výchozí adresář, pak není potřeba psát absolutní cesty
        $style = $this->shopModel->getShopInfo('Style');
        if ($style == NULL) {
            $style = 'no.css';
        }
        $wwwDir = $this->context->parameters['wwwDir'];
        $files = new \WebLoader\FileCollection($wwwDir . '/css');
   //     $files->addFiles(Finder::findFiles('*.css')->from($wwwDir . '/css'));
        $files->addFiles(array(
            'bootstrap.min.css',
            'font-awesome-ie7.min.css',
            'font-awesome.min.css',
            '/user/theme.css',
            '/themes/' . $style,
            'jquery.wysiwyg.css',
            'flag.css'
        ));

        // kompilátoru seznam předáme a určíme adresář, kam má kompilovat
        $compiler = \WebLoader\Compiler::createCssCompiler($files, $wwwDir . '/webtemp');

        // nette komponenta pro výpis <link>ů přijímá kompilátor a cestu k adresáři na webu
        return new \WebLoader\Nette\CssLoader($compiler, $this->template->basePath . '/webtemp');
    }

    public function createComponentJs() 
    {
        $wwwDir = $this->context->parameters['wwwDir'];
        $files = new \WebLoader\FileCollection($wwwDir . '/js');
        // můžeme načíst i externí js
        // $files->addRemoteFile('http://code.jquery.com/jquery-1.10.1.min.js');
        //$files->addRemoteFile('http://code.jquery.com/jquery-migrate-1.2.1.min.js');
        $files->addFiles(array(
            'jquery.min.js',
            'jquery-migrate.min.js',
            'netteForms.js',
            'bootstrap.min.js',
            'live-form-validation.js',
            'nette.ajax.js',
            'main.js',
            'imgLiquid-min.js',
            'jquery.wysiwyg.js',
            'jquery.jeditable.mini.js',
            'jquery.jeditable.wysiwyg.js'));

        $compiler = \WebLoader\Compiler::createJsCompiler($files, $wwwDir . '/webtemp');

        return new \WebLoader\Nette\JavaScriptLoader($compiler, $this->template->basePath . '/webtemp');
    }

    protected function createComponentMenu() 
    {
        $menuControl = new MenuControl($this->categoryModel, $this->productModel, 
                                       $this->blogModel, $this->shopModel, $this->orderModel, $this->translator);
        $menuControl->setCart($this->cart);
        $menuControl->setUserTracking($this->usertracking);
        $this->addComponent($menuControl, 'menu');
        return $menuControl;
    }

    protected function createComponentModalControl() 
    {
        $modalControl = new ModalControl($this->orderModel, $this->translator);
        $this->addComponent($modalControl, 'modalControl');
        return $modalControl;
    }

    protected function createComponentAdminPanelControl() 
    {
        $EditControl = new AdminPanelControl($this->productModel, $this->categoryModel, $this->translator);
        $this->addComponent($EditControl, 'adminPanelControl');
        return $EditControl;
    }

    protected function createComponentProduct() 
    {
        $productControl = new productControl($this->shopModel, $this->productModel, $this->categoryModel, $this->translator);
        $this->addComponent($productControl, 'product');
        return $productControl;
    }

    protected function createComponentMail() 
    {
        $mailControl = new mailControl($this->translator);
        $this->addComponent($mailControl, 'mailControl');
        return $mailControl;
    }

  /*  protected function createComponentSmartPanelBar() 
    {
        $smartPanelBar = new SmartPanelBarControl();
        $smartPanelBar->setTranslator($this->translator);
        $smartPanelBar->setProduct($this->productModel);
        $smartPanelBar->setCategory($this->categoryModel);
        $smartPanelBar->setBlog($this->blogModel);
        $smartPanelBar->setShop($this->shopModel);
        return $smartPanelBar;
    }
*/
    protected function createComponentModuleControl() 
    {
        $moduleControl = new moduleControl;
        $moduleControl->setTranslator($this->translator);
        $moduleControl->setProduct($this->productModel);
        $moduleControl->setCategory($this->categoryModel);
        $moduleControl->setShop($this->shopModel);
        $moduleControl->setOrder($this->orderModel);
        $moduleControl->setGapi($this->gapisession);

        return $moduleControl;
    }

    protected function createComponentRedesignControl()
    {
        $redesign = new redesignControl($this->shopModel, $this->productModel, $this->translator);
        $this->addComponent($redesign, 'redesignControl');
        return $redesign;
    }

    protected function createComponentSearchControl()
    {
        $searchControl = new SearchControl($this->translator);
        $this->addComponent($searchControl, 'searchControl');
        return $searchControl;
    }
    
    protected function createComponentVisitedProduct()
    {
        $visited = new visitedProductControl($this->productModel, $this->translator);
        $this->addComponent($visited, 'visitedProduct');
        return $visited;
    }
    
    protected function createComponentProductImageControl()
    {
        $productImage = new productImageControl($this->shopModel, $this->productModel, $this->translator);
        $this->addComponent($productImage, 'productImage');
        return $productImage;
    }

}
