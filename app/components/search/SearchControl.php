<?php

use Nette\Application\UI;
use Nette\Forms\Form,
    Nette\Utils\Html,
    Nette\Image,
    Nette\Templates\FileTemplate;

/*
 * Menu Control component
 */

class SearchControl extends BaseControl {

    /** @persistent */
    public $locale;

    /** @var NetteTranslator\Gettext */
    protected $translator;
   
    
    public function __construct(\Kdyby\Translation\Translator $translator) 
    {
        $this->translator = $translator;
    }   
    
    public function createTemplate($class = NULL)
{
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);
    // případně $this->translator přes konstrukt/inject

    return $template;
}
    
    protected function createComponentSearchForm() {
        $searchForm = new Nette\Application\UI\Form;
        $searchForm->setTranslator($this->translator);
        $text = $this->translator->translate('What are you looking for?');
        $searchForm->addText('search', '')
                ->setType('search')
                ->setAttribute('placeholder', $text)
                ->setAttribute('class', 'form-control');
        $searchForm->addSubmit('srch', 'Search')
                ->setAttribute('class', 'btn btn-default');
        $searchForm->onSuccess[] = $this->searchFormSubmitted;
        return $searchForm;
    }
    
    public function searchFormSubmitted($form) {
        $query = $form->values->search;
        $this->presenter->redirect('Homepage:search', $query);
        
    }


    public function render() {
        $this->template->setFile(__DIR__ . '/SearchControl.latte');
        
        $this->template->render();
    }
    
     public function renderMenu() {
        $this->template->setFile(__DIR__ . '/SearchMenuControl.latte');
        
        $this->template->render();
    }
      
    
    public function renderTop() {
        $this->template->setFile(__DIR__ . '/SearchTopControl.latte');
        $this->template->cart = $this->cart->numberItems;
        if($this->presenter->getUser()->isInRole('admin')){
        $news = $this->orderModel->loadUnreadOrdersCount($this->usertracking->date);
        $comments = $this->productModel->loadUnreadCommentsCount($this->usertracking->date);
        $this->template->news = $news + $comments;
        }
        $this->template->render();
    }
    
    
    public function renderSide() {
        $this->template->setFile(__DIR__ . '/SearchSideControl.latte');
        $this->template->menu = $this->loadStaticMenu();
        $this->template->render();
    }
}
