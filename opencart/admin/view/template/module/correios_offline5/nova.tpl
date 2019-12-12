<?php 
echo $header; 
?>


<?php echo $column_left; ?>

<div id="content">
<div class="page-header">
<div class="container-fluid">

<div class="pull-right">
<a href="<?php echo $cancel; ?>" class="btn btn-info">Voltar <i class="fa fa-reply"></i></a></div>

<h1>Nova Tabela</h1>

<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
</ul>
</div>
</div>
<div class="container-fluid">

<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-list"></i> Cadastrar Nova Tabela</h3>
</div>
<div class="panel-body">

<div class="alert alert-info" role="alert">Para as modalidades de entrega PAC e Sedex ser&atilde;o calculados fretes de 1 a 30 kgs para todos os CEPs Bases disponiveis no cadastro, e para modalidade de e-Sedex o limite ser&atilde; de 1 a 15 kgs.</div>

<form method="post" action="<?php echo $nova;?>" class="form-horizontal">
<div class="form-group">
<label for="inputEmail3" class="col-sm-2 control-label">Servi&ccedil;o Correios</label>
<div class="col-sm-10">
<select name="servico" class="form-control">
<?php foreach($servicos->rows as $servico){?>
<option value="<?php echo $servico['cod'];?>"><?php echo $servico['cod'];?> - <?php echo $servico['nome'];?></option>
<?php } ?>
</select>
</div>
</div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-info">Criar Tabela</button>
    </div>
  </div>
</form>

</div>
</div>

</div>
</div>

<script>
$(document).ready(function(){
    $('#pedidos_estoque').DataTable({
		"order": [],
		language: {
			url: 'https://cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json'
		}
	});
});
</script>

<?php echo $footer; ?>