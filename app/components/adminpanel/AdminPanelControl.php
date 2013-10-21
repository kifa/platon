<?php

use Nette\Application\UI;
use Nette\Forms\Container;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminPanelControl extends BaseControl {
    
    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $categoryModel;
    private $productModel;
    private $blogModel;
    private $category;

    
    public function __construct(ProductModel $productModel, CategoryModel $categoryModel, NetteTranslator\Gettext $translator) {
        $this->productModel = $productModel;
        $this->categoryModel = $categoryModel;
        $this->translator = $translator;
        $this->category = $this->categoryModel->loadCategoryListAdmin();
    }

    
    
    public function createTemplate($class = NULL)
{
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);

    return $template;
}
    

   public function handleHideProduct($id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            
            $this->productModel->hideProduct($id);
            $this->presenter->flashMessage('Product sucessfully hidden', 'alert alert-success');
            
            if($this->presenter->isAjax())
            {            
              $this->parent->invalidateControl();
                $this->presenter->invalidateControl('products');
                $this->presenter->invalidateControl('script');
            }
            
            else {
              $this->presenter->redirect('this');
            }
        }
    }
    
    public function handleDeleteProduct($id, $catID) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->productModel->updateProduct($id, 'ProductStatusID', 0);
            if($this->presenter->isAjax()) {
               $this->parent->invalidateControl();
                $this->presenter->invalidateControl('products');
                $this->presenter->invalidateControl('script');
                
            }
            else {
            
            $this->redirect('Catalog:default', $catID);
            
            }
        }
    }

    public function handleShowProduct($id) {

        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->productModel->showProduct($id);
            $this->presenter->flashMessage('Product sucessfully published', 'alert alert-success');
            
            if($this->presenter->isAjax()) {
               $this->parent->invalidateControl();
                $this->presenter->invalidateControl('products');
                $this->presenter->invalidateControl('script');
                
            }
            else {
            $this->presenter->redirect('this');
            }
        }
    }
    
    public function handleSetProductStatus($id, $statusid) {
        if($this->presenter->getUser()->isInRole('admin')){ 
            if($this->presenter->isAjax())
            {            
                $this->productModel->updateProduct($id, 'ProductStatusID', $statusid);
                $this->parent->invalidateControl();
                $this->presenter->invalidateControl('products');
                $this->presenter->invalidateControl('script');
            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }
    
    public function handleSetProductCategory($id, $catid) {
        if($this->presenter->getUser()->isInRole('admin')){ 
            if($this->presenter->isAjax())
            {            
                $this->productModel->updateProduct($id, 'CategoryID', $catid);
                $this->parent->invalidateControl();
                $this->presenter->invalidateControl('products');
                $this->presenter->invalidateControl('bread');
                $this->presenter->invalidateControl('script');
                

            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }

    public function handleSetProductProducer($id, $producerid) {
        if($this->presenter->getUser()->isInRole('admin')){ 
            if($this->presenter->isAjax())
            {            
                $this->productModel->updateProduct($id, 'ProducerID', $producerid);
                $this->parent->invalidateControl();
                $this->presenter->invalidateControl('products');
                $this->presenter->invalidateControl('script');

            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }
  
    
    public function handleSetCategoryStatus($catID, $categoryStatus) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->categoryModel->setCategoryStatus($catID, $categoryStatus);
            $status = $this->categoryModel->getStatusName($categoryStatus);
          //  $status = $categoryStatus;
            $text = $this->translator->translate('Category status is now: ');
            $e = $text . $status;
            $this->presenter->flashMessage($e, 'alert alert-success');
            
            if($this->presenter->isAjax()) {
                $this->parent->invalidateControl();
                $this->presenter->invalidateControl('script');

            }
            else {
            $this->presenter->redirect('this', $catID);
            }
        }
    }
    
    public function handleSetParentCategory($catid, $parentid) {
        if($this->presenter->getUser()->isInRole('admin')){
        
            $this->categoryModel->updateCategoryParent($catid, $parentid);
                
            if($this->presenter->isAjax())
           {            
                $this->parent->invalidateControl();
                $this->presenter->invalidateControl('bread');
                $this->presenter->invalidateControl('menu');
                $this->presenter->invalidateControl('script');

           }
           else {
            $this->presenter->redirect('this');
           }

       }
    }

    
    public function renderProduct($id, $status, $cat, $prod) {

        $this->template->id = $id;
        $this->template->categor = $cat;
        $this->template->stat = $status;
        $this->template->producer = $prod;
        $this->template->categories = $this->categoryModel->loadCategoryListAdmin();
        $this->template->producers = $this->productModel->loadProducers();
        $this->template->setFile( __DIR__ . '/AdminPanelProduct.latte');
        
        $this->template->render();
    }
    
    
    
    public function renderProductMini($id, $status, $cat) {

        $this->template->id = $id;
        $this->template->categor = $cat;
        $this->template->stat = $status;
        $this->template->categories = $this->category;
        $this->template->setFile( __DIR__ . '/AdminPanelProductMini.latte');
        
        $this->template->render();
    }
    
    public function renderCategory($catid, $higher, $status, $photo) {

        $this->template->categor = $catid;
        $this->template->stat = $status;
        $this->template->higher = $higher;
        $this->template->photo = $photo;
        $this->template->categories = $this->categoryModel->loadCategoryListAdmin();
        $this->template->setFile( __DIR__ . '/AdminPanelCategory.latte');
        
        $this->template->render();
    }
    
}
