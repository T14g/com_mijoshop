<div class="buttons">
<form class="form_pagamento_tef_braspag form-horizontal">

<input type="hidden" id="banco-debito" name="banco" value="">

<div class="form-group">
<label class="col-md-3 control-label">Selecione</label>
<div class="col-md-9 selectContainer">
<?php foreach($banco AS $k=>$v){?>
<img onclick="selecionar_banco('<?php echo trim($v);?>')" style="cursor:pointer" width="50" class="bancos_lista <?php echo trim($v);?>" src="app/braspag5/img/<?php echo trim($v);?>.png">
<?php } ?>
</div>
</div>
  
<div class="form-group">
<label class="col-xs-3 control-label">Nome</label>
<div class="col-xs-9">
<p class="form-control-static"><?php echo $titular;?></p>
</div>
</div>

<div class="form-group">
<label class="col-xs-3 control-label">Total</label>
<div class="col-xs-9">
<p class="form-control-static"><?php echo $total;?></p>
</div>
</div>
  
<div class="form-group buttons">
<label class="col-xs-3 control-label">&nbsp;</label>
<div class="col-xs-9 text-left">
<button type="button" class="button btn btn-primary botao_pagar_tef" id="button-confirm" disabled><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Finalizar Pedido</button>
</div>
</div>

</form>
</div>

<script type="text/javascript">
<!--
function selecionar_banco(banco){
	$('#banco-debito').val(banco);
	$('.bancos_lista').css('opacity','0.5');
	$('.'+banco).css('opacity','1');
	$(".botao_pagar_tef").removeAttr("disabled");
}

$('#button-confirm').on('click', function() {

var banco = $('#banco-debito').val();
$.ajax({
	type: 'post',
	url: '<?php echo $criar; ?>&banco='+banco,
	dataType: 'JSON',
	cache: false,
	beforeSend: function() {
		$('#button-confirm').button('loading');
	},
	complete: function() {
	},
	success: function(data) {
		console.log(data);
		if(data.erro==true){
			bootbox.dialog({
				message: data.log,
				title: "Problema no processamento!",
			});
			$('#button-confirm').button('reset');
		}else{
			location = data.href;
		}
	}
});

});
//-->
</script>
