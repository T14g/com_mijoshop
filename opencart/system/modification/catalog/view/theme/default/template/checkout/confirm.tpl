<?php if (!isset($redirect)) { ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>

			<td class="text-left">&nbsp;</td>
			
        <td class="text-left"><?php echo $column_name; ?></td>
        <td class="text-left"><?php echo $column_model; ?></td>
        <td class="text-right"><?php echo $column_quantity; ?></td>
        <td class="text-right"><?php echo $column_price; ?></td>
        <td class="text-right"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>

			<?php if(isset($product['imagem'])){ ?>
			<td class="text-left"><img src="<?php echo @$product['imagem']; ?>" class="img-thumbnail" /></td>
			<?php } ?>
			
        <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?>
          <?php if($product['recurring']) { ?>
          <br />
          <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
          <?php } ?></td>
        <td class="text-left"><?php echo $product['model']; ?></td>
        <td class="text-right"><?php echo $product['quantity']; ?></td>
        <td class="text-right"><?php echo $product['price']; ?></td>
        <td class="text-right"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="text-left"><?php echo $voucher['description']; ?></td>
        <td class="text-left"></td>
        <td class="text-right">1</td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td 
			<?php if(isset($product['imagem'])){ ?>
			colspan="5"
			<?php }else{ ?>
			colspan="4"
			<?php } ?>
			 class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
        <td class="text-right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<?php echo $payment; ?>

<script type="text/javascript">
	window.ga = window.ga || function() {
    	(window.ga.q = window.ga.q || []).push(arguments)
 	}
  ga('create', 'UA-51069647-1');
  ga('require', 'ecommerce');
  // Código para adicionar pedido: 
  ga('ecommerce:addTransaction', {
    'id': '<?php echo $order_id; ?>', // número do pedido
    'affiliation': 'CTSEM', 
    'revenue': '<?php echo number_format($total["gatotal"], 2, ".", ""); ?>', // valor total do pedido
    'shipping': '0', 
    'tax': '0', 
    'currency': 'BRL'  
    });

//add os produtos
<?php foreach ($products as $product) { 
   $preco = number_format($product["gaprice"], 2, '.', '');
?>
  ga('ecommerce:addItem', {
    'id': '<?php echo $order_id; ?>', // número do pedido;
    'name': '<?php echo $product["name"]; ?>', 
    'sku': '', 
    'category': '<?php echo $product["model"]; ?>', 
    'price': '<?php echo $preco; ?>', 
    'quantity': '<?php echo $product["quantity"]; ?>', 
    'currency': 'BRL' 
  });
<?php } ?>
  $('#button-confirm').click(function () {
    ga('ecommerce:send'); // envia a transação
    <!-- Event snippet for Pedido no site - ADS conversion page -->
	gtag('event', 'conversion', {
      'send_to': 'AW-988536480/zoMACMedx6UBEKC9r9cD',
      'transaction_id': '<?php echo $order_id; ?>'
  	});
    
    //facebook
    <?php foreach ($products as $product) { ?>
	    fbq('track', 'Purchase', {
	    	value: <?php echo number_format($product["gaprice"], 2, '.', '') ?>, 
	    	currency: 'BRL',	    	
		    contents: [
		        {
		            id: '<?php echo $order_id; ?>',
		            quantity: 1
		        }
		    ],
		    content_ids: '<?php echo $order_id; ?>',
		    content_type: 'product',
		});
	<?php } ?>
  }); 
</script>
			
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
