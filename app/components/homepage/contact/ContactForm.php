<?php

use Nette\Forms\Form,
    Nette\Utils\Html;

class ContactForm extends BaseForm{
    
    public $translator;
    
    public function build() {
      $this->setTranslator($this->translator);
      $this->addText('email', 'Email:')
                ->setEmptyValue('@')
                ->addRule(Form::EMAIL, 'Would you fill your email, please?')
                ->setAttribute('class', 'form-control');
      $this->addTextArea('note', 'What would you like to know:')
                ->setRequired()
                ->setAttribute('class', 'form-control');
      $this->addSubmit('send', 'Ask')
                ->setAttribute('class', 'ajax btn btn-success btn-lg form-control')
                ->setAttribute('data-loading-text', 'Asking...');
    }
}
