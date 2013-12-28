<?php

use Nette\Application\UI\Form;
use Nette\Templates\FileTemplate;
use Nette\Mail\Message;
use Nette\Diagnostics\Debugger;

class ContactControl extends BaseControl {

    protected $translator;
    private $shopModel;
    
    public function __construct(\ShopModel $shopModel, \Kdyby\Translation\Translator $translator) {
        $this->shopModel = $shopModel;
        $this->translator = $translator;
    }
    
    protected function createComponentContactForm() {
        $contactForm = new ContactForm();
        $contactForm->translator = $this->translator;
        $contactForm->onSuccess[] = $this->contactFormSubmitted;
        return $contactForm;
    }

    public function contactFormSubmitted(Form $form) {
        $email = $form->values->email;
        $note = $form->values->note;
        
        if ($this->presenter->isAjax()) {
            $text = $this->translator->translate('Great! We have received your question.');
            $this->presenter->flashMessage($text, 'alert alert-info');
            $form->setValues(array(), TRUE);
            $this->redrawControl();
            $this->presenter->redrawControl('script');
        } else {
            $this->presenter->redirect('this');
        }

        try {
            $this->sendNoteMail($email, $note);
        } catch (Exception $e) {
            \Nette\Diagnostics\Debugger::log($e);
        }
    }

    protected function sendNoteMail($email, $note) {

        $adminMail = $this->shopModel->getShopInfo('OrderMail');
        $shopName = $this->shopModel->getShopInfo('Name');
        $template = new Nette\Templating\FileTemplate($this->context->parameters['appDir'] . '/templates/Email/contactMail.latte');
        $template->registerFilter(new Nette\Latte\Engine);
        $template->registerHelperLoader('Nette\Templating\Helpers::loader');
        $template->email = $email;
        $template->note = $note;
        $template->adminMail = $adminMail;
        $template->shopName = $shopName;

        $orderMail = $this->shopModel->getShopInfo('ContactMail');
        $shopName = $this->shopModel->getShopInfo('Name');

        $mailIT = new mailControl($this->translator);
        $mailIT->sendSuperMail($orderMail, $shopName . ': New question', $template, $email);
    }

    public function render() {
        $this->template->setFile(__DIR__ .'/templates/contact.latte');
        $this->template->render();
    }
}
