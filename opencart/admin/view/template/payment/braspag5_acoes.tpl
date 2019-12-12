<?php
function getStatusCielo5($status) {
switch($status) {
	case "0": $status = "Aguardando Pagamento";
	break;
	case "1": $status = "Autorizado";
	break;
	case "2": $status = "Pagamento Confirmado";
	break;
	case "3": $status = "Negado";
	break;
	case "20": $status = "Agendado";
	break;
	case "13": $status = "Abortado";
	break;
	case "12": $status = "Pendente";
	break;
	case "11": $status = "Devolvido";
	break;
	case "10": $status = "Cancelado";
	break;
	default: $status = "N&atilde;o Finalizado";
	break;
}
return $status;
}
?>
<?php
if(isset($cielo['link'])){ 
?>
<h2>Detalhes do Pedido <?php echo $operadora;?></h2>
<div class="alert alert-success" id="bluepay_redirect_transaction_msg" style="display:none;"></div>
<table class="table table-striped table-bordered">
<?php if(isset($cielo['lr_log']) && !empty($cielo['lr_log'])){ ?>
  <tr>
    <td>LR</td>
    <td><?php echo @$cielo['lr_log']; ?></td>
  </tr>
<?php } ?>
    <tr>
    <td>Status</td>
    <td><?php echo getStatusCielo5(@$cielo['status']); ?></td>
  </tr>
<?php if(isset($cielo['tid']) && !empty($cielo['tid'])){ ?>
  <tr>
    <td>TID</td>
    <td><?php echo @$cielo['tid']; ?></td>
  </tr>
<?php } ?>
  <tr>
    <td>Total da transa&ccedil;&atilde;o</td>
    <td><?php echo @$cielo['total_pago']; ?></td>
  </tr>
  <tr>
    <td>Meio</td>
    <td><?php echo strtoupper(@$cielo['bandeira']); ?> em <?php echo @$cielo['parcelas']; ?>x</td>
  </tr>
<?php if(isset($cielo['bin']) && !empty($cielo['bin'])){ ?>
  <tr>
    <td>BIN</td>
    <td><?php echo @$cielo['bin']; ?></td>
  </tr>
<?php } ?>
  <tr>
    <td></td>
    <td><span class="btn btn-primary" onclick="javascript:abrir('<?php echo $link_consulta;?>');" id="aprovar_cielo">Consultar Pedido</span></td>
  </tr>
</table>
<script type="text/javascript">
<!--
function isNumberKey(evt){
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
}
function abrir(URL) {
 
  var width = 600;
  var height = 500;
 
  var left = 99;
  var top = 99;
 
  window.open(URL,'janela', 'width='+width+', height='+height+', top='+top+', left='+left+', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');
 
}
//-->
</script>
<?php } ?>