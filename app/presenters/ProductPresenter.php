<?php

use Nette\Forms\Form,
        Nette\Utils\Html;
use Kdyby\BootstrapFormRenderer\BootstrapRenderer;
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

    private $id;
    private $catId;
    protected $translator;

    private $albumID;


    protected function startup() {
        parent::startup();
        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;
        

        /* Kontrola přihlášení
         * 
         * if (!$this->getUser()->isInRole('admin')) {
          $this->redirect('Sign:in');
          } */
    }
    
   public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }


    public function handleDeletePhoto($product, $id) {
        if($this->getUser()->isInRole('admin')) {
        $row = $this->productModel->loadPhoto($id);
        if (!$row) {
            $this->flashMessage('There is no photo to delete', 'alert');
        }
        else {
            
            $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $row->PhotoAlbumID . '/' . $row->PhotoURL;
            if ($imgUrl) {
                unlink($imgUrl);
            }
            
            $e = 'Photo ' . $row->PhotoName . ' was sucessfully deleted.';
            
            $this->productModel->deletePhoto($id);
            $this->flashMessage($e, 'alert');
        }
        
        $this->redirect('Product:product', $product);
    }
    }

    

    protected function createComponentProduct() {

        $control = new ProductControl();
        $control->setService($this->context->productModel);
        $control->setCategoryID($this->catId);
        $control->setTranslator($this->translator);
        return $control;
    }
 
    
    
    
    /*
     * Creating form for adding product
     */
    
    public function createComponentAddProductForm() {
        
        if($this->getUser()->isInRole('admin')) {
         
        $category = array();
         
        foreach ($this->categoryModel->loadCategoryList() as $id => $name) {
                    $category[$id] = $name->CategoryName;
                }
                
        $addProduct = new Nette\Application\UI\Form;
        $addProduct->setRenderer(new BootstrapRenderer);
        $addProduct->setTranslator($this->translator);
        $addProduct->addText('name', 'Name:')
                ->setRequired();
        $addProduct->addText('price', 'Price:')
                ->setRequired()
                ->addRule(FORM::FLOAT, 'It has to be a number!');
        $addProduct->addText('amount', 'Amount')
                ->setDefaultValue('1')
                ->addRule(FORM::INTEGER, 'It has to be a number!')
                ->setRequired();
        $addProduct->addTextArea('desc', 'Description: ', 10)
                ->setRequired()
                ->setAttribute('class', 'mceEditor');
        $addProduct->addSelect('cat', 'Category: ', $category)
                ->setDefaultValue($this->catId);
        $addProduct->addText('producer', 'Producer: ')
                ->setDefaultValue('neuvedeno');
        $addProduct->addUpload('image', 'Image:')
                ->addRule(FORM::IMAGE , 'Je podporován pouze soubor JPG, PNG a GIF')
                ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024);
        $addProduct->addSubmit('add', 'Add Product')
                ->setAttribute('class', 'upl')
                ->setAttribute('data-loading-text', 'Adding...');
        $addProduct->onSubmit('tinyMCE.triggerSave()');
        $addProduct->onSuccess[] = $this->addProductFormSubmitted;
        return $addProduct;
        }
    }

    /*
     * Processing added product
     */
    
    public function addProductFormSubmitted($form) {
  if($this->getUser()->isInRole('admin')) {
    //    $albumID = $this->productModel->countPhotoAlbum() + 1;
        
        $albumID =  $this->productModel->insertPhotoAlbum(
                $form->values->name,
                $form->values->desc
                );
        
        $this->productModel->insertProduct(
                $form->values->name, //Name
                $form->values->producer, //Producer
                //$albumID, //Album
                '11111', //Product Number
                $form->values->desc, //Description
                1, //Parametr Album
                '123456', //Ean
                '122', //QR
                'rok', //Warranty
                $form->values->amount, //Pieces
                $form->values->cat  , //CatID
                //2, //PriceID
                '', //Date of avail.                
                1 //Comment             
                
        );
        
        if($form->values->image->isOK()){
        
         $this->productModel->insertPhoto(
                        $form->values->image->name,
                        $albumID,
                        1
                );
          $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $albumID . '/' . $form->values->image->name;
          $form->values->image->move($imgUrl);
           //  dump($form->values->image->getName);
           //  dump($form->values->image->getTemporaryFile);
        }
        
        $this->redirect('Product:products', $form->values->cat);
    }
    
        }


    
    /*
     * Adding product photos
     */
    
    public function createComponentAddPhotoForm()  {
        if($this->getUser()->isInRole('admin')) {
        $addPhoto = new Nette\Application\UI\Form;
        $addPhoto->setRenderer(new BootstrapRenderer);
        $addPhoto->setTranslator($this->translator);
        $addPhoto->addHidden('name', $this->productModel->loadProduct($this->id)->ProductName);
        $addPhoto->addHidden('albumID', $this->albumID);
        $addPhoto->addUpload('image', 'Photo:')
                ->addRule(FORM::IMAGE , 'Je podporován pouze soubor JPG, PNG a GIF')
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
        if($this->getUser()->isInRole('admin')) {
        if($form->values->image->isOK()){
        
         $this->productModel->insertPhoto(
                        $form->values->image->name,
                        $form->values->name,
                        $form->values->albumID
                );
          $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $form->values->albumID . '/' . $form->values->image->name;
          $form->values->image->move($imgUrl);
          
          $e = HTML::el('span', ' Photo ' . $form->values->image->name . ' was sucessfully uploaded');
          $ico = HTML::el('i')->class('icon-ok-sign left');
          $e->insert(0, $ico);
          $this->flashMessage($e, 'alert');
        }
        
        $this->redirect('this');
    }
    }
    
    
    public function createComponentEditDescForm() {
         if($this->getUser()->isInRole('admin')) {
             
           $desc = $this->productModel->loadProduct($this->id)->ProductDescription;  
           $editForm = new Nette\Application\UI\Form;
           $editForm->setTranslator($this->translator);
          // $editForm->setRenderer(new BootstrapRenderer);
           $editForm->addTextArea('text', 'Description:', 150, 150)
                   ->setDefaultValue($desc)
                   ->setRequired()
                ->setAttribute('class', 'mceEditor');
                 
           $editForm->addSubmit('edit', 'Save description')
                   ->setAttribute('class', 'upl btn btn-primary')
                   ->setAttribute('data-loading-text', 'Saving...');
            $editForm->onSubmit('tinyMCE.triggerSave()');
            $editForm->onSuccess[] = $this->editDescFormSubmitted;
            return $editForm;
         }
    }
    
    public function editDescFormSubmitted($form) {
        
    }
    
    /*
     * Handle for removing products 
     */
    
    public function handleDeleteProduct($id, $catID) {
      if($this->getUser()->isInRole('admin')) {
        $this->productModel->deleteProduct($id);
        $this->redirect('Product:products', $catID);
       }
                
    }

    
    /*
     * renderProducts
     * @param ?
     * @param ? example: pozice počátečního znaku
       * @return string
     */

    public function renderProducts($id) {
        
        $this->catId = $id;
        $this->template->products = $this->productModel->loadCatalog($id);
        $this->template->category = $this->categoryModel->loadCategory($id); 
    }
    
    
     /*
     * renderProduct();
     * rendering Product with full info
      * * @param ?
* @param ? example: pozice počátečního znaku
* @return string
     */
    
    public function renderProduct($id) {
        $this->id = $id;
        $row = $this->productModel->loadProduct($id);
        if (!$row) {
            $this->flashMessage('Product not available', 'alert');
            $this->redirect('Homepage:');
        }
        else {

            $this->albumID = $row->PhotoAlbumID;
            $this->template->product = $row;
            $this->template->album = $this->productModel->loadPhotoAlbum($id);
        }
    }

    
    /*
     * renderDefault()
     * rendering default product catalog
     *  @param ?
* @param ? example: pozice počátečního znaku
* @return string
     */
    public function renderDefault()
	{
            $this->redirect('Products');
            $this->template->anyVariable = 'any value';
	}

}
?>
