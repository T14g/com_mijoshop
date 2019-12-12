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
			<td><b>Data do Pedido</b></td>
			<td><b>ID do produto</b></td>
			<td><b><?php echo $txt_column_name_product; ?></b></td>
			<td><b><?php echo $txt_column_name_cliente; ?></b></td>
			<td><b><?php echo $txt_column_email; ?></b></td>
			<td><b>Telefone</b></td>
			<td><b>Celular</b></td>
			<td><b>CPF</b></td>
			<td><b>CRM / Coren</b></td>
			<td><b>Data Nascimento</td>
			<td><b>Especialidade</b></td>
			<td><b>Nome do Pagador</b></td>
			<td><b>RG</b></td>
			<td><b><?php echo $txt_column_situation; ?></b></td>
			<td><b><?php echo $txt_column_total; ?></b></td>
			<td><b>Como nos conheceu?</b></td>
				<td><b>Complemento</b></td>
					<td><b>Nº do endereço</b></td>
			<td><b>Interesse em outros cursos</b></td>
				
		</tr>
	<?php foreach($products as $product) { ?>
		<tr>
			<td><?php echo $product["order_id"]; ?></td>
			<td><?php $data_pedido = new DateTime($product['date_added']);
				echo $data_pedido->format('d/m/Y'); ?>	</td>
			<td><?php echo $product['product_id']; ?></td>
			<td><?php echo $product["name"]; ?></td>
			<td><?php echo $product["fullname"]; ?></td>
			<td><?php echo $product["email"]; ?></td>
			<td><?php echo $product["telephone"]; ?></td>
			<?php $account_custom_field = unserialize($product['personalizados']);  
				$address['custom_field'] = unserialize($product['end_personalizados']); ?> 
              <?php foreach ($custom_fields as $custom_field) { ?>
                <?php if ($custom_field['location'] == 'account') { ?>
                	<?php if ($custom_field['type'] == 'select') { ?>
                	<td>                   
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
    					<?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $account_custom_field[$custom_field['custom_field_id']]) { ?>
                            <?php echo $custom_field_value['name']; ?>                            
                    <?php } ?>
                    <?php } ?> 
                    </td>                        
                <?php } ?>
                     
                <?php if ($custom_field['type'] == 'text') { ?>
                <td>                      
                 <?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>
                </td>                                  
                <?php } ?> 
                      
               <?php } ?>
               
            <?php } ?>
            <td><?php echo (isset($all_statuses[$product['order_status_id']]) ? $all_statuses[$product['order_status_id']] : $text_status_not_found ); ?></td>
			<td><?php echo number_format($product['total'], 2, ',',''); ?></td>
			<?php foreach ($custom_fields as $custom_field) { ?>
              <?php if ($custom_field['location'] == 'address') { ?>
                <?php if ($custom_field['type'] == 'select') { ?>
                      <td>                          
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <?php if (isset($address['custom_field'][$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address['custom_field'][$custom_field['custom_field_id']]) { ?>
                            <?php echo $custom_field_value['name']; ?>
                            <?php }  ?>                            
                            <?php } ?>                                                     
                       </td>
                      
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'text') { ?>
                      <td>                        
                          <?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>                          
                      </td> 
                      <?php } ?>

               <?php } ?>
            <?php } ?>

		</tr>
	<?php } ?>
<?php } ?>