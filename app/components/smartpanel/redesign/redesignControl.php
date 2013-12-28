<?php

/**
 * Description of redesignControl
 *
 * @author Lukas
 */

use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image;


class redesignControl extends BaseControl {
    
    /** @persistent */
    public $locale;
    
    protected $translator;
    protected $shopModel;
    protected $shopInfo;
    protected $productModel;


    public function __construct(\ShopModel $shopModel, \ProductModel $productModel, \Kdyby\Translation\Translator $translator) {
        $this->shopModel =  $shopModel;
        $this->translator = $translator;
        $this->productModel = $productModel;
        
        $this->shopInfo = $this->shopModel->getShopSettings();        
    }
    
     public function createTemplate($class = NULL) {
        $template = parent::createTemplate($class);
        $template->setTranslator($this->translator);

        return $template;
     }
     
     public function handleSetShopLayout($layout, $value) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->shopModel->setShopInfo($layout, $value);
            $this->presenter->redirect('this');
        }
    }
    
    public function handleRegenerateThumb() {
        foreach ($this->productModel->loadPhotoAlbum('') as $id => $product) {
            $sizes = $this->shopModel->loadPhotoSize();
            
            if ($product->PhotoAlbumID) {
                foreach ($this->productModel->loadPhotoAlbum($product->ProductID) as $id => $photo) {      
                    $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/images/' . $product->PhotoAlbumID . '/';

                     
                    $image = Image::fromFile($imgUrl . $photo->PhotoURL);
                    $image->resize(null, $sizes['Large']->Value, Image::SHRINK_ONLY);

                    $imgUrl300 = $imgUrl . 'l-' . $photo->PhotoURL;
                    $image->save($imgUrl300);
                    
                    $image = Image::fromFile($imgUrl . $photo->PhotoURL);
                    $image->resize(null, $sizes['Medium']->Value, Image::SHRINK_ONLY);

                    $imgUrl150 = $imgUrl . 'm-' . $photo->PhotoURL;
                    $image->save($imgUrl150);

                    $image = Image::fromFile($imgUrl . $photo->PhotoURL);
                    $image->resize(null, $sizes['Small']->Value, Image::SHRINK_ONLY);

                    $imgUrl50 = $imgUrl . 's-' . $photo->PhotoURL;
                    $image->save($imgUrl50);
             
                }
            }
        }
        $text = $this->translator->translate('Thumbs sucessfully regenerated.');
        $this->presenter->flashMessage($text, 'alert alert-success');
        $this->presenter->redirect("this");
    }

    protected function createComponentAddVideoForm() {
        $videoForm = new Nette\Application\UI\Form;
        $videoForm->setTranslator($this->translator);
        $videoForm->addTextArea('video', 'Video embed code')
                ->setAttribute('class', 'form-control');
        $videoForm->addSubmit('add', 'Add video')
                ->setAttribute('class', 'btn btn-success form-control');
        $videoForm->onSuccess[] = $this->addVideoFormSubmitted;
        return $videoForm;
    }

    public function addVideoFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->shopModel->setShopInfo('homepageVideo', $form->values->video);
            $this->presenter->redirect('this');
        }
    }

    protected function createComponentAddBannerForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addUpload('bannerone', 'Photo:')
                    ->addCondition(Form::FILLED)
                    ->addRule(FORM::IMAGE, 'You can upload only JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Max 2MB', 6400 * 1024);
            $items = array(1 => 'first banner', 2 => 'second banner', 3 => 'third banner', 4 => 'four banner');
            $addPhoto->addSelect('banner', 'Banner pos.', $items)
                    ->setDefaultValue(1)
                    ->setAttribute('class', 'form-control');
            $addPhoto->addText('link', 'Insert link')
                    ->setAttribute('placeholder', 'http://')
                    ->setAttribute('class', 'form-control');
            $addPhoto->addSubmit('add', 'Add Banner')
                    ->setAttribute('class', 'form-control btn btn-primary upl col-md-6')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addPhoto->onSuccess[] = $this->addBannerFormSubmitted;
            return $addPhoto;
        }
    }

    public function addBannerFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($form->values->bannerone->isOK()) {

                if ($this->shopModel->loadBannerByType('banner' . $form->values->banner)) {
                    $this->shopModel->updateBannerByType('banner' . $form->values->banner, $form->values->bannerone->name, $form->values->link);
                } else {
                    $this->shopModel->insertBanner('banner' . $form->values->banner, $form->values->bannerone->name, $form->values->link);
                }
                $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/images/banner/' . $form->values->bannerone->name;
                $form->values->bannerone->move($imgUrl);

                $image = Image::fromFile($imgUrl);

                if ($image->width > 380) {

                    $image->resize(380, null, Image::SHRINK_ONLY);
                    $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/images/banner/' . $form->values->bannerone->name;
                    $image->save($imgUrl);
                }


                $message = $this->translator->translate(' was sucessfully uploaded');
                $photo = $this->translator->translate(' Photo ');
                $e = HTML::el('span', $photo . $form->values->bannerone->name . '' . $message);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert alert-success');
            } else {
                if ($this->shopModel->loadBannerByType('banner' . $form->values->banner)) {
                    $this->shopModel->updateBannerByType('banner' . $form->values->banner, NULL, $form->values->link);
                }
            }

            $this->presenter->redirect('this');
        }
    }

    protected function createComponentAddSliderForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $addPhoto = new Nette\Application\UI\Form;
            $addPhoto->setTranslator($this->translator);
            $addPhoto->addUpload('slideone', 'Photo:')
                    ->addCondition(Form::FILLED)
                    ->addRule(FORM::IMAGE, 'You can upload only JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Max 2MB', 6400 * 1024);
            $items = array(1 => 'first slide', 2 => 'second slide', 3 => 'third slide');
            $addPhoto->addSelect('slide', 'Slider list', $items)
                    ->setDefaultValue(1)
                    ->setAttribute('class', 'form-control');
            $addPhoto->addText('link', 'Insert link')
                    ->setAttribute('placeholder', 'http://')
                    ->setAttribute('class', 'form-control');
            $addPhoto->addSubmit('add', 'Add Photo')
                    ->setAttribute('class', 'form-control btn btn-primary upl col-md-6')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addPhoto->onSuccess[] = $this->addSliderFormSubmitted;
            return $addPhoto;
        }
    }

    public function addSliderFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($form->values->slideone->isOK()) {

                if ($this->shopModel->loadBannerByType('slider' . $form->values->slide)) {
                    $this->shopModel->updateBannerByType('slider' . $form->values->slide, $form->values->slideone->name, $form->values->link);
                } else {
                    $this->shopModel->insertBanner('slider' . $form->values->slide, $form->values->slideone->name, $form->values->link);
                }
                $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/images/slider/' . $form->values->slideone->name;
                $form->values->slideone->move($imgUrl);

                $image = Image::fromFile($imgUrl);

                if ($image->width > 1140) {

                    $image->resize(1140, null, Image::SHRINK_ONLY);
                    $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/images/slider/' . $form->values->slideone->name;
                    $image->save($imgUrl);
                }


                $message = $this->translator->translate(' was sucessfully uploaded');
                $photo = $this->translator->translate(' Photo ');
                $e = HTML::el('span', $photo . $form->values->slideone->name . '' . $message);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->flashMessage($e, 'alert alert-success');
            }

            $this->presenter->redirect('this');
        }
    }

    protected function createComponentAddLogoForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $addLogo = new Nette\Application\UI\Form;
            $addLogo->setTranslator($this->translator);
            $addLogo->addUpload('logo', 'Select your logo')
                    ->addRule(FORM::IMAGE, 'Supported files are JPG, PNG a GIF')
                    ->addRule(FORM::MAX_FILE_SIZE, 'Maximálně 2MB', 6400 * 1024)
                    ->setAttribute('class', 'form-control');
            $addLogo->addSubmit('upload', 'Upload')
                    ->setAttribute('class', 'form-control btn btn-primary');
            $addLogo->onSuccess[] = $this->addLogoFormSubmitted;
            return $addLogo;
        }
    }

    public function addLogoFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {

            if ($form->values->logo->isOK()) {

                $this->shopModel->setShopInfo('Logo', $form->values->logo->name);

                $logoURL = $this->presenter->context->parameters['wwwDir'] . '/images/logo/' . $form->values->logo->name;
                $form->values->logo->move($logoURL);

                $logo = Image::fromFile($logoURL);
                $logo->resize(null, 300, Image::SHRINK_ONLY);

                $logoUrl = $this->presenter->context->parameters['wwwDir'] . '/images/logo/300-' . $form->values->logo->name;
                $logo->save($logoUrl);

                $logo->resize(null, 90, Image::SHRINK_ONLY);

                $logoUrl = $this->presenter->context->parameters['wwwDir'] . '/images/logo/90-' . $form->values->logo->name;
                $logo->save($logoUrl);
            }

            $this->presenter->redirect('this');
        }
    }
    
    protected function createComponentUploadStyleForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {

            $addStyle = new Nette\Application\UI\Form;
            $addStyle->setTranslator($this->translator);
            $addStyle->addUpload('style', 'Select your style.css');
            $addStyle->addSubmit('upload', 'Upload')
                    ->setAttribute('class', 'upl btn btn-primary form-control')
                    ->setAttribute('data-loading-text', 'Uploading...');
            $addStyle->onSuccess[] = $this->uploadStyleFormSubmitted;
            return $addStyle;
        }
    }

    public function uploadStyleFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($form->values->style->isOK()) {

                $this->shopModel->setShopInfo('Style', $form->values->style->name);
                $styleUrl = $this->presenter->context->parameters['wwwDir'] . '/css/themes/' . $form->values->style->name;
                $form->values->style->move($styleUrl);
            }
            $this->presenter->redirect('this');
        }
    }

    public function handleDeleteStyle($name) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $styleUrl = $this->presenter->context->parameters['wwwDir'] . '/css/themes/' . $name;
            $this->shopModel->setShopInfo('Style', '');
            if ($styleUrl) {
                unlink($styleUrl);
            }

            $this->presenter->redirect('this');
        }
    }

    protected function createComponentMenuSwitcherForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {

            $menus = array('Category' => 'Category', 'Info' => 'Info',
                'All' => 'All', 'Cart' => 'Cart', 'Producer' => 'Producer');
            
            $menuSwitcherForm = new Nette\Application\UI\Form;
            $menuSwitcherForm->setTranslator($this->translator);
            $menuSwitcherForm->addSelect('topMenu', 'Top Main Menu', $menus)
                    ->setAttribute('class', 'form-control')
                    ->setDefaultValue($this->shopInfo['TopMenu']->Value);
            $menuSwitcherForm->addSelect('sideMenu', 'Side Menu', $menus)
                    ->setAttribute('class', 'form-control')
                    ->setDefaultValue($this->shopInfo['SideMenu']->Value);
            $menuSwitcherForm->addSelect('footerMenu', 'Footer Menu', $menus)
                    ->setAttribute('class', 'form-control')
                    ->setDefaultValue($this->shopInfo['FooterMenu']->Value);
            $menuSwitcherForm->addSubmit('save', 'Set menus')
                    ->setAttribute('class', 'form-control upl btn btn-primary')
                    ->setAttribute('data-loading-text', 'Setting...');
            ;
            $menuSwitcherForm->onSuccess[] = $this->menuSwitcherFormSubmitted;
            return $menuSwitcherForm;
        }
    }

    public function menuSwitcherFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $this->shopModel->setShopInfo('TopMenu', $form->values->topMenu);
            $this->shopModel->setShopInfo('SideMenu', $form->values->sideMenu);
            $this->shopModel->setShopInfo('FooterMenu', $form->values->footerMenu);

            $this->presenter->redirect('this');
        }
    }
    
    
    public function render() {
        $this->template->setFile(__DIR__ . '/templates/redesignButton.latte');
        $this->template->render();
    }
    
    public function renderModal() {
       $this->template->setFile(__DIR__ . '/templates/redesignModal.latte');
       $this->template->layoutSel = $this->shopInfo['ShopLayout']->Value;
       $this->template->productMiniSel = $this->shopInfo['ProductMiniLayout']->Value;
       $this->template->homepageSel = $this->shopInfo['HomepageLayout']->Value;
       $this->template->productSel = $this->shopInfo['ProductLayout']->Value;
       $this->template->style = $this->shopInfo['Style']->Value;
       $this->template->render(); 
    }
    
    
}
