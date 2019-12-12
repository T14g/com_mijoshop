<div class="row" style="position: relative;">

<div style="text-align: center;" class="centralizado col-md-offset-2 col-md-8 md-66 lg-66 xl-100">

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Acesse sua conta ou Cadastre-se</h3>
</div>
<div class="panel-body">

<div id='div-login-msg-aviso'></div>

<form id="form-email-inicial" class="validar_form" onsubmit="return false;" autocomplete="off" action="#" method="post" novalidate="novalidate">
<p>Acesse sua conta na loja para concluir sua compra ou caso n&atilde;o tenha registro clique em cadastro!</p>
<p style="padding-top: 5px;"><label>E-mail:</label> <input style="text-align: center;" autocomplete="off"  data-msg-required="Informe o seu e-mail!" id="email" name="email" class="form-control input-lg" type="email" placeholder="meu@email.com" value="<?php echo isset($email)?$email:'';?>" required></p>

<p style="padding-top: 5px;"><label>Senha:</label> <input style="text-align: center;" autocomplete="off" data-msg-required="Informe sua senha!" id="senha" name="senha" class="form-control input-lg" type="password" placeholder="********" value="" required></p>

<p style="padding-top: 5px;"><button type="submit" id="botao-email-incial" class="btn btn-primary button"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Login</button> ou <button type="button" id="botao-email-cadastre-se" onclick="location.href='index.php?route=account/registroexpress';" class="btn btn-primary button"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span> Cadastro</button><?php if($facebook_ativado){?> ou <button onclick="LoginFB(false)" type="button" class="btn btn-primary button"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Facebook</button><?php } ?></p>
<p style="padding-top: 5px;"><a href="index.php?route=account/forgotten">Esqueceu sua senha?</a></p>

</form>
  
</div>
</div> 
</div>  

</div>

<script>
$(document).ready(function() {
	//verifica se o email ja existe ou nao
	$("#form-email-inicial").validate({
    <?php if($exibir_erro==0){ ?>
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
    <?php } ?>
	ignore: ".ignore",
    onfocusout: false,
    onkeyup: false,
    errorElement: "span",
    onclick: false,
    errorClass: "campo_invalido",
	submitHandler: function(form) {
        var dados = $( form ).serialize();
        $.ajax({
            type: "POST",
            url: urlSSL+"index.php?route=checkout/compraexpress/entrar",
            dataType: 'html',
            data: dados,
            beforeSend: function() {
                bloqueartela();
            },
        }).done(function( html ) {
                if(html==0){
                    $('#div-login-msg-aviso').html('<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle"></i> Ops, e-mail ou senha invalida!</div>');
                    $.unblockUI();
                    $('.blockUI').remove();
                }else{
                    location.reload(true);
                }
        });			
        return false;
	}
	});
});
</script>