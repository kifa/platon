<?php //netteCache[01]000380a:2:{s:4:"time";s:21:"0.80918500 1363708696";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:58:"C:\xampp\htdocs\platon\app\templates\Order\orderDone.latte";i:2;i:1363708692;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: C:\xampp\htdocs\platon\app\templates\Order\orderDone.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'cu1s7rzhv9')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb67edc362df_content')) { function _lb67edc362df_content($_l, $_args) { extract($_args)
?><h1>Order summary</h1>
<div class="span12">

    <table class="table table-hover span11">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Amount</th>
                <th>Item price</th>
                <th>Total price</th>
                <th>X</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


    <div class="span5">
        
        <dl>
            <dt>Date created:</dt>
            <dd><?php echo Nette\Templating\Helpers::escapeHtml($template->date($order->DateCreated, '%d.%m.%Y'), ENT_NOQUOTES) ?></dd>
        </dl>
        <dl>
            <dt>Shipping:</dt>
            <dd><?php echo Nette\Templating\Helpers::escapeHtml($order->DeliveryID, ENT_NOQUOTES) ?></dd>
                
        </dl>
    </div>

    <div class="span5">

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
?>

<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 