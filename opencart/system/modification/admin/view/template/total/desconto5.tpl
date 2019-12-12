<?php echo $header; ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<?php echo $column_left; ?>
<div id="content">
<div class="page-header">
<div class="container-fluid">
<div class="pull-right">

            <button type="submit" onclick="save('save')" form="form-desconto5" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Save"><i class="fa fa-save"></i></button>
            <button type="submit" form="form-desconto5" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Save & Close"><i class="fa fa-sign-out"></i></button>
            
<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
<h1><?php echo $heading_title; ?></h1>
<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
<?php } ?>
</ul>
</div>
</div>
<div class="container-fluid">
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
<button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
</div>
<div class="panel-body">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-desconto5" class="form-horizontal">

<div class="form-group">
<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
<div class="col-sm-10">
<select name="desconto5_status" id="input-status" class="form-control">
<?php if ($desconto5_status) { ?>
<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
<option value="0"><?php echo $text_disabled; ?></option>
<?php } else { ?>
<option value="1"><?php echo $text_enabled; ?></option>
<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
<?php } ?>
</select>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
<div class="col-sm-10">
<input type="text" name="desconto5_sort_order" value="<?php echo $desconto5_sort_order; ?>" placeholder="Ordem" id="input-sort-order" class="form-control" />
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label" for="input-sort-order"></label>
<div class="col-sm-10">
<table class="table table-bordered">
<thead>
<tr>
<th>#</th>
<th>Forma de Pagamento</th>
<th>Desconto/Taxa</th>
<th>%</th>
</tr>
</thead>
	  
<?php 
foreach($meios AS $k=>$v){
?>
<tr>
<th scope="row"><?php echo $k;?></th>
<td><?php echo str_replace('_',' ',strtoupper($v));?></td>
<td>
<select name="desconto5_descontos[<?php echo $v;?>][tipo]" id="input-status" class="form-control">
<?php if (@$desconto5_descontos[$v]['tipo']) { ?>
<option value="1" selected="selected">Taxa</option>
<option value="0">Desconto</option>
<?php } else { ?>
<option value="1">Taxa</option>
<option value="0" selected="selected">Desconto</option>
<?php } ?>
</select>
</td>
<td><input type="text" name="desconto5_descontos[<?php echo $v;?>][taxa]" value="<?php echo @$desconto5_descontos[$v]['taxa']; ?>" placeholder="0.00" class="dinheiro form-control" /></td>
</tr>
<?php } ?>

</table>
</div>
</div>

</form>
</div>
</div>
</div>
</div>

<script>
  $(function() {
    $('.dinheiro').maskMoney();
  })
</script>

        <script type="text/javascript"><!--
        function save(type){
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'button';
            input.value = type;
            form = $("form[id^='form-']").append(input);
            form.submit();
        }
        //--></script>
            
<?php echo $footer; ?>