<?php //netteCache[01]000384a:2:{s:4:"time";s:21:"0.94335600 1363894224";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:62:"G:\xampp\htdocs\GIT\platon\app\components\ProductControl.latte";i:2;i:1363894222;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\components\ProductControl.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'kwhgytmuxu')
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
<ul class="thumbnails">
<?php while ($product = $products->fetch()): ?>
            <li class="span3">
                <div class="thumbnail">
                    <img src="http://www.google.com/nexus/images/n4-product-hero.png" class="img-circle" class="span2" style="height: 200px; width: auto;" />
                    <h4><?php echo Nette\Templating\Helpers::escapeHtml($product->ProductName, ENT_NOQUOTES) ?></h4>
                    <div class="caption"><?php $desc = $product->ProductDescription ?>

                        <p><?php echo Nette\Templating\Helpers::escapeHtml($template->truncate($desc, 30), ENT_NOQUOTES) ?></p>
                    </div>
                     <hr />
                    <div class="row">
                        <div class="span3">
                            <div class="span3">
                            <a href="<?php echo htmlSpecialChars($_presenter->link("Product:product", array($product->ProductID))) ?>" class="pull-left btn btn-primary"><i class="icon-info-sign"></i> View</a>
                        <p class="span1">In stock
<?php if ($product->PiecesAvailable > 2): ?>
                    <span class="badge badge-info"><?php echo Nette\Templating\Helpers::escapeHtml($product->PiecesAvailable, ENT_NOQUOTES) ?></span>
<?php else: ?>
                    <span class="badge badge-warning"><?php echo Nette\Templating\Helpers::escapeHtml($product->PiecesAvailable, ENT_NOQUOTES) ?></span>
                    <?php endif ?></p>
                            </div>
                       
                        <div class="span3">
                        <p class="text-success lead"><a href="<?php echo htmlSpecialChars($_presenter->link("Order:cart", array($product->ProductID, "1"))) ?>
" class="btn btn-success"><i class="icon-shopping-cart"></i> Buy</a> <?php echo Nette\Templating\Helpers::escapeHtml($product->FinalPrice, ENT_NOQUOTES) ?>,- </p>
                        </div>
                    </div>
                    </div>
                
                </div>
            </li>
<?php endwhile ?>
</ul>