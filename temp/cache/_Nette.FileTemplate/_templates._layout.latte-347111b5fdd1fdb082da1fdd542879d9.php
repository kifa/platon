<?php //netteCache[01]000376a:2:{s:4:"time";s:21:"0.12321700 1363122326";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:54:"G:\xampp\htdocs\GIT\platon\app\templates\@layout.latte";i:2;i:1363122323;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\templates\@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '1gttw1nmkw')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lb983443c22d_title')) { function _lb983443c22d_title($_l, $_args) { extract($_args)
?>BirneShop<?php
}}

//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lb5b93467425_head')) { function _lb5b93467425_head($_l, $_args) { extract($_args)
;
}}

//
// block scripts
//
if (!function_exists($_l->blocks['scripts'][] = '_lbfd2951c835_scripts')) { function _lbfd2951c835_scripts($_l, $_args) { extract($_args)
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

<?php $iterations = 0; foreach ($flashes as $flash): ?>        <div class="flash <?php echo htmlSpecialChars($flash->type) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ?>

        <div class="container">

            <div class="masterhead">
                <h1>BirneShop!</h1>
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="container">
                            <ul class="nav">
                                <li class="active"><a href="<?php echo htmlSpecialChars($_control->link("Homepage:default")) ?>
">Home</a></li>
                                <li class=""><a href="<?php echo htmlSpecialChars($_control->link("Product:default")) ?>
">Kategorie</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>


            <div class="footer">
                &COPY; Birne shop 2013
            </div>

        </div>
<?php call_user_func(reset($_l->blocks['scripts']), $_l, get_defined_vars())  ?>
    </body>
</html>
