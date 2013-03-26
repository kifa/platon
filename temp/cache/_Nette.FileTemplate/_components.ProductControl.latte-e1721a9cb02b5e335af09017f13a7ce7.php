<?php //netteCache[01]000380a:2:{s:4:"time";s:21:"0.14456000 1364294978";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:58:"C:\xampp\htdocs\platon\app\components\ProductControl.latte";i:2;i:1364294933;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: C:\xampp\htdocs\platon\app\components\ProductControl.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '8qbgfz4air')
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
            
<?php try { $_presenter->link("Homepage:"); } catch (Nette\Application\UI\InvalidLinkException $e) {}; if ($_presenter->getLastCreatedRequestFlag("current")): ?>
            <div class="span3">
                You cannot edit product here!
            </div>
<?php else: ?>
            <div class="span3">
                Admin: <a href="<?php echo htmlSpecialChars($_presenter->link("deleteProduct!", array($product->ProductID, $product->CategoryID))) ?>" ><i id="deleteProduct" class="icon-large icon-trash"></i></a>
            
            </div>
<?php endif ?>
            <hr />
<?php if ($product->PiecesAvailable > 5): ?>
            <span class="badge badge-info"><?php echo Nette\Templating\Helpers::escapeHtml($product->PiecesAvailable, ENT_NOQUOTES) ?></span>
<?php else: ?>
            <span class="badge badge-warning"><?php echo Nette\Templating\Helpers::escapeHtml($product->PiecesAvailable, ENT_NOQUOTES) ?></span>
<?php endif ?>
            
            <img src="<?php echo htmlSpecialChars($basePath) ?>/images/<?php echo htmlSpecialChars($product->PhotoAlbumID) ?>
/<?php echo htmlSpecialChars($photo->PhotoURL) ?>" class="img-circle" class="span2" style="height: 200px; width: auto;" />
            <h4><?php echo Nette\Templating\Helpers::escapeHtml($product->ProductName, ENT_NOQUOTES) ?></h4>
            <div class="caption"><?php $desc = $product->ProductDescription ?>

                <p><?php echo Nette\Templating\Helpers::escapeHtml($template->truncate($desc, 30), ENT_NOQUOTES) ?></p>
            </div>
            <hr />
            <div class="row">
                    <div class="span3">
                    <div class="span3 btn-toolbar">
                        <div class="btn-group">
                            <a href="<?php echo htmlSpecialChars($_presenter->link("Product:product", array($product->ProductID))) ?>" class="btn btn-primary btn-large"><i class="icon-info-sign"></i> View</a>              
                            <a href="<?php echo htmlSpecialChars($_presenter->link("Order:cart", array($product->ProductID, "1"))) ?>" class="btn btn-success btn-large"><i class="icon-shopping-cart"></i> Buy</a>
                        </div>  
                    </div>

                    <div class="span3">
                        <p class="text-success text-center lead"> <?php echo Nette\Templating\Helpers::escapeHtml($product->FinalPrice, ENT_NOQUOTES) ?>,- </p>
                    </div>
                </div>
            </div>

        </div>
    </li>
<?php endwhile ?>
</ul>