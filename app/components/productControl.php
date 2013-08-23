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
    private $blogModel;
    private $shopModel;


    private $parameters;

    private $row;

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
    
    
    public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
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
            $this->productModel->showProduct($id);
            
            if($this->presenter->isAjax()) {
                $this->invalidateControl();
                $this->invalidateControl('script');
            }
            else {
            $this->redirect('this', $catID);
            }
        }
    }
    
    
    public function handleCoverPhoto($id, $photo) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $row = $this->productModel->loadPhoto($photo);
            if (!$row) {
                $this->flashMessage('There is no photo to set as cover', 'alert');
            } else {
                $this->productModel->updateCoverPhoto($id, $photo);
                $e = 'Photo ' . $row->PhotoName . ' was sucessfully set as COVER.';

                $this->productModel->coverPhoto($id);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('Product:product', $id);
        }
    }
    
   
    /*********************************************************************
     * komponenty
     */
    
    /***********************************************************************
     * RENDERY
     */
    
    public function renderProductMini($product) {
        $layout = $this->shopModel->getShopInfo('ProductMiniLayout');
       
        $albumID = $this->productModel->loadPhotoAlbumID($product['ProductID']);
        if($albumID){
            $albumID->PhotoAlbumID;
        }
        $this->template->setFile(__DIR__ . '/' . $layout . '.latte');    
        $this->template->product = $product;
        $this->template->albumID = $albumID;
        $this->template->photo = $this->productModel->loadCoverPhoto($product['ProductID']);

        $this->template->render();
    }
    
}