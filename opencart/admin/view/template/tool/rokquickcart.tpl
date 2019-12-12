<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="javascript::void(0);" onClick="importProducts();" class="btn btn-default">Import Products</a>
        <a href="javascript::void(0);" onClick="copyImages();" class="btn btn-default">Copy Images</a>
      </div>
      <h1>RokQuickCart Migration Tool</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (isset($error_warning)) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($success)) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" form="form-backup" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange"></i>RokQuickCart Migration Tool</h3>
      </div>
      <div class="panel-body">
        <div id="importProducts"></div>
        <div id="copyImages"></div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
<!--
function importProducts() {
    document.getElementById('importProducts').innerHTML = '<span style="color:green;">Loading...</span>';
    jQuery('#importProducts').load('index.php?option=com_mijoshop&route=tool/rokquickcart/importProducts');
}

function copyImages() {
    document.getElementById('copyImages').innerHTML = '<span style="color:green;">Loading...</span>';
    jQuery('#copyImages').load('index.php?option=com_mijoshop&route=tool/rokquickcart/copyImages');
}
-->
</script>