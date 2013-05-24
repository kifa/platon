<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class BlogPresenter extends BasePresenter {
    
     protected $translator;
     private $blog;
     private $productModel;
    private $categoryModel;


     public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }
    
    protected function startup() {
        parent::startup();

        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;
        $this->blog = $this->context->blogModel;
        /* Kontrola přihlášení
         * 
         * if (!$this->getUser()->isInRole('admin')) {
          $this->redirect('Sign:in');
          } */
    }
    
     public function createComponentAddPostForm() {

        if ($this->getUser()->isInRole('admin')) {

            $category = array();

            foreach ($this->categoryModel->loadCategoryList() as $id => $name) {
                $category[$id] = $name->CategoryName;
            }
            
            
            $addProduct = new Nette\Application\UI\Form;
            //      $addProduct->setRenderer(new BootstrapRenderer);
            $addProduct->setTranslator($this->translator);
            $addProduct->addText('name', 'Name:')
                    ->setRequired();
            $addProduct->addTextArea('short', 'Impress: ')
                    ->setRequired();
            $addProduct->addTextArea('desc', 'Description: ', 10)
                    ->setRequired()
                    ->setAttribute('class', 'mceEditor');
            $addProduct->addSelect('cat', 'Category: ', $category);
            
            $addProduct->addSubmit('add', 'Add Post')
                    ->setAttribute('class', 'upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addProduct->onSubmit('tinyMCE.triggerSave()');
            $addProduct->onSuccess[] = $this->addPostFormSubmitted;
            return $addProduct;
        }
    }

    /*
     * Processing added product
     */

    public function addPostFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

            $return = $this->blog->insertPost(
                    $form->values->name, //Name
                    $form->values->cat,
                    $form->values->desc,
                    $form->values->short
                     //Description
                    
            );

            
          $this->redirect('Blog:post', $return);
        }
    }
    
    
    
    public function renderCategory($id = NULL) {
        $this->template->category = $this->blog->loadCategory($id);
        $this->template->posts = $this->blog->loadPosts();
        
    }
      
    public function renderPost($postid) {
        $this->template->post = $this->blog->loadPost($postid);
        
    }
    
    
    
}
