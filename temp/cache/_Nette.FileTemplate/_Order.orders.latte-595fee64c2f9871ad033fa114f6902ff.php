<?php //netteCache[01]000381a:2:{s:4:"time";s:21:"0.03668600 1363972222";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:59:"G:\xampp\htdocs\GIT\platon\app\templates\Order\orders.latte";i:2;i:1363971303;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\templates\Order\orders.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '5wbwaakgwc')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lba5c9ae5427_content')) { function _lba5c9ae5427_content($_l, $_args) { extract($_args)
?><div class="container">

<?php if ($user->isLoggedIn()): ?>
<div class="page-header">
<?php call_user_func(reset($_l->blocks['title']), $_l, get_defined_vars())  ?>
</div>
<div class="row">

    <div class="span12">

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Total Payment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
<?php $iterations = 0; foreach ($orders as $id => $order): ?>
                
                <tr class="<?php if ($order->StatusID == 3): ?>success <?php elseif ($order->StatusID == 2): ?>
 info <?php else: ?> warning <?php endif ?>">
                    <td><?php echo Nette\Templating\Helpers::escapeHtml($id, ENT_NOQUOTES) ?></td>
                    <td><?php echo Nette\Templating\Helpers::escapeHtml($template->date($order->DateCreated, '%d.%m.%Y'), ENT_NOQUOTES) ?></td>
                    <td><a href="<?php echo htmlSpecialChars($_presenter->link("Order:orderDone", array($id))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($order->FirstName, ENT_NOQUOTES) ?>
 <?php echo Nette\Templating\Helpers::escapeHtml($order->SureName, ENT_NOQUOTES) ?></a></td>
                    <td><?php echo Nette\Templating\Helpers::escapeHtml($order->TotalPrice, ENT_NOQUOTES) ?>,-</td>
                    <td><?php echo Nette\Templating\Helpers::escapeHtml($order->StatusName, ENT_NOQUOTES) ?></td>
                </tr>
                
<?php $iterations++; endforeach ?>
            </tbody>
        </table>


    </div>

</div>
     
<?php else: Nette\Latte\Macros\CoreMacros::includeTemplate('../Sign/in.latte', $template->getParameters(), $_l->templates['5wbwaakgwc'])->render() ;endif ?>
</div>
<?php
}}

//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lb97a8c08c89_title')) { function _lb97a8c08c89_title($_l, $_args) { extract($_args)
?><h1>Orders <small>Here you see all orders placed in your shop.</small></h1>
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

<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 