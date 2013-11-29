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
    private $shopModel;
    private $row;
    private $row2;

    protected function startup() {
        parent::startup();
    }

    public function injectTranslator(\GettextTranslator\Gettext $translator) {
        $this->translator = $translator;
    }

    public function injectProductModel(\ProductModel $productModel) {
        parent::injectProductModel($productModel);
        $this->productModel = $productModel;
    }

    public function injectCategoryModel(\CategoryModel $categoryModel) {
        parent::injectCategoryModel($categoryModel);
        $this->categoryModel = $categoryModel;
    }

    public function injectBlogModel(\BlogModel $blogModel) {
        parent::injectBlogModel($blogModel);
        $this->blog = $blogModel;
    }

    public function injectShopModel(\ShopModel $shopModel) {
        parent::injectShopModel($shopModel);
        $this->shopModel = $shopModel;
    }

    public function actionDefault() {
        $this->setView('Category');
    }

    public function actionPost($postid) {
        $row = $this->blog->loadPost($postid);

        $row2 = $this->blog->loadPhotoAlbumID($postid);
        if ($this->getUser()->isInRole('admin')) {
            $this->row = array('BlogID' => $row->BlogID,
                'BlogName' => $row->BlogName,
                'PhotoAlbumID' => $row2,
                'BlogContent' => $row->BlogContent,
                'CategoryID' => $row->CategoryID);

            $editDescForm = $this['editDescForm'];
            $addPhotoForm = $this['addPhotoForm'];
        }
    }

    public function createComponentAddPostForm() {

        if ($this->getUser()->isInRole('admin')) {

            $category = array();

            foreach ($this->categoryModel->loadCategoryListAdmin() as $id => $name) {
                $category[$id] = $name->CategoryName;
            }


            $addPost = new Nette\Application\UI\Form;
            $addPost->setTranslator($this->translator);
            $addPost->addText('name', 'Name:')
                    ->setAttribute('class', 'form-control ')
                    ->setRequired();
            $addPost->addSelect('cat', 'Category: ', $category)
                    ->setAttribute('class', 'form-control');
            $addPost->addSubmit('add', 'Add Post')
                    ->setAttribute('class', 'upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Adding...');
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
                    $form->values->cat, ''
                    //Description
            );

            $this->productModel->insertPhotoAlbum($form->values->name, '', null, $return);


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

                $text = $this->translator->translate('was sucessfully deleted.');
                $e = 'Photo ' . $row->PhotoName . ' ' . $text;

                $this->productModel->deletePhoto($id);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('this');
        }
    }

    public function handleBlogName($blogid) {
        if ($this->getUser()->isInRole('admin')) {
            if ($this->isAjax()) {
                $content = $_POST['value'];
                $this->blog->updatePost($blogid, 'BlogName', $content);
            }
            if (!$this->isControlInvalid('BlogName')) {
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('BlogName');
            } else {
                $this->redirect('this');
            }
        }
    }

    public function handleBlogContent($blogid) {
        if ($this->getUser()->isInRole('admin')) {
            if ($this->isAjax()) {
                $content = $_POST['value'];
                $this->blog->updatePost($blogid, 'BlogContent', $content);
            }
            if (!$this->isControlInvalid('BlogContent')) {

                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('BlogContent');
            } else {
                $this->redirect('this');
            }
        }
    }

    protected function createComponentEditDescForm() {
        if ($this->getUser()->isInRole('admin')) {

            $editForm = new Nette\Application\UI\Form;
            $editForm->setTranslator($this->translator);

            foreach ($this->categoryModel->loadCategoryListAdmin() as $id => $category) {
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
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addHidden('name', 'name');
            $addPhoto->addHidden('blogID', $this->row['BlogID']);
            $addPhoto->addHidden('albumID', $this->row['PhotoAlbumID']);
            $addPhoto->addUpload('image', 'Photo:')
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addPhoto->addSubmit('add', 'Add Photo')
                    ->setAttribute('class', 'btn-primary');
            $addPhoto->onSuccess[] = $this->addPhotoFormSubmitted;
            return $addPhoto;
        }
    }

    /*
     * Adding submit form for adding photos
     */

    public function addPhotoFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            if ($form->values->image->isOK()) {

                $this->productModel->insertPhoto(
                        $form->values->name, $form->values->image->name, $form->values->albumID
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

                $text = $this->translator->translate('was sucessfully uploaded');
                $e = HTML::el('span', ' Photo ' . $form->values->image->name . ' ' . $text);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('this');
        }
    }

    public function renderCategory($blogid) {
        $this->template->category = $this->blog->loadCategory($blogid);
        if ($blogid == NULL) {
            $this->template->posts = $this->blog->loadPosts();
        } else {
            $this->template->posts = $this->blog->loadPosts($blogid);
        }
    }

    public function renderPost($postid) {
        $this->template->photo = $this->blog->loadCoverPhoto($postid);
        $this->template->album = $this->blog->loadPhotoAlbum($postid);
        $this->template->post = $this->blog->loadPost($postid);

        if ($this->getUser()->isInRole('admin')) {
            $this->template->adminAlbum = $this->blog->loadPhotoAlbum($postid);
        }
    }

    public function handleEditStaticTextContent($postid) {
        if ($this->getUser()->isInRole('admin')) {
            if ($this->isAjax()) {
                $content = $_POST['value'];
                $this->shopModel->updateStaticText($postid, 'StaticTextContent', $content);
            }
            if (!$this->isControlInvalid('StaticTextContent')) {

                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('StaticTextContent');
            } else {
                $this->redirect('this');
            }
        }
    }

    public function handleEditStaticTextName($postid) {
        if ($this->getUser()->isInRole('admin')) {
            if ($this->isAjax()) {
                $content = $_POST['value'];
                $this->shopModel->updateStaticText($postid, 'StaticTextName', $content);
            }
            if (!$this->isControlInvalid('StaticTextName')) {
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('StaticTextName');
            } else {
                $this->redirect('this');
            }
        }
    }

    public function handleSetStaticTextStatus($postid, $statusid) {
        if ($this->getUser()->isInRole('admin')) {

            $this->shopModel->updateStaticText($postid, 'StatusID', $statusid);


            if ($this->isAjax()) {
                $this->invalidateControl('controlPanel');
            } else {
                $this->redirect('this');
            }
        }
    }

    public function handleDeleteStaticText($postid) {
        if ($this->getUser()->isInRole('admin')) {

            $this->shopModel->deleteStaticText($postid);

            $this->redirect('Homepage:');
        }
    }

    public function createComponentAddPhotoStaticForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addHidden('name');
            $addPhoto->addHidden('textalbumid');
            $addPhoto->addUpload('image', 'Photo:')
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addPhoto->addSubmit('addPhoto', 'Add Photo')
                    ->setAttribute('class', 'form-control btn btn-primary upl');
            $addPhoto->onSuccess[] = $this->addPhotoStaticFormSubmitted;
            return $addPhoto;
        }
    }

    /*
     * Adding submit form for adding photos
     */

    public function addPhotoStaticFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            if ($form->values->image->isOK()) {

                $this->productModel->insertPhoto(
                        $form->values->name, $form->values->image->name, $form->values->textalbumid
                );

                $sizes = $this->shopModel->loadPhotoSize();

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/static/' . $form->values->textalbumid . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Large']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/static/' . $form->values->textalbumid . '/l-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Medium']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/static/' . $form->values->textalbumid . '/m-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Small']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/static/' . $form->values->textalbumid . '/s-' . $form->values->image->name;
                $image->save($imgUrl);

                $text = $this->translator->translate('was sucessfully uploaded');
                $e = HTML::el('span', ' Photo ' . $form->values->image->name . ' ' . $text);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert alert-success');
            }

            $this->redirect('this');
        }
    }

    public function actionStaticText($spostid) {

        if ($this->getUser()->isInRole('admin')) {

            $albumid = $this->blog->loadPhotoAlbumStatic($spostid);
            
            
            if ($albumid == FALSE) {
                $row = $this->shopModel->loadStaticText($spostid);
                $albumid = $this->productModel->insertPhotoAlbum($row->StaticTextName, '', NULL, NULL, $spostid);
            } else {
                $albumid = $this->blog->loadPhotoAlbumStaticID($spostid);
                $albumid = $albumid['PhotoAlbumID'];
            }

            $addPhotoStaticForm = $this['addPhotoStaticForm'];
            $addPhotoStaticForm->setDefaults(array('name' => 'photoalbum', 'textalbumid' => $albumid));
        }
    }

    public function renderStaticText($spostid) {
        if ($this->getUser()->isInRole('admin')) {
            $this->template->album = $this->blog->loadPhotoAlbumStatic($spostid);
            $this->template->adminAlbum = $this->blog->loadPhotoAlbumStatic($spostid);
        }

        $this->template->post = $this->shopModel->loadStaticText($spostid);
    }

}
