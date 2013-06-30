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


   public function setCategory($cat) {
        $this->categoryModel = $cat;

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
    

public function sendSuperMail($to, $subject, $message) {
        $mail = new Message;
            $mail->setFrom('Lukas <luk.danek@gmail.com>')
            ->addTo('luk.danek@gmail.com')
            ->addTo('jiri.kifa@gmail.com')
            ->setSubject('Zpráva z BIRNE: ' . $subject)
            ->setHtmlBody($message);

           $mailer = new Nette\Mail\SmtpMailer(array(
                'host' => 'smtp.gmail.com',
                'username' => 'obchod@inlinebus.cz',
                'password' => 'cerven31',
                'secure' => 'ssl',
                ));
            $mailer->send($mail);
    }
}


