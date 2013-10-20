<?php

use Nette\Application\UI;
use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;
 

/*
 * Menu Control component
 */

class MenuControl extends BaseControl {

    /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $cart;
    private $categoryModel;
    private $productModel;
    private $blogModel;
    private $shopModel;
    private $usertracking;
    private $orderModel;



    public function setCart($cart) {
        $this->cart = $cart;
    }

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
    
    public function setUserTracking($usertracking) {
        $this->usertracking = $usertracking;
    }

    public function setOrder($order) {
        $this->orderModel = $order;
    }

    public function setShop($shop) {
        $this->shopModel = $shop;
    }
    
    public function createTemplate($class = NULL) {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
}

    private function getBread($catID) {
        $list = $this->categoryModel->loadCategory($catID);
        $menu[$catID][$catID] = $list->CategoryName;
        
        while ($list->HigherCategoryID){
            $list = $this->categoryModel->loadCategory($list->HigherCategoryID);
            $menu[$catID][$list->CategoryID] = $list->CategoryName;
        }
        
        /* @var $menu array */
        $menu = array_reverse($menu[$catID], TRUE);
        return $menu;
        

    }

    private function loadStaticMenu() {
         if($this->parent->getUser()->isInRole('admin')){
             return $this->shopModel->loadStaticText(''); 
         }
         else {
             return $this->shopModel->loadActiveStaticText(''); 
         }
    }
    
    protected function createComponentAddCategoryForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {

            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);

            foreach ($this->categoryModel->loadCategoryListAdmin() as $id => $category) {
                $categories[$id] = $category->CategoryName;
            }
            $prompt = Html::el('option')->setText("-- No Parent --")->class('prompt');

            $addForm->addText('name', 'Name:')
                    ->setAttribute('class', 'form-control')
                    ->setRequired();

            $addForm->addSelect('parent', 'Parent Category:', $categories)
                    ->setAttribute('class', 'form-control')
                    ->setPrompt($prompt);

            $addForm->addSubmit('add', 'Create Category')
                    ->setAttribute('class', 'upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Creating...');
            $addForm->onSuccess[] = $this->addCategoryFormSubmitted;
            return $addForm;
        }
    }

    public function addCategoryFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $row = $this->categoryModel->createCategory($form->values->name, NULL, $form->values->parent);
            $this->presenter->redirect('Product:products', $row);
        }
    }
    
        protected function createComponentAddStaticTextForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {

            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);

            $addForm->addText('name', 'Name:')
                    ->setAttribute('class', 'form-control')
                    ->setRequired();

            $addForm->addSubmit('add', 'Create')
                    ->setAttribute('class', 'upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Creating...');
            $addForm->onSuccess[] = $this->addStaticTextFormSubmitted;
            return $addForm;
        }
    }

    public function addStaticTextFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $row = $this->shopModel->insertStaticText($form->values->name, '', 2);
            $this->productModel->insertPhotoAlbum($form->values->name, '', NULL, NULL, $row);
            $this->presenter->redirect('Blog:staticText', $row);
        }
    }
    
    
    public function renderAll($img = NULL, $ct = NULL) {
        $this->render($img, $ct);
    }

    public function render($img = NULL, $ct = NULL) {
        $this->template->setFile(__DIR__ . '/templates/MenuAllControl.latte');
        if($this->parent->getUser()->isInRole('admin')){
            $this->template->category = $this->categoryModel->loadCategoryListAdmin(); 
        }
        else {
            $this->template->category = $this->categoryModel->loadCategoryList();  
        }
        $this->template->producers = $this->productModel->loadProducers();
        $this->template->menu = $this->loadStaticMenu();
        $this->template->img = $img;
        $this->template->ct = $ct;
        $this->template->render();
    }
    
    
    public function renderCategory($img = NULL, $ct = NULL) {
        $this->template->setFile(__DIR__ . '/templates/MenuCategoryControl.latte');
        if($this->parent->getUser()->isInRole('admin')){
            $this->template->category = $this->categoryModel->loadCategoryListAdmin(); 
        }
        else {
            $this->template->category = $this->categoryModel->loadCategoryList();  
        }
        $this->template->img = $img;
        $this->template->ct = $ct;
        $this->template->render();
    }
    
    public function renderProducer() {
        $this->template->setFile(__DIR__ . '/templates/MenuProducerControl.latte');
        $this->template->producers = $this->productModel->loadProducers();
        $this->template->render();
    }
    
    public function renderBread($catID, $id=null) {
        $this->template->setFile(__DIR__ . '/templates/MenuBreadControl.latte');
        $this->template->category = $this->getBread($catID);
        if ($id) {
        $this->template->product = $this->productModel->loadProduct($id);
        }
        else {
           $this->template->product = null;  
        }
        $this->template->render();
    }
    
    public function renderBreadBlog($catID, $id=null) {
        $this->template->setFile(__DIR__ . '/templates/MenuBreadBlogControl.latte');
        $this->template->category = $this->getBread($catID);
        if ($id) {
        $this->template->blog = $this->blogModel->loadPost($id);
        }
        else {
           $this->template->blog = null;  
        }
        $this->template->render();
    }
    
    
    public function renderCart() {
        $this->template->setFile(__DIR__ . '/templates/MenuCartControl.latte');
        $this->template->cart = $this->cart->numberItems;
        $this->template->render();
    }
    
    public function renderSmartPanel() {
         if($this->presenter->getUser()->isInRole('admin')){
            $this->template->setFile(__DIR__ . '/templates/MenuSmartPanelControl.latte');
            $news = $this->orderModel->loadUnreadOrdersCount($this->usertracking->date);
            $comments = $this->productModel->loadUnreadCommentsCount($this->usertracking->date);
            $this->template->news = $news + $comments;
            $this->template->render();
          }
    }
    
    public function renderInfo() {
        $this->template->setFile(__DIR__ . '/templates/MenuInfoControl.latte');
        $this->template->menu = $this->loadStaticMenu();
        $this->template->render();
    }

    public function renderModal() {
        $this->template->setFile(__DIR__ . '/templates/MenuModal.latte');
        $this->template->render();
    }
    }
