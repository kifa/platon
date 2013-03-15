<?php //netteCache[01]000376a:2:{s:4:"time";s:21:"0.06817100 1363386978";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:54:"I:\xampp\htdocs\GIT\platon\app\templates\@layout.latte";i:2;i:1363386974;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: I:\xampp\htdocs\GIT\platon\app\templates\@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'hv9unewdfl')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lb61a9fdc3b1_title')) { function _lb61a9fdc3b1_title($_l, $_args) { extract($_args)
?>BirneShop<?php
}}

//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lbc85cbc194c_head')) { function _lbc85cbc194c_head($_l, $_args) { extract($_args)
;
}}

//
// block scripts
//
if (!function_exists($_l->blocks['scripts'][] = '_lbc1e8088847_scripts')) { function _lbc1e8088847_scripts($_l, $_args) { extract($_args)
?>        <script src="<?php echo htmlSpecialChars($basePath) ?>/js/jquery.js"></script>
        <script src="<?php echo htmlSpecialChars($basePath) ?>/js/netteForms.js"></script>
        <script src="<?php echo htmlSpecialChars($basePath) ?>/js/main.js"></script>
        <script src="<?php echo htmlSpecialChars($basePath) ?>/js/bootstrap.min.js"></script>
        
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
        <link rel="shortcut icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" />
        
	<?php call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars())  ?>

    </head>

    <body>
        <script> document.body.className+=' js' </script>

        
        <div class="container">

            <div class="masterhead">
                <a href="<?php echo htmlSpecialChars($_presenter->link("Homepage:default")) ?>"><h1>BirneShop!</h1></a>
            
<?php $_ctrl = $_control->getComponent("menu"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
                
            </div>
            
            <ul class="breadcrumb">
  <li><a href="#">Home</a> <span class="divider">/</span></li>
  <li><a href="#">Library</a> <span class="divider">/</span></li>
  <li class="active">Data</li>
</ul>
            
            
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>


            <div class="footer">
                &COPY; Birne shop 2013
            </div>

        </div>
<?php call_user_func(reset($_l->blocks['scripts']), $_l, get_defined_vars())  ?>
    </body>
</html>
