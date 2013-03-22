<?php //netteCache[01]000386a:2:{s:4:"time";s:21:"0.15258700 1363978979";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:64:"G:\xampp\htdocs\GIT\platon\app\components\MenuAdminControl.latte";i:2;i:1363978975;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\components\MenuAdminControl.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'dy0zm2zjim')
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

<ul class="nav nav-list">
            <li class="nav-header">Admin Menu</li>
<?php $iterations = 0; foreach ($category as $id => $cat): ?>
            <li class="active"><a href="<?php echo htmlSpecialChars($_presenter->link("product:products", array($id))) ?>
"><i class="icon-spinner"></i><?php echo Nette\Templating\Helpers::escapeHtml($cat->CategoryName, ENT_NOQUOTES) ?></a></li>    
<?php $iterations++; endforeach ?>
            <li class="divider"></li>
            <li class="active"><a href="#"><i class="icon-spinner"></i>Orders</a></li>
            <li><a href="#"><i class="icon-truck"></i>Shipping</a></li>
            <li><a href="#"><i class="icon-money"></i>Payment</a></li>
            <li><a href="#"><i class="icon-comment"></i>Other</a></li>
</ul>