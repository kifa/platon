<?php


use Nette\Application\UI\Form;

class OrderNoteForm extends BaseForm{

    public $translator;
    
    public function build() {
        $this->setTranslator($this->translator);
        $this->addHidden('orderID', '');
        $this->addHidden('userName', '');
        $this->addTextArea('note', 'Your Note:')
                ->setRequired()
                ->setAttribute('class', 'form-control');  
        $this->addSubmit('add' , 'Add note')
                ->setAttribute('class', 'btn-primary upl')
                ->setAttribute('data-loading-text', 'Adding...');
        
    }
}
