<div class="buttons">
<form class="form_pagamento_boleto_braspag form-horizontal">

<div class="form-group">
<label class="col-md-3 control-label"></label>
<div class="col-md-3 selectContainer">
<img src="app/braspag5/img/boleto-128px.png">
</div>
</div>
  
<div class="form-group">
<label class="col-xs-3 control-label">Nome</label>
<div class="col-xs-9">
<p class="form-control-static"><?php echo $titular;?></p>
</div>
</div>

<div class="form-group">
<label class="col-xs-3 control-label">CPF/CNPJ</label>
<div class="col-xs-9">
<input type="text" onkeypress="mascara(this,'soNumeros')" maxlength="14" class="form-control" style="width:50%" id="cpf_boleto" name="cpf_boleto" placeholder="00000000000" value="<?php echo $cpf;?>" />
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
<button class="button btn btn-primary botao_pagar_boleto" id="button-confirm"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Finalizar Pedido</button>
</div>
</div>

</form>
</div>

<script type="text/javascript" src="app/braspag5/jquery.validate.js?<?php echo time();?>"></script>
<script type="text/javascript" src="app/braspag5/additional-methods.js?<?php echo time();?>"></script>
<script type="text/javascript" src="app/braspag5/bootbox.min.js?<?php echo time();?>"></script>
<script type="text/javascript" src="app/braspag5/loja5.js?<?php echo time();?>"></script>
<script type="text/javascript">
<!--
$('#button-confirm').on('click', function() {

$(".form_pagamento_boleto_braspag").validate({
	showErrors: function(errorMap, errorList) {
		if(jQuery.isEmptyObject(errorList)==false && errorList.length > 0){
		console.log(errorList);
		var alerta = '';
		$.each(errorList, function( index, value ){
			alerta += value.message+'<br>';
		});
		bootbox.dialog({
			message: alerta,
			title: "Dados obrigatorios!",
		});
		}
	},
	onclick: false,
	onfocusout: false,
	onkeyup: false,
	submitHandler: function(form) {

	$.ajax({
		type: 'post',
		url: '<?php echo $criar; ?>',
		data: {fiscal: $('#cpf_boleto').val()},
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

	},
	errorClass: "ops_campo_erro",
	rules: {
	cpf_boleto: {
		required: true,
		validacpfcnpj:true
	},
	},
	messages: {
	cpf_boleto: "Informe CPF/CNPJ do titular!",
	}
});

});
//-->
</script>
