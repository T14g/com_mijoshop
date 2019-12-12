<?php if($products) { ?>
	<html xmlns:v="urn:schemas-microsoft-com:vml"
	xmlns:o="urn:schemas-microsoft-com:office:office"
	xmlns:x="urn:schemas-microsoft-com:office:excel"
	xmlns="http://www.w3.org/TR/REC-html40">
	<head>
		<title><?php echo $product["fullname"]; ?> - <?php echo $product["name"]; ?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
		<meta name=ProgId content=Excel.Sheet>
		<style>
		.text
		{
			mso-number-format: \@;
		}

		.date
		{
			mso-number-format: DD/MM/YYYY;
		}
		
		.datetime
		{
			mso-number-format: DD/MM/YYYY H:i:s;
		}
		</style>
	</head>
	<body>
	<table border="1">
		<tr>
			<td><b>Pedido</b></td>
			<td><b><?php echo $txt_column_name_product; ?></b></td>
			<td><b><?php echo $txt_column_name_cliente; ?></b></td>
			<td><b>CPF</b></td>
			<td><b>Telefone</b></td>
			<td>Endereço</td>
            <td>Nº</td>
            <td>Bairro</td>
            <td>Cidade</td>
            <td>UF</td>            
			<td>Valor Frete</td>
			<td>Data</td>
			<td><?php echo $txt_column_situation; ?></td>
            
				
		</tr>
	<?php foreach($products as $product) { ?>
	<?php if ($product['totalFrete'] > 0) { ?>
		<tr>
			<td><?php echo $product["order_id"]; ?></td>
			<td><?php echo $product["name"]; ?></td>
			<td><?php echo $product["fullname"]; ?></td>
			
			<?php $account_custom_field = unserialize($product['personalizados']); 
        ?> 
            <?php foreach ($custom_fields as $custom_field) { ?>
                <?php if ($custom_field['location'] == 'account') { ?>                    
                <?php if ($custom_field['type'] == 'text' AND $custom_field['custom_field_id'] == 3) { ?>
                <td>                      
                 <?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>
                </td>                                  
                <?php } ?> 
                      
               <?php } ?>
               
            <?php } ?>
            <td><?php echo $product["telephone"]; ?></td>
			<td><?php echo $product["shipping_address_1"]; ?></td>
			<?php $address['custom_field'] = Unserialize($product['end_personalizados']); 
        ?> 
            <?php foreach ($custom_fields as $custom_field) { ?>
              <?php if ($custom_field['location'] == 'address') { ?>
                 <?php if ($custom_field['type'] == 'text' AND $custom_field['custom_field_id'] == 7) { ?>
                      <td>                        
                          <?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>                          
                      </td> 
                      <?php } ?>

               <?php } ?>
            <?php } ?>
			<td><?php echo $product["shipping_address_2"]; ?></td>
			<td><?php echo $product["shipping_city"]; ?></td>
			<td><?php echo $product["shipping_zone"]; ?></td>
           <td class="right">
            	<?php echo number_format($product['totalFrete'], 2, ',',''); ?>

            </td>
            <td class="left">
            	<?php $data_pedido = new DateTime($product['date_modified']);
				echo $data_pedido->format('d/m/Y'); ?>								
			</td>
			<td><?php echo (isset($all_statuses[$product['order_status_id']]) ? $all_statuses[$product['order_status_id']] : $text_status_not_found ); ?></td>
		</tr>
	<?php } ?><?php } ?>
<?php } ?>