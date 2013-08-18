<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;

/**
 * Description of productEditControl
 *
 * @author Lukas
 */
class productControl extends BaseControl{
    
     /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $categoryModel;
    private $productModel;
    private $blogModel;
    private $shopModel;


    private $parameters;

    private $row;

    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function setCategory($cat) {
        $this->categoryModel = $cat;

    }
    
    public function setBlog($blog) {
        $this->blogModel = $blog;

    }
    
    public function setProduct($pro) {
        $this->productModel = $pro;
    }
    
    public function setShop($shop) {
        $this->shopModel = $shop;
    }
    
    
    public function setRow($row) {
        $this->row = $row;
        $this->parameters = $this->productModel->loadParameters($this->row['ProductID']);
    }   

    public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
    }
    
     

   /*****************************************************************
    * HANDLE
    */
    

    
    public function handleDeleteProduct($catID, $id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->productModel->deleteProduct($id);
            $this->redirect('this', $catID);
        }
    }

    public function handleArchiveProduct($catID, $id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->productModel->archiveProduct($id);
            $this->redirect('this', $catID);
        }
    }

     public function handleHideProduct($catID, $id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            
            $this->productModel->hideProduct($id);
            
            if($this->presenter->isAjax())
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
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->productModel->showProduct($id);
            
            if($this->presenter->isAjax()) {
                $this->invalidateControl();
                $this->invalidateControl('script');
            }
            else {
            $this->redirect('this', $catID);
            }
        }
    }
    
    
    public function handleDeletePhoto($id, $photo) {
        if ($this->presenter->getUser()->isInRole('admin')) {
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

                $e = 'Photo ' . $row->PhotoName . ' was sucessfully deleted.';

                $this->productModel->deletePhoto($photo);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('Product:product', $id);
        }
    }
    
    public function handleCoverPhoto($id, $photo) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $row = $this->productModel->loadPhoto($photo);
            if (!$row) {
                $this->flashMessage('There is no photo to set as cover', 'alert');
            } else {
                $this->productModel->updateCoverPhoto($id, $photo);
                $e = 'Photo ' . $row->PhotoName . ' was sucessfully set as COVER.';

                $this->productModel->coverPhoto($id);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('Product:product', $id);
        }
    }
    
    public function handleEditProdTitle($prodid){
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax()){            
                $content = $_POST['value'];
                $this->productModel->updateProduct($prodid, 'ProductName', $content);
            }
            if(!$this->isControlInvalid('editProdTitle')){
                $this->presenter->payload->edit = $content;
                $this->presenter->sendPayload();
                $this->invalidateControl('editProdTitle');
            }
            else {
             $this->presenter->redirect('this');
            }

        }
    }
    
    public function handleEditProdDescription($prodid) {
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax()){            
                $content = $_POST['value'];
                $this->productModel->updateProduct($prodid, 'ProductDescription', $content);
            }
            if(!$this->isControlInvalid('editProdDescription')){
                $this->presenter->payload->edit = $content;
                $this->presenter->sendPayload();
                $this->invalidateControl('editProdDescription');
            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }
    
    public function handleEditProdShort($prodid) {
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax()){            
                $content = $_POST['value'];
                $this->productModel->updateProduct($prodid, 'ProductShort', $content);
            }
            if(!$this->isControlInvalid('editProdShort')){
                $this->presenter->payload->edit = $content;
                $this->presenter->sendPayload();
                $this->invalidateControl('editProdShort');
            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }
    
    public function handleEditProdPrice($prodid, $sellingprice, $sale) {
        if($this->presenter->getUser()->isInRole('admin')){
            if($this->presenter->isAjax()){            
                $content = $_POST['value'];

                if ($sale == 0) {
                $this->productModel->updatePrice($prodid, $content, $sale);    
                }
                else {
                  $sale = $sellingprice - $content;
                $this->productModel->updatePrice($prodid, $sellingprice, $sale); 
                }
            }
            if(!$this->isControlInvalid('prodPrice')){
                $this->presenter->payload->edit = $content;
                $this->presenter->sendPayload();
            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }
    
    public function handleSetSale($prodid, $amount, $type){
        if ($this->presenter->getUser()->isInRole('admin')) {            
            $this->productModel->updateSale($prodid, $amount, $type);
            if($this->presenter->isAjax()) {
                $this->invalidateControl('prodPrice');
            }
            else{
              $this->presenter->redirect('this');  
            }
        }
    }

    public function handleEditProdAmount($prodid) {        
        if($this->presenter->getUser()->isInRole('admin')){ 
            if($this->presenter->isAjax())
            {            
                $content = $_POST['value'];

                $this->productModel->updateProduct($prodid, 'PiecesAvailable', $content);
            }
            if(!$this->isControlInvalid('editProdAmount'))
            {
                $this->presenter->payload->edit = $content;
                $this->presenter->sendPayload();
                $this->invalidateControl('editProdAmount');
            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }
    
    public function handleSetProductCategory($id, $catid) {
        if($this->presenter->getUser()->isInRole('admin')){ 
            if($this->presenter->isAjax())
            {            
                $this->productModel->updateProduct($id, 'CategoryID', $catid);
                $this->invalidateControl('productCategory');
                $this->invalidateControl('script');

            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }

    public function handleSetProductProducer($id, $producerid) {
        if($this->presenter->getUser()->isInRole('admin')){ 
            if($this->presenter->isAjax())
            {            
                $this->productModel->updateProduct($id, 'ProducerID', $producerid);
                $this->invalidateControl('content');
                $this->invalidateControl('script');

            }
            else {
             $this->presenter->redirect('this');
            }
        }
    }
    
    public function handleDeleteDocument($product, $id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $row = $this->productModel->loadDocumentation($product)->fetch();
            if (!$row) {
                $this->flashMessage('There is no Docs to delete', 'alert');
            } else {


                $docUrl = $this->context->parameters['wwwDir'] . '/docs/' . $row->ProductID . '/' . $row->DocumentURL;
                if ($docUrl) {
                    unlink($docUrl);
                }

                $e = 'Doc ' . $row->DocumentName . ' was sucessfully deleted.';

                $this->productModel->deleteDocumentation($id);
                $this->flashMessage($e, 'alert');
            }

            $this->redirect('Product:product', $product);
        }
    }

    
    
    
    /*********************************************************************
     * komponenty
     */
    
    protected function createComponentEditControl() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $editControl = new EditControl();
            $editControl->setService($this->productModel);
            $editControl->setTranslator($this->translator);
            $editControl->setParameters($this->productModel->loadParameters($this->row['ProductID']));
            $editControl->setProductID($this->row['ProductID']);
            return $editControl;
        }
    }
    
    protected function createComponentProductEdit() {
        $productEditControl = new productEditControl();
        $productEditControl->setTranslator($this->translator);
        $productEditControl->setProduct($this->productModel);
        $productEditControl->setCategory($this->categoryModel);
        $productEditControl->setBlog($this->blogModel);
        return $productEditControl;
    }
    
    protected function createComponentModalControl() {
        $modalControl = new ModalControl();
        $modalControl->setTranslator($this->translator);
       // $modalControl->setService($this->orderModel);
        return $modalControl;
    }
    
    
    
    protected function createComponentAddPhotoForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addHidden('name', 'name');
            $addPhoto->addHidden('albumID', $this->row['PhotoAlbumID']);
            $addPhoto->addUpload('image', 'Photo:')
                    ->addRule(FORM::IMAGE, 'Je podporován pouze soubor JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024)
                    ->setAttribute('class', 'span3');
            $addPhoto->addSubmit('add', 'Add Photo')
                    ->setAttribute('class', 'btn-primary upl span2')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addPhoto->onSuccess[] = $this->addProductPhotoFormSubmitted;
            return $addPhoto;
        }
    }

    
    public function addProductPhotoFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($form->values->image->isOK()) {

                $this->productModel->insertPhoto(
                        $form->values->name, $form->values->image->name, $form->values->albumID
                );
                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $form->values->albumID . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 300, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $form->values->albumID . '/300-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, 50, Image::SHRINK_ONLY);

                $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $form->values->albumID . '/50-' . $form->values->image->name;
                $image->save($imgUrl);

                $e = HTML::el('span', ' Photo ' . $form->values->image->name . ' was sucessfully uploaded');
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert');
            }

            $this->presenter->redirect('this');
        }
    }
    
    protected function createComponentEditPriceForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {

            $priceForm = new Nette\Application\UI\Form;
            $priceForm->setTranslator($this->translator);

            $priceForm->addText('price', 'Price:')
                    ->setDefaultValue($this->row['SellingPrice'])
                    ->setRequired()
                    ->addRule(FORM::FLOAT, 'This has to be a number.');
            $priceForm->addText('discount', 'Discount:')
                    ->setDefaultValue($this->row['SALE'])
                    ->addRule(FORM::FLOAT, 'This has to be a number.')
                    ->addRule(FORM::RANGE, 'It should be less then price', array(0, $this->row['SellingPrice']));
            $priceForm->addHidden('id', $this->row['ProductID']);
            $priceForm->addSubmit('edit', 'Save price')
                    ->setAttribute('class', 'btn btn-primary')
                    ->setHtmlId('priceSave')
                    ->setAttribute('data-loading-text', 'Saving...');
            $priceForm->onSuccess[] = $this->editPriceFormSubmitted;
            return $priceForm;
        }
    }

    public function editPriceFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {

            
            $this->productModel->updatePrice($form->values->id, $form->values->price, $form->values->discount);
            
            if($this->presenter->isAjax()){
                $this->invalidateControl('prodPrice');
            }
            else {
            $this->presenter->redirect('this');
            }
        }
    }

    protected function createComponentEditParamForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
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
        if ($this->presenter->getUser()->isInRole('admin')) {
            $prevID = null;
            foreach ($form->values as $id => $value) {

                if ($id == 'unit' . $prevID) {
                    $this->productModel->updateParameter($prevID, $prevVal, $value);
                }
                $prevID = $id;
                $prevVal = $value;
            }

            $this->presenter->redirect('this');
        }
    }

    protected function createComponentAddParamForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);


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
            $addForm->addHidden('productID', $this->row['ProductID']);
            $addForm->addSubmit('edit', 'Add Spec')
                    ->setAttribute('class', 'upl-add btn btn-primary')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addForm->onSuccess[] = $this->addParamFormSubmitted;

            return $addForm;
        }
    }

    public function addParamFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {

            if ($form->values->options != '') {
                $this->productModel->insertParameter($form->values->productID, $form->values->options, $form->values->paramValue, $form->values->unit);
            }
            if ($form->values->newParam) {
                $attrib = $this->productModel->insertAttribute($form->values->newParam);
                $this->productModel->insertParameter($form->values->productID, $attrib, NULL, $form->values->unit);
            }
            $this->edit->param = 1;
            $this->presenter->redirect('this');
        }
    }

    protected function createComponentDeleteParamForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
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
        if ($this->presenter->getUser()->isInRole('admin')) {

            foreach ($form->values as $id => $value) {
                if ($value == TRUE) {
                    $this->productModel->deleteParameter($id);
                }
            }
            $this->presenter->redirect('this');
        }
    }
    
    protected function createComponentAddDocumentationForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;

            $addPhoto->setTranslator($this->translator);
            $addPhoto->addText('name', 'Name:');
            $addPhoto->addHidden('productid', $this->row['ProductID']);
            $addPhoto->addUpload('doc', 'Document:')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
            $addPhoto->addText('desc', 'Description', 20, 100);
            $addPhoto->addSubmit('add', 'Add Document')
                    ->setAttribute('class', 'btn-primary upl')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addPhoto->onSuccess[] = $this->addDocumentationFormSubmitted;
            return $addPhoto;
        }
    }

    /*
     * Adding submit form for adding photos
     */

    public function addDocumentationFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($form->values->doc->isOK()) {

                $this->productModel->insertDocumentation(
                        $form->values->name, $form->values->doc->name, $form->values->productid, $form->values->desc
                );
                $imgUrl = $this->context->parameters['wwwDir'] . '/docs/' . $form->values->productid . '/' . $form->values->doc->name;
                $form->values->doc->move($imgUrl);

                $e = HTML::el('span', ' Docs ' . $form->values->doc->name . ' was sucessfully uploaded');
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert');
            }

            $this->presenter->redirect('this');
        }
    }

    /***********************************************************************
     * RENDERY
     */
    
    public function renderProductMini($id) {
        $layout = $this->shopModel->getShopInfo('ProductMiniLayout');
       
        $this->template->setFile(__DIR__ . '/' . $layout . '.latte');    
        $this->template->product = $this->productModel->loadProduct($id);
        $this->template->render();
    }
    
    
    public function renderProductPage($id) {
        
        $this->template->setFile(__DIR__ . '/productPageControl.latte');
        
        if ($this->presenter->getUser()->isInRole('admin')) { }
           /* if ($this->edit->param != NULL) {
                $this->template->attr = 1;
                $this->edit->param = NULL;
            } else {
                $this->template->attr = 0;
            }*/
        if ($this->presenter->getUser()->isInRole('admin')) {  
            $this->template->categories = $this->categoryModel->loadCategoryListAdmin();
        } else {
            $this->template->categories = $this->categoryModel->loadCategoryList();
        }
            $this->template->producers = $this->productModel->loadProducers();
       
        
        $this->template->product = $this->productModel->loadProduct($id);
        $this->template->photo = $this->productModel->loadCoverPhoto($id);
        $this->template->album = $this->productModel->loadPhotoAlbum($id);
        $this->template->parameter = $this->productModel->loadParameters($id);
      
        $this->template->docs = $this->productModel->loadDocumentation($id)->fetchPairs('DocumentID');
        $this->template->render();
    }
    
   /* public function renderProductMini($catID){
        $this->catId = $catID;

        if ($this->presenter->getUser()->isInRole('admin')) {
            // load all products
            $this->template->products = $this->productModel->loadCatalogAdmin($catID);
            $this->template->categories = $this->categoryModel->loadCategoryList();
        } else {
            // load published products
            $this->template->products = $this->productModel->loadCatalog($catID);
        }

        $this->template->category = $this->categoryModel->loadCategory($catID);
    } */
}