<script>
//mascaras javascript pura
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
	if(v_fun=='cep')
   	 v_obj.value=cep(v_obj.value);
}

function cep(v){
	v=v.replace(/[^1234567890-]/g,"");
    v=v.replace(/^(\d{5})(\d)/,"$1-$2")
    return v
}
//fim mascaras pura
</script>

<?php //print_r($custom);?>
<script>
//define a url ssl segura para javascript
var urlSSL = '<?php echo HTTPS_SERVER; ?>';
//carrega o endereco de acordo cep informado pelo cliente
function getEnderecoUp() {
var EstadosArray = [];
<?php foreach($lista_estados AS $k=>$v){?>
EstadosArray["<?php echo $v['code'];?>"] = <?php echo $v['zone_id'];?>;
<?php } ?>
var cep = $("#cep_e").val();
var s = (cep).replace(/\D/g,'');
var tam=(s).length;
if(tam==8){
$.getScript("<?php echo HTTPS_SERVER;?>api_cep.php?formato=javascript&cep="+cep, function(){
if(resultadoCEP["resultado"] != 0){
    console.log(resultadoCEP);
    $("#logradouro_e").val(unescape(resultadoCEP["tipo_logradouro"]).toUpperCase()+""+unescape(resultadoCEP["logradouro"]).toUpperCase());
    $("#bairro_e").val(unescape(resultadoCEP["bairro"]).toUpperCase());
    $("#cidade_e").val(unescape(resultadoCEP["cidade"]).toUpperCase());
    $("#uf_e").val(EstadosArray[resultadoCEP["uf"]]);
    //$("#numero_e").focus();
    //$("#dados_endereco_atualizar").valid();
    }
});				
}			
}
</script>

<div class="j-container" id="container">
<div class="row form_modal">
<div class="col-md-12 xl-100">

<div id="msg-ok" class="alert alert-success" style="display:none;" role="alert">Dados atualizado com sucesso!</div>

<form method="post" onsubmit="return false;" action="#" class="form-horizontal" id="dados_endereco_atualizar" autocomplete="off">

<input type="hidden" name="acao" id="acao_endereco" class="ignore" value="<?php echo $acao;?>">
<input type="hidden" name="tipo" id="tipo_endereco" class="ignore" value="<?php echo $tipocad;?>">

<div id="div-lista-enderecos" class="form-group">
<label for="idendereco" class="col-sm-2 control-label">Seus Endere&ccedil;os</label>
<div class="col-sm-10">
<select style="text-transform: uppercase;" onchange="dadosentregalogadojson(this.value)" id="idendereco_e" name="idendereco" class="form-control">
<?php foreach ($enderecos as $v) { ?>
<option value="<?php echo $v['address_id'];?>"><?php echo $v['firstname'];?> - <?php echo $v['address_1'];?></option>
<?php } ?>
</select>
<span id="helpBlock" class="help-block">Selecione um novo endere&ccedil;o ou atualize um j&aacute; existente.</span>
</div>
</div>

<div class="form-group">
<label for="nome" class="col-sm-2 control-label">Nome do aluno</label>
<div class="col-sm-10">
<input type="text" class="para_upper form-control dados_cliente_up" name="nome_completo" id="nome_completo_e" placeholder="Nome completo" required>
</div>
</div>

<div class="form-group">
<label for="cep" class="col-sm-2 control-label">CEP</label>
<div class="col-sm-10">
<input type="text" maxlength="9" onkeyup="getEnderecoUp();mascara(this,'cep');" onkeydown="getEnderecoUp();mascara(this,'cep');" value="" class="form-control mask_ceps" name="cep" id="cep_e" placeholder="00000-000" required>
</div>
</div>

<div class="form-group">
<label for="logradouro" class="col-sm-2 control-label">Logradouro</label>
<div class="col-sm-10">
<input type="text" class="para_upper form-control dados_cliente_up" name="logradouro" id="logradouro_e" placeholder="Logradouro" required>
</div>
</div>

<div class="form-group">
<label for="numero" class="col-sm-2 control-label">Número</label>
<div class="col-sm-10">
<input type="text" class="para_upper form-control dados_cliente_up" name="numero" id="numero_e" placeholder="Numero" required>
</div>
</div>

<div class="form-group">
<label for="complemento" class="col-sm-2 control-label">Complemento</label>
<div class="col-sm-10">
<input type="text" class="para_upper form-control dados_cliente_up" name="complemento" id="complemento_e" placeholder="Ex: Casa, Apto">
</div>
</div>

<div class="form-group">
<label for="bairro" class="col-sm-2 control-label">Bairro</label>
<div class="col-sm-10">
<input type="text" class="para_upper form-control dados_cliente_up" name="bairro" id="bairro_e" placeholder="Bairro" required>
</div>
</div>

<div class="form-group">
<label for="cidade" class="col-sm-2 control-label">Cidade</label>
<div class="col-sm-10">
<input type="text" class="para_upper form-control dados_cliente_up" name="cidade" id="cidade_e" placeholder="Cidade" required>
</div>
</div>

<div class="form-group">
<label for="uf" class="col-sm-2 control-label">Estado</label>
<div class="col-sm-10">
<select class="para_upper form-control dados_cliente_up" id="uf_e" name="uf" required>
<?php foreach($lista_estados AS $k=>$v){?>
<option value="<?php echo $v['zone_id'];?>"><?php echo $v['name'];?></option>
<?php } ?>
</select>
</div>
</div>

<div class="form-group">
<label for="pais" class="col-sm-2 control-label">Pa&iacute;s</label>
<div class="col-sm-10">
<select class="para_upper form-control dados_cliente_up" onchange="carregarEstados(this.value,'uf_e')" id="pais_e" name="pais" required>
<?php 
foreach($countries AS $k=>$v){
?>
<option value="<?php echo $v['country_id'];?>"<?php echo ($pais_padrao==$v['country_id'])?' selected':'';?>><?php echo $v['name'];?></option>
<?php } ?>
</select>
</div>
</div>

<?php 
$negar = array($numero,$complemento);
?>
<?php if($custom_fields){?>
<?php foreach($custom_fields as $campo){ ?>
<?php if($campo['location']=='address' && !in_array($campo['custom_field_id'],$negar)){ ?>

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
<input type="submit" id="botao-adicionar-atualizar" class="btn btn-success button" value="Salvar"> ou <input type="button" id="botao-limpar" class="btn btn-info button" onclick="novo_endereco()" value="Novo">
</div>
</div>

</form>

</div>
</div>
</div>

<script>
function novo_endereco(){
    $('#acao_endereco').val('adicionar');	
    $('#div-lista-enderecos').hide();
    $('#nome_completo_e').val('');
    $('#cep_e').val('');
    $('#logradouro_e').val('');
    $('#numero_e').val('');
    $('#bairro_e').val('');
    $('#cidade_e').val('');
    $('#uf_e').val('');
    $('#complemento_e').val('');
    $('#botao-adicionar-atualizar').val('Salvar');
    $('#botao-limpar').val('Limpar');
}

$(document).ready(function() {
//carrega dados do endereco inicial
dadosentregalogadojson(<?php echo $id_inicial;?>);
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

jQuery.validator.addMethod("fiscalValidator", function(value, element) {
	return this.optional(element) || validarCpfCnpjCheckoutExpress(value);
}, "Informe seu CPF/CNPJ correto");

jQuery.validator.addMethod("nomeValidator", function(value, element) {
	var count = (value.match(/[a-zA-Z0-9] [a-zA-Z0-9]/g) || []).length;
	console.log(count);
	return this.optional(element) || count;
}, "Informe seu nome/empresa correto");

//validar form
$("#dados_endereco_atualizar").validate({
    ignore: ".ignore",
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    submitHandler: function(form) {
        var meio_entrega = meio_entrega_sel();
        var dados = $(form).serialize();
        console.log(dados);
        bloqueartela();
        $.pgwModal('close');
        $.ajax({
        type: "POST",
        url: urlSSL+"index.php?route=checkout/compraexpress/atualizarend",
        dataType: 'json',
        data: dados
        }).done(function( valor ) {
            estalogadoounao();
        });
        return false;
    },
    rules: {
        nome_completo: {
            required: true,
            nomeValidator: true,
            minlength: 8
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
              minlength: 4
        },
        numero: {
              required: true,
              minlength: 1
        },
        bairro: {
              required: true,
              minlength: 4
        },
        cidade: {
              required: true,
              minlength: 4
        },
        uf: {
              required: true,
              minlength: 1
        },
    },
    messages: {
        nome_completo: {
            required: 'Qual seu nome?',
            nomeValidator: 'Infome o seu nome valido!',
            minlength: 'Informe seu nome de no minimo 8 digitos!'
        },
        cep: 'Qual CEP?',
        logradouro: 'Logradouro?',
        complemento: 'Informe o complemento do endereço (ex: Casa)',
        numero: 'Qual Numero?',
        bairro: 'Qual Bairro?',
        cidade: 'Qual Cidade?',
        uf: 'Qual UF?',
    }
    });
});

jQuery(function($){
   //$(".mask_data").mask("99/99/9999");
   //$(".mask_telefone").mask("(99)999999999");
   //$(".mask_cep").mask("99999-999");
   //$(".mask_fiscal").mask("99999999999999");
});
</script>