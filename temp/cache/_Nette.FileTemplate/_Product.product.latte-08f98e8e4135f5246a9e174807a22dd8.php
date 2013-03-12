<?php //netteCache[01]000380a:2:{s:4:"time";s:21:"0.08468500 1362858689";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:58:"C:\xampp\htdocs\platon\app\templates\Product\product.latte";i:2;i:1362858628;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: C:\xampp\htdocs\platon\app\templates\Product\product.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '6nsf1006qi')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbb362e49d43_content')) { function _lbb362e49d43_content($_l, $_args) { extract($_args)
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
                        <p>With your favorite Google Apps, an amazing Photo Sphere camera, cutting edge hardware,
                            and access to your favorite entertainment on Google Play – Nexus 4 puts the best of 
                            Google in the palm of your hand.</p>
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
                <h2 class="text-info span2">4999,-</h2>
                <p class="span1">In stock: <span class="badge badge-info">2</span></p>
                <a class="span2 btn btn-primary" href="<?php echo htmlSpecialChars($_control->link("Order:cart", array('produkt'))) ?>
">
                        <i class="icon-shopping-cart"></i> Buy</a>
                <a class="span1 btn btn-success" href="<?php echo htmlSpecialChars($_control->link("Order:cart", array('produkt'))) ?>
">
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
if (!function_exists($_l->blocks['title'][] = '_lb2a7e5ef34e_title')) { function _lb2a7e5ef34e_title($_l, $_args) { extract($_args)
?><h1>Galaxy Nexus</h1>
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