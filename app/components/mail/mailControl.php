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


    public function __construct(\Gettext\Gettext $translator) {
        parent::__construct();
        $this->translator = $translator;
    }

      
 
    public function createTemplate($class = NULL)
    {
    $template = parent::createTemplate($class);
    $template->setTranslator($this->translator);

    return $template;
}
    

public function sendSuperMail($to, $subject, $message, $from='shop@bebica.it') {
        
        $mail = new Message;
            $mail->setFrom($from)
            ->addTo($to)
            ->setSubject($subject)
            ->setHtmlBody($message);
            //->send();
              
           $mailer = new Nette\Mail\SmtpMailer(array(
                'host' => 'smtp.gmail.com',
                'username' => 'obchod@inlinebus.cz',
                'password' => 'cerven31',
                'secure' => 'ssl',
                )); 
           
           $mailer->send($mail);   
           
    }
}