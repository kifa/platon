<?php //netteCache[01]000385a:2:{s:4:"time";s:21:"0.94726500 1363725989";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:63:"G:\xampp\htdocs\GIT\platon\app\templates\Homepage\default.latte";i:2;i:1363123166;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\templates\Homepage\default.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'hfg9uozjti')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block head
//
if (!function_exists($_l->blocks['head'][] = '_lba569feb989_head')) { function _lba569feb989_head($_l, $_args) { extract($_args)
?><title>Kategorie</title><?php
}}

//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lbd7d568665e_content')) { function _lbd7d568665e_content($_l, $_args) { extract($_args)
?><div id="myCarousel" class="carousel slide">
    <ol class="carousel-indicators">
        <li data-target= "#myCarousel"  data-slide-to="0" class="active"></li>
        <li data-target= "#myCarousel"  data-slide-to="1"></li>
        <li data-target= "#myCarousel"  data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner jumbotron">
        <div class="item active">
            <img src="http://www.zlepsovak.cz/433-large/unikatni-papirovy-blocek-hruska.jpg" alt="" />
            <div class="container">
                <div class="carousel-caption">
                    <h1>Představujeme BIRNE SHOP!</h1>
                    <p class="lead">Jedinečný a dokonalý eshop právě pro Vás!</p>
                    <a class="btn btn-large btn-primary" href="#">Začněte již dnes</a>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="http://www.zlepsovak.cz/433-large/unikatni-papirovy-blocek-hruska.jpg" alt="" />
            <div class="container">
                <div class="carousel-caption">
                    <h1>Nejdou obchody? BIRNE SHOP!</h1>
                    <p class="lead">Řešení, které prostě funguje.</p>
                    <a class="btn btn-large btn-primary" href="#">Zjistit více</a>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="http://www.zlepsovak.cz/433-large/unikatni-papirovy-blocek-hruska.jpg" alt="" />
            <div class="container">
                <div class="carousel-caption">
                    <h1>Radost prodávat? BIRNE SHOP!</h1>
                    <p class="lead">Provozovat eshop na naší platformě je radost!</p>
                    <a class="btn btn-large btn-primary" href="#">Přesvědčete se</a>
                </div>
            </div>
        </div>
    </div>
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>
</div>

<hr />

<div id="content">
    <h2>Zde přijdou produkty a doprava novinky a infomace.</h2>

<div class="row">
    <div class="span10">
<?php $_ctrl = $_control->getComponent("product"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
        
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
if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['head']), $_l, get_defined_vars())  ?>

<?php call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 