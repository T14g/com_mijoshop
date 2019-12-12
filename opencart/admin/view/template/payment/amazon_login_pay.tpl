<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
	  <div class="pull-right">
		<button type="submit" form="form-amazon-login-pay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
      <div class="panel-body">
		<div class="alert alert-info">
		  <div class="form-group required">
			<form method="POST" target="_blank" action="<?php echo $registration_url; ?>" class="form-horizontal" id="registration-form">
			  <input type="hidden" value="<?php echo $locale; ?>" name="locale">
			  <input type="hidden" value="<?php echo $sp_id; ?>" name="spId">
			  <input type="hidden" value="<?php echo $unique_id; ?>" name="uniqueId">
			  <input type="hidden" value="<?php echo $allowed_login_domain; ?>" name="allowedLoginDomains[]">
			  <?php foreach ($login_redirect_urls as $login_redirect_url) { ?>
				  <input type="hidden" value="<?php echo $login_redirect_url; ?>" name="loginRedirectURLs[]">
			  <?php } ?>
			  <input type="hidden" value="<?php echo $store_name; ?>" name="storeDescription">
			  <input type="hidden" value="<?php echo $amazon_login_pay_language; ?>" name="language">
			  <input type="hidden" value="<?php echo $ipn_url; ?>" name="sandboxMerchantIPNURL">
			  <input type="hidden" value="<?php echo $ipn_url; ?>" name="productionMerchantIPNURL">
			  <input type="hidden" value="POST" name="returnMethod">
			  <button type="button" class="btn btn-link" id="sign-up"><?php echo $text_amazon_signup; ?></button>
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			</form>
			<textarea class="form-control" id="input-credentials" placeholder="<?php echo $text_credentials; ?>" rows="7" name="credentials"></textarea>
			<br>
			<button id="button-credentials" class="btn btn-primary" type="button" ><?php echo $text_validate_credentials; ?></button>
		  </div>
		</div>
	  </div>
	</div>
	<div class="panel panel-default">
	  <div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
	  </div>
	  <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-amazon-login-pay" class="form-horizontal">
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="amazon-login-pay-merchant-id"><?php echo $entry_merchant_id; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="amazon_login_pay_merchant_id" value="<?php echo $amazon_login_pay_merchant_id; ?>" placeholder="<?php echo $entry_merchant_id; ?>" id="amazon-login-pay-merchant-id" class="form-control" />
			  <?php if ($error_merchant_id) { ?>
				  <div class="text-danger"><?php echo $error_merchant_id; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="amazon-login-pay-access-key"><?php echo $entry_access_key; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="amazon_login_pay_access_key" value="<?php echo $amazon_login_pay_access_key; ?>" placeholder="<?php echo $entry_access_key; ?>" id="amazon-login-pay-access-key" class="form-control" />
			  <?php if ($error_access_key) { ?>
				  <div class="text-danger"><?php echo $error_access_key; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="amazon-login-pay-access-secret"><?php echo $entry_access_secret; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="amazon_login_pay_access_secret" value="<?php echo $amazon_login_pay_access_secret; ?>" placeholder="<?php echo $entry_access_secret; ?>" id="amazon-login-pay-access-secret" class="form-control" />
			  <?php if ($error_access_secret) { ?>
				  <div class="text-danger"><?php echo $error_access_secret; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="amazon-login-pay-client-id"><?php echo $entry_client_id; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="amazon_login_pay_client_id" value="<?php echo $amazon_login_pay_client_id; ?>" placeholder="<?php echo $entry_client_id; ?>" id="amazon-login-pay-client-id" class="form-control" />
			  <?php if ($error_client_id) { ?>
				  <div class="text-danger"><?php echo $error_client_id; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="amazon-login-pay-client-secret"><?php echo $entry_client_secret; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="amazon_login_pay_client_secret" value="<?php echo $amazon_login_pay_client_secret; ?>" placeholder="<?php echo $entry_client_secret; ?>" id="amazon-login-pay-client-secret" class="form-control" />
			  <?php if ($error_client_secret) { ?>
				  <div class="text-danger"><?php echo $error_client_secret; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-test"><?php echo $entry_login_pay_test; ?></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_test" id="amazon-login-pay-test" class="form-control">
				<?php if ($amazon_login_pay_test == 'sandbox') { ?>
					<option value="sandbox" selected="selected"><?php echo $text_sandbox; ?></option>
				<?php } else { ?>
					<option value="sandbox"><?php echo $text_sandbox; ?></option>
				<?php } ?>
				<?php if ($amazon_login_pay_test == 'live') { ?>
					<option value="live" selected="selected"><?php echo $text_live; ?></option>
				<?php } else { ?>
					<option value="live"><?php echo $text_live; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-mode"><span data-toggle="tooltip" title="<?php echo $help_pay_mode; ?>"><?php echo $entry_login_pay_mode; ?></span></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_mode" id="amazon-login-pay-mode" class="form-control">
				<?php if ($amazon_login_pay_mode == 'payment') { ?>
					<option value="payment" selected="selected"><?php echo $text_payment; ?></option>
				<?php } else { ?>
					<option value="payment"><?php echo $text_payment; ?></option>
				<?php } ?>
				<?php if ($amazon_login_pay_mode == 'auth') { ?>
					<option value="auth" selected="selected"><?php echo $text_auth; ?></option>
				<?php } else { ?>
					<option value="auth"><?php echo $text_auth; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-marketplace"><?php echo $entry_marketplace; ?></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_marketplace" id="amazon-login-pay-marketplace" class="form-control">
				<?php if ($amazon_login_pay_marketplace == 'uk') { ?>
					<option value="de"><?php echo $text_germany; ?></option>
					<option value="uk" selected="selected"><?php echo $text_uk; ?></option>
					<option value="us"><?php echo $text_us; ?></option>
				<?php } elseif ($amazon_login_pay_marketplace == 'de') { ?>
					<option value="de" selected="selected"><?php echo $text_germany; ?></option>
					<option value="uk"><?php echo $text_uk; ?></option>
					<option value="us"><?php echo $text_us; ?></option>
				<?php } else { ?>
					<option value="de"><?php echo $text_germany; ?></option>
					<option value="uk"><?php echo $text_uk; ?></option>
					<option value="us" selected="selected"><?php echo $text_us; ?></option>
				<?php } ?>
			  </select>
			  <?php if ($error_curreny) { ?>
				  <div class="text-danger"><?php echo $error_curreny; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-checkout"><span data-toggle="tooltip" title="<?php echo $help_checkout; ?>"><?php echo $entry_checkout; ?></span></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_checkout" id="amazon-login-checkout-mode" class="form-control">
				<?php if ($amazon_login_pay_checkout == 'account') { ?>
					<option value="account" selected="selected"><?php echo $text_account; ?></option>
				<?php } else { ?>
					<option value="account"><?php echo $text_account; ?></option>
				<?php } ?>
				<?php if ($amazon_login_pay_checkout == 'guest') { ?>
					<option value="guest" selected="selected"><?php echo $text_guest; ?></option>
				<?php } else { ?>
					<option value="guest"><?php echo $text_guest; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-capture-status"><span data-toggle="tooltip" title="<?php echo $help_capture_status; ?>"><?php echo $entry_capture_status; ?></span></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_capture_status" id="amazon-login-pay-capturet-status" class="form-control">
				<option value=""><?php echo $text_no_capture; ?></option>
				<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $amazon_login_pay_capture_status) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-pending-status"><?php echo $entry_pending_status; ?></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_pending_status" id="amazon-login-pay-pendingt-status" class="form-control">
				<?php foreach ($order_statuses as $order_status) { ?>
					<?php if ($order_status['order_status_id'] == $amazon_login_pay_pending_status) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					<?php } ?>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-ipn-token"><span data-toggle="tooltip" title="<?php echo $help_ipn_token; ?>"><?php echo $entry_ipn_token; ?></span></label>
			<div class="col-sm-10">
			  <input type="text" name="amazon_login_pay_ipn_token" value="<?php echo $amazon_login_pay_ipn_token; ?>" id="input-ipn-token" class="form-control" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-ipn-url"><?php echo $entry_ipn_url; ?></label>
			<div class="col-sm-10">
			  <div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span>
				<input type="text" readonly value="<?php echo $ipn_url; ?>" id="input-ipn-url" class="form-control" />
			  </div>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-minimum-total"><span data-toggle="tooltip" title="<?php echo $help_minimum_total; ?>"><?php echo $text_minimum_total; ?></span></label>
			<div class="col-sm-10">
			  <input type="text" name="amazon_login_pay_minimum_total" value="<?php echo $amazon_login_pay_minimum_total; ?>" placeholder="<?php echo $text_minimum_total; ?>" id="amazon-login-pay-minimum-total" class="form-control" />
			  <?php if ($error_minimum_total) { ?>
				  <div class="text-danger"><?php echo $error_minimum_total; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-geo-zone"><?php echo $text_geo_zone; ?></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_geo_zone" id="amazon-login-pay-geo-zone" class="form-control">
				<?php if ($amazon_login_pay_geo_zone == 0) { ?>
					<option value="0" selected="selected"><?php echo $text_all_geo_zones; ?></option>
				<?php } else { ?>
					<option value="0"><?php echo $text_all_geo_zones; ?></option>
				<?php } ?>
				<?php foreach ($geo_zones as $geo_zone) { ?>
					<?php if ($amazon_login_pay_geo_zone == $geo_zone['geo_zone_id']) { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
					<?php } else { ?>
						<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
					<?php } ?>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-input-debug"><span data-toggle="tooltip" title="<?php echo $help_debug; ?>"><?php echo $entry_debug; ?></span></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_debug" id="amazon-login-pay-input-debug" class="form-control">
				<?php if ($amazon_login_pay_debug) { ?>
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
			<label class="col-sm-2 control-label" for="amazon-login-pay-sort-order"><?php echo $text_sort_order; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="amazon_login_pay_sort_order" value="<?php echo $amazon_login_pay_sort_order; ?>" placeholder="<?php echo $text_sort_order; ?>" id="amazon-login-pay-sort-order" class="form-control" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-status"><?php echo $text_status; ?></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_status" id="amazon-login-pay-status" class="form-control">
				<?php if ($amazon_login_pay_status == 1) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
				<?php } ?>
				<?php if ($amazon_login_pay_status == 0) { ?>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
					<option value="0"><?php echo $text_disabled; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="amazon-login-pay-declined_codes"><span data-toggle="tooltip" title="<?php echo $help_declined_codes; ?>"><?php echo $text_declined_codes; ?></span></label>
			<div class="col-sm-10">
			  <select name="amazon_login_pay_declined_code" id="amazon-login-pay-declined_code" class="form-control">
				<option value=""><?php echo $text_amazon_no_declined; ?></option>
				<?php foreach ($declined_codes as $declined_code) { ?>
					<?php if ($amazon_login_pay_declined_code == $declined_code) { ?>
						<option value="<?php echo $declined_code; ?>" selected="selected"><?php echo $declined_code; ?></option>
					<?php } else { ?>
						<option value="<?php echo $declined_code; ?>"><?php echo $declined_code; ?></option>
					<?php } ?>
				<?php } ?>
			  </select>
			</div>
		  </div>
		</form>
	  </div>
	</div>
  </div>
  <script type="text/javascript">
      $('#button-credentials').on('click', function () {
        var json = $('#input-credentials').val()
        try {
          var credentials = $.parseJSON($('#input-credentials').val());
          $('#amazon-login-pay-merchant-id').val(credentials['merchant_id']);
          $('#amazon-login-pay-access-key').val(credentials['access_key']);
          $('#amazon-login-pay-access-secret').val(credentials['secret_key']);
          $('#amazon-login-pay-client-id').val(credentials['client_id']);
          $('#amazon-login-pay-client-secret').val(credentials['client_secret']);
          $('<input>').attr({
            type: 'hidden',
            value: 'true',
            name: 'language_reload'
          }).appendTo('#form-amazon-login-pay');
          $('.pull-right > .btn-primary').click();
        } catch (e) {
          $('.container-fluid:eq(1)').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_credentials; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>')
        }
      });
      //</script>
  <script type="text/javascript">
      $('input[name=\'amazon_login_pay_ipn_token\']').change(function () {
        $('#input-ipn-url').val('<?php echo HTTPS_CATALOG; ?>index.php?route=payment/amazon_login_pay/ipn&token=' + $(this).val());
      });
      //</script>
  <script type="text/javascript">
      $('#amazon-login-pay-marketplace').on('change', function () {
        if ($(this).val() === 'de') {
          var language = 'de-DE';
          var sp_id = 'AGGDPRPDPL7SL';
          var locale = 'EUR';
          var registration_url = "https://sellercentral-europe.amazon.com/hz/me/sp/redirect?ld=SPEXDEAPA-OpencartPL";
        } else if ($(this).val() === 'uk') {
          var language = 'en-GB';
          var sp_id = 'A1P8WV11EWOP9H';
          var locale = 'GBP';
          var registration_url = "https://sellercentral-europe.amazon.com/hz/me/sp/redirect?ld=SPEXUKAPA-OpencartPL";
        } else {
          var language = 'en-US';
          var sp_id = 'A3GK1RS09H3A7D';
          var locale = 'US';
          var registration_url = "https://sellercentral.amazon.com/hz/me/sp/redirect?ld=SPEXUSAPA-OpencartPL";
        }
        $('input[name="language"]').val(language);
        $('input[name="spId"]').val(sp_id);
        $('input[name="locale"]').val(locale);
        $('#registration-form').attr('action', registration_url);
      });
      //</script>
  <script type="text/javascript">
      $('#sign-up').on('click', function () {
        $("#registration-form").submit();
      });
      //</script>
</div>
<?php echo $footer; ?>