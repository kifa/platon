<?php

/*
 * Component to render modal window
 */

class ModalControl extends BaseControl {
    
    
     /* @var Gettext\translator */
    protected $translator;
    
    
    
    /* 
     *Settin Translator to implement localization
     * 
     * @param Nette\Gettext\translator
     * @return void
     */

    
   public function setTranslator($translator) {
        $this->translator = $translator;
    }
    
    /*
     * Create control template for localization
     * 
     * @param NULL
     * @return Translator template
     */

    public function createTemplate($class = NULL) {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);

    return $template;
    }

    /*
     * Rendering component
     */

    public function render() {

        $this->template->setFile(__DIR__ . '/ModalControl.latte');
        $this->template->render();
    }
    
    public function renderInfo($id, $title, $content) {

        $this->template->setFile(__DIR__ . '/ModalControl.latte');
        $this->template->id = $id;
        $this->template->title = $title;
        $this->template->content = $content;
        $this->template->render();
    }
    
    
    
}
