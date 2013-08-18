<?php

use Nette\Mail\Message;

/**
 * Description of mailControl
 *
 * @author Lukas
 */
class mailControl extends BaseControl {
    
      /** @persistent */
    public $lang;

    /** @var NetteTranslator\Gettext */
    protected $translator;
    private $categoryModel;
    private $productModel;
    private $blogModel;
    private $shopModel;


    public function setCategory($cat) {
        $this->categoryModel = $cat;

    }
    
    public function setShop($shop){
        $this->shopModel = $shop;
    }

    public function setBlog($blog) {
        $this->blogModel = $blog;

    }
    
    public function setProduct($pro) {
        $this->productModel = $pro;
    }
    
    
   public function setTranslator($translator) {
        $this->translator = $translator;
    }
    
 
    public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);

    return $template;
}
    

public function sendSuperMail($to, $subject, $message, $from='luk.danek@gmail.com') {
        
        $mail = new Message;
            $mail->setFrom($from)
            ->addTo($to)
            ->setSubject($subject)
            ->setHtmlBody($message)
            ->send();
   /*

           $mailer = new Nette\Mail\SmtpMailer(array(
                'host' => 'smtp.gmail.com',
                'username' => 'obchod@inlinebus.cz',
                'password' => 'cerven31',
                'secure' => 'ssl',
                )); 
            $mailer->send($mail);   */
    }
}