<?php

use Nette\Application\UI;

/*
 * Menu Control component
 */

class commentModule extends moduleControl {

    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;

    private $categoryModel;
    private $productModel;
    private $blogModel;
    private $shopModel;




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
    
    

     public function handleInstallModule() {
         if($this->presenter->getUser()->isInRole('admin')){
      /* if($this->shopModel->isModuleActive('zasilkovna')) {
           $this->presenter->flashMessage('Module already installed.', 'alert alert-warning');
           return TRUE;
       }
       else {}*/
       
       $this->shopModel->updateModuleStatus('comment', 1);
       $this->presenter->flashMessage('Module installation OK!', 'alert alert-success');  
       $this->presenter->redirect('this');
         }
   }
   
    public function handleUninstallModule() {
         if($this->presenter->getUser()->isInRole('admin')){
      /* if($this->shopModel->isModuleActive('zasilkovna')) {
           $this->presenter->flashMessage('Module already installed.', 'alert alert-warning');
           return TRUE;
       }
       else {}*/
       
       $this->shopModel->updateModuleStatus('comment', 2);
       $this->presenter->flashMessage('Module uninstallation OK!', 'alert alert-success');  
       $this->presenter->redirect('this');
         }
   }
   
   public function renderAdmin() {
        
        $this->template->setFile(__DIR__ . '/commentAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('comment');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
        $this->template->render();
    }
    
    public function renderInstall() {
        
        $this->template->setFile(__DIR__ . '/commentInstallModule.latte');
        
        $info = $this->shopModel->loadModuleByName('comment');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
                
        $this->template->render();
    }
    
    
   public function render($id) {
        $this->template->setFile(__DIR__ . '/commentModule.latte');
        $info = $this->shopModel->loadModuleByName('comment');

        $this->template->status = $info->StatusID; 
        
        $this->template->id = $id;
        $this->template->render();
    }
    
}
