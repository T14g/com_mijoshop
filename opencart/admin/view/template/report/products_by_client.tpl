<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Produtos / Cursos</h1>
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>Listando os pedidos</h3>
      </div>
       <div class="panel-body">
        <div class="well">
          <div class="row">
          <div class="col-sm-3">
          	 <div class="form-group">
          	 	<label class="control-label" for="input-date-start">Data início:</label>
          	 	<div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="Data inícial" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
          	 </div>
          	</div>
          	 <div class="col-sm-3">
          	 <div class="form-group">
                <label class="control-label" for="input-date-end">Data final:</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="Data final" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
          </div>
          <div class="col-sm-3">
           <label class="control-label">Situação do pedido:</label>
          	<select name="filter_order_status_id">
						<option value="" <?php echo ($filter_order_status_id == '' ? 'selected' : ''); ?>></option>
						<?php foreach($order_statuses as $order_status){ ?>
							<?php if($filter_order_status_id != '' && $filter_order_status_id == $order_status['order_status_id']){ ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected><?php echo $order_status['name']; ?></option>
							<?php }else{ ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>					
							<?php } ?>
						<?php } ?>
					</select>
          </div>
          <div class="col-sm-3">
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            <a href="<?php echo $url_export; ?>&format=raw" target="_blank"><button type="button" class="btn btn-primary pull-right"><i class="fa fa-file-excel-o"></i> Exportar</button></a>
            <!--
            <a href="<?php //echo $url_export_quanti; ?>&format=raw" target="_blank"><button type="button" class="btn btn-primary pull-right"><i class="fa fa-file-excel-o"></i> Exportar Quantidade</button></a>
          -->
			</div>
         </div> <!-- Row 1 -->
         <div class="row">
         	<div class="col-sm-3">
            	<label class="control-label">ID produto:</label><br>
          		<input type="text" name="filter_product_id" value="<?php echo $filter_product_id; ?>" />
          	</div>
          	<div class="col-sm-3">
	            <label class="control-label">Curso / Produto:</label><br>
	          	<input type="text" autocomplete="on" name="filter_product_name" value="<?php echo $filter_product_name; ?>" />
          	</div>
          	<div class="col-sm-3">
	            <label class="control-label">Cliente:</label><br>
	          	<input type="text" name="filter_customer_name" value="<?php echo $filter_customer_name; ?>" />
          	</div>
          	<div class="col-sm-3">
	            <label class="control-label">E-mail:</label><br>
	          	<input type="text" name="filter_customer_email" value="<?php echo $filter_customer_email; ?>" />
          	</div>          
         </div><!-- Row 2 -->
         </div>
      <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <td class="left">
				<?php if ($sort == 'op.product_id') { ?>
                <a href="<?php echo $sort_id_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $txt_column_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_id_product; ?>"><?php echo $txt_column_id; ?></a>
                <?php } ?>
			</td>
            <td class="left">
				<?php if ($sort == 'op.name') { ?>
                <a href="<?php echo $sort_name_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $txt_column_name_product; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name_product; ?>"><?php echo $txt_column_name_product; ?></a>
                <?php } ?>
			</td>
            <td class="left">
				<?php if ($sort == 'o.fullname') { ?>
                <a href="<?php echo $sort_client; ?>" class="<?php echo strtolower($order); ?>"><?php echo $txt_column_name_cliente; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_client; ?>"><?php echo $txt_column_name_cliente; ?></a>
                <?php } ?>
			</td>
            <td class="left">
				<?php if ($sort == 'o.email') { ?>
                <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $txt_column_email; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_email; ?>"><?php echo $txt_column_email; ?></a>
                <?php } ?>
			</td>
			<td class="left"><?php echo $txt_column_situation; ?></td>
      <td class="left">
				<?php if ($sort == 'total') { ?>
                <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $txt_column_total; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_total; ?>"><?php echo $txt_column_total; ?></a>
                <?php } ?>			
			</td>
			<td class="left">Data</td>
            <td class="left"><?php echo $txt_column_action; ?></td>
          </tr>			
          </thead>
        <tbody>
          <?php if ($products) { ?>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="left"><?php echo $product['product_id']; ?>  </td>
            <td class="left"><?php echo $product['name']; ?></td>
            <td class="left"><?php echo $product['fullname']; ?></td>
            <td class="left"><?php echo $product['email']; ?></td>
            <td class="right"><?php echo (isset($all_statuses[$product['order_status_id']]) ? $all_statuses[$product['order_status_id']] : $text_status_not_found ); ?></td>
            <td class="right"><?php echo number_format($product['total'], 2, ',',''); ?></td>
            <td class="left">
            	<?php $data_pedido = new DateTime($product['date_modified']);
				echo $data_pedido->format('d/m/Y'); ?>								
			</td>
            <td class="right"><!--<a href="<?php echo $url_info.'&details='.$product['customer_id'].'|'.$product['product_id']; ?>">Detalhes</a>-->
            <a href="index.php?option=com_mijoshop&route=sale/order/info&token=f536cd8d504744dde3d942aa0baacb29&order_id=<?php echo $product['order_id']; ?>">
              Ver</a>

            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
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
</div></div>
<script type="text/javascript"><!--

// Customer
$('input[name=\'customer\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        json.unshift({
          customer_id: '0',
          customer_group_id: '<?php echo $customer_group_id; ?>',           
          name: '<?php echo $text_none; ?>',
          customer_group: '',
          firstname: '',
          lastname: '',
          email: '',
          telephone: '',
          fax: '',
          custom_field: [],
          address: []     
        });       
        
        response($.map(json, function(item) {
          return {
            category: item['customer_group'],
            label: item['name'],
            value: item['customer_id'],
            customer_group_id: item['customer_group_id'],           
            firstname: item['firstname'],
            lastname: item['lastname'],
            email: item['email'],
            telephone: item['telephone'],
            fax: item['fax'],
            custom_field: item['custom_field'],
            address: item['address']
          }
        }));
      }
    });
  },
  'select': function(item) {
    // Reset all custom fields
    $('#tab-customer input[type=\'text\'], #tab-customer input[type=\'text\'], #tab-customer textarea').not('#tab-customer input[name=\'customer\'], #tab-customer input[name=\'customer_id\']').val('');
    $('#tab-customer select option').removeAttr('selected');
    $('#tab-customer input[type=\'checkbox\'], #tab-customer input[type=\'radio\']').removeAttr('checked');
    
    $('#tab-customer input[name=\'customer\']').val(item['label']);
    $('#tab-customer input[name=\'customer_id\']').val(item['value']);
    $('#tab-customer select[name=\'customer_group_id\']').val(item['customer_group_id']);
    $('#tab-customer input[name=\'firstname\']').val(item['firstname']);
    $('#tab-customer input[name=\'lastname\']').val(item['lastname']);
    $('#tab-customer input[name=\'email\']').val(item['email']);
    $('#tab-customer input[name=\'telephone\']').val(item['telephone']);
    $('#tab-customer input[name=\'fax\']').val(item['fax']);    
        
    for (i in item.custom_field) {
      $('#tab-customer select[name=\'custom_field[' + i + ']\']').val(item.custom_field[i]);
      $('#tab-customer textarea[name=\'custom_field[' + i + ']\']').val(item.custom_field[i]);
      $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'text\']').val(item.custom_field[i]);
      $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'hidden\']').val(item.custom_field[i]);
      $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'radio\'][value=\'' + item.custom_field[i] + '\']').prop('checked', true);  
      
      if (item.custom_field[i] instanceof Array) {
        for (j = 0; j < item.custom_field[i].length; j++) {
          $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + item.custom_field[i][j] + '\']').prop('checked', true);
        }
      }
    }
  
    $('select[name=\'customer_group_id\']').trigger('change');
    
    html = '<option value="0"><?php echo $text_none; ?></option>'; 
      
    for (i in  item['address']) {
      html += '<option value="' + item['address'][i]['address_id'] + '">' + item['address'][i]['firstname'] + ' ' + item['address'][i]['lastname'] + ', ' + item['address'][i]['address_1'] + ', ' + item['address'][i]['city'] + ', ' + item['address'][i]['country'] + '</option>';
    }
    
    $('select[name=\'payment_address\']').html(html);
    $('select[name=\'shipping_address\']').html(html);
    
    $('select[name=\'payment_address\']').trigger('change');
    $('select[name=\'shipping_address\']').trigger('change');
  }
});
// Custom Fields
$('select[name=\'customer_group_id\']').on('change', function() {
  $.ajax({
    url: 'index.php?route=sale/customer/customfield&token=<?php echo $token; ?>&customer_group_id=' + this.value,
    dataType: 'json', 
    success: function(json) {
      $('.custom-field').hide();
      $('.custom-field').removeClass('required');
      
      for (i = 0; i < json.length; i++) {
        custom_field = json[i];
        
        $('.custom-field' + custom_field['custom_field_id']).show();
        
        if (custom_field['required']) {
          $('.custom-field' + custom_field['custom_field_id']).addClass('required');
        }
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});


$('.date').datetimepicker({
	pickTime: false
});
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/products_by_client&token=<?php echo $token; ?>';
	
	var filter_product_id = $('input[name=\'filter_product_id\']').val();
	
	if (filter_product_id) {
		url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
	}

	var filter_product_name = $('input[name=\'filter_product_name\']').val();
	
	if (filter_product_name) {
		url += '&filter_product_name=' + encodeURIComponent(filter_product_name);
	}

	var filter_customer_name = $('input[name=\'filter_customer_name\']').val();
	
	if (filter_customer_name) {
		url += '&filter_customer_name=' + encodeURIComponent(filter_customer_name);
	}
	
	var filter_customer_email = $('input[name=\'filter_customer_email\']').val();
	
	if (filter_customer_email) {
		url += '&filter_customer_email=' + encodeURIComponent(filter_customer_email);
	}

	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();

	if (filter_order_status_id != "*") {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}

	location = url;
});

//--></script> 
<?php echo $footer; ?>