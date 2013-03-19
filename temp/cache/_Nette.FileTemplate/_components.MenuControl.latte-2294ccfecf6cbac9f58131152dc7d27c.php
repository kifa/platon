<?php //netteCache[01]000377a:2:{s:4:"time";s:21:"0.94024900 1363728563";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:55:"C:\xampp\htdocs\platon\app\components\MenuControl.latte";i:2;i:1363728540;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: C:\xampp\htdocs\platon\app\components\MenuControl.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'ho2xwwz1dx')
;
// prolog Nette\Latte\Macros\UIMacros

// snippets support
if (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
?>

<div class="navbar">
                    <div class="navbar-inner">
                        <div class="container">
                            <ul class="nav">
                                <li class="active"><a href="<?php echo htmlSpecialChars($_presenter->link("Homepage:default")) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Home"), ENT_NOQUOTES) ?></a></li>
                                <li class=""><a href="<?php echo htmlSpecialChars($_presenter->link("Product:default")) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Category"), ENT_NOQUOTES) ?></a></li>
                                <li class=""><a href="<?php echo htmlSpecialChars($_presenter->link("Order:cart")) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Cart"), ENT_NOQUOTES) ?></a></li>
                                <li class=""><a href="<?php echo htmlSpecialChars($_presenter->link("Order:orders")) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Orders"), ENT_NOQUOTES) ?></a></li>
                                
                            </ul>
                        </div>
                    </div>
                </div>