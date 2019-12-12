<div class="row" style="position: relative;">
<div style="text-align: center;" class="centralizado col-md-offset-2 col-md-8 md-66 lg-66 xl-100">

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Cadastrar / Acessar</h3>
</div>
<div class="panel-body">

<div id='div-login-msg-aviso'></div>

<form id="form-email-inicial" class="validar_form" onsubmit="return false;" action="#" method="post" novalidate="novalidate">

<p style="padding-bottom: 5px;text-align: left;">Informe seu e-mail para continuar sua inscrição.</p>

<p style="padding-top: 5px;"><input autocomplete="off" style="text-align: left;" data-msg-required="Informe o seu e-mail!" id="email" name="email" class="form-control input-lg" type="email" placeholder="meu@email.com" value="<?php echo $email;?>" required></p>

<p style="padding-top: 15px;text-align: left;"><button type="submit" id="botao-email-incial" class="btn btn-primary button"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Continuar inscrição</button><?php if($facebook_ativado){?> ou <button onclick="LoginFB(false)" type="button" class="btn btn-primary button"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> Facebook</button><?php } ?></p>
</form>

</div>
</div>
</div>
</div>

<script>
$(document).ready(function() {
//verifica se o email ja existe ou nao Tewste
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
            url: urlSSL+"index.php?route=checkout/compraexpress/entrarcomemail",
            dataType: 'html',
            data: dados,
            beforeSend: function() {
                bloqueartela();
            },
        }).done(function( html ) {
            if(html=='0'){
                location.href = urlSSL+"index.php?route=account/registroexpress";
            }else{
                $('#html-compraexpress').html(html);
                $.unblockUI();
                $('.blockUI').remove();
            }
        });			
        return false;
    }
});
});
</script>

