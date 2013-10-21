<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
use Nette\Mail\Message;
use Nette\Http\Request;
use Nette\Http\Session;
use Nette\Http\SessionSection;

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
    private $filter;

    protected $visited;
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
        
        if (!isset($this->visited)) {
            $this->visited = $this->getSession('visited' . $salt);
            $this->visited->prd = NULL;
        }
        $visited = $this->getSession('visited' . $salt);
        $visited->setExpiration('1 month', 'visited'.$salt);
        $this->visited = $visited;
    }

    public function injectTranslator(NetteTranslator\Gettext $translator) {
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

    

   
    

    /*     * **********************************************************************
     *                            Render Products aka CATEGORY
     * @param 
     * ********************************************************************** */

    

    protected function createComponentAddProductVariantForm() {

        if ($this->getUser()->isInRole('admin')) {

            $addProduct = new Nette\Application\UI\Form;
            $addProduct->setTranslator($this->translator);
            $addProduct->addText('name', 'Variant Name:')
                    ->setRequired()
                    ->setAttribute('placeholder', "Enter variant name… (black color, extra charger, etc.)")
                    ->setAttribute('class', 'form-control');
            $addProduct->addText('price', 'Price:')
                    ->setRequired()
                    ->setType('number')
                    ->addRule(FORM::FLOAT, 'It has to be a number!')
                    ->setAttribute('class', 'form-control');
            $addProduct->addText('amount', 'Amount')
                    ->setDefaultValue('1')
                    ->addRule(FORM::INTEGER, 'It has to be a number!')
                    ->setType('number')
                    ->setRequired()
                    ->setAttribute('class', 'form-control');
            $addProduct->addHidden('id', '');
            $addProduct->addUpload('image', 'Image:')
                    ->addCondition(Form::FILLED)                    
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addProduct->addSubmit('add', 'Add Product Variant')
                    ->setAttribute('class', 'upl btn btn-success
                        ')
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
                
                $sizes = $this->shopModel->loadPhotoSize();

                $albumID = $this->insertPhotoAlbum($form->values->name, '', $return, null);
                
                $this->productModel->insertPhoto(
                        $form->values->name, $form->values->image->name, $albumID, 1
                );
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Large']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/l-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Small']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/s-' . $form->values->image->name;
                $image->save($imgUrl);
            }
            

             }
            catch (Exception $e) {
                 \Nette\Diagnostics\Debugger::log($e);
            }
            
                        $this->redirect('this');


        }
    }
    
    

    public function handleDeleteProduct($id, $catID) {
        if ($this->getUser()->isInRole('admin')) {
            $this->productModel->updateProduct($id, 'ProductStatusID', 0);
            $this->redirect('Catalog:default', $catID);
        }
    }

    public function handleArchiveProduct($catID, $id) {
        if ($this->getUser()->isInRole('admin')) {
           $this->productModel->updateProduct($id, 'ProductStatusID', 0);
            $this->redirect('this', $catID);
        }
    }
    
     public function handleArchiveVariantProduct($varid) {
        if ($this->getUser()->isInRole('admin')) {
            $this->productModel->updateProduct($varid, 'ProductStatusID', 0);
            $this->redirect('this');
        }
    }
    

    public function handleDeleteVideo($videoID) {
         if ($this->getUser()->isInRole('admin')) {
            $this->productModel->deleteVideo($videoID);
            $this->redirect('this');
        }
    }
 
    
   
    
    
    
    public function handleEditProdTitle($id){
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $this->presenter->context->httpRequest->getPost('value');
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
    
    
    

    public function handleEditProdDescription($id) {
        if($this->getUser()->isInRole('admin')){
            if($this->isAjax()){            
                $content = $this->presenter->context->httpRequest->getPost('value');
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
                $content = $this->presenter->context->httpRequest->getPost('value');
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
                $content = $this->presenter->context->httpRequest->getPost('value');

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
    
    
    

    
    
    
    
    public function handleDeletePhoto($id, $photo) {
        if ($this->getUser()->isInRole('admin')) {
            $row = $this->productModel->loadPhoto($photo);
            if (!$row) {
                $this->flashMessage('There is no photo to delete', 'alert alert-warning');
            } else {

                try{
                    $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $row->PhotoAlbumID . '/' . $row->PhotoURL;
                    if (file_exists($imgUrl)) {
                        unlink($imgUrl); 
                    }


                    $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $row->PhotoAlbumID . '/s-' . $row->PhotoURL;
                    if (file_exists($imgUrl)) {
                        unlink($imgUrl); 
                    }


                    $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $row->PhotoAlbumID . '/m-' . $row->PhotoURL;
                    if (file_exists($imgUrl)) {
                        unlink($imgUrl); 
                    }


                    $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $row->PhotoAlbumID . '/l-' . $row->PhotoURL;
                    if (file_exists($imgUrl)) {
                        unlink($imgUrl); 
                    }

                }
                catch (Exception $e) {
                    \Nette\Diagnostics\Debugger::log($e);
                }
                
                $this->productModel->deletePhoto($photo);
                
                $pht = $this->translator->translate('Photo ');
                $text = $this->translator->translate(' was sucessfully deleted.');
                $message = $pht . $row->PhotoName . $text;
                $this->flashMessage($message, 'alert alert-success');
            }

            $this->redirect('this');
        }
    }
    

    
    public function handleDuplicateProduct($id) {
         if ($this->getUser()->isInRole('admin')) {
             
         $product = $this->productModel->loadProduct($id);
         $duplicate = $this->translator->translate('Copy');
         
         try {
            $new_id = $this->productModel->insertProduct(
                    $duplicate. ' - ' . $product['ProductName'], //Name
                    $product['SellingPrice'], 
                    $product['ProducerID'], //Producer                
                    '11111', //Product Number
                    $product['ProductShort'], $product['ProductDescription'], //Description
                    $product['ProductEAN'], //Ean
                    $product['ProductQR'], //QR
                    'rok', //Warranty
                    $product['PiecesAvailable'], //Pieces
                    $product['CategoryID'], //CatID
                    '' //Date of avail.                
                    //NULL //Comment   
            );

            }
            catch (Exception $e) {
                 \Nette\Diagnostics\Debugger::log($e);
                 $this->redirect('this');
            }
            
            
            
            try {
                $variants = $this->productModel->loadProductVariants($id);
                
                foreach($variants as $vid => $variant) {
                    if($vid != $new_id[0]) {
                        $return = $this->productModel->insertProductVariant(
                                $new_id[0],
                                $variant['ProductVariantName'],
                                $variant['PiecesAvailable'],//Name
                                $variant['SellingPrice']);
                    }
                }
            }
            catch (Exception $e) {
                 \Nette\Diagnostics\Debugger::log($e);
                 $this->redirect('this');
            }
            
                     
             
             $this->redirect('Product:product', $new_id[0]);
         }
    }
    
    

    
    
    /*     * *******************************************************************
     *                      RENDER PRODUCT
     * rendering Product with full info
     * ********************************************************************* */

    public function actionProduct($id, $slug = NULL) {
        $row = $this->productModel->loadProduct($id);
        if (!$row) {
            $text = $this->translator->translate('Product not available');
            $this->flashMessage($text, 'alert alert-warning');
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
                $seo = $this['productSeoForm'];
                $seo->setDefaults(array('id' => $id, 'name' => $row['ProductSeoName']));
                $addForm = $this['addParamForm'];
                $addForm->setDefaults(array('productID' => $id));
                
                $videoForm = $this['productVideoForm'];
                $videoForm->setDefaults(array('id' => $id, 'name' => $row['ProductSeoName']));
               
                
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


    public function handleCoverPhoto($id, $photo) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $row = $this->productModel->loadPhoto($photo);
            if (!$row) {
                $this->flashMessage('There is no photo to set as cover', 'alert');
            } else {
                $this->productModel->updateCoverPhoto($id, $photo);
                $text = $this->translator->translate(' was sucessfully set as COVER.');
                $text2 = $this->translator->translate('Photo ');
                $e = $text2 . $row->PhotoName . $text;

                $this->productModel->coverPhoto($id);
                $this->flashMessage($e, 'alert alert-success');
            }

            $this->redirect('Product:product', $id);
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
                    ->addRule(FORM::IMAGE, 'You can upload only JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Max 2MB', 6400 * 1024);
            $addPhoto->addSubmit('add', 'Add Photo')
                    ->setAttribute('class', 'btn-primary upl col-md-3')
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
                
                    $sizes = $this->shopModel->loadPhotoSize();
                
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Large']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/l-' . $form->values->image->name;
                $image->save($imgUrl);
                
                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Medium']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/m-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Small']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/s-' . $form->values->image->name;
                $image->save($imgUrl);

                $message = $this->translator->translate(' was sucessfully uploaded');
                $photo = $this->translator->translate(' Photo ');
                $e = HTML::el('span', $photo . $form->values->image->name . '' . $message);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert alert-success');
            }

            if($this->isAjax()) {
                $this->invalidateControl('textPhoto');
                
            } else {
            $this->redirect('this');
            }
        }
    }

    protected function createComponentProductVideoForm() {
        if ($this->getUser()->isInRole('admin')) {
            $video = new Nette\Application\UI\Form;
            $video->setTranslator($this->translator);
            $video->addHidden('id');
            $video->addHidden('name');
            $video->addTextArea('videocode', 'Video Code')
                    ->setRequired()
                    ->setAttribute('class', 'form-control');
            $video->addSubmit('save', 'Add Product Video')
                    ->setAttribute('class', 'btn btn-primary upl')
                    ->setAttribute('data-loading-text', 'Saving...');
            $video->onSuccess[] = $this->productVideoFormSubmitted;
            return $video;
        }
    }
    
    public function productVideoFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            $this->productModel->insertVideo($form->values->id, NULL, NULL, $form->values->name, $form->values->videocode);
            
            $this->redirect('this');
        }
    }
    
    
    protected function createComponentProductSeoForm() {
        if ($this->getUser()->isInRole('admin')) {
            $seo = new Nette\Application\UI\Form;
            $seo->setTranslator($this->translator);
            $seo->addHidden('id', '');
            $seo->addText('name', 'SEO Title')
                    ->setAttribute('class', 'form-control')
                    ->setRequired();
            $seo->addSubmit('set', 'Save SEO text')
                    ->setAttribute('class', 'btn btn-primary upl')
                    ->setAttribute('data-loading-text', 'Setting...');
            $seo->onSuccess[] = $this->productSeoFormSubmitted;
            return $seo;
        }
    }
    
    public function productSeoFormSubmitted($form) {
        if ($this->getUser()->isInRole('admin')) {
            $this->productModel->updateProduct($form->values->id, 'ProductSeoName', $form->values->name);
            
            $this->redirect('this');
        }
    }

   
   protected function createComponentAskForm() {
      
            $askForm = new Nette\Application\UI\Form;
            $askForm->setTranslator($this->translator);
            $askForm->addTextArea('note', 'Question:', 7, 4)
                    ->setAttribute('class', 'form-control')
                    ->setRequired('Please enter your question.');
            $askForm->addText('email', 'Email:')
                    ->setEmptyValue('@')
                    ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                    ->setRequired('Please fill your email.')
                    ->setAttribute('class', 'form-control');
            $askForm->addHidden('name', '');
            $askForm->addSubmit('ask', 'Ask')
                    ->setAttribute('class', 'btn btn-primary form-control ')
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

     
   
    
    protected function createComponentVariantControl() {
        $variant = new variantControl($this->productModel, $this->translator);
        $this->addComponent($variant, 'variantControl');      
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
        
        if(isset($this->visited)) {
            $this->visited->prd[$id] = $slug;
        }
       $this->template->setFile( $this->context->parameters['appDir'] . '/templates/Product/' . $layout . '.latte'); 
       if ($this->presenter->getUser()->isInRole('admin')) { 
            if ($this->edit->param != NULL) {
                $this->template->attr = 1;
                $this->edit->param = NULL;
            } else {
                $this->template->attr = 0;
            }
            
            
                
            $this->template->categories = $this->categoryModel->loadCategoryListAdmin();
            $this->template->producers = $this->productModel->loadProducers();
             $this->template->adminAlbum = $this->productModel->loadPhotoAlbum($id);
            
       
            }
        
        $album = $this->productModel->loadPhotoAlbumID($id);
     
        if($album){
            $album = $album->PhotoAlbumID;
        }
       
        $this->template->pieces = $this->productModel->loadTotalPieces($id);
        $this->template->product = $this->productModel->loadProduct($id);
        $this->template->photo = $this->productModel->loadCoverPhoto($id);
        $this->template->albumID = $album;
        $this->template->album = $this->productModel->loadPhotoAlbum($id);
         $this->template->videos = $this->productModel->loadProductVideo($id);
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

    public function actionDefault() {
        $this->redirect('Catalog:default');
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

            $mailIT = new mailControl($this->translator);
            $mailIT->sendSuperMail($contactMail, 'New question about product ' . $name, $template, $email);
    }

}

