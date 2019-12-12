<div class="j-container" id="container">
<div class="row form_modal">
<div class="col-md-12 xl-100">

<div id="div-rec-msg"></div>

<form method="post" onsubmit="return recupersenha(this);" action="#" class="validar_form form-horizontal" id="form_dados_rec" novalidate="novalidate">

<div class="form-group">
<label for="inputEmail3" class="col-sm-3 control-label">E-mail</label>
<div class="col-sm-9">
<input type="email" value="<?php echo $email;?>" class="form-control" name="email" id="email" placeholder="E-mail" required>
</div>
</div>

<div class="form-group">
<div class="col-sm-12" style="text-align:center">
<button class="btn btn-success button"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Recuperar Senha</button>
</div>
</div>
</form>

</div>
</div>
</div>