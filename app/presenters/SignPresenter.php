<?php

use Nette\Application\UI;
use Nette\Application\UI\Form;
use  Nette\Utils\Html;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{


	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
    
    
    private $authenticator;
    
    protected function startup()
    {
        parent::startup();
        
        $this->authenticator = $this->context->authenticator;
    }
    
	protected function createComponentSignInForm()
	{
		$form = new UI\Form;
		$form->addText('username', 'Uživatelské jméno:')
			->setRequired('Please enter your username.');

		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Keep me signed in');

		$form->addSubmit('send', 'Sign in');

		// call method signInFormSucceeded() on success
		$form->onSuccess[] = $this->signInFormSucceeded;
		return $form;
	}



	public function signInFormSucceeded($form)
	{
		$values = $form->getValues();
 

		if ($values->remember) {
			$this->getUser()->setExpiration('+ 14 days', FALSE);
		} else {
			$this->getUser()->setExpiration('+ 20 minutes', TRUE);
		}

		try {
                        
			 $this->getUser()->login($values->username, $values->password);
		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
			return;
		}

		$this->redirect('Homepage:');
	}



	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('Sign:in');
	}
        
        protected function createComponentPasswordForm()
    {
        $form = new Form();
        $form->addText('id', 'Login:', 3);
        $form->addPassword('newPassword', 'Nové heslo:', 30)
            ->addRule(Form::MIN_LENGTH, 'Nové heslo musí mít alespoň %d znaků.', 6);
        $form->addPassword('confirmPassword', 'Potvrzení hesla:', 30)
            ->addRule(Form::FILLED, 'Nové heslo je nutné zadat ještě jednou pro potvrzení.')
            ->addRule(Form::EQUAL, 'Zadná hesla se musejí shodovat.', $form['newPassword']);
        $form->addSubmit('set', 'Změnit heslo');
        $form->onSuccess[] = $this->passwordFormSubmitted;
        return $form;
    }


    public function passwordFormSubmitted(Form $form)
    {
        $values = $form->getValues();
        $user = $form->getValues('id');
        try {
           // $this->authenticator->authenticate(array($user->getIdentity()->username, $values->oldPassword));
            $this->authenticator->setPassword($values->id, $values->newPassword);
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' Your password was successfully changed.');
            $message -> insert(0, $ico);
            $this->flashMessage($message, 'alert alert-info');
            $this->redirect('this');
        } catch (NS\AuthenticationException $e) {
            $form->addError('Zadané heslo není správné.');
        }
    }
    
    protected function createComponentNewUserForm() {
        $form = new Form();
        $form ->addText('username', 'Uživatelské jméno:', 10);
        $form  ->addText('name', 'Vaše jméno', 30);
        $form ->addPassword('password', 'Heslo:', 30)
                ->addRule(Form::MIN_LENGTH, 'Nové heslo musí mí alespoň %d znaků', 6);
        $form ->addPassword('confirmPassword', 'Heslo pro kontrolu', 30)
                ->addRule(Form::FILLED, 'Je nutné vyplnit!')
                ->addRule(Form::EQUAL, 'Zadaná hesla se musí shodovat', $form['password']);
        $form ->addSubmit('add', 'Zaregistrovat');
        $form->onSuccess[] = $this->newUserFormSubmitted;
        return $form;
    }
    
    public function newUserFormSubmitted(Form $form) {
        $value = $form->getValues();
        try {
           $this->authenticator->userAdd($value->name, $value->username, $value->password);
           $this->flashMessage('Jste zaregistrováni. Můžete se přihlásit', 'success') ;
           $this->redirect('Sign:in');
    }   catch (NS\AuthenticationException $e) {
            $form ->addError('Prostě nám to nejde');
        }
    }

}
