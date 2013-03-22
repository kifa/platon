<?php //netteCache[01]000384a:2:{s:4:"time";s:21:"0.28529400 1363970563";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:62:"G:\xampp\htdocs\GIT\platon\app\templates\Order\orderDone.latte";i:2;i:1363970559;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\templates\Order\orderDone.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'dgqytozjca')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb4c9f54186a_content')) { function _lb4c9f54186a_content($_l, $_args) { extract($_args)
?><div class="container">
<div class="row">
    <div class="span12">
<div class="page-header">
        <h1>Order summary <small> Complete summary order.</small></h1>
</div>
<?php $iterations = 0; foreach ($flashes as $flash): ?>
        <div class="<?php echo htmlSpecialChars($flash->type) ?>">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
<?php echo Nette\Templating\Helpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ?>
        <div class="row">
        <table class="table table-hover span11">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Amount</th>
                    <th>Item price</th>
                    <th>Total price</th>

                </tr>
            </thead>
            <tbody>
<?php $grandtotal = 0 ;$iterations = 0; foreach ($products as $id => $product): ?>


                <tr>
                    <td><img src="http://www.google.com/nexus/images/n4-product-hero.png" class="img-circle" style="width: 38px;" />
                        <?php echo Nette\Templating\Helpers::escapeHtml($product->ProductNumber, ENT_NOQUOTES) ?></td>
                    <td><a href="<?php echo htmlSpecialChars($_presenter->link("Product:product", array($product->ProductID))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($product->ProductName, ENT_NOQUOTES) ?></a></td>
                    <td><?php echo Nette\Templating\Helpers::escapeHtml($product->Quantity, ENT_NOQUOTES) ?></td>
                    <td><?php echo Nette\Templating\Helpers::escapeHtml($product->UnitPrice, ENT_NOQUOTES) ?>,-</td>
                    <td><?php echo Nette\Templating\Helpers::escapeHtml($subtotal = $product->Quantity * $product->UnitPrice, ENT_NOQUOTES) ?>,-</td>

                </tr>
<?php $grandtotal += $subtotal ?>

<?php $iterations++; endforeach ?>

            <div class="span9 offset1 alert alert-info">   
                <div class="span1">                    
                    <i class="icon-shopping-cart icon-4x"></i></div>
                <div class="span7">
                    <h3 class="span2"><strong class="span1">Products:</strong>
                            <span class="span1"><?php echo Nette\Templating\Helpers::escapeHtml($grandtotal, ENT_NOQUOTES) ?>,-</span></h3>
                    <h3 class="span2"><strong class="span1">Shipping:</strong> 
                            <span class="shipping span1"><?php echo Nette\Templating\Helpers::escapeHtml($shipping = $order->DeliveryPrice, ENT_NOQUOTES) ?>,-</span></h3>
                    <h3 class="span2"><strong class="span1">Total:</strong>
                            <span class="ordertotal span1"><?php echo Nette\Templating\Helpers::escapeHtml($grandtotal += $shipping, ENT_NOQUOTES) ?>,-</span></h3>
                </div>
            </div>

            </tbody>
        </table>
    </div>
        <div class="row">
        <div class="span5 alert alert-block">

            <div class="span5">
                <p>
                <i class="icon-calendar icon-large left"></i><strong> Date created:</strong>&nbsp;
            <?php echo Nette\Templating\Helpers::escapeHtml($template->date($order->DateCreated, '%d.%m.%Y'), ENT_NOQUOTES) ?>

                </p>
            </div>
          
            <div class="span5">
                <p>
                <i class="icon-truck icon-large left"></i><strong> Shipping:</strong>&nbsp;
            <?php echo Nette\Templating\Helpers::escapeHtml($order->DeliveryName, ENT_NOQUOTES) ?>

                </p>
            </div>
            <br />
            <div class="span5">
                <p>
                <i class="icon-money icon-large left"></i><strong> Payment:</strong>&nbsp;
            <?php echo Nette\Templating\Helpers::escapeHtml($order->PaymentName, ENT_NOQUOTES) ?>

                </p>
            </div>
        </div>
            <div class="row">
        <div class="span5 alert alert-block">
            <div class="span5">
                <p>
                <i class="icon-spinner icon-spin left icon-large"></i><strong> Status:</strong>&nbsp;
            <?php echo Nette\Templating\Helpers::escapeHtml($order->StatusName, ENT_NOQUOTES) ?>

                </p>
            </div>
            <br />
            <div class="progress progress-info active span5">
                <div class="bar" style="width: 20%"></div>
            </div>
        </div>
    </div>
        <div class="span5 alert alert-block">
            <dl>
                <dt>Contact:</dt>
                <dd><?php echo Nette\Templating\Helpers::escapeHtml($order->FirstName, ENT_NOQUOTES) ?>
 <?php echo Nette\Templating\Helpers::escapeHtml($order->SureName, ENT_NOQUOTES) ?></dd>
                <dd><?php echo Nette\Templating\Helpers::escapeHtml($order->Email, ENT_NOQUOTES) ?></dd>
                <dd><?php echo Nette\Templating\Helpers::escapeHtml($order->PhoneNumber, ENT_NOQUOTES) ?></dd>
            </dl>
<?php if ($order->CompanyName != ""): ?>
            <dl>
                <dt>Company:</dt>
                <dd><?php echo Nette\Templating\Helpers::escapeHtml($order->CompanyName, ENT_NOQUOTES) ?></dd>
            </dl>
<?php endif ?>
        </div>

        <div class="span5 alert alert-block">
            <dl>
                <dt>Status:</dt>
                <dd><?php echo Nette\Templating\Helpers::escapeHtml($order->StatusID, ENT_NOQUOTES) ?></dd>
            </dl>
            <dl>
                <dt>Payment:</dt>
                <dd><?php echo Nette\Templating\Helpers::escapeHtml($order->PaymentName, ENT_NOQUOTES) ?></dd>
            </dl>
        </div>
        </div>
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
?>

<?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 