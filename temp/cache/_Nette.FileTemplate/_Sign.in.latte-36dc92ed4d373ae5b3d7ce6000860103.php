<?php //netteCache[01]000372a:2:{s:4:"time";s:21:"0.60302200 1364309423";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:50:"C:\xampp\htdocs\platon\app\templates\Sign\in.latte";i:2;i:1364165855;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: C:\xampp\htdocs\platon\app\templates\Sign\in.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '2nys7crpji')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb95dc1d99fc_content')) { function _lb95dc1d99fc_content($_l, $_args) { extract($_args)
?><div class="page-header">
<?php call_user_func(reset($_l->blocks['title']), $_l, get_defined_vars())  ?>
</div>
<div class="row">

    <div class="span12">

<?php $iterations = 0; foreach ($flashes as $flash): ?>
        <div class="<?php echo htmlSpecialChars($flash->type) ?>">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
<?php echo Nette\Templating\Helpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ?>
        
<?php $_ctrl = $_control->getComponent("signInForm"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
        
<?php $_ctrl = $_control->getComponent("newUserForm"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
        
<?php $_ctrl = $_control->getComponent("passwordForm"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>


<?php
}}

//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lb38731b1431_title')) { function _lb38731b1431_title($_l, $_args) { extract($_args)
?>    <h1>Sign in <small>To continue, you need to be logged in!</small></h1>
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
$robots = 'noindex' ?>

<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 