<?php
use Nette\Application\UI\Form;
use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image,
    Nette\Templates\FileTemplate;

class AddPhotoControl extends BaseControl{
    
    protected $translator;
    private $shopModel;
    private $productModel;
    
    public function __construct(\ProductModel $productModel, \ShopModel $shopModel,
                                \Kdyby\Translation\Translator $translator) {
        $this->shopModel = $shopModel;
        $this->productModel = $productModel;
        $this->translator = $translator;
    }

    protected function createComponentAddPhotoStaticForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
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

    public function addPhotoStaticFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($form->values->image->isOK()) {

                $this->productModel->insertPhoto(
                        $form->values->name, $form->values->image->name, $form->values->textalbumid
                );

                $sizes = $this->shopModel->loadPhotoSize();

                $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/images/static/' . $form->values->textalbumid . '/' . $form->values->image->name;
                $form->values->image->move($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Large']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/images/static/' . $form->values->textalbumid . '/l-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Medium']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/images/static/' . $form->values->textalbumid . '/m-' . $form->values->image->name;
                $image->save($imgUrl);

                $image = Image::fromFile($imgUrl);
                $image->resize(null, $sizes['Small']->Value, Image::SHRINK_ONLY);

                $imgUrl = $this->presenter->context->parameters['wwwDir'] . '/images/static/' . $form->values->textalbumid . '/s-' . $form->values->image->name;
                $image->save($imgUrl);

                $text = $this->translator->translate('was sucessfully uploaded');
                $e = HTML::el('span', ' Photo ' . $form->values->image->name . ' ' . $text);
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $e->insert(0, $ico);
                $this->presenter->flashMessage($e, 'alert');
            }

            $this->presenter->redirect('this');
        }
    }

    public function render() {
        
    }
}
