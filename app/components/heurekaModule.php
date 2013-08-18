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
class heurekaModule extends moduleControl {
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $shopModel;
    private $orderModel;
    private $categoryModel;
    private $productModel;


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
    

    
    protected function createComponentInstallModule() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $installForm = new Nette\Application\UI\Form;
            $installForm->setTranslator($this->translator);
            $installForm->addText('api', 'KEY:')
                    ->setRequired('Please enter your Heureka Key.')
                    ->setAttribute('class', 'span12');
            $installForm->addSubmit('install', 'Install module')
                    ->setAttribute('class', 'btn-primary upl span12')
                    ->setAttribute('data-loading-text', 'Installing...');
            $installForm->onSuccess[] = $this->installModuleSubmitted;
            return $installForm;
        }
    }
    
    
    public function installModuleSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if($this->shopModel->getShopInfo('heurekaKEY') == NULL) {
            $this->shopModel->insertShopInfo('heurekaKEY', $form->values->api);
                }
            try {
                $this->installModule();
                $this->shopModel->updateModuleStatus('ulozenka', 1);
            }catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
            }          
            
            $this->presenter->redirect('this');
        }
    }
    
    protected function createComponentUpdateForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $priceForm = new Nette\Application\UI\Form;
            $priceForm->setTranslator($this->translator);
            $priceForm->addText('key', 'KEY:')
                    ->setRequired('Please set new HEUREKA key.')
                    ->setAttribute('class', 'span12');
            $priceForm->addSubmit('set', 'Set new HEUREKA Key')
                    ->setAttribute('class', 'btn-primary upl span12')
                    ->setAttribute('data-loading-text', 'Setting...');
            $priceForm->onSuccess[] = $this->updateFormSubmitted;
            return $priceForm;
        }
        
    }
    
    public function updateFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            
            $this->shopModel->setShopInfo('heurekaKEY', $form->values->key);
            
            $this->presenter->redirect('this');
        }
    }


    /*****************************************************************
    * HANDLE
    */
    
   protected function installModule() {
      /* if($this->shopModel->isModuleActive('zasilkovna')) {
           $this->presenter->flashMessage('Module already installed.', 'alert alert-warning');
           return TRUE;
       }
       else {}*/
       
       
      if($this->shopModel->getShopInfo('heurekaKEY')) {
                try {
                    $this->reloadXML();
                    $this->presenter->flashMessage('Module installation OK!', 'alert alert-success');
                    
                } catch(Exception $e) {   
                   \Nette\Diagnostics\Debugger::log($e);
                }          
               }   
        else {
            $this->presenter->flashMessage('Module installation OK, please ENTER your API key!', 'alert alert-warning');
        }        
   }
   
   public function handleUninstallModule() {
       if($this->shopModel->isModuleActive('heureka')) {
           
            $this->shopModel->updateModuleStatus('heureka', 2);
           
            $this->shopModel->deleteShopInfo('heurekaKEY');
           
       }
       else {
           $this->presenter->flashMessage('Module already uninstalled', 'alert');
       }
       $this->presenter->redirect('this');
   }

   public function handleUpdateXML() {
       if($this->shopModel->isModuleActive('heureka') && $this->shopModel->getShopInfo('heurekaKEY')) {
           $this->reloadXML();
           }
       else {
           return FALSE;
       }
   }
   
   protected function reloadXML() {
       
            try {
                 $template = $this->createTemplate();
                 $template->setFile($this->presenter->context->parameters['appDir'] . '/templates/components/heureka.latte');
                 $template->registerFilter(new Nette\Latte\Engine);
                 $template->registerHelperLoader('Nette\Templating\Helpers::loader');

                 $template->products = $this->productModel->loadCatalog("");
                 $template->category = $this->categoryModel->loadCategory("");

                 $template->save($this->presenter->context->parameters['wwwDir'] . '/heureka.xml');
                 $this->presenter->flashMessage('Heureka XML feed sucessfully generated.', 'alert alert-success');
             }
             catch(Exception $e) {

                        \Nette\Diagnostics\Debugger::log($e);
                        $this->presenter->flashMessage('XML feed crashed. I´m so sorry.', 'alert alert-danger');

                 }       
   
   }


   public function actionOrder($orderid, $progress) {
       if($this->shopModel->isModuleActive('heureka')) {
          
           if ($progress == 10) {
            $products = $this->orderModel->loadOrderProduct($orderid);
            $order = $this->orderModel->loadOrder($orderid);
            
            
            $key = $this->shopModel->getShopInfo('heurekaKEY');
            $heurekaURL = 'http://www.heureka.cz';
            
            $url = '/direct/dotaznik/objednavka.php?id=' . $key . '&email=' . $order->UsersID;
            foreach ($products as $id => $product) {
                $url .= '&produkt[]=' . $product->ProductName;
            }
        
        
        $fp = fsockopen('www.heureka.cz', 80, $errno, $errstr, 5);
        if (!$fp) {
            \Nette\Diagnostics\Debugger::log($errstr . ' (' . $errno . ')');
        } else {
            $return = '';
            $out = "GET " . $url . " HTTP/1.1\r\n" . 
                "Host: www.heureka.cz\r\n" . 
                "Connection: Close\r\n\r\n";
            fputs($fp, $out);
            while (!feof($fp)) {
                $return .= fgets($fp, 128);
            }
            fclose($fp);
            
            \Nette\Diagnostics\Debugger::log($return);
            }
         }
       }
   }
   /***********************************************************************
     * RENDERY
     */
    
   public function renderAdmin() {
        
        $this->template->setFile(__DIR__ . '/heurekaAdminModule.latte');
        $info = $this->shopModel->loadModuleByName('heureka');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
        $this->template->key = $this->shopModel->getShopInfo('heurekaKEY');
        $this->template->render();
    }
    
    public function renderInstall() {
        
        $this->template->setFile(__DIR__ . '/heurekaInstallModule.latte');
        
        $info = $this->shopModel->loadModuleByName('heureka');
       
        $this->template->name = $info->ModuleName;
        $this->template->desc = $info->ModuleDescription;
        $this->template->status = $info->StatusID; 
                
        $this->template->render();
    }

    
    public function renderSmartPanel() {
          
    }
}