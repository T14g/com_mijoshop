<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        
            <button type="submit" onclick="save('save')" form="form-sagepay-direct" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Save"><i class="fa fa-save"></i></button>
            <button type="submit" form="form-sagepay-direct" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Save & Close"><i class="fa fa-sign-out"></i></button>
            
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-sagepay-direct" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-vendor"><?php echo $entry_vendor; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sagepay_direct_vendor" value="<?php echo $sagepay_direct_vendor; ?>" placeholder="<?php echo $entry_vendor; ?>" id="input-vendor" class="form-control" />
              <?php if ($error_vendor) { ?>
              <div class="text-danger"><?php echo $error_vendor; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_test; ?></label>
            <div class="col-sm-10">
              <select name="sagepay_direct_test" id="input-test" class="form-control">
                <?php if ($sagepay_direct_test == 'sim') { ?>
                <option value="sim" selected="selected"><?php echo $text_sim; ?></option>
                <?php } else { ?>
                <option value="sim"><?php echo $text_sim; ?></option>
                <?php } ?>
                <?php if ($sagepay_direct_test == 'test') { ?>
                <option value="test" selected="selected"><?php echo $text_test; ?></option>
                <?php } else { ?>
                <option value="test"><?php echo $text_test; ?></option>
                <?php } ?>
                <?php if ($sagepay_direct_test == 'live') { ?>
                <option value="live" selected="selected"><?php echo $text_live; ?></option>
                <?php } else { ?>
                <option value="live"><?php echo $text_live; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-transaction"><span data-toggle="tooltip" title="<?php echo $help_transaction; ?>"><?php echo $entry_transaction; ?></span></label>
            <div class="col-sm-10">
              <select name="sagepay_direct_transaction" id="input-transaction" class="form-control">
                <?php if ($sagepay_direct_transaction == 'PAYMENT') { ?>
                <option value="PAYMENT" selected="selected"><?php echo $text_payment; ?></option>
                <?php } else { ?>
                <option value="PAYMENT"><?php echo $text_payment; ?></option>
                <?php } ?>
                <?php if ($sagepay_direct_transaction == 'DEFERRED') { ?>
                <option value="DEFERRED" selected="selected"><?php echo $text_defered; ?></option>
                <?php } else { ?>
                <option value="DEFERRED"><?php echo $text_defered; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="sagepay_direct_total" value="<?php echo $sagepay_direct_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-card"><?php echo $entry_card; ?></label>
            <div class="col-sm-10">
              <select name="sagepay_direct_card" id="input-card" class="form-control">
                <?php if ($sagepay_direct_card) { ?>
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
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="sagepay_direct_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $sagepay_direct_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="sagepay_direct_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $sagepay_direct_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-debug"><span data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
            <div class="col-sm-10">
              <select name="sagepay_direct_debug" id="input-debug" class="form-control">
                <?php if ($sagepay_direct_debug) { ?>
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
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="sagepay_direct_status" id="input-status" class="form-control">
                <?php if ($sagepay_direct_status) { ?>
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
              <input type="text" name="sagepay_direct_sort_order" value="<?php echo $sagepay_direct_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="sagepay-direct-cron-job-token"><span data-toggle="tooltip" title="<?php echo $help_cron_job_token; ?>"><?php echo $entry_cron_job_token; ?></span></label>
            <div class="col-sm-10">
              <div class="input-group">
                <input type="text" name="sagepay_direct_cron_job_token" value="<?php echo $sagepay_direct_cron_job_token; ?>" placeholder="<?php echo $entry_cron_job_token; ?>" id="sagepay-direct-cron-job-token" class="form-control" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="cron-job-url"><span data-toggle="tooltip" title="<?php echo $help_cron_job_url; ?>"><?php echo $entry_cron_job_url; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="sagepay_direct_cron_job_url" value="<?php echo $sagepay_direct_cron_job_url ?>" placeholder="<?php echo $entry_cron_job_url; ?>" id="input-cron-job-url" class="form-control" />
            </div>
          </div>
          <?php if ($sagepay_direct_last_cron_job_run) { ?>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_last_cron_job_run; ?></label>
            <div class="col-sm-10"><?php echo $sagepay_direct_last_cron_job_run; ?></div>
          </div>
          <?php } ?>
        </form>
      </div>
    </div>
  </div>
</div>

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
