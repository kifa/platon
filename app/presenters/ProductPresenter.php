<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
use Nette\Mail\Message;

/*
 * class ProductPresenter
 * ProductPresenter rendering product info, and catalog info
 */

class ProductPresenter extends BasePresenter {
    /*
     * @var productModel
     * @var categoryModel;
     */

    private $productModel;
    private $categoryModel;
    private $shopModel;
    private $blogModel;
    protected $translator;

    private $parameters;
    private $edit;

    protected function startup() {
        parent::startup();
        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;
        $this->shopModel = $this->context->shopModel;
        $this->blogModel = $this->context->blogModel;
        
        if ($this->getUser()->isInRole('admin')) {
            $this->edit = $this->getSession('edit');
        }
        /* Kontrola přihlášení
         * 
         * if (!$this->getUser()->isInRole('admin')) {
          $this->redirect('Sign:in');
          } */
    }

    public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }

    public function handleDeletePhotoCategory($id, $name) {
        if ($this->getUser()->isInRole('admin')) {


            $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/' . $name;
            if ($imgUrl) {
                unlink($imgUrl);
            }

            $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/150-' . $name;
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
            $this->flashMessage($e, 'alert');

            $this->redirect('Product:products', $id);
        }
    }

   
    

    /*     * **********************************************************************
     *                            Render Products aka CATEGORY
     * @param 
     * ********************************************************************** */

    public function actionProducts($catID, $slug) {
        $cat = $this->categoryModel->loadCategory($catID);
        if (!isset($cat->CategoryName)) {
            $text = $this->translator->translate('Category not available');
            $this->flashMessage($text, 'alert');
            $this->redirect('Homepage:');
        } else {
        
            if ($this->getUser()->isInRole('admin')) {
                
                $productForm = $this['addProductForm'];
                $productForm->setDefaults(array('catID' => $catID));

                $addCategoryForm = $this['addCategoryForm'];
                $addCategoryForm->setDefaults(array('catID' => $catID));

                $addCategoryPhoto = $this['addCategoryPhotoForm'];
                $addCategoryPhoto->setDefaults(array('catID' => $catID));

                $deleteCategoryForm = $this['deleteCategoryForm'];
                $deleteCategoryForm->setDefaults(array('catID' => $catID));
            } 
        
        }
    }

    public function handleSetCategoryStatus($catID, $categoryStatus) {
        if ($this->getUser()->isInRole('admin')) {
            $this->categoryModel->setCategoryStatus($catID, $categoryStatus);
            $status = $this->categoryModel->getStatusName($categoryStatus);
          //  $status = $categoryStatus;
            $text = $this->translator->translate('Category status is now: ');
            $e = $text . $status;
            $this->flashMessage($e, 'alert');
            
            if($this->isAjax()) {
                $this->invalidateControl('categoryStatus');
                $this->invalidateControl('script');

            }
            else {
            $this->redirect('this', $catID);
            }
        }
    }
    
    public function handleSetFilter($filter, $sorting) {
      
            $this->filter->sort = array($filter, $sorting);
            $this->redirect('this');
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
                    ->setAttribute('class', 'span10');
            $addProduct->addText('price', 'Price:')
                    ->setRequired()
                    ->addRule(FORM::FLOAT, 'It has to be a number!')
                    ->setAttribute('class', 'span10');
            $addProduct->addText('amount', 'Amount')
                    ->setDefaultValue('1')
                    ->addRule(FORM::INTEGER, 'It has to be a number!')
                    ->setType('number')
                    ->setRequired()
                    ->setAttribute('class', 'span7');
            $addProduct->addHidden('catID', '');
            $addProduct->addUpload('image', 'Image:')
                    ->addCondition(Form::FILLED)                    
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addProduct->addSubmit('add', 'Add Product')
                    ->setAttribute('class', 'upl btn btn-primary')
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

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 300, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $return[1] . '/300-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 50, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $return[1] . '/50-' . $form->values->image->name;
                $image->save($imgUrl);
            }
            
            $this->redirect('Product:product', $return[0]);

             }
            catch (Exception $e) {
                 \Nette\Diagnostics\Debugger::log($e);
                 $this->redirect('this');
            }

        }
    }

    protected function createComponentAddProductVariantForm() {

        if ($this->getUser()->isInRole('admin')) {

            $addProduct = new Nette\Application\UI\Form;
            $addProduct->setTranslator($this->translator);
            $addProduct->addText('name', 'Variant Name:')
                    ->setRequired()
                    ->setAttribute('placeholder', "Enter variant name… (black color, extra charger, etc.)")
                    ->setAttribute('class', 'span5');
            $addProduct->addText('price', 'Price:')
                    ->setRequired()
                    ->setType('number')
                    ->addRule(FORM::FLOAT, 'It has to be a number!')
                    ->setAttribute('class', 'span5');
            $addProduct->addText('amount', 'Amount')
                    ->setDefaultValue('1')
                    ->addRule(FORM::INTEGER, 'It has to be a number!')
                    ->setType('number')
                    ->setRequired()
                    ->setAttribute('class', 'span5');
            $addProduct->addHidden('id', '');
            $addProduct->addUpload('image', 'Image:')
                    ->addCondition(Form::FILLED)                    
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addProduct->addSubmit('add', 'Add Product Variant')
                    ->setAttribute('class', 'upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addProduct->onSuccess[] = $this->addProductVariantFormSubmitted;
            return $addProduct;
        }
    }
    
    public function addProductVariantFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

          try {
            $return = $this->productModel->insertProductVariant(
                    $form->values->id,
                    $form->values->name,
                    $form->values->amount,//Name
                    $form->values->price);

            if ($form->values->image->isOK()) {

                $albumID = $this->insertPhotoAlbum($form->values->name, '', $return, null);
                
                $this->productModel->insertPhoto(
                        $form->values->name, $form->values->image->name, $albumID, 1
                );
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 300, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/300-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 50, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/50-' . $form->values->image->name;
                $image->save($imgUrl);
            }
            

             }
            catch (Exception $e) {
                 \Nette\Diagnostics\Debugger::log($e);
            }
            
                        $this->redirect('this');


        }
    }
    
    

    public function handleDeleteProduct($catID, $id) {
        if ($this->getUser()->isInRole('admin')) {
            $this->productModel->updateProduct($id, 'ProductStatusID', 1);
            $this->redirect('this', $catID);
        }
    }

    public function handleArchiveProduct($catID, $id) {
        if ($this->getUser()->isInRole('admin')) {
           $this->productModel->updateProduct($id, 'ProductStatusID', 1);
            $this->redirect('this', $catID);
        }
    }
    
     public function handleArchiveVariantProduct($varid) {
        if ($this->getUser()->isInRole('admin')) {
            $this->productModel->updateProduct($varid, 'ProductStatusID', 1);
            $this->redirect('this');
        }
    }
    

     public function handleHideProduct($catID, $id) {
        if ($this->getUser()->isInRole('admin')) {
            
            $this->productModel->hideProduct($id);
            
            if($this->isAjax())
            {            
              $this->invalidateControl('products');
              $this->invalidateControl('script');
            }
            
            else {
              $this->redirect('this', $catID);
            }
        }
    }

    public function handleShowProduct($catID, $id) {
        if ($this->getUser()->isInRole('admin')) {
            $this->productModel->showProduct($id);
            
            if($this->presenter->isAjax()) {
                $this->invalidateControl('products');
                $this->invalidateControl('script');
            }
            else {
            $this->redirect('this', $catID);
            }
        }
    }
    


    
    public function handleSetCatalogLayout($catID, $layoutID) {
        if ($this->getUser()->isInRole('admin')) {
            $this->shopModel->setShopInfo('CatalogLayout', $layoutID);
            $this->redirect('this', $catID);
        }
    }

    
    
    public function handleEditCatTitle($catid) {
        if($this->getUser()->isInRole('admin')){
            
          $this->invalidateControl('bread');
          
            if($this->isAjax()){
               //$name = $_POST['id'];
               $content = $_POST['value'];
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
    
    
    
    public function handleEditProdTitle($id){
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->productModel->updateProduct($id, 'ProductName', $content);
            }
            if(!$this->isControlInvalid('editProdTitle')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('editProdTitle');
            }
            else {
             $this->redirect('this');
            }

        }
    }
    
    
    public function handleEditProdVarTitle($id){
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->productModel->updateProduct($id, 'ProductVariantName', $content);
            }
            if(!$this->isControlInvalid('editProdVarTitle'.$id)){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('editProdVarTitle'.$id);
            }
            else {
             $this->redirect('this');
            }

        }
    }

    public function handleEditProdDescription($id) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->productModel->updateProduct($id, 'ProductDescription', $content);
            }
            if(!$this->isControlInvalid('editProdDescription')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('editProdDescription');
            }
            else {
             $this->redirect('this');
            }
        }
    }
    
    public function handleEditProdShort($id) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];
                $this->productModel->updateProduct($id, 'ProductShort', $content);
            }
            if(!$this->isControlInvalid('editProdShort')){
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('editProdShort');
            }
            else {
             $this->redirect('this');
            }
        }
    }
    
    public function handleEditProdPrice($id, $sellingprice, $sale) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $_POST['value'];

                if ($sale == 0) {
                $this->productModel->updatePrice($id, $content, $sale);    
                }
                else {
                  $sale = $sellingprice - $content;
                $this->productModel->updatePrice($id, $sellingprice, $sale); 
                }
            }
            if(!$this->isControlInvalid('prodPrice')){
                $this->invalidateControl('page-header');
                $this->payload->edit = $content;
                $this->sendPayload();
            }
            else {
             $this->redirect('this');
            }
        }
    }
    
    
    

    public function handleEditProdAmount($id) {        
        if($this->getUser()->isInRole('admin')){ 
            if($this->isAjax())
            {            
                $content = $_POST['value'];

                $this->productModel->updateProduct($id, 'PiecesAvailable', $content);
                $this->invalidateControl('page-header');
            }
            if(!$this->isControlInvalid('editProdAmount'))
            {
                $this->invalidateControl('editProdAmount');
                $this->payload->edit = $content;
                $this->sendPayload();
            }
            else {
             $this->redirect('this');
            }
        }
    }
    
    
    
    public function handleDeletePhoto($id, $photo) {
        if ($this->getUser()->isInRole('admin')) {
            $row = $this->productModel->loadPhoto($photo);
            if (!$row) {
                $this->flashMessage('There is no photo to delete', 'alert');
            } else {

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $row->PhotoAlbumID . '/' . $row->PhotoURL;
                if ($imgUrl) {
                    unlink($imgUrl);
                }


                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $row->PhotoAlbumID . '/50-' . $row->PhotoURL;
                if ($imgUrl) {
                    unlink($imgUrl);
                }


                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $row->PhotoAlbumID . '/300-' . $row->PhotoURL;
                if ($imgUrl) {
                    unlink($imgUrl);
                }

                $photo = $this->translator->translate('Photo ');
                $text = $this->translator->translate(' was sucessfully deleted.');
                $e = $photo . $row->PhotoName . $text;

                $this->productModel->deletePhoto($photo);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('this');
        }
    }
    
    public function handleSetProductCategory($id, $catid) {
        if($this->getUser()->isInRole('admin')){ 
            if($this->isAjax())
            {            
                $this->productModel->updateProduct($id, 'CategoryID', $catid);
                $this->invalidateControl('productCategory');
                $this->invalidateControl('bread');
                $this->invalidateControl('script');

            }
            else {
             $this->redirect('this');
            }
        }
    }

    public function handleSetProductProducer($id, $producerid) {
        if($this->getUser()->isInRole('admin')){ 
            if($this->isAjax())
            {            
                $this->productModel->updateProduct($id, 'ProducerID', $producerid);
                $this->invalidateControl('productProducer');
                $this->invalidateControl('script');

            }
            else {
             $this->redirect('this');
            }
        }
    }
 

    
    public function handleSetProductStatus($id, $statusid) {
        if($this->getUser()->isInRole('admin')){ 
            if($this->isAjax())
            {            
                $this->productModel->updateProduct($id, 'ProductStatusID', $statusid);
                $this->invalidateControl('page-header');
                $this->invalidateControl('productStatus');
                $this->invalidateControl('products');
                $this->invalidateControl('script');
            }
            else {
             $this->redirect('this');
            }
        }
    }


    public function handleEditCatDescription($catid) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
               $content = $_POST['value']; //odesílaná nová hodnota
               $this->categoryModel->updateCategoryDesc($catid, $content);

           }
           if(!$this->isControlInvalid('editDescription')){           
               $this->payload->edit = $content; //zaslání nové hodnoty do šablony
               $this->sendPayload();
               $this->invalidateControl('menu');       
               $this->invalidateControl('editDescription'); //invalidace snipetu
           }
           else {
            $this->redirect('this');
           }

       }
    }
    
    public function handleSetParentCategory($catid, $parentid) {
        if($this->getUser()->isInRole('admin')){
        
            $this->categoryModel->updateCategoryParent($catid, $parentid);
                
            if($this->isAjax())
           {            
                $this->invalidateControl('parentCategory');
                $this->invalidateControl('bread');
                $this->invalidateControl('menu');
                $this->invalidateControl('script');

           }
           else {
            $this->redirect('this');
           }

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

            foreach ($this->productModel->loadCatalog($form->values->catID) as $product) {
                $this->productModel->updateProduct($product->ProductID, 'CategoryID', $form->values->parent);
            }

            $this->categoryModel->deleteCategory($form->values->catID);
            $this->redirect('Product:products', $form->values->parent);
        }
    }

    public function renderProducts($catID, $slug) {

        if ($this->getUser()->isInRole('admin')) {
            // load all products
            $this->template->products = $this->productModel->loadCatalogAdmin($catID, $this->filter->sort);
            $this->template->categories = $this->categoryModel->loadCategoryListAdmin();
        } else {
            // load published products
            $this->template->products = $this->productModel->loadCatalog($catID, $this->filter->sort);
            $this->template->categories = $this->categoryModel->loadCategoryList();
        }

        $this->template->slider = NULL;
        $this->template->category = $this->categoryModel->loadCategory($catID);
    }

    public function renderProductsBrand($prodID) {

        $this->template->products = $this->productModel->loadCatalogBrand($prodID);

        $this->template->producer = $this->productModel->loadProducer($prodID);
    }

    /*     * *******************************************************************
     *                      RENDER PRODUCT
     * rendering Product with full info
     * ********************************************************************* */

    public function actionProduct($id, $slug = NULL) {
        $row = $this->productModel->loadProduct($id);
        if (!$row) {
            $text = $this->translator->translate('Product not available');
            $this->flashMessage($text, 'alert');
            $this->redirect('Homepage:');
        } else {
 
            $this->parameters = $this->productModel->loadParameters($id);
            $album = $this->productModel->loadPhotoAlbumID($id);
            
            if($album){
                $album = $album->PhotoAlbumID;
            } else {
                $album = FALSE;
            }
            
            if ($this->getUser()->isInRole('admin')) {
                                               
                $editForm = $this['editParamForm'];
                $addForm = $this['addParamForm'];
                $addForm->setDefaults(array('productID' => $id));
               
                
                $addVariant = $this['addProductVariantForm'];
                $addVariant->setDefaults(array(
                    'id' => $row['ProductID'],
                    'price' => $row['SellingPrice'] ));
                
                
                $photoForm = $this['addPhotoForm'];
                $photoForm->setDefaults(array(
                    'productName' => $row['ProductName'],
                    'productDescription' => $row['ProductShort'],
                    'productID' => $row['ProductID'],
                    'albumID' => $album));
                
            }
           
               $askForm = $this['askForm'];
               $askForm->setDefaults(array('name' => $row['ProductName']));
        }
    }


    


    public function createComponentAddPhotoForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addHidden('productName', '');
            $addPhoto->addHidden('productDescription', '');
            $addPhoto->addHidden('albumID', '');
            $addPhoto->addHidden('productID', '');
            $addPhoto->addUpload('image', 'Photo:')
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024)
                    ->setAttribute('class', 'span7');
            $addPhoto->addSubmit('add', 'Add Photo')
                    ->setAttribute('class', 'btn-primary upl span4')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addPhoto->onSuccess[] = $this->addProductPhotoFormSubmitted;
            return $addPhoto;
        }
    }

  


    public function addProductPhotoFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            if ($form->values->image->isOK()) {

                if($form->values->albumID == NULL) {
                    $albumID = $this->productModel->insertPhotoAlbum($form->values->productName, $form->values->productDescription, $form->values->productID);
                    $this->productModel->insertPhoto(
                        $form->values->productName, $form->values->image->name, $albumID, 1
                    );
                    
                } else {
                    $albumID = $form->values->albumID;
                    $this->productModel->insertPhoto(
                        $form->values->productName, $form->values->image->name, $albumID
                    );
                }
                
                
                
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 300, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/300-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 50, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/50-' . $form->values->image->name;
                $image->save($imgUrl);

                $message = $this->translator->translate(' was sucessfully uploaded');
                $photo = $this->translator->translate(' Photo ');
                $e = HTML::el('span', $photo . $form->values->image->name . '' . $message);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert');
            }

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
                    ->setAttribute('class', 'btn-primary upl')
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

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/' . $form->values->image->name;
                $form->values->image->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 150, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/150-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 20, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/category/20-' . $form->values->image->name;
                $image->save($imgUrl);

                $this->categoryModel->addPhoto($form->values->categoryID, $form->values->image->name);

                $message = $this->translator->translate(' was sucessfully uploaded');
                $photo = $this->translator->translate(' Photo ');
                $e = HTML::el('span', $photo . $form->values->image->name . '' . $message);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('this');
        }
    }

   protected function createComponentAskForm() {
      
            $askForm = new Nette\Application\UI\Form;
            $askForm->setTranslator($this->translator);
            $askForm->addTextArea('note', 'Question:', 7, 4)
                    ->setAttribute('class', 'span11')
                    ->setRequired('Please enter your question.');
            $askForm->addText('email', 'Email:')
                    ->setEmptyValue('@')
                    ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                    ->setRequired('Please fill your email.')
                    ->setAttribute('class', 'span11');
            $askForm->addHidden('name', '');
            $askForm->addSubmit('ask', 'Ask')
                    ->setAttribute('class', 'btn btn-primary span5')
                    ->setHtmlId('askButton')
                    ->setAttribute('data-loading-text', 'Asking...');
            $askForm->onSuccess[] = $this->askFormSubmitted;
            return $askForm;
    }

    public function askFormSubmitted($form) {

        $email = $form->values->email;
        $note = $form->values->note;
        $name = $form->values->name;
        
        $message = $this->translator->translate(' Great! We have received your question.');
        $e = HTML::el('span', $message);
        $ico = HTML::el('i')->class('icon-ok-sign left');
        $e->insert(0, $ico);
        $this->flashMessage($e, 'alert alert-success');
         
        if($this->isAjax()){
            
           
            $form->setValues(array(), TRUE);
            $this->invalidateControl('contact');  
//            $this->invalidateControl('content'); 
            
        }
        else {
            $this->redirect('this');
        }
        
        try {
            $this->sendAskMail($email, $note, $name);
        }
        catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
        }
    }
    
    protected function createComponentEditParamForm() {
        if ($this->getUser()->isInRole('admin')) {
            $editForm = new Nette\Application\UI\Form;
            $editForm->setTranslator($this->translator);

            foreach ($this->productModel->loadUnit('') as $id => $unit) {
                $units[$id] = $unit->UnitShort;
            }
            $prompt = Html::el('option')->setText("-- Select --")->class('prompt');

            foreach ($this->parameters as $id => $param) {
                $editForm->addGroup($param->AttribName);
                $editForm->addText($param->ParameterID, 'Value:')
                        ->setDefaultValue($param->Val);
                $editForm->addSelect('unit' . $param->ParameterID, 'Select unit:', $units, 1, 4)
                        ->setPrompt($prompt)
                        ->setDefaultValue($param->UnitID);
            }

            $editForm->addSubmit('edit', 'Save Specs')
                    ->setAttribute('class', 'upl-edit btn btn-primary')
                    ->setAttribute('data-loading-text', 'Saving...');
            $editForm->onSuccess[] = $this->editParamFormSubmitted;
            return $editForm;
        }
    }

    public function editParamFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            $prevID = null;
            foreach ($form->values as $id => $value) {

                if ($id == 'unit' . $prevID) {
                    $this->productModel->updateParameter($prevID, $prevVal, $value);
                }
                $prevID = $id;
                $prevVal = $value;
            }

            $this->redirect('this');
        }
    }

    protected function createComponentAddParamForm() {
        if ($this->getUser()->isInRole('admin')) {
            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);

            $options = array();
            $units = array();
            foreach ($this->productModel->loadAttribute('') as $id => $param) {
                $options[$id] = $param->AttribName;
            }

            foreach ($this->productModel->loadUnit('') as $id => $unit) {
                $units[$id] = $unit->UnitShort;
            }

            $addForm->addGroup('Select one of already created:');
            $prompt = Html::el('option')->setText("-- Select --")->class('prompt');
            $addForm->addSelect('options', 'Select spec:', $options)
                    ->setPrompt($prompt);
            $addForm->addText('paramValue', 'Enter Value:');
            $addForm->addSelect('unit', 'Select unit:', $units)
                    ->setPrompt($prompt);
            $addForm->addGroup('Create a new one:');
            $addForm->addText('newParam', 'Name of new Spec:');
            $addForm->addSelect('unit2', 'Select unit:', $units)
                    ->setPrompt($prompt);
            $addForm->addHidden('productID', '');
            $addForm->addSubmit('edit', 'Add Spec')
                    ->setAttribute('class', 'upl-add btn btn-primary')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addForm->onSuccess[] = $this->addParamFormSubmitted;

            return $addForm;
        }
    }

    public function addParamFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

            if ($form->values->options != '') {
                $this->productModel->insertParameter($form->values->productID, $form->values->options, $form->values->paramValue, $form->values->unit);
            }
            if ($form->values->newParam) {
                $attrib = $this->productModel->insertAttribute($form->values->newParam);
                $this->productModel->insertParameter($form->values->productID, $attrib, NULL, $form->values->unit);
            }
            $this->edit->param = 1;
            $this->redirect('this');
        }
    }

    protected function createComponentDeleteParamForm() {
        if ($this->getUser()->isInRole('admin')) {
            $editForm = new Nette\Application\UI\Form;
            $editForm->setTranslator($this->translator);
            foreach ($this->parameters as $id => $param) {
                $editForm->addCheckbox($param->ParameterID, $param->AttribName)
                        ->setDefaultValue(FALSE);
            }

            $editForm->addSubmit('delete', 'Delete Specs')
                    ->setAttribute('class', 'upl-del btn btn-primary')
                    ->setAttribute('data-loading-text', 'Deleting...');
            $editForm->onSuccess[] = $this->deleteParamFormSubmitted;
            return $editForm;
        }
    }

    public function deleteParamFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {

            foreach ($form->values as $id => $value) {
                if ($value == TRUE) {
                    $this->productModel->deleteParameter($id);
                }
            }
            $this->redirect('this');
        }
    }

   

    /*
     * Adding product photos
     */

   

    
    protected function createComponentProduct() {
        $productControl = new productControl();
        $productControl->setTranslator($this->translator);
        $productControl->setProduct($this->productModel);
        $productControl->setCategory($this->categoryModel);
        $productControl->setShop($this->shopModel);
        return $productControl;
    }
    
    protected function createComponentVariantControl() {
        $variant = new variantControl();
        $variant->setTranslator($this->translator);
        $variant->setProduct($this->productModel);        
        return $variant;
    }
    
    protected function createComponentGapiModule() {
       
       $gapi = new gapiModule();
       $gapi->setTranslator($this->translator);
       $gapi->setShop($this->shopModel);
 //      $gapi->setOrder($this->orderModel);
       $gapi->setProduct($this->productModel);
       $gapi->setCategory($this->categoryModel);
       $gapi->setGapi($this->gapisession);
       return $gapi;
   }

        public function renderProduct($id, $slug) {
        $layout = $this->shopModel->getShopInfo('ProductLayout');
        
       $this->template->setFile( $this->context->parameters['appDir'] . '/templates/Product/'  . $layout . '.latte'); 
       if ($this->presenter->getUser()->isInRole('admin')) { 
            if ($this->edit->param != NULL) {
                $this->template->attr = 1;
                $this->edit->param = NULL;
            } else {
                $this->template->attr = 0;
            }
            
            
                
            $this->template->categories = $this->categoryModel->loadCategoryListAdmin();
            $this->template->producers = $this->productModel->loadProducers();
       
            }
        
        $album = $this->productModel->loadPhotoAlbumID($id);
     
        if($album){
            $album = $album->PhotoAlbumID;
        }
       
        $this->template->product = $this->productModel->loadProduct($id);
        $this->template->photo = $this->productModel->loadCoverPhoto($id);
        $this->template->albumID = $album;
        $this->template->album = $this->productModel->loadPhotoAlbum($id);
        $this->template->parameter = $this->productModel->loadParameters($id);
        $this->template->productVariants = $this->productModel->loadProductVariants($id);
        $this->template->slider = NULL;
        $this->template->shippingPrice = $this->productModel->loadCheapestDelivery();
        $this->template->docs = $this->productModel->loadDocumentation($id)->fetchPairs('DocumentID');

    }

    /*     * ***********************************************************************
     *                      Render Default
     * 
     * ********************************************************************** */

    public function renderDefault() {
        $this->redirect('products');
        $this->template->slider = NULL;
        $this->template->anyVariable = 'any value';
    }
    
    
    
    protected function sendAskMail($email, $note, $name) {
        
            $contactMail = $this->shopModel->getShopInfo('ContactMail');
            $template = new Nette\Templating\FileTemplate($this->context->parameters['appDir'] . '/templates/Email/askMail.latte');
            $template->registerFilter(new Nette\Latte\Engine);
            $template->registerHelperLoader('Nette\Templating\Helpers::loader');
            $template->email = $email;
            //$template->mailOrder = $row->UsersID;
            $template->note = $note;
            $template->product = $name; 
            
            $mailIT = new mailControl();
            $mailIT->sendSuperMail($contactMail, 'Nový dotaz k produktu ' . $name, $template, $email);
    }

}

