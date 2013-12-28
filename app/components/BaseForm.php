<?php

use Nette\Application\UI\Form;
use Nette\InvalidStateException;
use Nette\ComponentModel\IContainer;

abstract class BaseForm extends Form {
     private $startupCheck = false;

    /** @var \Closure[] array of callbacks, when standard validation is ok */
    public $onValidated = array();
    
    public function __construct(IContainer $parent = NULL, $name = NULL) {
        parent::__construct($parent, $name);
    }

    public function attached($presenter) {
        parent::attached($presenter);
	//    $this->setRenderer(new BaseRenderer());
        //$this->presenterComponentAdapter->adapt($this);
        $this->startup();
        if (!$this->startupCheck) {
            throw new InvalidStateException('If you override startup method, you need call parent::startup(); in '.get_class($this));
        }
        $this->build();
    }

    public function startup() {
        $this->startupCheck = true;
    }

    public abstract function build();

    public function render() {
        $args = func_get_args();
        if (method_exists($this, 'setup')) {
            call_user_func_array(array($this, 'setup'), $args);
        }
        parent::render();
    }

    public function validate(array $controls = NULL) {
        parent::validate($controls);
        if (!$this->getAllErrors()) {
            // only if pre validation is ok, but can add errors (to no success)
            $this->onValidated($this);
        }
    }
    
        }
