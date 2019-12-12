<?php 
echo $header; 
function tipoPagamento($tipo){
if($tipo==1){
return 'Cartão de crédito';
}elseif($tipo==2){
return 'Boleto bancário';
}elseif($tipo==3){
return 'Débito online';
}elseif($tipo==4){
return 'Saldo pagseguro';
}elseif($tipo==5){
return 'Oi paggo';
}elseif($tipo==7){
return 'Deposito bancário';
}
}
?>
<div id="container" class="container j-container">
<div class="container">
<div class="row">
<?php $class = 'col-sm-12'; ?>
<div id="content" class="<?php echo $class; ?>">

<?php 
$erro = false;
if($erro){ 
?>
<div class="buttons">
<div style="text-align: center;">
  <h2>Ops, problema no pagamento!</h2>
  <p>Ocorreu um erro ao processar o seu pagamento juntao pagseguro! <?php echo $msg;?></p>
</div>
</div>
<?php 
}else{ 
?>
<div class="buttons">
<h3>Resultado da transa&ccedil;&atilde;o</h3>
<p>
	<img width="30" height="30" src="app/pagsegurolightbox/logo.png" style="float:left; margin: 0px 10px 5px 0px;" />
	Transa&ccedil;&atilde;o processada por PagSeguro pagamentos online.
	<br/>
	Detalhes de sua transa&ccedil;&atilde;o:
</p>
<p style="margin-top:20px;">
	- Pedido:
	<span><?php echo $pedido;?></span>
<br>
	- Transa&ccedil;&atilde;o:
	<span><?php echo $dados['code'];?></span>
<br>
	- Valor total do pedido:
	<span id="amount" class="price"><?php echo number_format($order['total'], 2, '.', '');?></span>
<br>
	- Metodo de pagamento:
	<span><?php echo tipoPagamento($dados['paymentMethod']['type']);?></span><?php if($dados['paymentMethod']['type']=='1'){?> em  <?php echo $dados['installmentCount'];?>x<?php } ?>
<br>
	- Status do pagamento:
	<span><u><?php echo $status['nome'];?></u></span>
</p>
<?php if ($dados['paymentMethod']['type']=='2' && $dados['status']==1){?>
<button onclick="window.open('<?php echo $dados['paymentLink'];?>','boleto');" class="button btn btn-primary"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Imprimir Boleto Banc&aacute;rio</button><br><br>
<?php } ?>
<?php if($dados['paymentMethod']['type']=='3' && $dados['status']==1){?>
<button onclick="window.open('<?php echo $dados['paymentLink'];?>','debito');" class="button btn btn-primary"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span> Concluir Pagamento Online</button><br><br>
<?php } ?>
<p>
Para visualizar detalhes de seu pedido <a class="" href="index.php?route=account/order/info&order_id=<?php echo $pedido;?>">clique aqui</a> ou em caso de duvidas entre em <a href="index.php?route=information/contact">contato</a> com a loja.
</p>


<?php include("app/pagsegurolightbox/html.php");?>

</div>
<?php } ?>

</div>
</div>
</div>
</div>

<script>
(function(){
    var i = document.createElement('iframe');
    i.style.display = 'none';
    i.onload = function() { i.parentNode.removeChild(i); };
    i.src = '<?php echo $iframe;?>';
    document.body.appendChild(i);
})();
</script>

<?php echo $footer; ?>