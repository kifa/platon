<?php

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image,
    Nette\Templates\FileTemplate;
use Nette\Mail\Message;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    private $productModel;
    private $catalogModel;
    private $categoryModel;
    private $shopModel;
    private $blogModel;
    protected $translator;
    public $locale;

    protected function startup() {
        parent::startup();
    }

    public function injectTranslator(\Kdyby\Translation\Translator $translator) {
        $this->translator = $translator;
    }
    
    public function injectCatalogModel(\CatalogModel $catalogModel) {
        parent::injectCatalogModel($catalogModel);
        $this->catalogModel = $catalogModel;
    }

    public function injectCategoryModel(\CategoryModel $categoryModel) {
        parent::injectCategoryModel($categoryModel);
        $this->categoryModel = $categoryModel;
    }

    public function injectShopModel(\ShopModel $shopModel) {
        parent::injectShopModel($shopModel);
        $this->shopModel = $shopModel;
    }

    public function injectBlogModel(\BlogModel $blogModel) {
        parent::injectBlogModel($blogModel);
        $this->blogModel = $blogModel;
    }

    public function injectProductModel(\ProductModel $productModel) {
        parent::injectProductModel($productModel);
        $this->productModel = $productModel;
    }

    public function actionDefault() {
        if ($this->getUser()->isInRole('admin')) {
            $this['addVideoForm'];
        }
    }

    public function renderDefault() {

        $this->template->products = $this->catalogModel->loadMainPage();

        $this->template->slider = 1;

        $this->template->sliderone = $this->shopModel->loadBannerByType('slider1');
        $this->template->slidertwo = $this->shopModel->loadBannerByType('slider2');
        $this->template->sliderthree = $this->shopModel->loadBannerByType('slider3');

        $this->template->bannerone = $this->shopModel->loadBannerByType('banner1');

        $this->template->bannertwo = $this->shopModel->loadBannerByType('banner2');
        $this->template->bannerthree = $this->shopModel->loadBannerByType('banner3');
        $this->template->bannerfour = $this->shopModel->loadBannerByType('banner4');

        $home = $this->shopModel->getShopInfo('HomepageLayout');
        $this->template->home = $home . '.latte';
        $this->template->categories = $this->categoryModel->loadFeaturedCategories();
        $this->template->video = $this->shopModel->getShopInfo('homepageVideo');
        $this->template->anyVariable = 'any value';
    }

    public function renderSearch($query) {
        $this->template->query = $query;
        if ($query != NULL || $query != '') {
            $this->template->products = $this->catalogModel->search($query);
            $this->template->categories = $this->categoryModel->search($query);
            $this->template->posts = $this->blogModel->search($query);
        }
    }


    public function actionContact() {
        if ($this->getUser()->isInRole('admin')) {

            $albumid = $this->blogModel->loadPhotoAlbumStatic(3);
            if ($albumid == FALSE) {
                $row = $this->shopModel->loadStaticText(3);
                $albumid = $this->productModel->insertPhotoAlbum($row->StaticTextName, '', NULL, NULL, 3);
            }
        }
    }
    
    public function createComponentContactControl() {
        $contact = new ContactControl($this->shopModel, $this->translator);
        $this->addComponent($contact, 'contactControl');
        return $contact;
    }

    public function handleEditContactTextHeading($staticID) {
        if ($this->user->isInRole('admin')) {
            if ($this->isAjax()) {
                $content = $_POST['value'];
                $this->shopModel->updateStaticText($staticID, 'StaticTextName', $content);
            }
            if (!$this->isControlInvalid('contactTextHeading')) {
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('contactTextHeading');
            } else {
                $this->redirect('this');
            }
        }
    }

    public function handleEditContactText($staticID) {
        if ($this->user->isInRole('admin')) {
            if ($this->isAjax()) {
                $content = $_POST['value'];
                $this->shopModel->updateStaticText($staticID, 'StaticTextContent', $content);
            }
            if (!$this->isControlInvalid('contactText')) {
                $this->payload->edit = $content;
                $this->sendPayload();
                $this->invalidateControl('contactText');
            } else {
                $this->redirect('this');
            }
        }
    }

    protected function createComponentAddPhotoStatic() {
        
        $addPhoto = new AddPhotoControl($this->productModel, $this->shopModel, $this->translator);
        $this->addComponent($addPhoto, 'addPhotoControl');
        return $addPhoto;
    }

    public function renderContact() {
        if ($this->getUser()->isInRole('admin')) {
            $this->template->album = $this->blogModel->loadPhotoAlbumStatic(3);
            $this->template->adminAlbum = $this->blogModel->loadPhotoAlbumStatic(3);
        }
        $this->template->post = $this->shopModel->loadStaticText(3);
        $this->template->companyName = $this->shopModel->getShopInfo('Name');
        $this->template->companyPhone = $this->shopModel->getShopInfo('ContactPhone');
        $this->template->companyMail = $this->shopModel->getShopInfo('ContactMail');
        $this->template->companyAddress = $this->shopModel->getShopInfo('CompanyAddress');
        $this->template->account = $this->shopModel->getShopInfo('Account');
    }

    public function renderPhotos() {
        $this->template->Albums = $this->productModel->loadPhotoAlbum('');
    }

    public function renderPhoto($id) {
        $this->template->product = $this->productModel->loadProduct($id);
        $this->template->Albums = $this->productModel->loadPhotoAlbum($id);
    }
}
