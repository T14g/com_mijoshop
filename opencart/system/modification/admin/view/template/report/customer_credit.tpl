<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button id="button-output" data-toggle="tooltip" title="<?php echo $button_output; ?>" class="btn btn-default"><i class="fa fa-print"></i></button>
        <button id="button-export" data-toggle="tooltip" title="<?php echo $button_export; ?>" class="btn btn-default"><i class="fa fa-file-excel-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <?php echo $graph; ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-left"><?php echo $column_customer; ?></td>
                <td class="text-left"><?php echo $column_email; ?></td>
                <td class="text-left"><?php echo $column_customer_group; ?></td>
                <td class="text-left"><?php echo $column_status; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>
                <td class="text-right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($customers) { ?>
              <?php foreach ($customers as $customer) { ?>
              <tr>
                <td class="text-left"><?php echo $customer['customer']; ?></td>
                <td class="text-left"><?php echo $customer['email']; ?></td>
                <td class="text-left"><?php echo $customer['customer_group']; ?></td>
                <td class="text-left"><?php echo $customer['status']; ?></td>
                <td class="text-right"><?php echo $customer['total']; ?></td>
                <td class="text-right"><a href="<?php echo $customer['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>