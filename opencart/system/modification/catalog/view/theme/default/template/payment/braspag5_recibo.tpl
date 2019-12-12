<?php echo $header; ?>

<?php //print_r($braspag);?>

<div class="container" id="tela_cielo_pagamento">
<script type="text/javascript">
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}
</script>

<div id="printableArea" style="text-align:center;">

<?php if(isset($braspag['MerchantOrderId'])){ ?>

<b>Transa&ccedil;&atilde;o processada com sucesso!</b>
<p>Sua transa&ccedil;&atilde;o foi processada com sucesso junto ao <?php echo $braspag['Payment']['Provider'];?>!
<br>O mesmo encontra-se no status <b><?php echo $status['nome'];?></b>, aguarde o processamento de seu pedido e em breve receber&aacute; atualiza&ccedil;&otilde;es sobre o mesmo.</p>

<?php if($braspag['Payment']['Type']=='CreditCard'){ ?>

<b>Detalhes</b>
<p>
<b>Pedido:</b> <?php echo $pedido['order_id'];?><br>
<?php if(isset($braspag['Payment']['AcquirerTransactionId']) || isset($braspag['Payment']['Tid'])){ ?>
<b>TID:</b> <?php echo isset($braspag['Payment']['AcquirerTransactionId'])?$braspag['Payment']['AcquirerTransactionId']:$braspag['Payment']['Tid'];?><br><?php }else{ ?>
<b>ID:</b> <?php echo $braspag['Payment']['PaymentId'];?><br>
<?php } ?>
<b>Valor a pagar:</b> <?php echo number_format(($braspag['Payment']['Amount']/100), 2, '.', '');?> em <?php echo $braspag['Payment']['Installments'];?>x<br>
<b>Bandeira:</b> <?php echo strtoupper($braspag['Payment']['CreditCard']['Brand']);?> (<?php echo strtoupper($braspag['Payment']['CreditCard']['CardNumber']);?>)<br>
</p>
</div>

<center>
<a class="button btn btn-info" href="javascript:void(0);" onclick="printDiv('printableArea')">Imprimir cupom</a><?php if($repagamento && $braspag['Payment']['Status']==3){ ?> ou caso queira tentar um novo pagamento <a class="button btn btn-primary" href="index.php?route=payment/braspag5/repagar&id=<?php echo $pedido['order_id'];?>&hash=<?php echo md5(sha1($pedido['order_id']));?>">Clique aqui</a>
<?php } ?>
</center>

<?php 
}elseif($braspag['Payment']['Type']=='Boleto'){ 
?>
<p>Pague seu boleto at&eacute; o vencimento em: <b><?php echo date('d/m/Y',strtotime($braspag['Payment']['ExpirationDate']));?></b><p>
<p>
<b>Linha digitavel:</b><br>
<i><?php echo $braspag['Payment']['DigitableLine'];?></i>
</p>
<p>
<a class="button btn btn-info btn-xl" target="_blank" href="<?php echo $braspag['Payment']['Url'];?>">Imprimir boleto</a>
</p>

<?php 
}elseif($braspag['Payment']['Type']=='Boleto'){ 
?>
<p>Conclua o seu pagamento online no banco para que passamos confirmar o pagamento de seu pedido.<p>
<p>
<a class="button btn btn-info btn-xl" target="_blank" href="<?php echo $braspag['Payment']['Url'];?>">Concluir Pagamento Online</a>
</p>

<?php } ?>

<?php }else{ ?>
<p>Ops, problema ao consultar pedido! Atualize a p&aacute;gina.</p>
<?php } ?>

<br>
<p style="text-align:center;">Para qualquer duvida ou informa&ccedil;&atilde;o entre em contato com o loja <a href="index.php?route=information/contact">clicando aqui</a> ou veja <a href="index.php?route=account/order">detalhes de seu pedido</a>.</p>

<br>
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