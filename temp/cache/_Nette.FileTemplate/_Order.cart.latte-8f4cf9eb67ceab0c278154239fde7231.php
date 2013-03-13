<?php //netteCache[01]000379a:2:{s:4:"time";s:21:"0.91664800 1363182508";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:57:"G:\xampp\htdocs\GIT\platon\app\templates\Order\cart.latte";i:2;i:1363182276;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"b7f6732 released on 2013-01-01";}}}?><?php

// source file: G:\xampp\htdocs\GIT\platon\app\templates\Order\cart.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'ioi95bpkgd')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb8126351a3d_content')) { function _lb8126351a3d_content($_l, $_args) { extract($_args)
?><div class="row">
    <div class="span12">
        <h2>Your cart</h2>


<table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Product Name</th>
                  <th>Amount</th>
                  <th>Unite price</th>
                  <th>Total price</th>
                  <th>X</th>
                </tr>
              </thead>
              <tbody>
<?php $iterations = 0; foreach ($cart as $id => $products): $iterations = 0; foreach ($products as $amnt => $product): ?>
                <tr>
                  <td>1</td>
                  <td><?php echo Nette\Templating\Helpers::escapeHtml($product->ProductName, ENT_NOQUOTES) ?></td>
                  <td><?php echo Nette\Templating\Helpers::escapeHtml($amnt, ENT_NOQUOTES) ?> ks
                        <a href="<?php echo htmlSpecialChars($_presenter->link("Order:addAmount", array($id))) ?>"><i class="icon-plus-sign"></i></a>
                        <a href="<?php echo htmlSpecialChars($_presenter->link("Order:removeAmount", array($id))) ?>"><i class="icon-minus-sign"></i></a></td>
                  <td><?php echo Nette\Templating\Helpers::escapeHtml($product->FinalPrice, ENT_NOQUOTES) ?>,-</td>
                  <td><?php echo Nette\Templating\Helpers::escapeHtml($product->FinalPrice, ENT_NOQUOTES) ?>,-</td>
                  <td><i class="icon-trash"></i><a href="<?php echo htmlSpecialChars($_control->link("Order:removeItem", array($id))) ?>
"> SMAZAT</a></td>
                </tr>            
<?php $iterations++; endforeach ;$iterations++; endforeach ?>
              </tbody>
            </table>


        <div class="span5">
            <fieldset>
                <legend>About you</legend>

<?php $_ctrl = $_control->getComponent("cartForm"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
            </fieldset>
        </div>
            <div class="span5">
                <fieldset>
                    <legend>Shiping info</legend>

<?php $_ctrl = $_control->getComponent("shippingForm"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ?>
                </fieldset>
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