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
    
    private $row;
    private $parameters;


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

    
    public function handleCoverPhoto($product, $id) {
        if($this->getUser()->isInRole('admin')) {
        $row = $this->productModel->loadPhoto($id);
        if (!$row) {
            $this->flashMessage('There is no photo to delete', 'alert');
        }
        else {
            
            $e = 'Photo ' . $row->PhotoName . ' was sucessfully set as COVER.';
            
            $this->productModel->coverPhoto($id);
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
 
    
    protected function createComponentEditControl() {
        if($this->getUser()->isInRole('admin')) {
                $editControl = new EditControl();
                $editControl->setService($this->productModel);
                $editControl->setTranslator($this->translator);
                $editControl->setParameters($this->productModel->loadParameters($this->row['ProductID']));
                $editControl->setProductID($this->row['ProductID']);
                return $editControl;
            }
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

        $return = $this->productModel->insertProduct(
                $form->values->name, //Name
                $form->values->price,
                $form->values->producer, //Producer                
                '11111', //Product Number
                $form->values->desc, //Description
                '123456', //Ean
                '122', //QR
                'rok', //Warranty
                $form->values->amount, //Pieces
                $form->values->cat  , //CatID
                '', //Date of avail.                
                1 //Comment   
                
                
        );
        
        if($form->values->image->isOK()){
        
         $this->productModel->insertPhoto(
                        $form->values->name,
                        $form->values->image->name,
                        $return[1],
                        1
                );
          $imgUrl = $this->context->parameters['wwwDir'] . '/images/' . $return[1] . '/' . $form->values->image->name;
          $form->values->image->move($imgUrl);
        }
        
        $this->redirect('Product:product', $return[0]);
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
        $addPhoto->addHidden('name', 'name');
        $addPhoto->addHidden('albumID', $this->row['PhotoAlbumID']);
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
                        $form->values->name,
                        $form->values->image->name,
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
           
           // = $this->productModel->loadProduct($id)->ProductDescription;  
           $editForm = new Nette\Application\UI\Form;
           $editForm->setTranslator($this->translator);
          // $editForm->setRenderer(new BootstrapRenderer);
           $editForm->addTextArea('text', 'Description:', 150, 150)
                   ->setDefaultValue($this->row['ProductDescription'])
                   ->setRequired()
                   ->setAttribute('class', 'mceEditor');
           $editForm->addHidden('id', $this->row['ProductID']);
           $editForm->addSubmit('edit', 'Save description')
                   ->setAttribute('class', 'upl btn btn-primary')
                   ->setAttribute('data-loading-text', 'Saving...');
            $editForm->onSubmit('tinyMCE.triggerSave()');
            $editForm->onSuccess[] = $this->editDescFormSubmitted;
            return $editForm;
         }
    }
    
    public function editDescFormSubmitted($form) {
        if($this->getUser()->isInRole('admin')) {
            
           $this->productModel->updateProduct($form->values->id, 'ProductDescription', $form->values->text);
           
           $this->redirect('this');
        }
    }
    
    
    public function createComponentEditPriceForm() {
         if($this->getUser()->isInRole('admin')) {
           
           // = $this->productModel->loadProduct($id)->ProductDescription;  
           $editForm = new Nette\Application\UI\Form;
           $editForm->setTranslator($this->translator);
          // $editForm->setRenderer(new BootstrapRenderer);
           $editForm->addText('price', 'Price:')
                   ->setDefaultValue($this->row['SellingPrice'])
                   ->setRequired()
                   ->addRule(FORM::FLOAT, 'This has to be a number.');
           $editForm->addText('discount', 'Discount:')
                   ->setDefaultValue($this->row['SALE'])
                   ->addRule(FORM::FLOAT, 'This has to be a number.');
           $editForm->addHidden('id', $this->row['ProductID']);
           $editForm->addSubmit('edit', 'Save price')
                   ->setAttribute('class', 'upl btn btn-primary')
                   ->setAttribute('data-loading-text', 'Saving...');
            $editForm->onSuccess[] = $this->editPriceFormSubmitted;
            return $editForm;
         }
    }
    
    public function editPriceFormSubmitted($form) {
        if($this->getUser()->isInRole('admin')) {
          
           $this->productModel->updatePrice($form->values->id, $form->values->price, $form->values->discount);
           
           $this->redirect('this');
        }
    }
    
    
   
    
    
    public function createComponentEditPiecesForm() {
         if($this->getUser()->isInRole('admin')) {
            
           $editForm = new Nette\Application\UI\Form;
           $editForm->setTranslator($this->translator);
           $editForm->setRenderer(new BootstrapRenderer);
           $editForm->addText('pieces', 'Pieces:')
                   ->setDefaultValue($this->row['PiecesAvailable'])
                   ->setRequired()
                   ->addRule(FORM::INTEGER, 'This has to be a number.');
           $editForm->addHidden('id', $this->row['ProductID']);
           $editForm->addSubmit('edit', 'Save it')
                   ->setAttribute('class', 'upl btn btn-primary')
                   ->setAttribute('data-loading-text', 'Saving...');
            $editForm->onSuccess[] = $this->editPiecesFormSubmitted;
            return $editForm;
         }
    }
    
    public function editPiecesFormSubmitted($form) {
        if($this->getUser()->isInRole('admin')) {
            
           $this->productModel->updateProduct($form->values->id, 'PiecesAvailable', $form->values->pieces);
           
           $this->redirect('this');
        }
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
    
public function actionProduct($id) {
     $this->parameters = $this->productModel->loadParameters($id);
    $editForm = $this['editParamForm'];
  
}

protected function createComponentEditParamForm() {
         
           $editForm = new Nette\Application\UI\Form;
           $editForm->setTranslator($this->translator);
           $number = 0;
         
          foreach ($this->parameters as $id => $param) {
               
               $editForm->addText($param->ParameterID, $param->AttribName)
                   ->setDefaultValue($param->Val)
                   ->setRequired();
                   
            
           } 

           $editForm->addSubmit('edit', 'Save attributes')
                   ->setAttribute('class', 'upl btn btn-primary')
                   ->setAttribute('data-loading-text', 'Saving...');
            $editForm->onSuccess[] = $this->editParamFormSubmitted;
            return $editForm;
         
    }
    
    public function editParamFormSubmitted($form) {
           
           foreach($form->values as $id => $value) {
           $this->productModel->updateParameter($id, $value);

           }
          $this->redirect('this');
        
    }
    
protected function createComponentAddParamForm() {
         
           $addForm = new Nette\Application\UI\Form;
           $addForm->setTranslator($this->translator);
         
          foreach ($this->parameters as $id => $param) {
               $options[$id] = $param->AttribName;
           } 
           $addForm->addGroup('Select one of already created:');
           $addForm->addMultiSelect('options', 'Predefined:', $options);
           $addForm->addGroup('Create new atributes:');
           $addForm->addText('newParam', 'Name of atribute:');
           $addForm->addHidden('productID', $this->row['ProductID']);
           $addForm->addSubmit('edit', 'Add attributes')
                   ->setAttribute('class', 'upl btn btn-primary')
                   ->setAttribute('data-loading-text', 'Adding...');
            $addForm->onSuccess[] = $this->editParamFormSubmitted;
            return $addForm;
         
    }
    
    public function addParamFormSubmitted($form) {
           
           foreach($form->values->options as $id => $value) {
           $this->productModel->insertParameter($form->values->ProductID, $id, $value);

           }
          $this->redirect('this');
        
    }
    
    
    public function renderProduct($id) {
        
        $row = $this->productModel->loadProduct($id);
        if (!$row) {
            $this->flashMessage('Product not available', 'alert');
            $this->redirect('Homepage:');
        }
        else {
            
            $this->row = array('ProductID' => $row->ProductID,
                               'PhotoAlbumID' => $row->PhotoAlbumID,
                               'ProductDescription' => $row->ProductDescription,
                               'SellingPrice' => $row->SellingPrice,
                               'SALE' => $row->SALE,
                               'PiecesAvailable' => $row->PiecesAvailable);
            $this->template->product = $row;
            $this->template->album = $this->productModel->loadPhotoAlbum($id);
            $this->template->parameter = $this->productModel->loadParameters($id);
            //$this->prarameters = $this->productModel->loadParameters($id);
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
