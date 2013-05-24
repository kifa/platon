<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
        Nette\Image;
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
            $addProduct->addUpload('image', 'Image:')
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
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
            
            if ($form->values->image->isOK()) {

                $albumid = $this->productModel->insertPhotoAlbum($form->values->name, $form->values->desc, null, $return);
                
                $this->productModel->insertPhoto(
                        $form->values->name, $form->values->image->name, $albumid, 1
                );
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/blog/' . $return . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);
                
                $image = Image::fromFile($imgUrl);
                $image->resize(null, 300, Image::SHRINK_ONLY);
                
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/blog/' . $return . '/300-' . $form->values->image->name;
                $image->save($imgUrl);
                
                $image = Image::fromFile($imgUrl);
                $image->resize(null, 50, Image::SHRINK_ONLY);
                
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/blog/' . $return . '/50-' . $form->values->image->name;
                $image->save($imgUrl);
            }

            
          $this->redirect('Blog:post', $return);
        }
    }
    
    
    
    public function renderCategory($id) {
        $this->template->category = $this->blog->loadCategory($id);
        if($id == NULL) {
        $this->template->posts = $this->blog->loadPosts();
        }
        else {
            $this->template->posts = $this->blog->loadPosts($id);
        }
        
    }
      
    public function renderPost($postid) {
        $this->template->photo = $this->blogModel->loadCoverPhoto($postid);
        $this->template->album = $this->blogModel->loadPhotoAlbum($postid);
        $this->template->post = $this->blog->loadPost($postid);
        
    }
    
    
    
}
