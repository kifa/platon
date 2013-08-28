<?php

use Nette\Application\UI;
use Nette\Application\UI\Form;
use Nette\Utils\Html;

/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter {

    /**
     * Sign-in form factory.
     * @return Nette\Application\UI\Form
     */
    private $authenticator;

    private $users;
    
    protected $translator;
     
    protected function startup() {
        parent::startup();

        $this->authenticator = $this->context->authenticator;
        $this->users = $this->context->userModel;
    }

    public function injectTranslator(NetteTranslator\Gettext $translator) {
        $this->translator = $translator;
    }
    
    
    protected function createComponentSignInForm() {
        $form = new UI\Form;

        $form->setTranslator($this->translator);
        $form->addText('username', 'User name:')
                ->setRequired('Please enter your username.')
                ->setAttribute('class', 'form-control');

        $form->addPassword('password', 'Password:')
                ->setRequired('Please enter your password.')
                ->setAttribute('class', 'form-control');

        $form->addCheckbox('remember', 'Keep me signed in')
                ->setAttribute('class', 'form-control');

        $form->addSubmit('send', 'Sign in')
                ->setAttribute('class', 'form-control btn btn-success');

        // call method signInFormSucceeded() on success
        $form->onSuccess[] = $this->signInFormSucceeded;
        return $form;
    }

    public function signInFormSucceeded($form) {
        $values = $form->getValues();

		if ($values->remember) {
			$this->getUser()->setExpiration('+ 14 days', FALSE);
		} else {
			$this->getUser()->setExpiration(0, TRUE); //'+ 1 minutes'
		}

		try {
			$this->getUser()->login($values->username, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}

                $this->usertracking->date = date("Y-m-d H:i:s");
		$this->redirect('Homepage:'); 
    }

    public function actionOut() {
        $this->getUser()->logout();
        $this->flashMessage('You have been signed out.', 'alert alert-success');
        $this->redirect('Sign:in');
    }

}
