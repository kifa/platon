<?php

use Nette\Application\UI;

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
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
    private $id;




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
   
   public function handleDeleteComment($commentid) {
       if($this->presenter->getUser()->isInRole('admin')){
           $this->productModel->deleteComment($commentid);
           
                $doc = $this->translator->translate(' Comment');
                $text = $this->translator->translate(' was sucessfully deleted');
                $e = HTML::el('span', $doc . $text);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->presenter->flashMessage($e, 'alert');
                
                
           $this->presenter->redirect('this');
       }
   }

   protected function createComponentAddCommentForm() {
            $addComment = new Nette\Application\UI\Form;
            $addComment->setTranslator($this->translator);
            $addComment->addText('title', 'Title:')
                    ->setRequired('Please fill document name')
                    ->setAttribute('class', 'col-md-10');
            $addComment->addHidden('productid', $this->id);
            $addComment->addTextArea('content', 'Review', 20, 5)
                    ->setAttribute('class', 'col-md-10');
            $addComment->addText('author', 'Your name:')
                    ->setRequired('Please fill document name')
                    ->setAttribute('class', 'col-md-10');
            $addComment->addSubmit('add', 'Add Review')
                    ->setAttribute('class', 'btn btn-primary upl')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addComment->onSuccess[] = $this->addCommentFormSubmitted;
            return $addComment;
        
    }

    /*
     * Adding submit form for adding photos
    */ 

    public function addCommentFormSubmitted($form) {
 
            $this->productModel->insertComment(
                        $form->values->title, $form->values->content, $form->values->author, $form->values->productid, NULL
                );


                $doc = $this->translator->translate(' Comment ');
                $text = $this->translator->translate(' was sucessfully added');
                $e = HTML::el('span', $doc . $form->values->title . $text);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->presenter->flashMessage($e, 'alert alert-success');
  

            $this->presenter->redirect('this');
    }
    
     public function actionOrder($orderInfo) {
         
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
       $this->id = $id;
        $this->template->setFile(__DIR__ . '/commentModule.latte');
        $info = $this->shopModel->loadModuleByName('comment');

        $this->template->status = $info->StatusID; 
        
        if($info->StatusID  == 1) {
        $this->template->comments = $this->productModel->loadProductComments($id);
        $this->template->product = $id;
        }
        $this->template->render();
    }
    
    
    public function renderSmartPanel() {
        $this->template->setFile(__DIR__ . '/commentSmartPanelModule.latte');
        $info = $this->shopModel->loadModuleByName('comment');

        $this->template->status = $info->StatusID; 
        
        if($info->StatusID  == 1) {
            
        $this->template->comments = $this->productModel->loadCommentsByDate();
        } 
        $this->template->render();
    }
    
}
