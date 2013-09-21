<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of zasilkovnaControl
 *
 * @author Lukas
 */
class xmlFeedModule extends moduleControl {
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $shopModel;
    private $orderModel;
    private $categoryModel;
    private $productModel;
    
    private $view;
    public function setView($view)
    {
        $this->view = $view;
    }


    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setShop($shop) {
        $this->shopModel = $shop;
    }
  
     public function setOrder($order) {
        $this->orderModel = $order;
    }
    
    public function setCategory($cat) {
        $this->categoryModel = $cat;
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
    

    
    
    public function handleInstallModule() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            try {
                $this->installModule();
                $this->shopModel->updateModuleStatus('xmlfeed', 1);
            }catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
            }          
            $this->presenter->redirect('this');
        }
    }

    


    /*****************************************************************
    * HANDLE
    */
    
   protected function installModule() {
       if($this->shopModel->isModuleActive('xmlfeed')) {
           $this->presenter->flashMessage('Module already installed.', 'alert alert-warning');
           return TRUE;
       }
       else {
            try {
                $this->reloadXML();
                $this->presenter->flashMessage('Module installation OK!', 'alert alert-success');

            } catch(Exception $e) {   
               \Nette\Diagnostics\Debugger::log($e);
            }          
         }
   }
   
   public function handleUninstallModule() {
       if($this->shopModel->isModuleActive('xmlfeed')) {
           
            $this->shopModel->updateModuleStatus('xmlfeed', 2);           
       }
       else {
           $this->presenter->flashMessage('Module already uninstalled', 'alert');
       }
       $this->presenter->redirect('this');
   }

   public function handleUpdateXML() {
       if($this->shopModel->isModuleActive('xmlfeed')) {
           
           $this->reloadXML();
           $this->presenter->redirect('this');
           }
       else {
           return FALSE;
       }
   }
   
   protected function reloadXML() {
      
       try {
        $this->generateHeureka();
       } catch(Exception $e) {

                        \Nette\Diagnostics\Debugger::log($e);
                        $this->presenter->flashMessage('Heureka XML feed crashed. I´m so sorry.', 'alert alert-danger');
       }
       
       try {
       $this->generateGoogle();
       } catch(Exception $e) {
                        \Nette\Diagnostics\Debugger::log($e);
                        $this->presenter->flashMessage('Google XML feed crashed. I´m so sorry.', 'alert alert-danger');
       }
   }

   private function generateHeureka() {
       
                 $template = $this->createTemplate();
                 $template->setFile($this->presenter->context->parameters['appDir'] . '/templates/components/heureka.latte');
                 $template->registerFilter(new Nette\Latte\Engine);
                 $template->registerHelperLoader('Nette\Templating\Helpers::loader');

                 $template->products = $this->productModel->loadHeurekaCatalog();
                 $template->category = $this->categoryModel->loadCategory("");
                 
                 $template->save($this->presenter->context->parameters['wwwDir'] . '/heureka.xml');
                 $this->presenter->flashMessage('Heureka XML feed sucessfully generated.', 'alert alert-success');
   }
   
   private function generateGoogle() {
                 $template = $this->createTemplate();
                 $template->setFile($this->presenter->context->parameters['appDir'] . '/templates/components/google.latte');
                 $template->registerFilter(new Nette\Latte\Engine);
                 $template->registerHelperLoader('Nette\Templating\Helpers::loader');

                 $template->products = $this->productModel->loadHeurekaCatalog();
                 $template->category = $this->categoryModel->loadCategory("");
                 
                 $template->save($this->presenter->context->parameters['wwwDir'] . '/google.xml');
                 $this->presenter->flashMessage('Google XML feed sucessfully generated.', 'alert alert-success');
   }

   public function actionOrder($orderInfo) {

   }
   /***********************************************************************
     * RENDERY
     */
    
   public function renderAdmin() {
        
        $this->template->setFile(__DIR__ . '/xmlfeedAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('xmlfeed');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
        $this->template->render();
    }
    
    public function renderInstall() {
        
        $this->template->setFile(__DIR__ . '/../simpleInstallModule.latte');
        
        $info = $this->shopModel->loadModuleByName('xmlfeed');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
                
        $this->template->render();
    }

    
   final public function render($arrgs) {
        
        if($arrgs == 'admin') {
            $this->renderAdmin();
        }
        
        if($arrgs == 'install') {
            $this->renderInstall();
        }
        
    }
}