<script type="text/javascript" src="<?php echo HTTPS_SERVER;?>app/pagsegurolightbox/bootbox.min.js?<?php echo time();?>"></script>

<div class="buttons">
  <div class="pull-left">
  Seu pagamento ser&aacute; concluido em ambiente seguro PagSeguro!
  </div>
  <div class="pull-right">
    <input type="button" value="Concluir Pagamento" id="button-confirm" class="btn btn-primary button" />
  </div>
</div>

<script type="text/javascript">
//carrega o javascript pagseguro lightbox
$.getScript("https://stc.<?php echo $modo;?>pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js?<?php echo time();?>")
.done(function( script, textStatus ) {

}).fail(function( jqxhr, settings, exception ) {
	//alert de erro
	bootbox.dialog({
	message: 'Ops, problema ao carregar PagSeguro! atualize a pagina.',
	title: "Erro",
	});
	//desativa o botao
	$('#button-confirm').attr('disabled', 'disabled');
});
//funcao abrir pagseguro
function pagarComPagseguroLight(){
	var checkoutCode = '<?php echo $pagseguro['code'];?>';

	isOpenLightbox = PagSeguroLightbox({code: checkoutCode}, {
	success : function(transactionCode) {
		//redir se ok
		location.href = '<?php echo $url_criar;?>&id='+transactionCode;
	},
	abort : function() {
		//alerta se erro
		bootbox.dialog({
		message: 'Ops, transa&ccedil;&atilde;o cancelado pelo cliente, escolha outra forma de pagamento ou tente novamente.',
		title: "Cancelado pelo Cliente",
		});
	}});
	
	if (!isOpenLightbox){
		location.href="https://<?php echo $modo;?>pagseguro.uol.com.br/v2/checkout/payment.html?code="+checkoutCode;
	}
}
//abre manualmente
$('#button-confirm').bind('click', function() {
  pagarComPagseguroLight();
});
</script>