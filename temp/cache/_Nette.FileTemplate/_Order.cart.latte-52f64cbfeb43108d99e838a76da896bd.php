<?php //netteCache[01]000375a:2:{s:4:"time";s:21:"0.24718600 1363088085";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:53:"C:\xampp\htdocs\platon\app\templates\Order\cart.latte";i:2;i:1363088083;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: C:\xampp\htdocs\platon\app\templates\Order\cart.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'o62l8bi85q')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbf18df240b3_content')) { function _lbf18df240b3_content($_l, $_args) { extract($_args)
;echo Nette\Templating\Helpers::escapeHtml($cart->userName, ENT_NOQUOTES) ?>

<div class="row">
    <div class="span12">
        <h2>Your cart</h2>
        <div class="span5">
            <fieldset>
                <legend>About you</legend>

<?php $_ctrl = $_control->getComponent("cartForm"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
            </fieldset>
        </div>
            <div class="span5">
                <fieldset>
                    <legend>Shiping info</legend>

<?php $_ctrl = $_control->getComponent("shippingForm"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
                </fieldset>
            </div> 
        </div> 
    </div>
</div>




<?php
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $template->_extended = $_extended = TRUE;


if ($_l->extends) {
	ob_start();

} elseif (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
?>


<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 