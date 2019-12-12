<div class="alert alert-info" role="alert">Adicione as faixas de cep de acordo deseja usar em sua loja, fique atento para n&atilde;o adicionar faixas duplicadas, lembrando que sempre que atualizar qualquer faixa de cep dever&aacute; atualizar as tabelas de frete.</div>

<form method="post" action="<?php echo $nova;?>" class="form-horizontal">

<div class="form-group">
<label for="inputEmail3" class="col-sm-2 control-label">Estado (UF)</label>
<div class="col-sm-10">
<input style="width:200px" type="text" maxlength="2" class="form-control" id="uf" name="uf" placeholder="Ex: SP">
<span id="helpBlock" class="help-block">UF do estado correspondente a faixa de CEP.</span>
</div>
</div>

<div class="form-group">
<label for="inputEmail3" class="col-sm-2 control-label">Detalhes</label>
<div class="col-sm-10">
<input type="text" class="form-control" id="detalhes" name="detalhes" placeholder="Ex: Capital do estado X">
<span id="helpBlock" class="help-block">Detalhes da faixa.</span>
</div>
</div>

<div class="form-group">
<label for="inputEmail3" class="col-sm-2 control-label">CEP Inicial</label>
<div class="col-sm-10">

<div style="width:200px" class="input-group">
<input type="text" value="" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="5" class="form-control" id="cep_inicio" name="cep_inicio" placeholder="Somente numeros!">
<span class="input-group-addon" id="basic-addon2">000</span>
</div>
<span id="helpBlock" class="help-block">Os 5 digitos iniciais do CEP.</span>

</div>
</div>
<div class="form-group">
<label for="inputEmail3" class="col-sm-2 control-label">CEP Final</label>
<div class="col-sm-10">

<div style="width:200px" class="input-group">
<input type="text" value="" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="5" class="form-control" id="cep_final" name="cep_final" placeholder="Somente numeros!">
<span class="input-group-addon" id="basic-addon2">999</span>
</div>
<span id="helpBlock" class="help-block">Os 5 digitos iniciais do CEP.</span>

</div>
</div>
<div class="form-group">
<label for="inputEmail3" class="col-sm-2 control-label">CEP Base</label>
<div class="col-sm-10">
<input style="width:200px" type="text" value="" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="8" class="form-control" id="cep_base" name="cep_base" placeholder="Somente numeros!">
<span id="helpBlock" class="help-block">Este CEP ser&aacute; o base usado para calculo do frete junto aos correios para toda faixa.</span>
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<button type="submit" class="btn btn-info">Salvar</button>
</div>
</div>
</form>
