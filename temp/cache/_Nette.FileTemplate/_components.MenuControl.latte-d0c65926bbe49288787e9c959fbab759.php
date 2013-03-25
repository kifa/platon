<?php //netteCache[01]000381a:2:{s:4:"time";s:21:"0.04758100 1364246141";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:59:"G:\xampp\htdocs\GIT\platon\app\components\MenuControl.latte";i:2;i:1363982751;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\components\MenuControl.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'kgdul7q14c')
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
                <li class="<?php try { $_presenter->link("Homepage:default"); } catch (Nette\Application\UI\InvalidLinkException $e) {}; if ($_presenter->getLastCreatedRequestFlag("current")): ?>
active<?php endif ?>"><a href="<?php echo htmlSpecialChars($_presenter->link("Homepage:default")) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Home"), ENT_NOQUOTES) ?></a></li>
<?php $iterations = 0; foreach ($category as $id => $cat): ?>
                <li class="<?php try { $_presenter->link("product:products", array($id)); } catch (Nette\Application\UI\InvalidLinkException $e) {}; if ($_presenter->getLastCreatedRequestFlag("current")): ?>
active<?php endif ?>"><a href="<?php echo htmlSpecialChars($_presenter->link("product:products", array($id))) ?>
"><i class="icon-spinner"></i><?php echo Nette\Templating\Helpers::escapeHtml($cat->CategoryName, ENT_NOQUOTES) ?></a></li> 
<?php $iterations++; endforeach ?>
                
                
                <li class="<?php try { $_presenter->link("Order:cart"); } catch (Nette\Application\UI\InvalidLinkException $e) {}; if ($_presenter->getLastCreatedRequestFlag("current")): ?>
active<?php endif ?>"><a href="<?php echo htmlSpecialChars($_presenter->link("Order:cart")) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Cart"), ENT_NOQUOTES) ?>
 <?php if ($cart > 0): ?><span class="badge badge-info"><?php echo Nette\Templating\Helpers::escapeHtml($cart, ENT_NOQUOTES) ?>
</span><?php else: ?><span class="badge"><?php echo Nette\Templating\Helpers::escapeHtml($cart, ENT_NOQUOTES) ?>
</span><?php endif ?></a></li>
                <li class="<?php try { $_presenter->link("Order:orders"); } catch (Nette\Application\UI\InvalidLinkException $e) {}; if ($_presenter->getLastCreatedRequestFlag("current")): ?>
active<?php endif ?>"><a href="<?php echo htmlSpecialChars($_presenter->link("Order:orders")) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate("Orders"), ENT_NOQUOTES) ?></a></li>
            </ul>
        </div>
    </div>
</div>