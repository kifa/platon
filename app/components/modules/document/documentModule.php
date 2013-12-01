<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
use Nette\Application\UI;

/*
 * Menu Control component
 */

class documentModule extends moduleControl {

    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;

    private $categoryModel;
    private $productModel;
    private $blogModel;
    private $shopModel;
    private $id;


    
    private $view;
    
    public function setView($view)
    {
        $this->view = $view;
    }

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
       
       $this->shopModel->updateModuleStatus('document', 1);
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
       
       $this->shopModel->updateModuleStatus('document', 2);
       $this->presenter->flashMessage('Module uninstallation OK!', 'alert alert-success');  
       $this->presenter->redirect('this');
         }
   }
   
    public function handleDeleteDocument($id, $doc) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $row = $this->productModel->loadDocumentation($id)->fetch();
            if (!$row) {
                $this->flashMessage('There is no Docs to delete', 'alert');
            } else {
                $docUrl = $this->presenter->context->parameters['wwwDir'] . '/docs/' . $row->ProductID . '/' . $row->DocumentURL;

                
                if ($docUrl) {
                    unlink($docUrl);
                }

                $doc = $this->translator->translate('Doc ');
                $text = $this->translator->translate(' was sucessfully deleted.');
                $e = $doc . $row->DocumentName . $text;

                $this->productModel->deleteDocumentation($id);
                $this->presenter->flashMessage($e, 'alert');
            }

            $this->presenter->redirect('this');
        }
    }
    
    
    protected function createComponentAddDocumentationForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addText('name', 'Name:')
                    ->setRequired('Please fill document name')
                    ->setAttribute('class', 'col-md-10');
            $addPhoto->addHidden('productid', $this->id);
            $addPhoto->addUpload('doc', 'Document:')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024)
                    ->setAttribute('class', 'col-md-10');
            $addPhoto->addText('desc', 'Description')
                    ->setAttribute('class', 'col-md-10');
            $addPhoto->addSubmit('add', 'Add Document')
                    ->setAttribute('class', 'btn btn-primary upl')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addPhoto->onSuccess[] = $this->addDocumentationFormSubmitted;
            return $addPhoto;
        }
    }

    /*
     * Adding submit form for adding photos
    */ 

    public function addDocumentationFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($form->values->doc->isOK()) {

                $this->productModel->insertDocumentation(
                        $form->values->name, $form->values->doc->name, $form->values->productid, $form->values->desc
                );
                $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/docs/' . $form->values->productid . '/' . $form->values->doc->name;
                $form->values->doc->move($imgUrl);

                $doc = $this->translator->translate(' Doc ');
                $text = $this->translator->translate(' was sucessfully uploaded');
                $e = HTML::el('span', $doc . $form->values->doc->name . $text);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert');
            }

            $this->presenter->redirect('this');
        }
    }
    
     public function actionOrder($orderInfo) {
         
     }
    
    
   public function renderAdmin() {
        
        $this->template->setFile(__DIR__ . '/documentAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('document');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
        $this->template->render();
    }
    
    public function renderInstall() {
        
        $this->template->setFile(__DIR__ . '/documentInstallModule.latte');
        
        $info = $this->shopModel->loadModuleByName('document');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
                
        $this->template->render();
    }
    
    
   public function renderProduct($id) {
        $this->id = $id;
        $this->template->setFile(__DIR__ . '/documentModule.latte');
        $info = $this->shopModel->loadModuleByName('document');

        $this->template->status = $info->StatusID; 
        
        if($info->StatusID == 1) {
        $this->template->docs = $this->productModel->loadDocumentation($id)->fetchPairs('DocumentID');
       
        $this->template->product = $id;
        }
        $this->template->render();
    }
    
  final public function render($arrgs) {
        
        if($arrgs == 'admin') {
            $this->renderAdmin();
        }
        
        if($arrgs == 'install') {
            $this->renderInstall();
        }
        
        if($arrgs == 'product') {
            $this->renderSmartPanel();
        }
    }
    
}
