<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-stripe" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-stripe" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-stripe" class="form-horizontal">
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-test-api-key"><?php echo $entry_test_api_key; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="stripe_test_secret_key" value="<?php echo $stripe_test_secret_key; ?>" placeholder="<?php echo $entry_test_api_key; ?>" id="input-test-api-key" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-test-publishable-key"><?php echo $entry_test_publishable_key; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="stripe_test_publishable_key" value="<?php echo $stripe_test_publishable_key; ?>" placeholder="<?php echo $entry_test_publishable_key; ?>" id="input-test-publishable-key" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-live-api-key"><?php echo $entry_live_api_key; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="stripe_live_secret_key" value="<?php echo $stripe_live_secret_key; ?>" placeholder="<?php echo $entry_live_api_key; ?>" id="input-live-api-key" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-live-publishable-key"><?php echo $entry_live_publishable_key; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="stripe_live_publishable_key" value="<?php echo $stripe_live_publishable_key; ?>" placeholder="<?php echo $entry_live_publishable_key; ?>" id="input-live-publishable-key" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-environment"><span data-toggle="tooltip" data-original-title="<?php echo $help_test; ?>"><?php echo $entry_environment; ?></span></label>
                    <div class="col-sm-10">
                      <select name="stripe_environment" id="input-environment" class="form-control">
                        <?php if ($stripe_environment == 'live') { ?>
                        <option value="live" selected="selected"><?php echo $text_live; ?></option>
                        <option value="test"><?php echo $text_test; ?></option>
                        <?php } else { ?>
                        <option value="test" selected="selected"><?php echo $text_test; ?></option>
                        <option value="live"><?php echo $text_live; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                    <div class="col-sm-10">
                      <select name="stripe_order_status_id" id="input-order-status" class="form-control">
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if ($order_status['order_status_id'] == $stripe_order_status_id) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-currency"><span data-toggle="tooltip" data-original-title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></label>
                    <div class="col-sm-10">
                      <select name="stripe_currency" id="input-currency" class="form-control">
                        <?php if ($stripe_currency == 'usd') { ?>
                        <option value="usd" selected="selected"><?php echo $text_usd; ?></option>
                        <option value="eur" selected="selected"><?php echo $text_euro; ?></option>
                        <?php } else { ?>
                        <option value="eur"><?php echo $text_euro; ?></option>
                        <option value="usd"><?php echo $text_usd; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-card"><?php echo $entry_card; ?></label>
                    <div class="col-sm-10">
                      <select name="stripe_store_cards" id="input-card" class="form-control">
                        <?php if ($stripe_store_cards) { ?>
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
                      <select name="stripe_status" id="input-status" class="form-control">
                        <?php if ($stripe_status) { ?>
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
                      <input type="text" name="stripe_sort_order" value="<?php echo $stripe_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                    </div>
                  </div>
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
