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
//fim mascaras pura
</script>

<?php //print_r($custom);?>
<div class="j-container" id="container">
<div class="row form_modal">
<div class="col-md-12 xl-100">

<div id="msg-ok" class="alert alert-success success" style="display:none;" role="alert">Dados atualizado com sucesso!</div>

<form method="post" onsubmit="return false;" action="#" class="form-horizontal" id="dados_cliente" autocomplete="off">

<?php 
if($tipo==0){
$nome_empresa = 'seu nome/empresa';
$frase_validar = 'Qual seu CPF/CNPJ?';
$frase_validar_ok = 'Informe um CPF/CNPJ valido!';
$mascara = "mascara(this,'cpfcnpj')";
$max = 17;
?>
<div class="form-group">
<label for="inputEmail3" class="col-sm-2 control-label">Tipo</label>
<div class="col-sm-10">
<label style="width: 100%;">
<input type="radio" onclick="tipo_cliente_cliente('pf')" name="tipo_pessoa" value="pf">
Pessoa F&iacute;sica &nbsp; 
<input type="radio" onclick="tipo_cliente_cliente('pj')" name="tipo_pessoa" value="pj">
Pessoa Juridica
</label>
</div>
</div>
<?php 
} elseif($tipo==1){
$nome_empresa = 'nome do aluno';
$frase_validar = 'Qual seu CPF?';
$frase_validar_ok = 'Informe um CPF valido!';
$mascara = "mascara(this,'cpf')";
$max = 14;
?>
<input type="hidden" name="tipo_pessoa" value="pf">
<?php 
} elseif($tipo==2){
$nome_empresa = 'sua empresa';
$frase_validar = 'Qual seu CNPJ?';
$frase_validar_ok = 'Informe um CPF/CNPJ valido!';
$mascara = "mascara(this,'cnpj')";
$max = 17;
?>
<input type="hidden" name="tipo_pessoa" value="pj">
<?php } ?>

<div class="form-group">
<label for="inputPassword3" id="registro-nome" class="col-sm-2 control-label">Nome do aluno</label>
<div class="col-sm-10 class_nome">
<input type="text" value="<?php echo $dados_cliente['firstname'];?> <?php echo $dados_cliente['lastname'];?>" class="para_upper form-control dados_cliente_up" name="nome" id="nome" placeholder="Nome completo/Empresa" required>
</div>
</div>

<div class="form-group">
<label for="inputPassword3" id="registro-fiscal" class="col-sm-2 control-label">CPF/CNPJ</label>
<div class="col-sm-10">
<input maxlength="<?php echo $max;?>" type="text" onkeyup="<?php echo $mascara;?>" onkeydown="<?php echo $mascara;?>" value="<?php echo (isset($custom[$fiscal]))?($custom[$fiscal]):'';?>" class="form-control mask_fiscals" name="fiscal" id="fiscal" placeholder="<?php echo $frase_validar;?>" required>
</div>
</div>

<div class="form-group">
<label for="inputPassword3" class="col-sm-2 control-label">Telefone</label>
<div class="col-sm-10">
<input type="text" maxlength="13" onkeyup="mascara(this,'telefone')" onkeydown="mascara(this,'telefone')" value="<?php echo $dados_cliente['telephone'];?>" class="form-control mask_telefones" name="telefone" id="telefone" placeholder="(00)000000000" required>
</div>
</div>

<?php 
$negar = array($fiscal,$fiscalpj);
?>
<?php if($custom_fields){?>
<?php foreach($custom_fields as $campo){ ?>
<?php if($campo['location']=='account' && !in_array($campo['custom_field_id'],$negar)){ ?>

<?php if($campo['type']=='select'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="col-sm-2 control-label"><?php echo $campo['name'];?></label>
<div class="col-sm-10">
<select <?php echo ($campo['required'])?'data-msg-required="Selecione uma op&ccedil;&atilde;o em '.$campo['name'].'"':'';?> class="form-control" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>"<?php echo ($campo['required'])?' required':'';?>>
<?php foreach($campo['custom_field_value'] as $valor){ ?>
<option value="<?php echo $valor['custom_field_value_id'];?>"<?php echo(isset($custom[$campo['custom_field_id']]) && $custom[$campo['custom_field_id']]==$valor['custom_field_value_id'])?' selected':'';?>><?php echo $valor['name'];?></option>
<?php } ?>
</select>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='checkbox'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="col-sm-2 control-label"><?php echo $campo['name'];?></label>
<div class="col-sm-10">
<div class="checkbox">
<?php foreach($campo['custom_field_value'] as $valor){ ?>
<label><input <?php echo ($campo['required'])?'data-msg-required="Selecione as op&ccedil;&tilde;es':'';?> name="custom_field[<?php echo $campo['custom_field_id'];?>][]" id="custom-<?php echo $campo['custom_field_id'];?>" type="checkbox" value="<?php echo $valor['custom_field_value_id'];?>"<?php echo ($campo['required'])?' required':'';?><?php echo(isset($custom[$campo['custom_field_id']]) && in_array($valor['custom_field_value_id'],$custom[$campo['custom_field_id']]))?' checked':'';?>> <?php echo $valor['name'];?></label>
<?php } ?>
</div>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='radio'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="col-sm-2 control-label"><?php echo $campo['name'];?></label>
<div class="col-sm-10">
<div class="radio">
<?php foreach($campo['custom_field_value'] as $valor){ ?>
<label><input <?php echo ($campo['required'])?'data-msg-required="Selecione uma op&ccedil;&atilde;o!"':'';?> name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" type="radio" value="<?php echo $valor['custom_field_value_id'];?>"<?php echo ($campo['required'])?' required':'';?><?php echo(isset($custom[$campo['custom_field_id']]) && $custom[$campo['custom_field_id']]==$valor['custom_field_value_id'])?' checked':'';?>> <?php echo $valor['name'];?></label>
<?php } ?>
</div>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='text'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="col-sm-2 control-label"><?php echo $campo['name'];?></label>
<div class="col-sm-10">
<input <?php echo ($campo['required'])?'data-msg-required="Digite os dados em '.$campo['name'].'"':'';?> type="text" class="form-control" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" value="<?php echo(isset($custom[$campo['custom_field_id']]))?$custom[$campo['custom_field_id']]:'';?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='date'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="col-sm-2 control-label"><?php echo $campo['name'];?></label>
<div  class="col-sm-10">
<input <?php echo ($campo['required'])?'data-msg-required="Informar uma data em '.$campo['name'].'"':'';?> type="text" class="form-control date" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" value="<?php echo(isset($custom[$campo['custom_field_id']]))?$custom[$campo['custom_field_id']]:'';?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='time'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="col-sm-2 control-label"><?php echo $campo['name'];?></label>
<div class="col-sm-10">
<input <?php echo ($campo['required'])?'data-msg-required="Informar uma hora em '.$campo['name'].'"':'';?> type="text" class="form-control time" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" value="<?php echo(isset($custom[$campo['custom_field_id']]))?$custom[$campo['custom_field_id']]:'';?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='datetime'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="col-sm-2 control-label"><?php echo $campo['name'];?></label>
<div class="col-sm-10">
<input type="text" <?php echo ($campo['required'])?'data-msg-required="Informar uma data e hora em '.$campo['name'].'"':'';?> class="form-control datetime" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>" value="<?php echo(isset($custom[$campo['custom_field_id']]))?$custom[$campo['custom_field_id']]:'';?>"<?php echo ($campo['required'])?' required':'';?>>
</div>
</div>
<?php } ?>

<?php if($campo['type']=='textarea'){ ?>
<div class="form-group">
<label for="custom-<?php echo $campo['custom_field_id'];?>" class="col-sm-2 control-label"><?php echo $campo['name'];?></label>
<div class="col-sm-10">
<textarea <?php echo ($campo['required'])?'data-msg-required="Digite os dados em '.$campo['name'].'"':'';?> style="height:60px;" class="form-control" name="custom_field[<?php echo $campo['custom_field_id'];?>]" id="custom-<?php echo $campo['custom_field_id'];?>"<?php echo ($campo['required'])?' required':'';?>><?php echo(isset($custom[$campo['custom_field_id']]))?$custom[$campo['custom_field_id']]:'';?></textarea>
</div>
</div>
<?php } ?>

<?php } ?>
<?php } ?>
<?php } ?>


<div class="form-group">
<div class="col-sm-12 text-center" style="text-align:center">
<input type="submit" class="btn btn-success button" value="Atualizar">
</div>
</div>

</form>

</div>
</div>
</div>

<script>
$(document).ready(function() {

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

//somente numeros
$('.somente_numeros').on( "keyup keydown", function() {
var string = $(this).val();
$(this).val(string.replace(/[^0-9]/g,'').toUpperCase());
});

//converte para maiusculo
$('.para_upper').on('change keyup', function() {
	this.value = this.value.toUpperCase();
	console.log( this.value );
});

$("#dados_cliente").validate({
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
            bloqueartela();
            var dados = $(form).serialize();
            console.log(dados);
            $.ajax({
            type: "POST",
            url: urlSSL+"index.php?route=checkout/compraexpress/atualizarcliente",
            dataType: 'html',
            data: dados
            }).done(function( valor ) {
                $.pgwModal('close');
                estalogadoounao();
            });	
            return false;
    },
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
    }
});

tipo_cliente_cliente('<?php echo (isset($custom[$fiscal]) && strlen(preg_replace('/\D/', '', $custom[$fiscal]))==14)?'pj':'pf';?>');

});

jQuery(function($){
   //$(".mask_data").mask("99/99/9999");
   //$(".mask_telefone").mask("(99)999999999");
   //$(".mask_cep").mask("99999-999");
   //$(".mask_fiscal").mask("99999999999999");
});
</script>