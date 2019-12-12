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
		.text{	mso-number-format: \@;	}
		.date{	mso-number-format: DD/MM/YYYY;	}		
		.datetime{mso-number-format: DD/MM/YYYY H:i:s;	}
		</style>
	</head>
	<body>
	<table border="1">
		<tr>
			<td><b>Nome do curso</b></td>
			<td><b>Mês</b></td>
			<td><b>Patrocinado</b></td>
			<td><b>Investido</b></td>
			<td><b>Completo</b></td>
			<td><b>Pendente</b></td>
			<td><b>Cancelado</b></td>			
				
		</tr>	

	
</table>
</body>

<?php } ?>