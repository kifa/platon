<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
        Nette\Image;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class BlogPresenter extends BasePresenter {
    
     protected $translator;
     private $blog;
     private $productModel;
    private $categoryModel;
    private $row;


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
    
    
    
     
    public function actionPost($postid) {
         $row = $this->blog->loadPost($postid);
         if ($this->getUser()->isInRole('admin')) {
                $this->row = array('BlogID' => $row->BlogID,
                    'BlogName' => $row->BlogName,

                    'BlogContent' => $row->BlogContent,
                    'PhotoAlbumID' => $row->PhotoAlbumID,
                    'CategoryID' => $row->CategoryID);

                $editDescForm = $this['editDescForm'];
               $addPhotoForm = $this['addPhotoForm'];
            }
    }
    
    
    
    
     public function createComponentAddPostForm() {

        if ($this->getUser()->isInRole('admin')) {

            $category = array();

            foreach ($this->categoryModel->loadCategoryList() as $id => $name) {
                $category[$id] = $name->CategoryName;
            }
            
            
            $addPost = new Nette\Application\UI\Form;
            //      $addProduct->setRenderer(new BootstrapRenderer);
            $addPost->setTranslator($this->translator);
            $addPost->addText('name', 'Name:')
                    ->setRequired();
            $addPost->addTextArea('desc', 'Description: ', 10)
                    ->setRequired()
                    ->setAttribute('class', 'mceEditor');
            $addPost->addSelect('cat', 'Category: ', $category);
            $addPost->addUpload('image', 'Image:')    
                    ->addCondition(Form::FILLED)
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addPost->addSubmit('add', 'Add Post')
                    ->setAttribute('class', 'upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addPost->onSubmit('tinyMCE.triggerSave()');
            $addPost->onSuccess[] = $this->addPostFormSubmitted;
            return $addPost;
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
                    $form->values->desc
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
    
   


    public function handleDeletePhoto($post, $id) {
        if ($this->getUser()->isInRole('admin')) {
            $row = $this->productModel->loadPhoto($id);
            if (!$row) {
                $this->flashMessage('There is no photo to delete', 'alert');
            } else {

            
            $imgUrl = array($this->context->parameters['wwwDir'] . '/images/blog/' . $this->row['BlogID'] . '/' . $row->PhotoURL,
                            $this->context->parameters['wwwDir'] . '/images/blog/' . $this->row['BlogID'] . '/300-' . $row->PhotoURL,
                            $this->context->parameters['wwwDir'] . '/images/blog/' . $this->row['BlogID'] . '/50-' . $row->PhotoURL);
                if ($imgUrl) {
                    foreach ($imgUrl as $img) {
                    unlink($img);
                    }
                }

                $e = 'Photo ' . $row->PhotoName . ' was sucessfully deleted.';

                $this->productModel->deletePhoto($id);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('this');
        }
    }
    
    protected function createComponentEditDescForm() {
        if ($this->getUser()->isInRole('admin')) {

            $editForm = new Nette\Application\UI\Form;
            $editForm->setTranslator($this->translator);
          //  $editForm->setRenderer(new BootstrapRenderer);
            foreach ($this->categoryModel->loadCategoryList() as $id => $category) {
                $categories[$id] = $category->CategoryName;
            }
            
      
            
            $prompt = Html::el('option')->setText("-- No Parent --")->class('prompt');
            
            $editForm->addText('name', 'Name:')
                    ->setDefaultValue($this->row['BlogName'])
                    ->setRequired();
            
            $editForm->addSelect('category', 'Select Category:', $categories)

                    ->setDefaultValue($this->row['CategoryID']);
            
           /* CURRENTLY UNUSED 
            * $editForm->addTextArea('short', 'Impress:')
                    ->setDefaultValue($this->row['BlogDescription'])
                    ->setRequired(); */
                    
            $editForm->addTextArea('text', 'Description:', 150, 150)
                    ->setDefaultValue($this->row['BlogContent'])
                    ->setRequired()
                    ->setAttribute('class', 'mceEditor');
            
            
            $editForm->addHidden('id', $this->row['BlogID']);
            
            $editForm->addSubmit('edit', 'Save post')
                    ->setAttribute('class', 'upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Saving...');
            $editForm->onSubmit('tinyMCE.triggerSave()');
            $editForm->onSuccess[] = $this->editDescFormSubmitted;
            return $editForm;
        }
    }

    public function editDescFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

            $this->blog->updatePost($form->values->id, 'BlogName', $form->values->name);
           $this->blog->updatePost($form->values->id, 'BlogContent', $form->values->text);
         //  $this->blog->updatePost($form->values->id, 'BlogDescription', $form->values->short);
            
            $this->blog->updatePost($form->values->id, 'CategoryID', $form->values->category);
            $this->redirect('this');
        }
    }
    
    public function createComponentAddPhotoForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setRenderer(new BootstrapRenderer);
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addHidden('name', 'name');
            $addPhoto->addHidden('blogID', $this->row['BlogID']);
            $addPhoto->addHidden('albumID', $this->row['PhotoAlbumID']);
            $addPhoto->addUpload('image', 'Photo:')
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addPhoto->addSubmit('add', 'Add Photo')
                    ->setAttribute('class', 'btn-primary upl')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addPhoto->onSuccess[] = $this->addProductPhotoFormSubmitted;
            return $addPhoto;
        }
    }

    /*
     * Adding submit form for adding photos
     */

    public function addProductPhotoFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            if ($form->values->image->isOK()) {

                $this->productModel->insertPhoto(
                        $form->values->name, $form->values->image->name,  $form->values->albumID
                );
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/blog/' . $form->values->blogID . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);
                
                $image = Image::fromFile($imgUrl);
                $image->resize(null, 300, Image::SHRINK_ONLY);
                
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/blog/' . $form->values->blogID . '/300-' . $form->values->image->name;
                $image->save($imgUrl);
                
                $image = Image::fromFile($imgUrl);
                $image->resize(null, 50, Image::SHRINK_ONLY);
                
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/blog/' . $form->values->blogID . '/50-' . $form->values->image->name;
                $image->save($imgUrl);

                $e = HTML::el('span', ' Photo ' . $form->values->image->name . ' was sucessfully uploaded');
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('this');
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
        $this->template->photo = $this->blog->loadCoverPhoto($postid);
        $this->template->album = $this->blog->loadPhotoAlbum($postid);
        $this->template->post = $this->blog->loadPost($postid);
        
    }
    
    
    
}
