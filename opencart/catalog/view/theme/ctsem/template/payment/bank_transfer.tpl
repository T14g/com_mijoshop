<div class="buttons">

  <div class="pull-right">

    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-success button-confirm" />

  </div>

</div>

<h3>Instruções de pagamento</h3>

<div class="well well-sm">

  <p><?php echo $bank; ?></p>

  <p><?php echo $text_payment; ?></p>

</div>

<div class="buttons">

  <div class="pull-right">

    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-success button-confirm" />

  </div>

</div>

<script type="text/javascript"><!--

$('.button-confirm').on('click', function() {

	$.ajax({ 

		type: 'get',

		url: 'index.php?route=payment/bank_transfer/confirm',

		cache: false,

		beforeSend: function() {

			$('.button-confirm').button('loading');

		},

		complete: function() {

			$('.button-confirm').button('reset');

		},		

		success: function() {

			location = '<?php echo $continue; ?>';

		}		

	});

});

//--></script> 

