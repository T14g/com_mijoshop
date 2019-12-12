<div class="row">
<div class="col-md-12 xl-100">
<div class="alert alert-info information" role="alert"><i class="fa fa-info-circle"></i> Os seus dados foram omitidos por quest&otilde;es de seguran&ccedil;a. Caso queira conferir ou alterar os seus dados <a href="javascript:void(0)" onclick="abrir_url_modal('index.php?route=checkout/compraexpress/formlogin','Acessar minha conta')">clique aqui</a>.</div>
</div>
</div>

<div class="row">
<div class="ladoB col-xs-12 col-md-8 xs-100 sm-100 md-66 lg-66 xl-66">

<!-- meios de entrega -->
<div class="row">
<div style="padding-right: 5px;display: none" class="col-xs-12 col-md-6 xs-100 sm-100 md-50 lg-50 xl-50">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title" style="text-transform: uppercase;"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Entrega</h3>
</div>
<div id="div-lista-meios-entrega" class="panel-body">
<?php
if($total_vazios==0){
if(isset($requer_entrega) && $requer_entrega){
?>
<input type="hidden" name="produto_digital" value="false">
<?php
$u=0;
foreach($meios_entrega AS $cotas){
if(is_array($cotas['quote']) && count($cotas['quote'])>0){
echo '<b style="text-transform: uppercase;">'.$cotas['title'].'</b><br>';
foreach($cotas['quote'] AS $cota){
$fretes_index[$u]=$cota['code'];
?>

<input type="radio" onclick="aplicarmeioentrega('<?php echo $cota['code'];?>');" id='<?php echo md5($cota['code']);?>' name="meios_entrega" value="<?php echo $cota['code'];?>"<?php echo ($meio_escolhido && $meio_escolhido==$cota['code'])?' checked':'';?>> <?php echo $cota['title'];?> - <?php echo $cota['text'];?><br>

<?php
$u++;
}
}
}
//se nenhum meio de entrega disponivel
if($u==0){
?>
<div class="alert alert-danger danger" role="alert">
Nenhum meio de entrega disponivel da loja ou para este produto, entre em contato com o atendimento da loja!<br>
</div>
<?php
}

}else{
$u=1;
?>
<input type="hidden" name="produto_digital" value="true">
<div class="alert alert-info info" role="alert">
Produto digital n&atilde;o requer meio de entrega!<br>
</div>
<?php
}
}else{
?>
<div class="alert alert-danger danger" role="alert">
Ops, seu cadastro encontre-se com alguns dados n&atilde;o atualizado clique em editar e atualize o mesmo!
</div>
<?php
}
?>

<?php if($com){?>
<!-- bloco de comentario do pedido -->
<hr>
<textarea onblur="$.post('index.php?route=checkout/compraexpress/salvarcomentario', { comentario: this.value } );" placeholder="Comentario ou instru&ccedil;&otilde;es" class="form-control" id="comentario_pedido" rows="2"><?php echo $comentario;?></textarea>
<?php } ?>

</div>
</div>
</div>

<!-- meios de pagamento -->
<div class="col-xs-12 col-md-12 xs-100 sm-100 md-100 lg-100 xl-100">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title" style="text-transform: uppercase;"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> Pagamento</h3>
</div>
<div id="div-lista-meios-pagamento" class="panel-body">

<?php 
$imp = 0;
if($total_vazios==0 && $u>0){ 
?>

<div class="row radios_pagamentos">
<?php 
$meio_index = array();
foreach($meios_pagamento AS $code=>$meio){
$meio_index[$imp]=$code;
?>
<div style="min-height: 25px;" class="col-md-12">
<label>
<input style="vertical-align: middle;" type="radio" onclick="javascript:aplicarmeiopagamento('<?php echo $code;?>');" name="meios_pagamento" class="radio_meios_pagamento" value="<?php echo $code;?>"> <?php echo !empty($meio['img'])?'<img src="'.$meio['img'].'" style="vertical-align: middle;" class="imagem_meios_pagamento">':'<span class="texto_meios_pagamento" style="vertical-align: middle;">'.$meio['title'].'</span>';?>
</label>
</div>
<?php 
$imp++;
} 
?>
</div>
<!-- Alerta pode ser aqui -->
<label style="margin-top: 17px;font-size: 12px;"><input type="checkbox" name="aceite" id="aceite">
	<a href="#" style="vertical-align: top;" data-toggle="modal" data-target="#termos">Informações e termos do pedido de inscrição</a>
</label>


<?php }else{ ?>
<div class="alert alert-danger warning" role="alert">Ops, atualize o seu cadastro na loja para poder concluir com sua compra.</div>
<?php } ?>

</div>
</div>
</div>
</div>

<!-- detalhes do pagamento -->
<div class="row">
<div class="col-md-12 xl-100">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title" style="text-transform: uppercase;"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Detalhes</h3>
<div class="pull-right" style="margin-top: -25px;"><a onclick="return meiopagamentoescolhido()" href="javascript:abrir_url_modal('index.php?route=checkout/compraexpress/formcupom','Aplicar vale presente/cupom');" class="btn btn-primary btn-xs" style="cursor:pointer;"><span class="glyphicon glyphicon-gift" aria-hidden="true"></span> Aplicar cupom?</a></div>
</div>
<div id="tela-meios-pagamento" class="panel-body">
<div id="html-detalhes-como-pagar">
<?php if($imp>0){?>
<div class="alert alert-info information hidden-xs" role="alert"><b>Pagamento:</b> Escolha e clique na forma de pagamento qual deseja concluir sua compra.</div>
<?php }else{ ?>
<div class="alert alert-danger danger" role="alert">Ops, nenhum meio de pagamento disponivel no momento! Entre em contato com o atendimento da loja.</div>
<?php } ?>
<div id="tabela-produtos"><?php echo $tabela_produto; ?></div>
</div>
<span id="gatilho-pagamento"></span>
</div>
</div>
</div>
</div>

</div>

<div style="padding-right: 5px;" class="LadoA col-xs-12 col-md-4 xs-100 sm-100 md-33 lg-33 xl-33">

<!-- dados do cliente -->
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title" style="text-transform: uppercase;"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Dados do Cliente</h3>
<a onclick="limparemail();" class="pull-right btn btn-danger btn-xs" style="margin-top:-25px;cursor:pointer;">Sair</a>
</div>
<div class="panel-body">
<b>CLIENTE:</b><br>
<?php echo $dados_cliente['email'];?><br>
<?php echo (strlen($jsoncliente[$campo_fiscal])==11)?'Nome':'Nome';?>: <?php echo strtoupper(substr($dados_cliente['firstname'],0,-3));?>*** <?php echo strtoupper(substr($dados_cliente['lastname'],0,-4));?>*****<br>
Telefone: <?php echo substr($dados_cliente['telephone'],0,-4);?>****<br>
<?php if(isset($jsoncliente[$campo_fiscal]) && !empty($jsoncliente[$campo_fiscal])){?>
<?php echo (strlen($jsoncliente[$campo_fiscal])==11)?'CPF':'CPF';?>: <?php echo substr($jsoncliente[$campo_fiscal],0,-4);?>*****<br>
<?php } ?>
<br>
<button type="button" onclick="abrir_url_modal('index.php?route=checkout/compraexpress/formlogin','Acessar minha conta')" class="btn btn-primary button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> editar cliente</button>
</div>
</div>

<!-- enderecos -->
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title" style="text-transform: uppercase;">
<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Endere&ccedil;os
</h3>
</div>
<div class="panel-body">
<b>COBRAN&Ccedil;A/ENTREGA:</b><br>
<?php
$jsonendereco = @$endereco_entrega['custom_field'];
?>
<u><?php echo strtoupper(substr($endereco_entrega['firstname'],0,-3));?>*** <?php echo strtoupper(substr($endereco_entrega['lastname'],0,-3));?>***</u><br>
<?php echo strtoupper(substr($endereco_entrega['address_1'],0,-6));?>******, <?php echo @$jsonendereco[$campo_numero];?><br>
<?php if(isset($jsonendereco[$campo_complemento]) && !empty($jsonendereco[$campo_complemento])){?>
<i><?php echo @$jsonendereco[$campo_complemento];?></i><br>
<?php } ?>
<?php echo strtoupper(substr($endereco_entrega['address_2'],0,-6));?>*****<br>
<?php echo strtoupper(substr($endereco_entrega['city'],0,-3));?>***/<?php echo strtoupper($endereco_entrega['zone_code']);?> - <?php echo strtoupper(substr($endereco_entrega['postcode'],0,-3));?>***<br>
<br>
<button type="button" onclick="abrir_url_modal('index.php?route=checkout/compraexpress/formlogin','Acessar minha conta')" class="btn btn-primary button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> editar endere&ccedil;o</button>
</div>
</div>

</div>


</div><!-- fica -->

<input type="hidden" value="" id="meio_selecionado_express">
<input type="hidden" value="<?php echo $dados_cliente['customer_id'];?>" id="cliente_id">
<input type="hidden" value="<?php echo sha1(md5($dados_cliente['customer_id']));?>" id="cliente_hash">

<script>
<?php if(isset($fretes_index[0])){ ?>
<?php if($meio_escolhido==false){ ?>
//selecione o primeio meio de entrega ou um ja selecionado
$("#<?php echo md5($fretes_index[0]);?>").prop( "checked", true );
aplicarmeioentrega('<?php echo $fretes_index[0];?>');
<?php }else{ ?>
//selecione o primeio meio de entrega ou um ja selecionado
aplicarmeioentrega('<?php echo @$meio_escolhido;?>');
<?php } } ?>

<?php if(isset($meio_index) && count($meio_index)==1 && isset($meio_index[0])){ ?>
//se so um meio de pagamento auto seleciona
aplicarmeiopagamento('<?php echo $meio_index[0];?>');
<?php } ?>
<?php if($total_vazios > 0){ ?>
//se tem erros
$.unblockUI();
$('.blockUI').remove();
<?php } ?>
</script>

<!-- Modal -->
<div id="termos" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" style="float: right;" class="btn btn-success fecharaceitar" data-dismiss="modal">Fechar e aceitar</button>
        <h4 class="modal-title">Termos do pedido</h4>
      </div>
       <div class="modal-body">
        <p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong style="font-size: 10pt;">INFORMAÇÕES GERAIS DO CURSO</strong><span style="font-size: 10pt;"> </span></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O Aluno (a) Receberá todas  as informações sobre o curso matriculado via e-mail em até 05 dias após a efetivação do pagamento, no comunicado constará informações básicas necessárias à execução, diretrizes e conclusão do curso.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong style="font-size: 10pt;">Inscrição:</strong><span style="font-size: 10pt;"> a inscrição do </span><strong style="font-size: 10pt;">Aluno(a)</strong><span style="font-size: 10pt;"> somente será efetivada após o pagamento integral da taxa de inscrição ou, em caso de parcelamento, após o pagamento da primeira parcela ajustada. Antes de realizado o pagamento e efetivada a inscrição, o CTSEM não garantirá a vaga do aluno no curso ministrado, estando este último ciente de que as turmas são reduzidas e limitadas a determinado número de pessoas.</span></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong>PAGAMENTO</strong></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Pagamentos por Depósitos / Transferências bancárias possuem um desconto de 5% no valor pago à vista.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Pagamentos por Pagseguro na modalidade Cartão de crédito - O preço ajustado poderá ser parcelado à escolha do <strong>Aluno(a)</strong>, na própria plataforma de pagamentos (Pagseguro), mediante tarifa de parcelamento cobrada diretamente pelo Pagseguro.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Pagamentos por Pagseguro na modalidade Boleto a vista será escolhido na própria plataforma e tem a validade máxima de 7 dias para a efetivação da liquidação, caso contrário o pedido será cancelado.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O parcelamento com pagamento de uma ou mais parcelas, deve ser acertado diretamente na sede do CSTEM – Rua Santana 1263 Porto Alegre-RS. Através de cartão de crédito, cujo parcelamento é limitado a 4 vezes.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O valor pago <strong>não será reembolsado</strong> em caso de não comparecimento do aluno ao curso. De igual forma, o comparecimento do <strong>Aluno(a)</strong> a apenas uma ou parte de determinada aula <strong>não ensejará reembolso do valor integral do curso. Em hipótese alguma a reprovação do Aluno(a) ensejará a devolução do valor pagou ou, ainda, abatimento no preço do curso. Consulte os Temos de Transferência E Aprovação.</strong></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"> </p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong>APROVAÇÃO E CERTIFICAÇÃO</strong> </p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Será considerado aprovado o Aluno(a) que:</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">(a) obtiver frequência de <strong>100%</strong> das horas/aula; </p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">(b)  obtiver aprovação na prova prática do curso; </p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">(c) obtiver aproveitamento de pelo menos 84% na prova teórica do curso.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Regras quanto a aprovação no curso <br /><br />Os alunos que não forem aprovados na prova teórica poderão repetir o exame sem ônus durante o prazo de 1 mês, em local e data a ser agendada pelo aluno com a secretaria do CTSEM. </p>
<ul style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">
<li>Os Alunos que não obtiverem aproveitamento satisfatório na prova prática terão uma segunda chance no mesmo dia. Caso o resultado insatisfatório se mantenha, os alunos nesta situação deverão refazer o curso, o que o CTSEM confere por mera liberalidade um desconto de 50% no valor do curso.</li>
</ul>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Ao final do curso, desde que <strong>aprovado</strong>, o <strong>Aluno(a)</strong> receberá Cartão de Certificação Internacional, com validade de 2 (dois) anos, sendo este o único certificado oficial da <em>American Heart Association – AHA</em>.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O <strong>Aluno(a)</strong> se responsabiliza exclusivamente pelos dados informados durante o cadastro realizado, estando ciente e concorde de que a solicitação de correção do nome, posterior à impressão do Cartão de Certificação, implicará na cobrança de taxa de R$100,00 (cem reais).</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Em caso de perda/extravio do Cartão de Certificação, a solicitação da 2ª via poderá ser realizada através do e-mail <a style="color: #0186ba;" href="mailto:secretaria@ctsem.com">secretaria@ctsem.com</a> , mediante pagamento de taxa de R$100,00 (cem reais).</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong><br />PEDIDO DE TRANSFERÊNCIA DE TURMA</strong></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O pedido de transferência de turma, que somente poderá ocorrer <strong>uma única vez</strong>, deverá ser solicitado por escrito, com antecedência <strong>mínima</strong> de 15 (quinze) dias da data da realização do curso, e deverá ser enviado para o seguinte e-mail <a style="color: #0186ba;" href="mailto:secretaria@ctsem.com">secretaria@ctsem.com</a></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O Aluno (a) que por motivos de força maior necessitar de transferência de datas de aulas ou provas, deve solicitar a secretaria do CTSEM com antecedência superior a 15 dias sem custo para o aluno.<br />Para Transferências solicitadas com menos de 15 dias da data de realização do curso, o aluno(a) deverá pagar a Taxa de Transferência no valor de R$ 300,00. </p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong>CANCELAMENTO POR PARTE DO CTSEM:</strong></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O CTSEM se reserva o direito de <strong>cancelar</strong> a realização do curso cuja turma não atinja o número mínimo de alunos.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Na hipótese de cancelamento, <strong>o Aluno(a)</strong> deverá ser notificado com antecedência mínima de 07 (sete) dias da data de realização do curso, notificação que será enviada para o e-mail cadastrado, ocasião em que poderá solicitar transferência para outra turma, desde que haja disponibilidade de vagas.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Caso a segunda turma não feche o número mínimo de inscritos o aluno(a) poderá solicitar ressarcimento do valor pago até o momento.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Realizado o cancelamento e optando o<strong> Aluno(a)</strong> pela devolução do valor pago, deverá este último restituir ao CTSEM a integralidade do material didático fornecido, em perfeito estado de conservação e sem qualquer violação ou abertura do lacre. A não observância do disposto no presente parágrafo autorizará o abatimento proporcional do preço do material didático do valor a ser restituído ao <strong>Aluno(a)</strong>.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><br /><strong>CANCELAMENTO POR PARTE DO ALUNO(A)</strong></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O <strong>Aluno(a)</strong> poderá cancelar a sua inscrição, no <strong>prazo máximo</strong> de 30  (trinta) dias anteriores ao início das aulas, mediante solicitação enviada por escrito, para o seguinte endereço de e-mail: <a style="color: #0186ba;" href="mailto:secretaria@ctsem.com">secretaria@ctsem.com</a>. O pedido de cancelamento está sujeito ao <strong>pagamento de multa</strong>, bem como <strong>à devolução integral do material didático</strong>, exceto quando o material for o e-book, o qual não há devolução, nos seguintes termos:</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Caso o cancelamento ocorra até 30 (trinta) dias antes da data de início das aulas, será devida uma multa de 40% (quarenta por cento) sobre o valor integral do curso;</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong> </strong></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong>MATERIAL DIDÁTICO</strong></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Após o recebimento do valor integral ajustado ou, na hipótese de parcelamento, após o pagamento da primeira parcela do preço, o CSTEM enviará para o <strong>Aluno(a)</strong> o material didático oficial, composto por 1 (um) livro do curso a ser ministrado, cuja leitura é obrigatória. <br />O material didático será enviado pelos Correios, no endereço fornecido no momento da inscrição.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O aluno escolherá a forma de envio do material no ato da inscrição, o que influenciará diretamente no prazo de entrega do mesmo.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Observação em alguns cursos o material é enviado de forma online em e-book (Caso o livro não seja recebido em um período de até 7 dias após a inscrição favor entrar em contato com a empresa).</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"> </p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">CARTA DE CONFIRMAÇÃO</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Leia atentamente a carta de confirmação enviada para seu e-mail, nela constará informações e requisitos obrigatórios para a realização do curso.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong><br />DISPOSIÇÕES FINAIS</strong></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">O<strong> Aluno(a) </strong>cede ao CTSEM, a título gratuito, o direito de uso e exploração de sua imagem, que poderá ser veiculada no Site oficial da instituição e em órgãos midiáticos impressos ou virtuais, durante ou após a realização do curso, a título de promoção e divulgação do evento ou da instituição.</p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;"><strong>ELEIÇÃO DE FORO</strong> </p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Para dirimir quaisquer dúvidas ou controvérsias decorrentes do presente Contrato as partes elegem o foro da Comarca de <strong>Porto Alegre</strong>, <strong>renunciando expressamente a qualquer outro, por mais privilegiado que seja.</strong></p>
<p style="font-family: Verdana, Geneva, sans-serif; font-size: 13.3333px;">Afirmo que li e concordo com os termos acima.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success fecharaceitar" data-dismiss="modal">Fechar e aceitar</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
	$('.fecharaceitar').click(function() {
		$('#aceite').attr('checked','checked');
	})
</script>