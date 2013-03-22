<?php //netteCache[01]000376a:2:{s:4:"time";s:21:"0.03579800 1363987879";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:54:"G:\xampp\htdocs\GIT\platon\app\templates\@layout.latte";i:2;i:1363986212;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\templates\@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'u2n11iqncq')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lb93adc7dd0f_title')) { function _lb93adc7dd0f_title($_l, $_args) { extract($_args)
?>BirneShop<?php
}}

//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lb7612dfe74a_head')) { function _lb7612dfe74a_head($_l, $_args) { extract($_args)
;
}}

//
// block scripts
//
if (!function_exists($_l->blocks['scripts'][] = '_lb17645f06a9_scripts')) { function _lb17645f06a9_scripts($_l, $_args) { extract($_args)
?>        
        <script src="<?php echo htmlSpecialChars($basePath) ?>/js/netteForms.js"></script>
        <script src="<?php echo htmlSpecialChars($basePath) ?>/js/main.js"></script>
        <script src="<?php echo htmlSpecialChars($basePath) ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo htmlSpecialChars($basePath) ?>/js/live-form-validation.js"></script>
        
        
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
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="description" content="" />
<?php if (isset($robots)): ?>        <meta name="robots" content="<?php echo htmlSpecialChars($robots) ?>" />
<?php endif ?>

        <title><?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
ob_start(); call_user_func(reset($_l->blocks['title']), $_l, get_defined_vars()); echo $template->upper($template->striptags(ob_get_clean()))  ?></title>

        <link rel="stylesheet" media="screen,projection,tv" href="<?php echo htmlSpecialChars($basePath) ?>/css/screen.css" />
        <link rel="stylesheet" media="print" href="<?php echo htmlSpecialChars($basePath) ?>/css/print.css" />
        <link rel="stylesheet" media="screen" href="<?php echo htmlSpecialChars($basePath) ?>/css/bootstrap.css" />
        <link rel="stylesheet" media="screen" href="<?php echo htmlSpecialChars($basePath) ?>/css/bootstrap-responsive.css" />
        <link rel="stylesheet" href="<?php echo htmlSpecialChars($basePath) ?>/css/font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo htmlSpecialChars($basePath) ?>/css/flag.css" />
        <link rel="shortcut icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" />
        <script src="<?php echo htmlSpecialChars($basePath) ?>/js/jquery.js"></script>
        
	<?php call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars())  ?>

    </head>

    <body>
        <script> document.body.className+=' js' </script>

        
        <div class="container">

            <div class="masterhead">
                <div class="row">
                <a href="<?php echo htmlSpecialChars($_presenter->link("Homepage:default")) ?>" class="span5"><h1>BirneShop!</h1></a>
                
                <div class="span3">
                <?php echo Nette\Templating\Helpers::escapeHtml($template->translate('Choose language:'), ENT_NOQUOTES) ?>

<a href="<?php echo htmlSpecialChars($_control->link("this", array('lang' => 'br'))) ?>
"><img src="<?php echo htmlSpecialChars($basePath) ?>/img/blank.gif" class="flag flag-br" alt="Brazil" /></a>
<a href="<?php echo htmlSpecialChars($_control->link("this", array('lang' => 'en'))) ?>
"><img src="<?php echo htmlSpecialChars($basePath) ?>/img/blank.gif" class="flag flag-gb" alt="English" /></a>
<a href="<?php echo htmlSpecialChars($_control->link("this", array('lang' => 'cs'))) ?>
"><img src="<?php echo htmlSpecialChars($basePath) ?>/img/blank.gif" class="flag flag-cz" alt="Česky" /></a>
                </div>
                <div class="span3">
<?php if ($user->isLoggedIn()): ?>
                    <a href="<?php echo htmlSpecialChars($_presenter->link("Sign:out")) ?>">Logout</a>
<?php endif ?>
                </div>
            </div>
                <div class="row">
                    <div class="span12">
<?php $_ctrl = $_control->getComponent("menu"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
                    </div>
                </div>
                
            </div>
            
            <ul class="breadcrumb">
  <li><a href="#"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Home"), ENT_NOQUOTES) ?></a> <span class="divider">/</span></li>
  <li><a href="#"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("You"), ENT_NOQUOTES) ?></a> <span class="divider">/</span></li>
  <li><a href="#"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Are"), ENT_NOQUOTES) ?></a> <span class="divider">/</span></li>
  <li class="active"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Here"), ENT_NOQUOTES) ?></li>
</ul>
            
            
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>

            
            
            <div class="footer">
                &COPY; Birne shop 2013
            </div>

        </div>
<?php call_user_func(reset($_l->blocks['scripts']), $_l, get_defined_vars())  ?>
    </body>
</html>
