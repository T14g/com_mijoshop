<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="javascript::void(0);" onClick="migrateDatabase();" class="btn btn-default">Migrate Database</a>
        <a href="javascript::void(0);" onClick="migrateFiles();" class="btn btn-default">Migrate Files</a>
        <a href="javascript::void(0);" onClick="fixMenus();" class="btn btn-default">Fix Menus</a>
        <a href="javascript::void(0);" onClick="fixModules();" class="btn btn-default">Fix Modules</a>
      </div>
      <h1>JooCart Migration Tool</h1>
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
        <h3 class="panel-title"><i class="fa fa-exchange"></i>JooCart Migration Tool</h3>
      </div>
      <div class="panel-body">
        <div id="migrateDatabase"></div>
        <div id="migrateFiles"></div>
        <div id="fixMenus"></div>
        <div id="fixModules"></div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">

function migrateDatabase() {
    document.getElementById('migrateDatabase').innerHTML = '<span style="color:green;">Loading...</span>';
    jQuery('#migrateDatabase').load('index.php?option=com_mijoshop&route=tool/joocart/migrateDatabase');
}

function migrateFiles() {
    document.getElementById('migrateFiles').innerHTML = '<span style="color:green;">Loading...</span>';
    jQuery('#migrateFiles').load('index.php?option=com_mijoshop&route=tool/joocart/migrateFiles');
}

function fixMenus() {
    document.getElementById('fixMenus').innerHTML = '<span style="color:green;">Loading...</span>';
    jQuery('#fixMenus').load('index.php?option=com_mijoshop&route=tool/joocart/fixMenus');
}

function fixModules() {
    document.getElementById('fixModules').innerHTML = '<span style="color:green;">Loading...</span>';
    jQuery('#fixModules').load('index.php?option=com_mijoshop&route=tool/joocart/fixModules');
}

</script>