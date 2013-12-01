<?php

use Nette\Application\UI,
    Nette\Forms\Form,
    Nette\Utils\Html;
use Nette\Http\Request;

/*
 * Component to render modal window
 */

class paymentControl extends BaseControl {
    /* @var Gettext\translator */

    protected $translator;
    protected $orderModel;
    private $row;

    public function __construct(\OrderModel $orderModel, \Kdyby\Translation\Translator $translator) {
        parent::__construct();

        $this->orderModel = $orderModel;
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

    public function handleEditPaymentName($paymentID, $price) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($this->presenter->isAjax()) {
                //$name = $_POST['id'];
                $content = $this->presenter->context->httpRequest->getPost('value');
                $this->orderModel->updatePaymentName($paymentID, $content);

                $text = $this->translator->translate('was sucessfully updated.');
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $message = HTML::el('span', ' ' . $text);
                $message->insert(0, ' ' . $content);
                $message->insert(0, $ico);
                $this->presenter->flashMessage($message, 'alert alert-success');
            }
            if (!$this->isControlInvalid()) {
                $this->presenter->payload->edit = $content;
                $this->presenter->sendPayload();
                $this->invalidateControl('payment');
                //$this->invalidateControl('flashMessages');
            } else {
                $this->presenter->redirect('this');
            }
        }
    }

    public function handleEditPaymentPrice($paymentID, $name) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($this->presenter->isAjax()) {
                //$name = $_POST['id'];
                $content = $this->presenter->context->httpRequest->getPost('value');
                $this->orderModel->updatePaymentPrice($paymentID, $content);
                $text = $this->translator->translate('was sucessfully updated.');
                $ico = HTML::el('i')->class('icon-ok-sign left');
                $message = HTML::el('span', ' ' . $text);
                $message->insert(0, ' ' . $name);
                $message->insert(0, $ico);
                $this->presenter->flashMessage($message, 'alert alert-success');
            }
            if (!$this->isControlInvalid()) {
                $this->presenter->payload->edit = $content;
                $this->presenter->sendPayload();
                $this->presenter->invalidateControl('payment');
            } else {
                $this->presenter->redirect('this');
            }
        }
    }

    public function handleEditPaymentStatus($payid) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            if ($this->presenter->isAjax()) {
                $content = $this->presenter->context->httpRequest->getPost('value'); //odesílaná nová hodnota
                $this->orderModel->updatePaymentStatus($payid, $content);
            }

            if (!$this->isControlInvalid()) {
                $this->presenter->payload->edit = $content; //zaslání nové hodnoty do šablony
                $this->presenter->sendPayload();
                $this->presenter->invalidateControl('payment'); //invalidace snipetu
            } else {
                $this->presenter->redirect('this');
            }
        }
    }

    public function handleRemovePay($pay_id) {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $row = $this->orderModel->deletePayment($pay_id);
            $text = $this->translator->translate('was removed.');
            $message = Html::el('span', ' ' . $text);
            $e = Html::el('i')->class('icon-ok-sign left');
            //$message->insert(0, ' '. $row->PaymentName);
            $message->insert(0, $e);
            $this->flashMessage($message, 'alert alert-success');

            if ($this->presenter->isAjax()) {
                $this->invalidateControl('payment');
                //  $this->invalidateControl('paymentName-'.$pay_id);
                //  $this->invalidateControl();
                //$this->presenter->invalidateControl();
                //$this->presenter->invalidateControl('content');
            } else {
                $this->presenter->redirect("this");
            }
        }
    }
    
         protected function createComponentAddPaymentForm() {
        if ($this->presenter->getUser()->isInRole('admin')) {
            $addForm = new Nette\Application\UI\Form;
            $addForm->setTranslator($this->translator);

            $addForm->addGroup('Create new payment:');
            $addForm->addText('newPay', 'Payment name:')
                    ->setRequired()
                    ->setAttribute('class', 'form-control');
            $addForm->addText('pricePay', 'Payment price:')
                    ->setRequired()
                    ->setAttribute('class', 'form-control')
                    ->addRule(FORM::FLOAT, 'This has to be a number');
                         //->setDefaultValue(0)
                    //->addRule(FORM::FLOAT, 'This has to be a number.');
            $addForm->addSubmit('add', 'Add Payment')
                    ->setAttribute('class', 'upl-add btn btn-success form-control')
                    ->setAttribute('data-loading-text', 'Adding...');
            $addForm->onSuccess[] = $this->addPaymentFormSubmitted;

            return $addForm;
        }
    }

    public function addPaymentFormSubmitted($form) {
        if ($this->presenter->getUser()->isInRole('admin')) {

            $this->orderModel->insertPayment($form->values->newPay,
                                              $form->values->pricePay,
                                              1
                                              );
            
            $text = $this->translator->translate('was added sucessfully to your payment method.');
            $ico = HTML::el('i')->class('icon-ok-sign left');
            $message = HTML::el('span', ' '.$text);
            $message->insert(0, ' ' . $form->values->newPay);
            $message->insert(0, $ico);
            $this->presenter->flashMessage($message, 'alert alert-success');
            $this->presenter->redirect('this');
        }
    }

    public function render() {

        $this->template->setFile(__DIR__ . '/templates/smartpanelPaymentList.latte');
        $this->template->payments = $this->orderModel->loadPayment('');
        $this->template->render();
    }

    public function renderJs($payment) {
        $this->template->setFile(__DIR__ . '/templates/smartpanelPaymentJs.latte');
        $this->template->payment = $payment;
        $status = array();

        foreach ($this->orderModel->loadStatuses('') as $key => $value) {
            $status[$key] = $value->StatusName;
        };

        $this->template->status = $status;
        $this->template->render();
    }
    
    public function renderForm() {
        $this->template->setFile(__DIR__ . '/templates/smartpanelPaymentForm.latte');
        $this->template->render();
    }

}
