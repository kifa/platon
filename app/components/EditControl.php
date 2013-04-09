<?php

use Nette\Application\UI;
use Nette\Forms\Container;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EditControl extends BaseControl {
    

    
       /* @var ProductModel  */
    private $service;
    
    /* @var CategoryID */
    private $id;
    
    /* @var Gettext\translator */
   protected $translator;
   
   private $productID;

   private $parameters;


   /**
     * Setting ProductModel service to access DB
     *
     * @param    ProductModel
     * @return   void
     */
    public function setService(ProductModel $service) {
        $this->service = $service;
    }
    
    
    /* 
     *Settin Translator to implement localization
     * 
     * @param Nette\Gettext\translator
     * @return void
     */

    
   public function setTranslator($translator) {
        $this->translator = $translator;
    }
    
    public function setParameters($parameters){
        $this->parameters = $parameters;
    }

    public function setProductID ($id) {
        $this->id = $id;
    }

    /*
     * Create control template for localization
     * 
     * @param NULL
     * @return Translator template
     */

    public function createTemplate($class = NULL)
{
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);

    return $template;
}
    

public function beforeRender($id) {
    $editForm = $this['editParamForm'];
    $editForm->setValues($this->parameters);
    dump($editForm);
    //$this->parameters = $this->service->loadParameters('1');
}

protected function createComponentEditParamForm() {
         
           
           
           $editForm = new Nette\Application\UI\Form;
           $editForm->setTranslator($this->translator);
           $number = 0;
         
          foreach ($this->parameters as $id => $param) {
               
               $editForm->addText($param->ParameterID, $param->Parameter)
                   ->setDefaultValue($param->Value)
                   ->setRequired();
                   
            
           } 
            /*
           $editFormaddDynamic('parameters', function (Container $container) {
                    $container->addText('parameter');
            }); */
           $editForm->addText('tets', 'TEST')
                   ->setDefaultValue('test')
                   ->setRequired();
           $editForm->addSubmit('edit', 'Save attributes')
                   ->setAttribute('class', 'upl btn btn-primary')
                   ->setAttribute('data-loading-text', 'Saving...');
            $editForm->onSuccess[] = $this->editParamFormSubmitted;
            return $editForm;
         
    }
    
    public function editParamFormSubmitted($form) {
            dump($form->values);
           foreach($form->values as $id => $value) {
           $this->service->updateParameter($id, $value);

           }
         // $this->redirect('this');
        
    }
    
    /*
     * Rendering component Product from ProductControl.latte
     */

    public function render($id) {

        $this->productID = $id;
        $this->parameters = $this->service->loadParameters($this->productID);
        $this->template->setFile(__DIR__ . '/EditControl.latte');
        
        $this->template->render();
    }
    
    
}
