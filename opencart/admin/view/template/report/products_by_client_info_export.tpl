<?php if(isset($orders)) { ?>
	<html xmlns:v="urn:schemas-microsoft-com:vml"
	xmlns:o="urn:schemas-microsoft-com:office:office"
	xmlns:x="urn:schemas-microsoft-com:office:excel"
	xmlns="http://www.w3.org/TR/REC-html40">
	
	<head>
		<title><?php echo $text_name; ?></title>
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
			<td><b><?php echo $txt_column_id; ?></b></td>
			<td><b><?php echo $txt_column_payment_method; ?></b></td>
			<td><b><?php echo $txt_column_situation; ?></b></td>
			<td><b><?php echo $txt_column_total; ?></b></td>
			<td><b><?php echo $txt_column_date_added; ?></b></td>
			<td><b><?php echo $txt_column_date_modified; ?></b></td>
		</tr>
	<?php foreach($orders as $order) { ?>
		<tr>
			<td><?php echo $order["order_id"]; ?></td>
			<td><?php echo strip_tags($order["payment_method"]); ?></td>
			<td><?php echo (isset($all_statuses[$order['order_status_id']]) ? $all_statuses[$order['order_status_id']] : $text_status_not_found); ?></td>
			<td><?php echo $this->currency->format($order['total']); ?></td>
			<td><?php echo date($this->language->get('date_format_short').' H:i:s', strtotime($order['date_added'])); ?></td>
			<td><?php echo date($this->language->get('date_format_short').' H:i:s', strtotime($order['date_modified'])); ?></td>
		</tr>
	<?php } ?>
<?php } ?>