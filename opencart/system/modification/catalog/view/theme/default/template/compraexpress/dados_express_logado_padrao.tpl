<div class="row">

<!-- dados do cliente -->
<div style="padding-right: 5px;" class="col-xs-12 col-md-4 xs-100 sm-100 md-33 lg-33 xl-33">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title" style="text-transform: uppercase;"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Dados do Cliente</h3>
<a onclick="limparemail();" class="pull-right btn btn-danger btn-xs" style="margin-top:-25px;cursor:pointer;">Sair</a>
</div>
<div class="panel-body">
<b>CLIENTE:</b><br>
<?php echo $dados_cliente['email'];?><br>
<?php echo (isset($jsoncliente[$campos_extras['fiscal']]) && strlen($jsoncliente[$campos_extras['fiscal']])==14)?'Empresa':'Nome';?>: <?php echo strtoupper($dados_cliente['firstname']);?> <?php echo strtoupper($dados_cliente['lastname']);?><br>
Telefone: <?php echo $dados_cliente['telephone'];?><br>
<?php if(isset($jsoncliente[$campos_extras['fiscal']])){?>
<?php echo (isset($jsoncliente[$campos_extras['fiscal']]) && strlen($jsoncliente[$campos_extras['fiscal']])==14)?'CNPJ':'CPF';?>: <?php echo $jsoncliente[$campos_extras['fiscal']];?><br>
<?php } ?>
<br>
<button type="button" onclick="abrir_url_modal('index.php?route=checkout/compraexpress/atualizarclienteform','Atualizar dados do cliente')" class="btn btn-success button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> editar cliente</button>
</div>
</div>
</div>

<!-- enderecos do cliente -->
<div style="padding-right: 5px;" class="col-xs-12 col-md-4 xs-100 sm-100 md-33 lg-33 xl-33">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title" style="text-transform: uppercase;">
<span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Endere&ccedil;os
</h3>

<div class="btn-group pull-right" style="margin-top:-25px;" role="group">
  <button type="button" onclick="ativar_mostrar_endereco('cobrar')" class="botoes_enderecos cobrar btn btn-default active">Cobran&ccedil;a</button>
  <?php if((isset($requer_entrega) && $requer_entrega)){?>
  <button type="button" onclick="ativar_mostrar_endereco('entregar')" class="botoes_enderecos entregar btn btn-default">Entrega</button>
  <?php } ?>
</div>

</div>
<div class="panel-body">

<!-- endereco de entrega -->
<?php if((isset($requer_entrega) && $requer_entrega)){?>
<?php
$jsonendereco = @$endereco_entrega['custom_field'];
?>
<span id="endereco-entregar" class="lista_enderecos" style="display:<?php echo (isset($requer_entrega) && $requer_entrega)?'none;':'';?>">
<b>ENTREGA:</b><br>
<u><?php echo strtoupper($endereco_entrega['firstname']);?> <?php echo strtoupper(($endereco_entrega['lastname']));?></u><br>
<?php echo strtoupper(($endereco_entrega['address_1']));?><?php if(isset($jsonendereco[$campos_extras['numero']])){?>, <?php echo $jsonendereco[$campos_extras['numero']];?><?php } ?> <?php if(isset($jsonendereco[$campos_extras['complemento']]) && !empty($jsonendereco[$campos_extras['complemento']])){?><br><i><?php echo $jsonendereco[$campos_extras['complemento']];?></i><?php } ?><br>
<?php echo strtoupper(($endereco_entrega['address_2']));?><br>
<?php echo strtoupper(($endereco_entrega['city']));?>/<?php echo strtoupper(($endereco_entrega['zone_code']));?> - <?php echo strtoupper(($endereco_entrega['postcode']));?>
<br>
<button type="button" onclick="abrir_url_modal('index.php?route=checkout/compraexpress/atualizarenderecoform&tipo=entrega&id=<?php echo $id_endereco_e;?>','Atualizar dados de entrega')" class="btn btn-info button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> editar endere&ccedil;o</button>
</span>
<?php } ?>

<!-- endereço de cobranca -->
<?php
$jsonenderecocob = @$endereco_cobranca['custom_field'];
?>
<span id="endereco-cobrar" class="lista_enderecos" style="">
<b>COBRAN&Ccedil;A:</b><br>
<u><?php echo strtoupper($endereco_cobranca['firstname']);?> <?php echo strtoupper(($endereco_cobranca['lastname']));?></u><br>
<?php echo strtoupper(($endereco_cobranca['address_1']));?><?php if(isset($jsonenderecocob[$campos_extras['numero']])){?>, <?php echo $jsonenderecocob[$campos_extras['numero']];?><?php } ?> <?php if(isset($jsonenderecocob[$campos_extras['complemento']]) && !empty($jsonenderecocob[$campos_extras['complemento']])){?><br><i><?php echo $jsonenderecocob[$campos_extras['complemento']];?></i><?php } ?><br>
<?php echo strtoupper(($endereco_cobranca['address_2']));?><br>
<?php echo strtoupper(($endereco_cobranca['city']));?>/<?php echo strtoupper(($endereco_cobranca['zone_code']));?> - <?php echo strtoupper(($endereco_cobranca['postcode']));?>
<br>
<button type="button" onclick="abrir_url_modal('index.php?route=checkout/compraexpress/atualizarenderecoform&tipo=cobranca&id=<?php echo $id_endereco_c;?>','Atualizar dados de cobranca')" class="btn btn-primary button"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> editar endere&ccedil;o</button>
</span>

</div>
</div>
</div>

<!-- formas de entrega -->
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
Nenhum meio de entrega disponivel para este produto, entre em contato com o atendimento!<br>
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
<div class="pull-right" style="margin-top: -25px;"><a onclick="return meiopagamentoescolhido()" href="javascript:abrir_url_modal('index.php?route=checkout/compraexpress/formcupom','Aplicar vale presente/cupom');" class="btn btn-primary btn-xs" style="cursor:pointer;"><span class="glyphicon glyphicon-gift" aria-hidden="true"></span> Aplicar cupom?</a></div>
</div>
<div id="tela-meios-pagamento" class="panel-body">

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

</div>
<div class="col-xs-12 col-md-8 xs-100 sm-100 md-66 lg-66 xl-66">
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
</div>
<?php }else{ ?>
<div class="alert alert-danger danger" role="alert">Ops, atualize o seu cadastro na loja para poder concluir com sua compra.</div>
<?php } ?>

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