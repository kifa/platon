<?php //netteCache[01]000384a:2:{s:4:"time";s:21:"0.82019300 1363181632";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:62:"G:\xampp\htdocs\GIT\platon\app\templates\Product\product.latte";i:2;i:1363181629;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\templates\Product\product.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '9g342iroi6')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbf27ec08aef_content')) { function _lbf27ec08aef_content($_l, $_args) { extract($_args)
;call_user_func(reset($_l->blocks['title']), $_l, get_defined_vars())  ?>
<div class="row">
    <div class="span10">

        <div class="span5">
            <img src="http://www.google.com/nexus/images/n4-product-hero.png" class="img-circle" style="width: 400px;" />
        </div>
        <div class="span4">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#info" data-toggle="tab">Info</a></li>
                    <li><a href="#spec" data-toggle="tab">Spec</a></li>
                    <li><a href="#other" data-toggle="tab">Other</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="info">
                        <p><?php echo Nette\Templating\Helpers::escapeHtml($product->ProductDescription, ENT_NOQUOTES) ?></p>
                     
                    </div>
                    <div class="tab-pane" id="spec">
                        <ul class="icons">
                            <li><i class="icon-ok"></i>Width:</li>
                            <li><i class="icon-ok"></i>Height:</li>
                            <li><i class="icon-ok"></i>Weight:</li>
                        </ul>
                    </div>
                    <div class="tab-pane" id="other">
                        <p>Other infos</p>
                    </div>
                </div>
            </div>
            
            <div class="span4">
                <h2 class="text-info span2"><?php echo Nette\Templating\Helpers::escapeHtml($product->FinalPrice, ENT_NOQUOTES) ?>,-</h2>
                <p class="span1">In stock: <span class="badge badge-info"><?php echo Nette\Templating\Helpers::escapeHtml($product->PiecesAvailable, ENT_NOQUOTES) ?></span></p>
                <a class="span2 btn btn-primary" href="<?php echo htmlSpecialChars($_control->link("Order:cart", array('produkt'))) ?>
">
                        <i class="icon-shopping-cart"></i> Buy</a>
                <a href="<?php echo htmlSpecialChars($product->ProductID) ?>" class="span1 btn btn-success">
                        <i class="icon-info-sign"></i> Ask</a>
            </div>
        </div>
    </div>

    <div class="span2">
        
        <ul class="nav nav-list">
            <li class="nav-header">Menu</li>
            <li class="active"><a href="#"><i class="icon-home"></i>Domů</a></li>
            <li><a href="#"><i class="icon-book"></i>Jak nakupovat</a></li>
            <li><a href="#"><i class="icon-heart"></i>Jak prodávat</a></li>
            <li><a href="#"><i class="icon-comment"></i>Jak cokoliv</a></li>
            <li class="divider"></li>
        </ul>

        <i class="icon-camera-retro icon-2x"></i><strong>Google Nexus</strong>
        <p>See source code of <a href="#template">this page template</a>, <a href="#layout">layout template</a> and
            <a href="#presenter">corresponding presenter</a>. And feel free to modify them!</p>

    </div>




</div>

<?php
}}

//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lb92761d2405_title')) { function _lb92761d2405_title($_l, $_args) { extract($_args)
?><h1><?php echo Nette\Templating\Helpers::escapeHtml($product->ProductName, ENT_NOQUOTES) ?></h1>
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
if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 