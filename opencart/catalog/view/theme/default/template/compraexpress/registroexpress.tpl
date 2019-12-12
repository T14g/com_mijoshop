<?php echo $header; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.11/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.11/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.11/js/locales/bootstrap-datetimepicker.pt-BR.js"></script>

<script>
//define a url ssl segura para javascript
var urlSSL = '<?php echo HTTPS_SERVER; ?>';
//carrega o endereco de acordo cep informado pelo cliente
function getEndereco() {
var EstadosArray = [];
<?php foreach($lista_estados AS $k=>$v){?>
EstadosArray["<?php echo $v['code'];?>"] = <?php echo $v['zone_id'];?>;
<?php } ?>
var cep = $("#cep").val();
var s = (cep).replace(/\D/g,'');
var tam=(s).length;
if(tam==8){
    $.getScript(urlSSL+"api_cep.php?formato=javascript&cep="+cep, function(){
    if(resultadoCEP["resultado"] != 0){
        console.log(resultadoCEP);
        $("#logradouro").val(unescape(resultadoCEP["tipo_logradouro"]).toUpperCase()+""+unescape(resultadoCEP["logradouro"]).toUpperCase());
        $("#bairro").val(unescape(resultadoCEP["bairro"]).toUpperCase());
        $("#cidade").val(unescape(resultadoCEP["cidade"]).toUpperCase());
        $("#uf").val(EstadosArray[resultadoCEP["uf"]]);
        //$("#numero").focus();
        //$(".form_dados_cliente_up").valid();
    }
    });
}
}
<?php
$nome_fb = '';
if(isset($_GET['fb'])){
$partes = explode('|',$_GET['fb']);
$email = isset($partes[0])?$partes[0]:'';
$nome_fb = isset($partes[1])?$partes[1]:'';
$nome_fb .= isset($partes[2])?$partes[2]:'';
} 
?>
</script>
<script>
//mascaras javascript pura
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
	if(v_fun=='telefone')
   	 v_obj.value=telefone(v_obj.value);
	if(v_fun=='soNumeros')
   	 v_obj.value=soNumeros(v_obj.value);
	if(v_fun=='cpf')
   	 v_obj.value=cpf(v_obj.value);
	if(v_fun=='cnpj')
   	 v_obj.value=cnpj(v_obj.value);
 	if(v_fun=='cpfcnpj')
   	 v_obj.value=cpfCnpj(v_obj.value);
  	if(v_fun=='cep')
   	 v_obj.value=cep(v_obj.value);
}

function cpfCnpj(v){
    v=v.replace(/\D/g,"")
    if (v.length <= 14) {
        v=v.replace(/(\d{3})(\d)/,"$1.$2")
        v=v.replace(/(\d{3})(\d)/,"$1.$2")
        v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
    } else {
        v=v.replace(/^(\d{2})(\d)/,"$1.$2")
        v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3")
        v=v.replace(/\.(\d{3})(\d)/,".$1/$2")
        v=v.replace(/(\d{4})(\d)/,"$1-$2")
    }
    return v
}

function soNumeros(v){
    return v.replace(/\D/g,"")
}

function telefone(v){
    v=v.replace(/\D/g,"")
    v=v.replace(/^(\d\d)(\d)/g,"($1)$2")
    return v
}

function cpf(v){
    v=v.substr(0,14);
	v=v.replace(/\D/g,"")
    v=v.replace(/(\d{3})(\d)/,"$1.$2")
    v=v.replace(/(\d{3})(\d)/,"$1.$2")
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
    return v
}

function cnpj(v){
   v=v.substr(0,18);
   v=v.replace(/\D/g,"")
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3")
    v=v.replace(/\.(\d{3})(\d)/,".$1/$2")
    v=v.replace(/(\d{4})(\d)/,"$1-$2")
    return v
}

function cep(v){
	v=v.replace(/[^1234567890-]/g,"");
    v=v.replace(/^(\d{5})(\d)/,"$1-$2")
    return v
}
//fim mascaras pura
</script>

<!-- css comum em todo checkout -->
<link rel="stylesheet" type="text/css" href="components/com_mijoshop/opencart/catalog/view/checkoutexpress/compraexpress.css" />

<div id="container" class="container j-container">

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Cadastre-se no site</h3>
</div>
<div class="panel-body">
<form method="post" onsubmit="return false;" action="#" class="form_dados_cliente_up" novalidate="novalidate" autocomplete="off">

<input type="hidden" name="produtos" value="<?php echo isset($produtos)?$produtos:0;?>">

<div style="padding-right: 10px;" class="col-xs-12 col-md-6 xl-50 xs-100">

<div class="form-group">
<label for="inputEmail3" class="control-label">&nbsp;</label>
<div class="">

</div>
</div>

<div class="form-group">
<label for="inputEmail3" class="control-label">E-mail</label>
<div class="">
<input type="email" value="<?php echo isset($email)?$email:'';?>" class="form-control dados_cliente_up" name="email" id="email" placeholder="E-mail" required>
</div>
</div>

<?php 
if($tipo==0){
$campo_fiscal = 'CPF ou CNPJ';
$nome_empresa = 'seu nome/empresa';
$frase_validar = 'Qual seu CPF/CNPJ?';
$frase_validar_ok = 'Informe um CPF/CNPJ valido!';
$mascara = "mascara(this,'cpfcnpj')";
$max = 17;
?>
<div class="form-group">
<label for="inputEmail3" class="control-label">Tipo</label>
<div class="">
<input type="radio" onclick="tipo_cliente_cliente('pf')" name="tipo_pessoa" value="pf">
Pessoa F&iacute;sica &nbsp; 
<input type="radio" onclick="tipo_cliente_cliente('pj')" name="tipo_pessoa" value="pj">
Pessoa Juridica
</div>
</div>
<?php 
} elseif($tipo==1){
$campo_fiscal = 'CPF';
$nome_empresa = 'Nome do aluno';
$frase_validar = 'Qual seu CPF?';
$frase_validar_ok = 'Informe um CPF valido!';
$mascara = "mascara(this,'cpf')";
$max = 14;
?>
<input type="hidden" name="tipo_pessoa" value="pf">
<?php 
} elseif($tipo==2){
$campo_fiscal = 'CNPJ';
$nome_empresa = 'sua empresa';
$frase_validar = 'Qual seu CNPJ?';
$frase_validar_ok = 'Informe um CPF/CNPJ valido!';
$mascara = "mascara(this,'cnpj')";
$max = 17;
?>
<input type="hidden" name="tipo_pessoa" value="pj">
<?php } ?>

<div class="form-group">
<label for="nome" id="registro-nome" class="control-label">Nome do aluno</label>
<div class="class_nome">
<input type="text" value="" class="form-control dados_cliente_up para_upper" name="nome" id="nome" placeholder="Nome completo" required>
</div>
</div>

<div class="form-group">
<label for="fiscal" id="registro-fiscal" class="control-label">CPF/CNPJ</label>
<div class="">
<input type="text" maxlength="<?php echo $max;?>" type="text" onkeyup="<?php echo $mascara;?>" onkeydown="<?php echo $mascara;?>" class="form-control dados_cliente_up mask_fiscals" name="fiscal" id="fiscal" placeholder="<?php echo $campo_fiscal;?>" required>
</div>
</div>

<div class="form-group">
<label for="telefone" class="control-label">Telefone</label>
<div class="">
<input type="text" maxlength="13" onkeyup="mascara(this,'telefone')" onkeydown="mascara(this,'telefone')" class="form-control dados_cliente_up mask_telefones" name="telefone" id="telefone" placeholder="(00)000000000" required>
</div>
</div>

<?php 
$negar = array($campo_fiscal,$campo_fiscalpj,$campo_numero,$campo_complemento);
?>
<?php if($custom_fields){?>
<?php foreach($custom_fields as $campo){ ?>
<?php if($campo['location']=='account' && !in_array($campo['custom_field_id'],$negar)){ ?>

<?php if($campo['type']=='select'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<select <?php echo ($campo['required'])?'data-msg-required="Selecione uma op&ccedil;&atilde;o em '.$campo['name'].'"':'';?> class="form-control" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>"<?php echo ($campo['required'])?' required':'';?>>
<?php foreach($campo['custom_field_value'] as $valor){ ?>
<option value="<?php echo $valor['custom_field_value_id'];?>"><?php echo $valor['name'];?></option>
<?php } ?>
</select>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='checkbox'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<div class="checkbox">
<?php foreach($campo['custom_field_value'] as $valor){ ?>
<label><input <?php echo ($campo['required'])?'data-msg-required="Selecione as op&ccedil;&tilde;es':'';?> name="custom_field[<?php echo $campo['custom_field_id'];?>][]" id="custom-<?php echo $campo['custom_field_id'];?>" type="checkbox" value="<?php echo $valor['custom_field_value_id'];?>"<?php echo ($campo['required'])?' required':'';?>> <?php echo $valor['name'];?></label>
<?php } ?>
</div>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='radio'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<div class="radio">
<?php foreach($campo['custom_field_value'] as $valor){ ?>
<label><input <?php echo ($campo['required'])?'data-msg-required="Selecione uma op&ccedil;&atilde;o!"':'';?> name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" type="radio" value="<?php echo $valor['custom_field_value_id'];?>"<?php echo ($campo['required'])?' required':'';?>> <?php echo $valor['name'];?></label>
<?php } ?>
</div>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='text'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<input <?php echo ($campo['required'])?'data-msg-required="Digite os dados em '.$campo['name'].'"':'';?> type="text" class="form-control" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" value="<?php echo $campo['value'];?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='date'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div  class="">
<input <?php echo ($campo['required'])?'data-msg-required="Informar uma data em '.$campo['name'].'"':'';?> type="text" class="form-control date" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" value="<?php echo $campo['value'];?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='time'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<input <?php echo ($campo['required'])?'data-msg-required="Informar uma hora em '.$campo['name'].'"':'';?> type="text" class="form-control time" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" value="<?php echo $campo['value'];?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='datetime'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<input type="text" <?php echo ($campo['required'])?'data-msg-required="Informar uma data e hora em '.$campo['name'].'"':'';?> class="form-control datetime" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" value="<?php echo $campo['value'];?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='textarea'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<textarea <?php echo ($campo['required'])?'data-msg-required="Digite os dados em '.$campo['name'].'"':'';?> class="form-control" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>"<?php echo ($campo['required'])?' required':'';?>><?php echo $campo['value'];?></textarea>
</div>
</div>
<?php } ?>

<?php } ?>
<?php } ?>
<?php } ?>

<div class="form-group">
<label for="senha" class="control-label">Senha</label>
<div class="">
<input type="password" class="form-control dados_cliente_up" name="senha" id="senha" placeholder="******" required>
</div>
</div>

<div class="form-group">
<label for="senha2" class="control-label">Confirme</label>
<div class="">
<input type="password" class="form-control dados_cliente_up" name="senha2" id="senha2" placeholder="******" required>
</div>
</div>

<div style="display: none" class="form-group">
<label class="control-label">&nbsp;</label>
<div class="">
<input type="checkbox" name="novidades" checked value="1"> Gostaria de receber ofertas e novidades de <?php echo $nomedaloja;?>
</div>
</div>

</div>

<div class="col-xs-12 col-md-6 xl-50 xs-100">

<div class="form-group">
<label for="inputEmail3" class="control-label">&nbsp;</label>
<div class="">

</div>
</div>

<div class="form-group">
<label for="inputEmail3" class="control-label">CEP</label>
<div class="">
<input type="text" maxlength="9" onkeyup="getEndereco();mascara(this,'cep');" onkeydown="getEndereco();mascara(this,'cep');" value="" class="form-control mask_cep" name="cep" id="cep" placeholder="00000-000" required>
</div>
</div>

<div class="form-group">
<label for="inputPassword3" class="control-label">Endereço</label>
<div class="">
<input type="text" class="form-control dados_cliente_up" name="logradouro" id="logradouro" placeholder="Endereço" required>
</div>
</div>

<div class="form-group">
<label for="inputPassword3" class="control-label">Número</label>
<div class="">
<input type="text" class="para_upper form-control dados_cliente_up" name="numero" id="numero" placeholder="Numero" required>
</div>
</div>

<div class="form-group">
<label for="inputPassword3" class="control-label">Complemento (casa, Apto)</label>
<div class="">
<input type="text" class="para_upper form-control dados_cliente_up" name="complemento" id="complemento" placeholder="Ex: Casa, Apto">
</div>
</div>

<div class="form-group">
<label for="inputPassword3" class="control-label">Cidade</label>
<div class="">
<input type="text" class="form-control dados_cliente_up para_upper" name="cidade" id="cidade" placeholder="Cidade" required>
</div>
</div>

<div class="form-group">
<label for="inputPassword3" class=" control-label">Bairro</label>
<div class="">
<input type="text" class="form-control dados_cliente_up para_upper" name="bairro" id="bairro" placeholder="Bairro" required>
</div>
</div>

<div class="form-group">
<label for="inputPassword3" class=" control-label">Estado</label>
<div class="">
<select class="form-control dados_cliente_up para_upper" id="uf" name="uf" required>
<option value="">Selecione</option>
<?php foreach($lista_estados AS $k=>$v){?>
<option value="<?php echo $v['zone_id'];?>"><?php echo ($v['name']);?></option>
<?php } ?>
</select>
</div>
</div>

<div class="form-group">
<label for="inputPassword3" class=" control-label">Pa&iacute;s</label>
<div class="">
<select class="form-control dados_cliente_up para_upper" onchange="carregarEstados(this.value,'uf')" id="pais" name="pais" required>
<?php
foreach($countries AS $k=>$v){
?>
<option value="<?php echo $v['country_id'];?>"<?php echo ($pais_padrao==$v['country_id'])?' selected':'';?>><?php echo ($v['name']);?></option>
<?php } ?>
</select>
</div>
</div>

<?php if($custom_fields){?>
<?php foreach($custom_fields as $campo){ ?>
<?php if($campo['location']=='address' && !in_array($campo['custom_field_id'],$negar)){ ?>

<?php if($campo['type']=='select'){ ?>
<div class="form-group">
<label for="custom-endereco-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<select <?php echo ($campo['required'])?'data-msg-required="Selecione uma op&ccedil;&atilde;o em '.$campo['name'].'"':'';?> class="form-control" name="custom_field_endereco[<?php echo $campo['custom_field_id'];?>]" id="custom-endereco-<?php echo $campo['custom_field_id'];?>"<?php echo ($campo['required'])?' required':'';?>>
<?php foreach($campo['custom_field_value'] as $valor){ ?>
<option value="<?php echo $valor['custom_field_value_id'];?>"><?php echo $valor['name'];?></option>
<?php } ?>
</select>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='checkbox'){ ?>
<div class="form-group">
<label for="custom-endereco-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<div class="checkbox">
<?php foreach($campo['custom_field_value'] as $valor){ ?>
<label><input <?php echo ($campo['required'])?'data-msg-required="Selecione as op&ccedil;&tilde;es':'';?> name="custom_field_endereco[<?php echo $campo['custom_field_id'];?>][]" id="custom-endereco-<?php echo $campo['custom_field_id'];?>" type="checkbox" value="<?php echo $valor['custom_field_value_id'];?>"<?php echo ($campo['required'])?' required':'';?>> <?php echo $valor['name'];?></label>
<?php } ?>
</div>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='radio'){ ?>
<div class="form-group">
<label for="custom-endereco-<?php echo $campo['custom_field_id'];?>" class=" control-label"><?php echo $campo['name'];?></label>
<div class="">
<div class="radio">
<?php foreach($campo['custom_field_value'] as $valor){ ?>
<label><input <?php echo ($campo['required'])?'data-msg-required="Selecione uma op&ccedil;&atilde;o!"':'';?> name="custom_field_endereco[<?php echo $campo['custom_field_id'];?>]" id="custom-endereco-<?php echo $campo['custom_field_id'];?>" type="radio" value="<?php echo $valor['custom_field_value_id'];?>"<?php echo ($campo['required'])?' required':'';?>> <?php echo $valor['name'];?></label>
<?php } ?>
</div>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='text'){ ?>
<div class="form-group">
<label for="custom-endereco-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<input <?php echo ($campo['required'])?'data-msg-required="Digite os dados em '.$campo['name'].'"':'';?> type="text" class="form-control" name="custom_field_endereco[<?php echo $campo['custom_field_id'];?>]" id="custom-endereco-<?php echo $campo['custom_field_id'];?>" value="<?php echo $campo['value'];?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='date'){ ?>
<div class="form-group">
<label for="custom-endereco-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<input <?php echo ($campo['required'])?'data-msg-required="Informar uma data em '.$campo['name'].'"':'';?> type="date" class="form-control" name="custom_field_endereco[<?php echo $campo['custom_field_id'];?>]" id="custom-endereco-<?php echo $campo['custom_field_id'];?>" value="<?php echo $campo['value'];?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='time'){ ?>
<div class="form-group">
<label for="custom-endereco-<?php echo $campo['custom_field_id'];?>" class=" control-label"><?php echo $campo['name'];?></label>
<div class="">
<input <?php echo ($campo['required'])?'data-msg-required="Informar uma hora em '.$campo['name'].'"':'';?> type="time" class="form-control" name="custom_field_endereco[<?php echo $campo['custom_field_id'];?>]" id="custom-endereco-<?php echo $campo['custom_field_id'];?>" value="<?php echo $campo['value'];?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='datetime'){ ?>
<div class="form-group">
<label for="custom-endereco-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<input type="datetime-local" <?php echo ($campo['required'])?'data-msg-required="Informar uma data e hora em '.$campo['name'].'"':'';?> class="form-control" name="custom_field_endereco[<?php echo $campo['custom_field_id'];?>]" id="custom-endereco-<?php echo $campo['custom_field_id'];?>" value="<?php echo $campo['value'];?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='textarea'){ ?>
<div class="form-group">
<label for="custom-endereco-<?php echo $campo['custom_field_id'];?>" class="control-label"><?php echo $campo['name'];?></label>
<div class="">
<textarea <?php echo ($campo['required'])?'data-msg-required="Digite os dados em '.$campo['name'].'"':'';?> class="form-control" name="custom_field_endereco[<?php echo $campo['custom_field_id'];?>]" id="custom-endereco-<?php echo $campo['custom_field_id'];?>"<?php echo ($campo['required'])?' required':'';?>><?php echo $campo['value'];?></textarea>
</div>
</div>
<?php } ?>

<?php } ?>
<?php } ?>
<?php } ?>

<!-- T14g - Custom hidden inputs -->
<input type="hidden" name="magica" value="" id="magica13">

<input type="hidden" name="magica2" value="" id="magica14">

</div>

<div class="col-xs-12 col-md-12 xl-100 xs-100 md-100">
<div class="form-group">
<div style="text-align:right;" class="col-sm-12">
<button type="submit" id="botao-cadastro-cliente" class="btn btn-success btn-lg button"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> Concluir cadastro</button>
</div>
</div>
</div>

</form>
</div>
</div>

<script>
$(document).ready(function() {

//T14g - Padrão ao iniciar
var conheceu = $("#custom-endereco-9").find("option:first-child").html();
$("#magica13").val(conheceu);

var outros = $("#custom-endereco-11").find("option:first-child").html();
$("#magica14").val(outros);

// var teste = $("#magica").val();
// console.log(teste);

// T14g - Ao mudar input
$("#custom-endereco-9").change(function(){
  let valor = $(this).find("option:selected").html();  
  $("#magica13").val(valor);
});

$("#custom-endereco-11").change(function(){
  let valor = $(this).find("option:selected").html();  
  $("#magica14").val(valor);
});

// $("#custom-endereco-9").live("click", function () {
//     //Get text or inner html of the selected option
//     var selectedText = $("#mySelect option:selected").html();
//     alert(selectedText);
// });
    
//formato datas 
$('.date').datetimepicker({
    format: 'yyyy-mm-dd',
    language: 'pt-BR',
    autoclose: true
});
$('.datetime').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    language: 'pt-BR',
    autoclose: true
});
$('.time').datetimepicker({
    format: 'hh:ii',
    language: 'pt-BR',
    autoclose: true
});

/*
//converte para maiusculo
$('.para_upper').keyup( function() {
	this.value = this.value.toUpperCase();
	console.log( this.value );
});


$('#nome').focusout(function(){
    $('#nome').val(function(){
        return this.value.toUpperCase();
    });
});
*/

$('#nome').focusout(function(){
    $(this).val($(this).val().toUpperCase());
});

$('.somente_numeros').on( "keyup keydown", function() {
var string = $(this).val();
$(this).val(string.replace(/[^0-9]/g,'').toUpperCase());
});

$(".form_dados_cliente_up").validate({
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
    onclick: false,
    errorElement: "span",
    errorClass: "campo_invalido",
    rules: {
    email: {
        required: true,
    },
    nome: {
        required: true,
        nomeValidator: true,
        minlength: 8
    },
    fiscal: {
        required: true,
        fiscalValidator:true
    },
    telefone: {
          required: true,
          minlength: 12
    },
    senha: {
          required: true,
          minlength: 6
    },
    senha2: {
          equalTo: "#senha"
    },
    cep: {
          required: true,
          minlength: 8
    },
    complemento: {
          required: true,
          minlength: 1
    },
    logradouro: {
          required: true,
          minlength: 3
    },
    numero: {
          required: true,
          minlength: 1
    },
    bairro: {
          required: true,
          minlength: 3
    },
    cidade: {
          required: true,
          minlength: 3
    },
    uf: {
          required: true,
          minlength: 1
    },
    },
    messages: {
    email: {
        required: 'Informe seu e-mail para cadastro!',
        email: 'Informe um e-mail valido!'
    },
    nome: {
        required: 'Qual <?php echo $nome_empresa;?>?',
        nomeValidator: 'Infome <?php echo $nome_empresa;?> valido!',
        minlength: 'Informe <?php echo $nome_empresa;?> no minimo 8 digitos!'
    },
    fiscal: {
        required: '<?php echo $frase_validar;?>',
        fiscalValidator:'<?php echo $frase_validar_ok;?>'
    },
    telefone: 'Qual seu telefone?',
    senha: {
          required: 'Informe sua senha!',
          minlength: 'Senha minima de 6 digitos!'
    },
    senha2: 'Confirme a senha',
    cep: 'Qual CEP?',
    logradouro: 'Logradouro?',
    complemento: 'Informe o complemento do endereço (ex: Casa)',
    numero: 'Qual Numero?',
    bairro: 'Qual Bairro?',
    cidade: 'Qual Cidade?',
    uf: 'Qual estado?',
    },
    submitHandler: function(form) {
        	var dados_form_cliente = $(form).serialize();
            var valido = true;
            if(valido){
            bloqueartela();
            $('#botao-cadastro-cliente').button('loading');
            $.ajax({
                type: "POST",
                url: urlSSL+"index.php?route=account/registroexpress/novocliente",
                data: dados_form_cliente,
                dataType: 'json',
            }).done(function( dados ) {
                console.log(dados);
                if(dados.erro==false){
                location.href = dados.href;
                }else{
                $.unblockUI();
                $('#botao-cadastro-cliente').button('reset');
                bootbox.alert({
                    message: dados.msg,
                    title: "Ops"
                });
                return false;
                }
            });
            }
        return false;
    }
});

});

//auto tipo
tipo_cliente_cliente('<?php echo ($tipo==0 || $tipo==1)?'pf':'pj';?>');

$("input[name='nome']").focusin(
    function (){
        $(this).after("<div style='margin-top:5px' class='responsabiliza alert alert-info information'>Atenção! Preencha o nome que irá no certificado. O Aluno(a) se responsabiliza exclusivamente pelos dados informados durante o cadastro realizado, estando ciente e concorda que a solicitação de correção do nome, posterior à impressão do Certificado, implicará na cobrança de taxa no valor R$ 100,00.</div>");              
        });
$("input[name='nome']").focusout(function (){
        $('.responsabiliza').remove();          
});
$("input[name='cep']").hover(function (){
        $(this).after("<div style='margin-top:5px' class='aviso alert alert-info information'>Preencha o CEP corretamente, para que o endereço seja preenchido automaticamente.</div>");
        }, function(){
            $(".aviso").remove();           
        });
$("#registro-nome").replaceWith('<label for="nome" id="registro-nome" class="control-label">Nome do Aluno</label>');
</script>

</div>
<?php echo $footer; ?>