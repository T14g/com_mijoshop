<div id="braspag5-cartao-credito">

<?php if($anti_fraude_ativado && !empty($fraudemid) && !empty($fraudeorgid)){ ?>
<!-- online metrix -->
<p style="background:url(https://h.online-metrix.net/fp/clear.png?org_id=<?php echo $fraudeorgid;?>&session_id=<?php echo $fraudemid;?><?php echo md5($pedido['order_id']);?>&m=1)"></p>
<img src="https://h.online-metrix.net/fp/clear.png?org_id=<?php echo $fraudeorgid;?>&session_id=<?php echo $fraudemid;?><?php echo md5($pedido['order_id']);?>&m=2" alt="">
<script src="https://h.online-metrix.net/fp/check.js?org_id=<?php echo $fraudeorgid;?>&session_id=<?php echo $fraudemid;?><?php echo md5($pedido['order_id']);?>" type="text/javascript"></script> 
<!-- online metrix -->
<?php } ?>

<input type="hidden" name="pedido_cielo" value="<?php echo $pedido['order_id'];?>">
<input type="hidden" name="hash_cielo" value="<?php echo md5(sha1($pedido['order_id']));?>">

<link href="app/braspag5/css.css" rel="stylesheet">

<div class="row">

<div class="col-md-12">
<div class="alert alert-info" role="alert"><i class="glyphicon glyphicon-info-sign"></i> Os dados informados abaixo deveram corresponder ao titular do cart&atilde;o usado na compra.</div>
</div>

<div class="col-md-12">

<form id="pagar-cartao-credito-braspag" class="form-horizontal">

<input type="hidden" name="pedido" value="<?php echo $pedido['order_id'];?>">
<input type="hidden" name="bandeira" id="bandeira" value="">

<div class="form-group cartoes-credito">
<label class="col-xs-3 control-label">Selecione</label>
<div class="col-xs-9"><p class="form-control-static">
<?php if($ativa_visa){?>
<img src="app/braspag5/images/visa.gif" onclick="cartao_manual_s('visa')" class="cartoes visa" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
<?php if($ativa_mastercard){?>
<img src="app/braspag5/images/mastercard.gif" onclick="cartao_manual_s('mastercard')" class="cartoes mastercard" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
<?php if($ativa_elo){?>
<img src="app/braspag5/images/elo.gif" onclick="cartao_manual_s('elo')" class="cartoes elo" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
<?php if($ativa_diners){?>
<img src="app/braspag5/images/diners.gif" onclick="cartao_manual_s('diners')" class="cartoes diners" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
<?php if($ativa_amex){?>
<img src="app/braspag5/images/amex.gif" onclick="cartao_manual_s('amex')" class="cartoes amex" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
<?php if($ativa_discover){?>
<img src="app/braspag5/images/discover.gif" onclick="cartao_manual_s('discover')" class="cartoes discover" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
<?php if($ativa_jcb){?>
<img src="app/braspag5/images/jcb.gif" onclick="cartao_manual_s('jcb')" class="cartoes jcb" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
<?php if($ativa_aura){?>
<img src="app/braspag5/images/aura.gif" onclick="cartao_manual_s('aura')" class="cartoes aura" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
<?php if($ativa_hiper){?>
<img src="app/braspag5/images/hiper.gif" onclick="cartao_manual_s('hiper')" class="cartoes hiper" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
<?php if($ativa_hipercard){?>
<img src="app/braspag5/images/hipercard.gif" onclick="cartao_manual_s('hipercard')" class="cartoes hipercard" style="width:50px;height:30px;cursor:pointer;" class="img-responsive">
<?php } ?>
</p></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">N&uacute;mero do Cart&atilde;o</label>
<div class="col-md-9">
<input type="text" value="" onkeypress="return isNumberKeybraspag5(event);" onkeyup="detectar_bandeira_cartao_credito(this.value);" onkeydown="detectar_bandeira_cartao_credito(this.value);" placeholder="Digite o n&uacute;mero do cart&atilde;o!" maxlength="23" style="width:100%" class="class_input_cartao tooltip_cartao form-control numero_cartao" id="numero" name="numero" />
</div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Titular</label>
<div class="col-md-9">
<input type="text" placeholder="Digite o nome do titular!" value="<?php echo $pedido['payment_firstname'];?> <?php echo $pedido['payment_lastname'];?>" onkeyup="upbraspag5(this);" onkeydown="upbraspag5(this);" style="width:100%" class="form-control" id="titular" name="titular" />
</div>
</div>

<?php if($fiscal_ativo){?>
<div class="form-group">
<label class="col-md-3 control-label">CPF/CNPJ</label>
<div class="col-md-9">
<input type="text" data-checkout="docNumber" onkeypress="return isNumberKeybraspag5(event)" maxlength="14" style="width:100%" value="<?php echo (isset($pedido['custom_field'][$cpf]))?preg_replace('/\D/', '', $pedido['custom_field'][$cpf]):'';?>" class="form-control" id="fiscal" name="fiscal" />
</div>
</div>
<?php }else{ ?>
<input type="hidden" value="<?php echo (isset($pedido['custom_field'][$cpf]))?preg_replace('/\D/', '', $pedido['custom_field'][$cpf]):'';?>" class="ignore_campo" id="fiscal_oculto" name="fiscal" />
<?php } ?>

<div class="form-group">
<label class="col-md-3 control-label">Validade M&ecirc;s</label>
<div class="col-md-3">
<select class="form-control" id="validadem" name="validadem">
<option value="">MM</option>
<?php for($i=1;$i<=12;$i++){?>
<option value="<?php echo str_pad($i,2,'0',STR_PAD_LEFT);?>"><?php echo str_pad($i,2,'0',STR_PAD_LEFT);?></option>
<?php } ?>
</select>
</div>
<label class="col-md-3 control-label">Validade Ano</label>
<div class="col-md-3">
<select class="form-control" id="validadea" name="validadea">
<option value="">AAAA</option>
<?php for($i=date('Y');$i<=(date('Y')+30);$i++){?>
<option value="<?php echo str_pad($i,4,'0',STR_PAD_LEFT);?>"><?php echo str_pad($i,4,'0',STR_PAD_LEFT);?></option>
<?php } ?>
</select>
</div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">C&oacute;digo (CVV) <a onclick="alerta_cvv()">(?)</a></label>
<div class="col-md-2">
<input type="text" value="" placeholder="CVV" onkeypress="return isNumberKeybraspag5(event)" maxlength="4" style="width:100%" class="form-control" id="codigo" name="codigo" />
</div>
<label class="col-md-2 control-label">Parcelas</label>
<div class="col-md-5">
<select name="parcelas" style="width:100%" class="form-control" id="parcelas">
<option value="">Selecione a bandeira desejada</option>
</select>
</div>
</div>

<div class="form-group buttons">
<div class="col-md-9 col-md-offset-3">
<button class="button btn btn-primary" id="button-confirm"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> Concluir Pagamento</button>
</div>
</div>
</form>

</div>

</div>
</div>

<script type="text/javascript" src="app/braspag5/jquery.validate.js?<?php echo time();?>"></script>
<script type="text/javascript" src="app/braspag5/additional-methods.js?<?php echo time();?>"></script>
<script type="text/javascript" src="app/braspag5/jquery.payment.js"></script>
<script type="text/javascript" src="app/braspag5/bootbox.min.js?<?php echo time();?>"></script>
<script type="text/javascript" src="app/braspag5/block.js?<?php echo time();?>"></script>
<script type="text/javascript" src="app/braspag5/loja5.js?<?php echo time();?>"></script>
<script>
function alerta_cvv(){
	bootbox.dialog({
	  message: "<img src='app/braspag5/images/cvvImage.jpg'>",
	  title: "Onde encontro o CVV?",
	});
}
</script>
<script>
jQuery(function() {
	//mascara cartao
	jQuery('.class_input_cartao').payment('formatCardNumber');
	//validar cartao de debito
	jQuery("#pagar-cartao-credito-braspag").validate({
	onfocusout: false,
	onkeyup: false,
	submitHandler: function(form) {
		var dados = jQuery(form).serialize();
		console.log(dados);
		jQuery.ajax({
			type: 'post',
			url: '<?php echo $criar; ?>',
			dataType: 'JSON',
			data: dados,
			cache: false,
			beforeSend: function() {
				jQuery('#button-confirm').button('loading');
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
					jQuery('#button-confirm').button('reset');
				}else{
					location = data.href;
				}
			}
		});
		
		return false;
	},
	ignore: ".ignore_campo",
	errorClass: "ops_campo_erro",
	rules: {
	titular: {
	required: true,
	minlength: 5
	},
	fiscal: {
	required: true,
	validacpfcnpj:true
	},
	numero: {
	required: true,
	validacartao:true
	},
	validadem: {
	required: true,
	min: 1,
	max: 12
	},
	validadea: {
	required: true,
	min: currentTime.getFullYear(),
	max: currentTime.getFullYear()+30
	},
	codigo: {
	required: true,
	minlength: 3,
	maxlength: 4
	},
	parcelas: "required"
	},
	messages: {
	titular: "Informe nome do titular",
	fiscal: "Informe o CPF/CNPJ do titular",
	numero: "Informe o n\u00famero do cart\u00e3o valido",
	validadem: "Informe o mes!",
	validadea: "Informe o ano!",
	codigo: "Informe o CVV!",
	parcelas: "Qual a parcela?",
	}
	});
});
</script>