<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of catalogPresenter
 *
 * @author Lukas
 */

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
use Nette\Mail\Message;
use Nette\Http\Request;
use Nette\Http\Session;
use Nette\Http\SessionSection;


class CatalogPresenter extends BasePresenter  {
    
    private $productModel;
    private $catalogModel;
    private $categoryModel;
    private $shopModel;
    private $blogModel;
    protected $translator;

    private $parameters;
    private $edit;
    private $filter;

    /** @var \Nette\Http\Request @inject */
    public $httpRequest;
    
     protected function startup() {
        parent::startup();
               
        if ($this->getUser()->isInRole('admin')) {
            $this->edit = $this->getSession('edit');
        }
        
        $salt = $this->shopModel->getShopInfo('Salt');
        $filter = $this->getSession('filter'.$salt);
        $filter->setExpiration(0, 'filter'.$salt);
        $this->filter =  $filter;
    }
    
    public function injectTranslator(GettextTranslator\Gettext $translator) {
        $this->translator = $translator;
    }
    
    public function injectBlogModel(\BlogModel $blogModel) {
        parent::injectBlogModel($blogModel);
        $this->blogModel = $blogModel;
    }
    
    public function injectShopModel(\ShopModel $shopModel) {
        parent::injectShopModel($shopModel);
        $this->shopModel = $shopModel;
    }
    
    public function injectCategoryModel(\CategoryModel $categoryModel) {
        parent::injectCategoryModel($categoryModel);
        $this->categoryModel = $categoryModel;
    }
    
    public function injectProductModel(\ProductModel $productModel) {
        parent::injectProductModel($productModel);
        $this->productModel = $productModel;
    }
    
    public function injectCatalogModel(\CatalogModel $catalogModel) {
        parent::injectCatalogModel($catalogModel);
        $this->catalogModel = $catalogModel;
    }

    public function actionDefault($catID, $slug) {
        $cat = $this->categoryModel->loadCategory($catID);

        if (!isset($cat->CategoryName)) {
            $text = $this->translator->translate('Category not available');
            $this->flashMessage($text, 'alert alert-warning');
            $this->redirect('Homepage:');
        } else {
        
            if ($this->getUser()->isInRole('admin')) {
                
                $productForm = $this['addProductForm'];
                $productForm->setDefaults(array('catID' => $catID));

                $seo = $this['categorySeoForm'];
                $seo->setDefaults(array('catid' => $catID, 'name' => $cat['CategorySeoName']));
               
                
              

                $addCategoryPhoto = $this['addCategoryPhotoForm'];
                $addCategoryPhoto->setDefaults(array('catID' => $catID));

                $deleteCategoryForm = $this['deleteCategoryForm'];
                $deleteCategoryForm->setDefaults(array('catID' => $catID));
            }
            
            $pr = $this['product'];
        
        }
    }

    
    public function handleSetFilter($filter, $sorting) {
      if($filter === 'price') {
            $filter = 'price.FinalPrice';
        }
        elseif($filter === 'product') {
            $filter = 'product.ProductName';
        }
        
        elseif($filter === 'sale') {
            $filter = 'price.SALE';
        }
        elseif($filter === 'pieces') {
            $filter = 'product.PiecesAvailable';
        }
        else {
            $this->redirect('this');
        }
        
        if($sorting === 'ASC' || $sorting === 'DESC') {
            
        
        
        $this->filter->sort = array($filter, $sorting);
            if ($this->isAjax()) {
                $this->invalidateControl('products');
                $this->invalidateControl('script');
            }
            else {
            $this->redirect('this');
            }
        }
        else{
            $this->redirect('this');
        }
    
    }
    
     public function handleEditCatTitle($catid) {
        if($this->getUser()->isInRole('admin')){
            
          $this->invalidateControl('bread');
          
            if($this->isAjax()){
                
               $content = $this->presenter->context->httpRequest->getPost('value');
               $this->categoryModel->updateCategory($catid, $content);
               
           }
           if(!$this->isControlInvalid('CatTitle')){
               $this->payload->edit = $content;
               $this->sendPayload();
               $this->invalidateControl('menu');  
               $this->invalidateControl('bread');
               $this->invalidateControl('CatTitle');
           }
           else {
            $this->redirect('this');
           }
       }
    }
    
     public function handleEditCatDescription($catid) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
               $content = $this->presenter->context->httpRequest->getPost('value');
               $this->categoryModel->updateCategoryDesc($catid, $content);

           }
           if(!$this->isControlInvalid('CatDescription')){           
               $this->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->sendPayload();     
               $this->invalidateControl('CatDescription');
               $this->invalidateControl('script'); //invalidace snipetu
           }
           else {
            $this->redirect('this');
           }

       }
    }
    
    public function handleDeletePhotoCategory($id, $name) {
        if ($this->getUser()->isInRole('admin')) {


            $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/' . $name;
            if ($imgUrl) {
                unlink($imgUrl);
            }

            $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/m-' . $name;
            if ($imgUrl) {
                unlink($imgUrl);
            }

            $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/20-' . $name;
            if ($imgUrl) {
                unlink($imgUrl);
            }

            $photo = $this->translator->translate('Photo ');
            $text = $this->translator->translate(' was sucessfully deleted.');
            $e = $photo . $name . $text;

            $this->categoryModel->deletePhoto($id);
            $this->flashMessage($e, 'alert alert-success');

            $this->redirect('Catalog:default', $id);
        }
    }

    public function createComponentAddProductForm() {

        if ($this->getUser()->isInRole('admin')) {

            
            foreach ($this->categoryModel->loadCategoryListAdmin() as $id => $category) {
                $categories[$id] = $category->CategoryName;
            }
                       
            $addProduct = new Nette\Application\UI\Form;
            $addProduct->setTranslator($this->translator);
            $addProduct->addText('name', 'Name:')
                    ->setRequired()
                    ->setAttribute('placeholder', "Enter product name…")
                    ->setAttribute('class', 'form-control');
            $addProduct->addText('price', 'Price:')
                    ->setRequired()
                    ->addRule(FORM::FLOAT, 'It has to be a number!')
                    ->setAttribute('class', 'form-control');
            $addProduct->addText('amount', 'Amount')
                    ->setDefaultValue('1')
                    ->addRule(FORM::INTEGER, 'It has to be a number!')
                    ->setType('number')
                    ->setRequired()
                    ->setAttribute('class', 'form-control');
            $addProduct->addHidden('catID', '');
            $addProduct->addUpload('image', 'Image:')
                    ->addCondition(Form::FILLED)                    
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addProduct->addSubmit('add', 'Add Product')
                    ->setAttribute('class', 'upl btn btn-success')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addProduct->onSuccess[] = $this->addProductFormSubmitted;
            return $addProduct;
        }
    }

    /*
     * Processing added product
     */

    public function addProductFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

          try {
            $return = $this->productModel->insertProduct(
                    $form->values->name, //Name
                    $form->values->price, 1, //Producer                
                    '11111', //Product Number
                    '', '', //Description
                    '123456', //Ean
                    '122', //QR
                    'rok', //Warranty
                    $form->values->amount, //Pieces
                    $form->values->catID, //CatID
                    '' //Date of avail.                
                    //NULL //Comment   
            );

            if ($form->values->image->isOK()) {

                $this->productModel->insertPhoto(
                        $form->values->name, $form->values->image->name, $return[1], 1
                );
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $return[1] . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);
                
                 $sizes = $this->shopModel->loadPhotoSize();

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Large']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $return[1] . '/l-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Small']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $return[1] . '/s-' . $form->values->image->name;
                $image->save($imgUrl);
            }
            
            $this->redirect('Product:product', $return[0], $form->values->name);

             }
            catch (Exception $e) {
                 \Nette\Diagnostics\Debugger::log($e);
                 $this->redirect('this');
            }

        }
    }
    
     protected function createComponentCategorySeoForm() {
        if ($this->getUser()->isInRole('admin')) {
            $seo = new Nette\Application\UI\Form;
            $seo->setTranslator($this->translator);
            $seo->addHidden('catid', '');
            $seo->addText('name', 'SEO Title')
                    ->setAttribute('class', 'form-control')
                    ->setRequired();
            $seo->addSubmit('set', 'Save SEO text')
                    ->setAttribute('class', 'btn btn-primary upl')
                    ->setAttribute('data-loading-text', 'Setting...');
            $seo->onSuccess[] = $this->categorySeoFormSubmitted;
            return $seo;
        }
    }
    
    public function categorySeoFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            $this->categoryModel->updateCat($form->values->catid, 'CategorySeoName', $form->values->name);
            
            $this->redirect('this');
        }
    }
    
    public function createComponentAddCategoryPhotoForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addHidden('catID', '');
            $addPhoto->addUpload('image', 'Photo:')
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addPhoto->addSubmit('add', 'Add Photo')
                    ->setAttribute('class', 'btn btn-primary upl')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addPhoto->onSuccess[] = $this->addCategoryPhotoFormSubmitted;
            return $addPhoto;
        }
    }

    /*
     * Adding submit form for adding photos
     */

    public function addCategoryPhotoFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            if ($form->values->image->isOK()) {

                 $sizes = $this->shopModel->loadPhotoSize();
                 
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/' . $form->values->image->name;
                $form->values->image->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Medium']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/m-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(50, 50, Image::EXACT);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/s-' . $form->values->image->name;
                $image->save($imgUrl);

                $this->categoryModel->addPhoto($form->values->catID, $form->values->image->name);

                $message = $this->translator->translate(' was sucessfully uploaded');
                $photo = $this->translator->translate(' Photo ');
                $e = HTML::el('span', $photo . $form->values->image->name . '' . $message);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert alert-success');
            }

            $this->redirect('this');
        }
    }

    
    
    protected function createComponentDeleteCategoryForm() {
        if ($this->getUser()->isInRole('admin')) {

            $deleteForm = new Nette\Application\UI\Form;
            $deleteForm->setTranslator($this->translator);

            foreach ($this->categoryModel->loadCategoryListAdmin() as $id => $category) {
                $categories[$id] = $category->CategoryName;
            }
            $prompt = Html::el('option')->setText("-- No Parent --")->class('prompt');

            $deleteForm->addHidden('catID', '');
            $deleteForm->addSelect('parent', 'Move products to category:', $categories)
                    ->setPrompt($prompt)
                    ->setRequired();
            $deleteForm->addSubmit('edit', 'Delete Category')
                    ->setAttribute('class', 'upl btn btn-danger')
                    ->setAttribute('data-loading-text', 'Deleting...');

            $deleteForm->onSuccess[] = $this->deleteCategoryFormSubmitted;
            return $deleteForm;
        }
    }

    public function deleteCategoryFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

            foreach ($this->catalogModel->loadCatalogAdmin($form->values->catID) as $product) {
                foreach($this->productModel->loadProductVariants($product->ProductID) as $productVariant) {
                        $this->productModel->updateProduct($productVariant->ProductID, 'CategoryID', $form->values->parent);
                        
                    }
                $this->productModel->updateProduct($product->ProductID, 'CategoryID', $form->values->parent);
                
                    
            }

            $this->categoryModel->deleteCategory($form->values->catID);
            $this->redirect('Catalog:default', $form->values->parent);
        }
    }

    public function renderDefault($catID, $slug) {

        if ($this->getUser()->isInRole('admin')) {
            // load all products
            $this->template->products = $this->catalogModel->loadCatalogAdmin($catID, $this->filter->sort);
            $this->template->categories = $this->categoryModel->loadCategoryListAdmin();
            $this->template->subcategories = $this->categoryModel->loadChildCategoryListAdmin($catID);
        } else {
            // load published products
            $this->template->products = $this->catalogModel->loadCatalog($catID, $this->filter->sort);
            $categories = $this->categoryModel->loadChildCategoryList($catID);

            if ($categories){
            $this->template->subcategories = $categories;
            }
            else{
                $this->template->subcategories = NULL;
            }
        }

        $this->template->slider = NULL;
        
        $this->template->category = $this->categoryModel->loadCategory($catID);
    }

    public function renderProductsBrand($prodID, $slug) {

        $this->template->products = $this->catalogModel->loadCatalogBrand($prodID);

        $this->template->producer = $this->productModel->loadProducer($prodID);
    }

    public function renderArchivedProducts(){
        if ($this->getUser()->isInRole('admin')) {
            $this->template->products = $this->catalogModel->loadArchivedCatalog();
        }
    }
}
