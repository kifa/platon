<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;

/**
 * Description of productEditControl
 *
 * @author Lukas
 */
class productControl extends BaseControl{
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $categoryModel;
    private $productModel;
    private $shopModel;

    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setCategory($cat) {
        $this->categoryModel = $cat;
    }
    
    public function setProduct($pro) {
        $this->productModel = $pro;
    }
    
    public function setShop($shop) {
        $this->shopModel = $shop;
    }
    
    
    public function __construct(\ShopModel $shopModel, \ProductModel $productModel, 
                                \CategoryModel $categoryModel, \GettextTranslator\Gettext $translator) {
        $this->shopModel = $shopModel;
        $this->productModel = $productModel;
        $this->categoryModel = $categoryModel;
        $this->translator = $translator;
    }

        public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
    }
    
     
    
       protected function createComponentAdminPanelControl() {
        $EditControl = new AdminPanelControl($this->productModel, $this->categoryModel, $this->translator );
        $this->addComponent($EditControl, 'adminPanelControl');
        return $EditControl;
       }

   /*****************************************************************
    * HANDLE
    */
    

    
    public function handleDeleteProduct($catID, $id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->productModel->deleteProduct($id);
            $this->redirect('this', $catID);
        }
    }

    public function handleArchiveProduct($catID, $id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->productModel->archiveProduct($id);
            $this->redirect('this', $catID);
        }
    }

     public function handleHideProduct($catID, $id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            
            $this->productModel->hideProduct($id);
            
            if($this->presenter->isAjax())
            {            
              $this->invalidateControl('products');
              $this->invalidateControl('script');
            }
            
            else {
              $this->redirect('this', $catID);
            }
        }
    }


    
    public function handleShowProduct($catID, $id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            try {
            $this->productModel->showProduct($id);
            $this->presenter->flashMessage('Product is successfully visible.', 'alert alert-success');
                if($this->presenter->isAjax()) {
                    $this->invalidateControl();
                    $this->invalidateControl('script');
                } else {
                
                }
            } catch (Exception $e) {
                Nette\Diagnostics\Debugger::log($e);
                $this->presenter->flashMessage('We are not able to unarchived this product. Try it later please.', 'alert alert-warning');
                
            }
            $this->presenter->redirect('this');
        }
    }
    
    public function handleUnarchiveProduct($catID, $id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            try {
            $this->productModel->showProduct($id);
            $this->presenter->flashMessage('Product was successfully back in catalog.', 'alert alert-success');
            
            } catch (Exception $e) {
                Nette\Diagnostics\Debugger::log($e);
                $this->presenter->flashMessage('We are not able to unarchived this product. Try it later please.', 'alert alert-warning');
                $this->presenter->redirect('this');
            }
            $this->presenter->redirect('Product:product', $id);
        }
    }
    
    
    
   
    /*********************************************************************
     * komponenty
     */
    
    /***********************************************************************
     * RENDERY
     */
    
    public function renderFourinline($product) {
        
        $albumID = $this->productModel->loadPhotoAlbumID($product['ProductID']);
        if($albumID){            
            $albumID->PhotoAlbumID;
        }
        $this->template->setFile(__DIR__ . '/templates/fourinline.latte');   
        $this->template->pieces = $this->productModel->loadTotalPieces($product['ProductID']);
        $this->template->product = $product;
        $this->template->albumID = $albumID['PhotoAlbumID'];
        $this->template->photo = $this->productModel->loadCoverPhoto($product['ProductID']);

        $this->template->render();
    }
    
    public function renderTwoinline($product) {
        
        $albumID = $this->productModel->loadPhotoAlbumID($product['ProductID']);
        if($albumID){
            $albumID->PhotoAlbumID;
        }
        $this->template->setFile(__DIR__ . '/templates/twoinline.latte');   
        $this->template->pieces = $this->productModel->loadTotalPieces($product['ProductID']);
        $this->template->product = $product;
        $this->template->albumID = $albumID['PhotoAlbumID'];
        $this->template->photo = $this->productModel->loadCoverPhoto($product['ProductID']);

        $this->template->render();
    }
    
    public function renderSingleton($product) {
        
        $albumID = $this->productModel->loadPhotoAlbumID($product['ProductID']);
        if($albumID){
            $albumID->PhotoAlbumID;
        }
        $this->template->setFile(__DIR__ . '/templates/singleton.latte');   
        $this->template->pieces = $this->productModel->loadTotalPieces($product['ProductID']);
        $this->template->product = $product;
        $this->template->albumID = $albumID['PhotoAlbumID'];
        $this->template->photo = $this->productModel->loadCoverPhoto($product['ProductID']);

        $this->template->render();
    }
    
    public function renderBigphoto($product) {
        
        $albumID = $this->productModel->loadPhotoAlbumID($product['ProductID']);
        if($albumID){
            $albumID->PhotoAlbumID;
        }       
                
        $this->template->setFile(__DIR__ . '/templates/bigphoto.latte');   
        $this->template->pieces = $this->productModel->loadTotalPieces($product['ProductID']);
        $this->template->product = $product;
        $this->template->albumID = $albumID['PhotoAlbumID'];
        $this->template->photo = $this->productModel->loadCoverPhoto($product['ProductID']);

        $this->template->render();
    }
    
    public function renderArchived($product) {
        $this->template->setFile(__DIR__ . '/templates/archive.latte');
        $this->template->product = $product;
        $this->template->render();
    }
}