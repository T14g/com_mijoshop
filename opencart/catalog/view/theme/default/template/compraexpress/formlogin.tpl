<div class="j-container" id="container">
<div class="row form_modal">
<div class="col-md-12 xl-100">

<div id="div-login-msg-aviso-modal"></div>

<form method="post" onsubmit="return logincompraexpressaviso(this);" action="#" class="validar_form form-horizontal" id="form_dados_login" novalidate="novalidate">
<div class="form-group">
<label for="inputEmail3" class="col-sm-3 control-label">E-mail</label>
<div class="col-sm-9">
<input type="email" data-msg-required="Informe sua e-mail de cadastro!" value="<?php echo $email;?>" class="form-control" name="email" id="email" placeholder="E-mail"<?php echo !empty($email)?'':'';?> required>
</div>
</div>
<div class="form-group">
<label for="inputPassword3" class="col-sm-3 control-label">Senha</label>
<div class="col-sm-9">
<input type="password" data-msg-required="Informe sua senha!" class="form-control" name="senha" id="senha" placeholder="Senha" required>
</div>
</div>
<div class="form-group">
<div class="col-sm-12" style="text-align:center">
<button class="btn btn-success button"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Entrar</button>
</div>
</div>
<div class="form-group">
<div class="col-sm-12" style="text-align:center">
<a style="cursor:pointer" onclick="abrir_url_modal('index.php?route=checkout/compraexpress/formsenha','Perdi minha senha');">Esqueceu sua senha?</a>
</div>
</div>
</form>

</div>
</div>
</div>