<?php

use Nette\Mail\Message,
    Nette\Mail\IMailer;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailPresenter extends BasePresenter {

    
    private $productModel;
    private $categoryModel;
    protected $translator;
    private $mailer;


    protected function startup() {
        parent::startup();
        $this->productModel = $this->context->productModel;
        $this->categoryModel = $this->context->categoryModel;
   }

    public function injectTranslator(GettextTranslator\Gettext $translator) {
        $this->translator = $translator;
    }
    
    public function injectMailer(Nette\Mail\IMailer $mailer) {
        $this->mailer = $mailer;
    }
    
    
    public function handleMail() {
        
        $template = new Nette\Templating\FileTemplate(__DIR__ . '/../templates/Email/yourOrder.latte');
        $template->registerFilter(new Nette\Latte\Engine);
        $template->registerHelperLoader('Nette\Templating\Helpers::loader');
        $template->orderId = 123;

        $mail = new Message;
        $mail->setFrom('Franta <franta@example.com>')
                ->addTo('luk.danek@gmail.com')
                ->setHtmlBody($template)
                ;
        
        /*$mailer = new Nette\Mail\SmtpMailer(array(
        'host' => 'smtp.gmail.com',
        'username' => 'luk.danek@gmail.com',
        'password' => 'heslo',
        'secure' => 'ssl'
        ));
        $mailer->send($mail); */
        
        $this->mailer->send($mail);
        
        $this->redirect('this');
    }
    
    public function renderDeafult() {
        
    }

}
