<?php echo $header; ?>

<!-- css e js comun em todo checkout -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.11/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.11/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.11/js/locales/bootstrap-datetimepicker.pt-BR.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.1.0/vanilla-masker.min.js"></script>
<link rel="stylesheet" type="text/css" href="components/com_mijoshop/opencart/catalog/view/checkoutexpress/compraexpress.css" />

<div id="container" class="container j-container">

<?php if ($error_warning) { ?>
<div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
<button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>

<div class="row">

<div id="content" class="tela_full_compraexpress col-md-12" style="min-height:500px;">
<span id="html-compraexpress"></span>
</div>

<!-- inicio imagem copy -->
<!--
<div class="col-md-12 text-center bloco_direitos" style="text-align:center">
<a href="http://www.loja5.com.br" target="_blank"><img class="center-block" src="data:image/png;base64,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"/></a>
</div>
-->
<!-- fim imagem copy -->

</div>

</div>

<script>
//init
$(document).ready(function() {
	//checa se ja esta logado
	bloqueartela();
    estalogadoounao();

});
</script>

<?php echo $footer; ?>