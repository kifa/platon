<?php


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

    public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
    }
    
    protected function createComponentProductEdit() {
        $productEditControl = new productEditControl();
        $productEditControl->setTranslator($this->translator);
        $productEditControl->setProduct($this->productModel);
        $productEditControl->setCategory($this->categoryModel);
        $productEditControl->setBlog($this->blogModel);
        return $productEditControl;
    }
   

       
       public function handleDeleteProduct($catID, $id) {
        if ($this->getUser()->isInRole('admin')) {
            $this->productModel->deleteProduct($id);
            $this->redirect('this', $catID);
        }
    }

    public function handleArchiveProduct($catID, $id) {
        if ($this->getUser()->isInRole('admin')) {
            $this->productModel->archiveProduct($id);
            $this->redirect('this', $catID);
        }
    }

     public function handleHideProduct($catID, $id) {
        if ($this->getUser()->isInRole('admin')) {
            
            $this->productModel->hideProduct($id);
            
            if($this->isAjax())
            {            
              $this->invalidateControl();
              $this->invalidateControl('script');
            }
            
            else {
              $this->redirect('this', $catID);
            }
        }
    }

    public function handleShowProduct($catID, $id) {
        if ($this->getUser()->isInRole('admin')) {
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
    
    public function render($id) {
        $this->template->setFile(__DIR__ . '/productControl.latte');    
        $this->template->product = $this->productModel->loadProduct($id);
        $this->template->render();
    }
}
