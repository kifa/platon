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
    private $shopModel;
    private $orderModel;



    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setCategory($cat) {
        $this->categoryModel = $cat;

    }
    
    public function setBlog($blog) {
        $this->blogModel = $blog;

    }
    
    public function setProduct($pro) {
        $this->productModel = $pro;
    }
    

    public function setOrder($order) {
        $this->orderModel = $order;
    }

    public function setShop($shop) {
        $this->shopModel = $shop;
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
              $this->presenter->invalidateControl();
            }
            
            else {
              $this->presenter->redirect('this');
            }
        }
    }

    public function handleShowProduct($id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->productModel->showProduct($id);
            $this->presenter->flashMessage('Product sucessfully published', 'alert alert-success');
            
            if($this->presenter->isAjax()) {
                $this->presenter->invalidateControl();
                
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
                $this->presenter->invalidateControl();
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
                $this->presenter->invalidateControl();

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
                $this->presenter->invalidateControl();

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
        $this->template->setFile( __DIR__ . '/AdminPanelControl.latte');
        
        $this->template->render();
    }
    
    
}
