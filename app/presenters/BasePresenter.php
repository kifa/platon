<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;

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
    protected $usertracking;
    protected $gapisession;
    private $filter;

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
        $salt = $this->shopModel->getShopInfo('Salt');
        $this->cart = $this->getSession('cart'.$salt);
        
        
        if(!isset($this->filter)){
        $this->filter = $this->getSession('filter'.$salt);
        $this->filter->sort = NULL;
        }
        
        
        $salt = md5($this->getUser()->getId());
        $this->usertracking = $this->getSession('user'.$salt);
        
        if(!isset($this->gapisession)){
        $this->gapisession = $this->getSession('gapitoken');
        }
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
            
            if($this->isAjax()) {
                $this->invalidateControl('cart');
                $this->invalidateControl('products');  
                $this->invalidateControl('variants'); 
                $this->invalidateControl('script');
            }
            else {
                $this->redirect('this');
            }
       
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
        
        $shopInfo = $this->shopModel->getShopSettings();
        
        $this->template->shopName = $shopInfo['Name']->Value;
        $this->template->shopDescription = $shopInfo['Description']->Value;
        $this->template->shopLogo = $shopInfo['Logo']->Value;
        $this->template->GA = $shopInfo['GA']->Value;
        $this->template->lang = $this->lang;
        
         $this->template->bannerone = $this->shopModel->loadBannerByType('banner1');
         
        

       
        // set theme layout
        $this->setLayout($shopInfo['ShopLayout']->Value);
        
        $this->template->menuTop = $shopInfo['TopMenu']->Value;
        $this->template->menuSide = $shopInfo['SideMenu']->Value;
        $this->template->menuFooter = $shopInfo['FooterMenu']->Value;
        $this->template->productMiniLayout = $shopInfo['ProductMiniLayout']->Value;
        
        
        if ($this->isAjax()) {
        $this->invalidateControl('flashMessages');
        }
        
          if ($this->getUser()->isInRole('admin')) {   
        $menuSwitcher = $this['menuSwitcherForm'];
        $menuSwitcher->setDefaults(array('topMenu' => $shopInfo['TopMenu']->Value,
                                         'sideMenu' =>$shopInfo['SideMenu']->Value,
                                         'footerMenu' =>  $shopInfo['FooterMenu']->Value));
        $this->template->style = $shopInfo['Style']->Value;
        $this->template->slider = NULL;

          }
    }
    
    
   public function handleSetShopLayout($layout, $value) {
        if ($this->getUser()->isInRole('admin')) {   
              $this->shopModel->setShopInfo($layout, $value);
                 $this->redirect('this');
          }
    }

    protected function createComponentBaseControl() {
            $base = new BaseControl();
            //$base->setTranslator($this->translator);
            return $base;
    }
    
    public function createComponentCss() {
    // připravíme seznam souborů
    // FileCollection v konstruktoru může dostat výchozí adresář, pak není potřeba psát absolutní cesty
    $style = $this->shopModel->getShopInfo('Style');
    if($style == NULL) {
        $style='no.css';
    }
    $wwwDir = $this->context->parameters['wwwDir'];
    $files = new \WebLoader\FileCollection($wwwDir . '/css');
    $files->addFiles(array(
        'bootstrap.min.css',
        'font-awesome-ie7.min.css',
        'font-awesome.min.css',
        '/user/theme.css',
        '/themes/'.$style,
        'jquery.wysiwyg.css',
        'flag.css'
    ));

    // kompilátoru seznam předáme a určíme adresář, kam má kompilovat
    $compiler = \WebLoader\Compiler::createCssCompiler($files, $wwwDir . '/webtemp');

    // nette komponenta pro výpis <link>ů přijímá kompilátor a cestu k adresáři na webu
    return new \WebLoader\Nette\CssLoader($compiler, $this->template->basePath . '/webtemp');
    }
    
    public function createComponentJs() {
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
    
    protected function createComponentMenu() {
        $menuControl = new MenuControl();
        $menuControl->setCart($this->cart);
        $menuControl->setCategory($this->categoryModel);
        $menuControl->setProduct($this->productModel);
        $menuControl->setBlog($this->blogModel);
        $menuControl->setTranslator($this->translator);
        $menuControl->setShop($this->shopModel);
        $menuControl->setOrder($this->orderModel);
        $menuControl->setUserTracking($this->usertracking);
        return $menuControl;
    }
    
    
    
    protected function createComponentModalControl() {
        $modalControl = new ModalControl();
        $modalControl->setTranslator($this->translator);
        $modalControl->setService($this->orderModel);
        return $modalControl;
    }
    
    protected function createComponentAdminPanelControl() {
        $EditControl = new AdminPanelControl();
        $EditControl->setTranslator($this->translator);
        $EditControl->setProduct($this->productModel);
        $EditControl->setCategory($this->categoryModel);
        $EditControl->setBlog($this->blogModel);
        return $EditControl;
    }
    
    protected function createComponentProduct() {
        $productControl = new productControl();
        $productControl->setTranslator($this->translator);
        $productControl->setProduct($this->productModel);
        $productControl->setCategory($this->categoryModel);
        $productControl->setShop($this->shopModel);

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
    
    protected function createComponentSmartPanelBar() {
        $smartPanelBar = newSmartPanelBarControl();
        $smartPanelBar->setTranslator($this->translator);
        $smartPanelBar->setProduct($this->productModel);
        $smartPanelBar->setCategory($this->categoryModel);
        $smartPanelBar->setBlog($this->blogModel);
        $smartPanelBar->setShop($this->shopModel);
        return $smartPanelBar;
    }

    
    protected function createComponentModuleControl() {
        $moduleControl = new moduleControl;
        $moduleControl->setTranslator($this->translator);
        $moduleControl->setProduct($this->productModel);
        $moduleControl->setCategory($this->categoryModel);
        $moduleControl->setShop($this->shopModel);
        $moduleControl->setOrder($this->orderModel);
        $moduleControl->setGapi($this->gapisession);
        
        return $moduleControl;
    
    }
    
    protected function createComponentMenuSwitcherForm() {
        if ($this->getUser()->isInRole('admin')) {

            $menus = array('Category' => 'Category', 'Info' =>'Info',
                            'All' => 'All','Cart'=> 'Cart', 'Producer' => 'Producer');
            
            $menuSwitcherForm = new Nette\Application\UI\Form;
            $menuSwitcherForm->setTranslator($this->translator);
            $menuSwitcherForm->addSelect('topMenu', 'Top Main Menu', $menus)
                    ->setAttribute('class', 'form-control');
            $menuSwitcherForm->addSelect('sideMenu', 'Side Menu', $menus)
                    ->setAttribute('class', 'form-control');
            $menuSwitcherForm->addSelect('footerMenu', 'Footer Menu', $menus)
                    ->setAttribute('class', 'form-control');
            $menuSwitcherForm->addSubmit('save', 'Set menus')
                    ->setAttribute('class', 'form-control upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Setting...');;
            $menuSwitcherForm->onSuccess[] = $this->menuSwitcherFormSubmitted;
            return $menuSwitcherForm;
        }
    }


    public function menuSwitcherFormSubmitted($form) {
         if ($this->getUser()->isInRole('admin')) {
             $this->shopModel->setShopInfo('TopMenu', $form->values->topMenu);
             $this->shopModel->setShopInfo('SideMenu', $form->values->sideMenu);
             $this->shopModel->setShopInfo('FooterMenu', $form->values->footerMenu);
             
             $this->redirect('this');
         }
    }

     protected function createComponentUploadStyleForm() {
        if ($this->getUser()->isInRole('admin')) {
            
            $addStyle = new Nette\Application\UI\Form;
            $addStyle->setTranslator($this->translator);
            $addStyle->addUpload('style', 'Select your style.css');
            $addStyle->addSubmit('upload', 'Upload')
                    ->setAttribute('class', 'upl btn btn-primary form-control')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addStyle->onSuccess[] = $this->uploadStyleFormSubmitted;
            return $addStyle;
        }
     }
     
     public function uploadStyleFormSubmitted($form) {
          if ($this->getUser()->isInRole('admin')) {
           if($form->values->style->isOK()) {
               
               $this->shopModel->setShopInfo('Style', $form->values->style->name);
               $styleUrl = $this->context->parameters['wwwDir'] . '/css/themes/' . $form->values->style->name;
               $form->values->style->move($styleUrl);
           }
           $this->redirect('this');
          }
     }
     
     public function handleDeleteStyle($name) {
         if ($this->getUser()->isInRole('admin')) {
             $styleUrl = $this->context->parameters['wwwDir'] . '/css/themes/' . $name;
             $this->shopModel->setShopInfo('Style', '');
            if ($styleUrl) {
                unlink($styleUrl);
            }
            
            $this->redirect('this');
         }
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
    
    protected function createComponentSearchControl() {
        $searchControl = new SearchControl();
        $searchControl->setTranslator($this->translator);
        return $searchControl;
    }
    
    
    
    public function createComponentAddBannerForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addUpload('bannerone', 'Photo:')
                     ->addCondition(Form::FILLED)     
                    ->addRule(FORM::IMAGE, 'You can upload only JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Max 2MB', 6400 * 1024);
            $items = array(1 => 'first banner', 2 => 'second banner', 3 => 'third banner', 4 => 'four banner');
            $addPhoto->addSelect('banner', 'Banner pos.', $items)
                    ->setDefaultValue(1)
                    ->setAttribute('class', 'form-control');
            $addPhoto->addText('link', 'Insert link')
                    ->setAttribute('placeholder', 'http://')
                    ->setAttribute('class', 'form-control');
            $addPhoto->addSubmit('add', 'Add Banner')
                    ->setAttribute('class', 'form-control btn btn-primary upl col-md-6')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addPhoto->onSuccess[] = $this->addBannerFormSubmitted;
            return $addPhoto;
        }
    }
    
     public function addBannerFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            if ($form->values->bannerone->isOK()) {

                if($this->shopModel->loadBannerByType('banner'.$form->values->banner)) {
                    $this->shopModel->updateBannerByType('banner'.$form->values->banner, $form->values->bannerone->name, $form->values->link);
                } else {
                    $this->shopModel->insertBanner('banner'.$form->values->banner, $form->values->bannerone->name, $form->values->link);
                }
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/banner/' . $form->values->bannerone->name;
                $form->values->bannerone->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                
                if($image->width > 380) {

                    $image->resize(380, null, Image::SHRINK_ONLY);
                    $imgUrl = $this->context->parameters['wwwDir'] . '/images/banner/' . $form->values->bannerone->name;
                    $image->save($imgUrl);
                }
  
                    
                $message = $this->translator->translate(' was sucessfully uploaded');
                $photo = $this->translator->translate(' Photo ');
                $e = HTML::el('span', $photo . $form->values->bannerone->name . '' . $message);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert alert-success');
            }
            
            else {
               if($this->shopModel->loadBannerByType('banner'.$form->values->banner)) {
                    $this->shopModel->updateBannerByType('banner'.$form->values->banner, NULL, $form->values->link);
                }
            }
            
            $this->redirect('this');
        }
    }
    
   public function createComponentAddSliderForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addUpload('slideone', 'Photo:')
                     ->addCondition(Form::FILLED)     
                    ->addRule(FORM::IMAGE, 'You can upload only JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Max 2MB', 6400 * 1024);
            $items = array(1 => 'first slide', 2 => 'second slide', 3 => 'third slide');
            $addPhoto->addSelect('slide', 'Slider list', $items)
                    ->setDefaultValue(1)
                    ->setAttribute('class', 'form-control');
            $addPhoto->addText('link', 'Insert link')
                    ->setAttribute('placeholder', 'http://')
                    ->setAttribute('class', 'form-control');
            $addPhoto->addSubmit('add', 'Add Photo')
                    ->setAttribute('class', 'form-control btn btn-primary upl col-md-6')
                    ->setAttribute('data-loading-text', 'Uploading...');            
            $addPhoto->onSuccess[] = $this->addSliderFormSubmitted;
            return $addPhoto;
        }
    }

    public function addSliderFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            if ($form->values->slideone->isOK()) {

                if($this->shopModel->loadBannerByType('slider'.$form->values->slide)) {
                    $this->shopModel->updateBannerByType('slider'.$form->values->slide, $form->values->slideone->name, $form->values->link);
                } else {
                    $this->shopModel->insertBanner('slider'.$form->values->slide, $form->values->slideone->name, $form->values->link);
                }
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/slider/' . $form->values->slideone->name;
                $form->values->slideone->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                
                if($image->width > 1140) {

                    $image->resize(1140, null, Image::SHRINK_ONLY);
                    $imgUrl = $this->context->parameters['wwwDir'] . '/images/slider/' . $form->values->slideone->name;
                    $image->save($imgUrl);
                }

            
                $message = $this->translator->translate(' was sucessfully uploaded');
                $photo = $this->translator->translate(' Photo ');
                $e = HTML::el('span', $photo . $form->values->slideone->name . '' . $message);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert alert-success');
            }
            
            $this->redirect('this');
        }
    }
    
    protected function createComponentAddLogoForm() {
         if ($this->getUser()->isInRole('admin')) {
        $addLogo = new Nette\Application\UI\Form;
        $addLogo->setTranslator($this->translator);
        $addLogo->addUpload('logo', 'Select your logo')
                 ->addRule(FORM::IMAGE, 'Supported files are JPG, PNG a GIF')
                 ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024)
                ->setAttribute('class', 'form-control');
        $addLogo->addSubmit('upload', 'Upload')
                ->setAttribute('class', 'form-control');
        $addLogo->onSuccess[] = $this->addLogoFormSubmitted;
        return $addLogo;
         }
    }
    
    public function addLogoFormSubmitted($form){
        if ($this->getUser()->isInRole('admin')) {
            
            if($form->values->logo->isOK()){
                
                $this->shopModel->setShopInfo('Logo', $form->values->logo->name);
                
                $logoURL = $this->context->parameters['wwwDir']  . '/images/logo/' . $form->values->logo->name;
                $form->values->logo->move($logoURL);
                
                $logo = Image::fromFile($logoURL);
                $logo->resize(null, 300, Image::SHRINK_ONLY);
                
                $logoUrl = $this->context->parameters['wwwDir'] . '/images/logo/300-' . $form->values->logo->name;
                $logo->save($logoUrl);  
                
                $logo->resize(null, 90, Image::SHRINK_ONLY);
                
                $logoUrl = $this->context->parameters['wwwDir'] . '/images/logo/90-' . $form->values->logo->name;
                $logo->save($logoUrl); 
            }
            
            $this->redirect('this');
        }
    }
    
    
   
}