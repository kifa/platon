<?php //netteCache[01]000379a:2:{s:4:"time";s:21:"0.33390800 1363977930";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:57:"G:\xampp\htdocs\GIT\platon\app\templates\Order\cart.latte";i:2;i:1363977925;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\templates\Order\cart.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'cvarp3recq')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb96be796d48_content')) { function _lb96be796d48_content($_l, $_args) { extract($_args)
?><div id="content">
<div class="row">
    <div class="span12">
        <div class="page-header">
        <h1>Your cart <small>Here you can complete your order. Just fill fields bellow.</small></h1>
        </div>
<?php $iterations = 0; foreach ($flashes as $flash): ?>
        <div class="<?php echo htmlSpecialChars($flash->type) ?>">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
<?php echo Nette\Templating\Helpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ?>

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
<?php $grandtotal = 0 ;$iterations = 0; foreach ($cart as $id => $products): $iterations = 0; foreach ($products as $amnt => $product): $subtotal = $amnt * $product->FinalPrice ?>
                <tr>
                    <td><img src="http://www.google.com/nexus/images/n4-product-hero.png" class="img-circle" style="width: 38px;" />
                        <?php echo Nette\Templating\Helpers::escapeHtml($product->ProductNumber, ENT_NOQUOTES) ?></td>
                    <td><a href="<?php echo htmlSpecialChars($_presenter->link("Product:product", array($product->ProductID))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($product->ProductName, ENT_NOQUOTES) ?></a></td>
                    <td><a href="<?php echo htmlSpecialChars($_presenter->link("addAmount!", array($id))) ?>
"><i class="icon-plus-sign"></i></a> <?php echo Nette\Templating\Helpers::escapeHtml($amnt, ENT_NOQUOTES) ?> pcs
                        
                        <a href="<?php echo htmlSpecialChars($_presenter->link("removeAmount!", array($id))) ?>"><i class="icon-minus-sign"></i></a></td>
                    <td><?php echo Nette\Templating\Helpers::escapeHtml($product->FinalPrice, ENT_NOQUOTES) ?>,-</td>
                    <td><?php echo Nette\Templating\Helpers::escapeHtml($subtotal, ENT_NOQUOTES) ?>,-</td>
                    <td><a href="<?php echo htmlSpecialChars($_control->link("removeItem!", array($id))) ?>
"><i class="icon-trash"></i> remove</a></td>
                </tr>
<?php $grandtotal = $grandtotal + $subtotal ;$iterations++; endforeach ;$iterations++; endforeach ?>
                
            </tbody>
        </table>

<div class="span9 offset1 alert alert-info">   
                        <div class="span1">                    
                            <i class="icon-shopping-cart icon-4x"></i></div>
                        <div class="span7">
                            <h3 class="span2"><strong class="span1">Products:</strong>
                                <span class="span1"><?php echo Nette\Templating\Helpers::escapeHtml($grandtotal, ENT_NOQUOTES) ?>,-</span></h3>
                            <h3 class="span2"><strong class="span1">Shipping:</strong> 
                                <span class="shipping span1">0,-</span></h3>
                            <h3 class="span2"><strong class="span1">Total:</strong>
                                <span class="ordertotal span1"><?php echo Nette\Templating\Helpers::escapeHtml($grandtotal, ENT_NOQUOTES) ?>,-</span></h3>
                        </div>
                    </div>
        
        
        <div class="span11">
            <fieldset>


<?php $_ctrl = $_control->getComponent("cartForm"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
            </fieldset>
        </div> 
    </div> 
</div>


<script>
    $(document).ready(function() {
        var sp = new Array(); 
<?php $iterations = 0; foreach ($shippers as $id => $value): ?>
                sp[<?php echo Nette\Templating\Helpers::escapeJs($id) ?>] = <?php echo Nette\Templating\Helpers::escapeJs($value) ?>;
<?php $iterations++; endforeach ?>
        //sp[1] = 150; sp[2] = 190;
        
        var pm = new Array(); 
        pm[1] = 0; pm[2] = -50;
        
        var shipping = 0;
        var payment = 0;
        
        $('input[name=shippers]').click(function() {
            i = $('input[name=shippers]:radio:checked').val();
            shipping = sp[i];
             var grandtotal = shipping + <?php echo Nette\Templating\Helpers::escapeJs($grandtotal) ?> + payment;
        $('.shipping').html(shipping + ',-');
        $('.ordertotal').html(grandtotal + ',-'); 
        });
        
        $('input[name=payment]').click(function() {
            i = $('input[name=payment]:radio:checked').val();
            payment = pm[i];
             var grandtotal = payment + <?php echo Nette\Templating\Helpers::escapeJs($grandtotal) ?> + shipping;
        $('.ordertotal').html(grandtotal + ',-'); 
        });

});

</script>
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