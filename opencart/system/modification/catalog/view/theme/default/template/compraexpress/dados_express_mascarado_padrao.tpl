<div class="row">

<div class="col-md-12 xl-100">
<div class="alert alert-info information" role="alert"><i class="fa fa-info-circle"></i> Os seus dados foram omitidos por quest&otilde;es de seguran&ccedil;a. Caso queira conferir ou alterar os seus dados <a href="javascript:void(0)" onclick="abrir_url_modal('index.php?route=checkout/compraexpress/formlogin','Acessar minha conta')">clique aqui</a>.</div>
</div>

<!-- tela de dados do cliente mascarado -->
<div style="padding-right: 5px;" class="col-xs-12 col-md-4 xs-100 sm-100 md-33 lg-33 xl-33">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title" style="text-transform: uppercase;"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Dados do Cliente</h3>
<a onclick="limparemail();" class="pull-right btn btn-danger btn-xs" style="margin-top:-25px;cursor:pointer;">Sair</a>
</div>
<div class="panel-body">
<b>CLIENTE:</b><br>
<?php echo $dados_cliente['email'];?><br>
<?php echo (strlen($jsoncliente[$campo_fiscal])==11)?'Nome':'Empresa';?>: <?php echo strtoupper(substr($dados_cliente['firstname'],0,-3));?>*** <?php echo strtoupper(substr($dados_cliente['lastname'],0,-4));?>*****<br>
Telefone: <?php echo substr($dados_cliente['telephone'],0,-4);?>****<br>
<?php if(isset($jsoncliente[$campo_fiscal]) && !empty($jsoncliente[$campo_fiscal])){?>
<?php echo (strlen($jsoncliente[$campo_fiscal])==11)?'CPF':'CNPJ';?>: <?php echo substr($jsoncliente[$campo_fiscal],0,-4);?>*****<br>
<?php } ?>
<br>
<button type="button" onclick="abrir_url_modal('index.php?route=checkout/compraexpress/formlogin','Acessar minha conta')" class="btn btn-primary button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> editar cliente</button>
</div>
</div>
</div>

<!-- tela de dados de entrega mascarado -->
<div  style="padding-right: 5px;" class="col-xs-12 col-md-4 xs-100 sm-100 md-33 lg-33 xl-33">
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

<!-- meios de entrega -->
<div class="col-xs-12 col-md-4 xs-100 sm-100 md-33 lg-33 xl-33">
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
<!-- ativa os comentarios -->
<hr>
<textarea onblur="$.post('index.php?route=checkout/compraexpress/salvarcomentario', { comentario: this.value } );" placeholder="Comentario ou instru&ccedil;&otilde;es" class="form-control" id="comentario_pedido" rows="2"><?php echo $comentario;?>
</textarea>
<?php } ?>

</div>
</div>
</div>
</div>

<!-- meios de pagamento -->
<?php 
$imp = 0;
if($total_vazios==0 && $u>0){ 
?>
<div class="row">
<div class="col-md-12 xl-100">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title" style="text-transform: uppercase;"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> Pagamento</h3>
<div class="pull-right" style="margin-top: -25px;"><a onclick="return meiopagamentoescolhido()" href="javascript:abrir_url_modal('index.php?route=checkout/compraexpress/formcupom','Aplicar vale presente/cupom');" class="btn btn-primary button btn-xs" style="cursor:pointer;"><span class="glyphicon glyphicon-gift" aria-hidden="true"></span> Aplicar cupom?</a></div>
</div>
<div class="panel-body">

<div class="row">
<div class="col-xs-12 col-md-4 xs-100 sm-100 md-33 lg-33 xl-33">

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
</div>
<div class="col-xs-12 col-md-8 xs-100 sm-100 md-66 lg-66 xl-66">
<div id="html-detalhes-como-pagar">
<?php if($imp>0){ ?>
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
</div>

<?php }else{ ?>
<div class="alert alert-danger danger" role="alert">Ops, atualize o seu cadastro na loja para poder concluir com sua compra.</div>
<?php } ?>

<input type="hidden" value="" id="meio_selecionado_express">
<input type="hidden" value="<?php echo $dados_cliente['customer_id'];?>" id="cliente_id">
<input type="hidden" value="<?php echo sha1(md5($dados_cliente['customer_id']));?>" id="cliente_hash">

<script>
<?php if(isset($fretes_index[0])){?>
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