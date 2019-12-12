<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-correios" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
		<div class="alert alert-info"><i class="fa fa-info-circle"></i>
			A tabela de preços dos Correios é fornecida para quem tem contrato, normalmente em arquivo PDF ou em Excel.<br />
			Para os serviços sem contrato como PAC à Vista (04510) e Sedex à Vista (04014), você deve solicitar a tabela junto com o gerente da agência dos Correios mais próxima.<br />
			Os valores devem ser preenchidos usando ponto (.) como separador de decimal.<br />
			Os serviços PAC (04510) e Sedex (04014) vêm com tabelas pré-configuradas como *EXEMPLO*. *NÃO* as use em loja de produção antes de revisar os seus dados.<br />
			[1] - <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/buscaFaixaCep.cfm" target="_blank">Busca por Faixa de CEP</a><br />
			[2] - <a href="https://www.correios.com.br/precos-e-prazos/servicos-adicionais-nacionais" target="_blank">Valores dos serviços adicionais</a>
		</div>	  
      	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-shipping" class="form-horizontal">
			<div class="table-responsive">
				<table id="faixas-peso" class="table table-striped table-bordered table-hover">
				  <thead>
					<tr>
					  <td class="text-left" colspan="2"></td>
					  <td class="text-center" rowspan="3">
						  <label><span data-toggle="tooltip" title="<?php echo $help_lx; ?>"><?php echo $entry_lx; ?></span></label>
				  
						  <input type="text" name="cep_local_inicio" value="" placeholder="<?php echo $text_cep_local_inicio; ?>" class="form-control" />
						  <input type="text" name="cep_local_fim" value="" placeholder="<?php echo $text_cep_local_fim; ?>" class="form-control" />
						  <button type="button" onclick="addFaixaCepLocal();" data-toggle="tooltip" title="<?php echo $button_add_faixa_cep_local; ?>" class="btn btn-primary btn-faixa-cep"><i class="fa fa-chevron-down"></i></button>
						  <div id="faixa-cep-local-box" class="well well-sm" style="height: 100px; overflow: auto;"> 
							<?php $faixa_index = 0; ?>
							<?php if(isset($faixa_cep_local)) { ?>
								<?php foreach ($faixa_cep_local as $faixa) { ?>
								<div id="faixa-cep-local<?php echo $faixa_index; ?>"><i class="fa fa-minus-circle"></i> <?php echo $faixa['cep_inicio']; ?> até <?php echo $faixa['cep_fim']; ?>
								  <input type="hidden" name="faixa_cep_local[]" value="<?php echo $faixa['cep_inicio']; ?>*<?php echo $faixa['cep_fim']; ?>" />
								</div>
								<?php $faixa_index++; ?>
								<?php } ?>
							<?php } ?>
						  </div>							  
					  </td>
					  <td class="text-left" rowspan="3"><label><span data-toggle="tooltip" title="<?php echo $help_ex; ?>"><?php echo $entry_ex; ?></span></label></td>
					  <td class="text-center" colspan="6"><label><span data-toggle="tooltip" title="<?php echo $help_ix; ?>"><?php echo $text_ix; ?></span></label></td>
					  <td class="text-center" colspan="6"><label><span data-toggle="tooltip" title="<?php echo $help_nx; ?>"><?php echo $text_nx; ?></span></label></td>
					  <td class="text-left"></td>
					</tr>	
					<tr>
					  <td class="text-center" colspan="2"><label><span data-toggle="tooltip" title="<?php echo $help_faixa_peso; ?>"><?php echo $text_faixa_peso; ?></span></label></td>
					  <td class="text-left"><?php echo $entry_i1; ?></td>
					  <td class="text-left"><?php echo $entry_i2; ?></td>
					  <td class="text-left"><?php echo $entry_i3; ?></td>
					  <td class="text-left"><?php echo $entry_i4; ?></td>
					  <td class="text-left"><?php echo $entry_i5; ?></td>
					  <td class="text-left"><?php echo $entry_i6; ?></td>
					  <td class="text-left"><?php echo $entry_n1; ?></td>
					  <td class="text-left"><?php echo $entry_n2; ?></td>
					  <td class="text-left"><?php echo $entry_n3; ?></td>
					  <td class="text-left"><?php echo $entry_n4; ?></td>
					  <td class="text-left"><?php echo $entry_n5; ?></td>
					  <td class="text-left"><?php echo $entry_n6; ?></td>
					  <td></td>
					</tr>				  
					<tr>
					  <td class="text-left"><?php echo $entry_peso_inicial; ?></td>
					  <td class="text-left"><?php echo $entry_peso_final; ?></td>
					  <?php for($i = 1; $i <= 6; $i++) { ?>
					  <td class="text-left">
						  <input type="text" name="zonei<?php echo $i; ?>" value="" placeholder="<?php echo $text_autocomplete; ?>" class="zoneautocomplete-i form-control" />
						  <div id="zone-box-i<?php echo $i; ?>" class="well well-sm" style="height: 100px; overflow: auto;"> 
							<?php if(isset($tabela_regiao['i' . $i])) { ?>
								<?php foreach($tabela_regiao['i' . $i] as $zone) { ?>
								<div id="zone-i<?php echo $i; ?>-<?php echo $zone['zone_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $zone['code']; ?>
								  <input type="hidden" name="tabela_regiao[i<?php echo $i; ?>][]" value="<?php echo $zone['zone_id']; ?>" />
								</div>
								<?php } ?>
							<?php } ?>
						  </div>							  
					  </td>
					  <?php } ?>
					  <?php for($i = 1; $i <= 6; $i++) { ?>
					  <td class="text-left">
						  <input type="text" name="zonen<?php echo $i; ?>" value="" placeholder="<?php echo $text_autocomplete; ?>" class="zoneautocomplete-n form-control" />
						  <div id="zone-box-n<?php echo $i; ?>" class="well well-sm" style="height: 100px; overflow: auto;"> 
							<?php if(isset($tabela_regiao['n' . $i])) { ?>
								<?php foreach($tabela_regiao['n' . $i] as $zone) { ?>
								<div id="zone-n<?php echo $i; ?>-<?php echo $zone['zone_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $zone['code']; ?>
								  <input type="hidden" name="tabela_regiao[n<?php echo $i; ?>][]" value="<?php echo $zone['zone_id']; ?>" />
								</div>
								<?php } ?>
							<?php } ?>
						  </div>							  
					  </td>
					  <?php } ?>					  
					  <td></td>
					</tr>
				  </thead>
				  <tbody>
					<?php $faixa_peso_row = 0; ?>
					<?php foreach ($faixa_peso as $faixa_peso_info) { ?>
					<tr id="faixa-peso-row<?php echo $faixa_peso_row; ?>">
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][peso_inicial]" value="<?php echo $faixa_peso_info['peso_inicial']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][peso_final]" value="<?php echo $faixa_peso_info['peso_final']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][lx]" value="<?php echo $faixa_peso_info['lx']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][ex]" value="<?php echo $faixa_peso_info['ex']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][i1]" value="<?php echo $faixa_peso_info['i1']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][i2]" value="<?php echo $faixa_peso_info['i2']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][i3]" value="<?php echo $faixa_peso_info['i3']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][i4]" value="<?php echo $faixa_peso_info['i4']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][i5]" value="<?php echo $faixa_peso_info['i5']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][i6]" value="<?php echo $faixa_peso_info['i6']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][n1]" value="<?php echo $faixa_peso_info['n1']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][n2]" value="<?php echo $faixa_peso_info['n2']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][n3]" value="<?php echo $faixa_peso_info['n3']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][n4]" value="<?php echo $faixa_peso_info['n4']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][n5]" value="<?php echo $faixa_peso_info['n5']; ?>" class="form-control" /></td>
					  <td class="text-left"><input type="text" name="faixa_peso[<?php echo $faixa_peso_row; ?>][n6]" value="<?php echo $faixa_peso_info['n6']; ?>" class="form-control" /></td>
					  
					  <td class="text-left"><button type="button" onclick="$('#faixa-peso-row<?php echo $faixa_peso_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
					</tr>
					<?php $faixa_peso_row++; ?>
					<?php } ?>
				  </tbody>
				  <tfoot>
					<tr>
					  <td colspan="17" class="text-right"><button type="button" onclick="addFaixaPeso();" data-toggle="tooltip" title="<?php echo $button_add_faixa_peso; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
					</tr>				  
					<tr>
						<td class="text-right" colspan="2"><strong><?php echo $entry_adicional_kg; ?><strong></td>
						<td class="text-left"><input type="text" name="lx_adicional_kg" value="<?php echo $lx_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="ex_adicional_kg" value="<?php echo $ex_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="i1_adicional_kg" value="<?php echo $i1_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="i2_adicional_kg" value="<?php echo $i2_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="i3_adicional_kg" value="<?php echo $i3_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="i4_adicional_kg" value="<?php echo $i4_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="i5_adicional_kg" value="<?php echo $i5_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="i6_adicional_kg" value="<?php echo $i6_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="n1_adicional_kg" value="<?php echo $n1_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="n2_adicional_kg" value="<?php echo $n2_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="n3_adicional_kg" value="<?php echo $n3_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="n4_adicional_kg" value="<?php echo $n4_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="n5_adicional_kg" value="<?php echo $n5_adicional_kg; ?>" class="form-control" /></td>
						<td class="text-left"><input type="text" name="n6_adicional_kg" value="<?php echo $n6_adicional_kg; ?>" class="form-control" /></td>
						<td></td>
					</tr>				  
				  </tfoot>				  
				</table>
			</div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-max-peso-real"><span data-toggle="tooltip" title="<?php echo $help_max_peso_real; ?>"><?php echo $entry_max_peso_real; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="max_peso_real" value="<?php echo $max_peso_real; ?>" placeholder="<?php echo $entry_max_peso_real; ?>" id="input-max-peso-real" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-ad-valorem"><span data-toggle="tooltip" title="<?php echo $help_ad_valorem; ?>"><?php echo $entry_ad_valorem; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ad_valorem" value="<?php echo $ad_valorem; ?>" placeholder="<?php echo $entry_ad_valorem; ?>" id="input-ad-valorem" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-aviso-recebimento"><span data-toggle="tooltip" title="<?php echo $help_aviso_recebimento; ?>"><?php echo $entry_aviso_recebimento; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="aviso_recebimento" value="<?php echo $aviso_recebimento; ?>" placeholder="<?php echo $entry_aviso_recebimento; ?>" id="input-aviso-recebimento" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mao-propria"><span data-toggle="tooltip" title="<?php echo $help_mao_propria; ?>"><?php echo $entry_mao_propria; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="mao_propria" value="<?php echo $mao_propria; ?>" placeholder="<?php echo $entry_mao_propria; ?>" id="input-mao-propria" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-a-cobrar-vpne"><span data-toggle="tooltip" title="<?php echo $help_a_cobrar_vpne; ?>"><?php echo $entry_a_cobrar_vpne; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="a_cobrar_vpne" value="<?php echo $a_cobrar_vpne; ?>" placeholder="<?php echo $entry_a_cobrar_vpne; ?>" id="input-a-cobrar-vpne" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-taxa-emergencial"><span data-toggle="tooltip" title="<?php echo $help_taxa_emergencial; ?>"><?php echo $entry_taxa_emergencial; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="taxa_emergencial" value="<?php echo $taxa_emergencial; ?>" placeholder="<?php echo $entry_taxa_emergencial; ?>" id="input-taxa-emergencial" class="form-control" />
            </div>
          </div>		  
 		</form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var faixa_peso_row = <?php echo $faixa_peso_row; ?>;

function addFaixaPeso() {
	html  = '<tr id="faixa-peso-row' + faixa_peso_row + '">';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][peso_inicial]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][peso_final]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][lx]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][ex]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][i1]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][i2]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][i3]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][i4]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][i5]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][i6]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][n1]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][n2]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][n3]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][n4]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][n5]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="faixa_peso[' + faixa_peso_row  + '][n6]" value="" class="form-control" /></td>';

 	  html += '  <td class="text-left"><button type="button" onclick="$(\'#faixa-peso-row' + faixa_peso_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#faixas-peso tbody').append(html);
	
	faixa_peso_row++;
}

function zoneautocomplete(index, column) {
	var indice = column + String((index + 1));
	
	$('input[name="zone' + indice + '"]').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=shipping/correios/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['zone_id'],
							code: item['code']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name="zone' + indice + '"]').val('');

			$('#zone-' + indice + '-' + item['value']).remove();

			$('#zone-box-' + indice).append('<div id="zone-' + indice + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['code'] + '<input type="hidden" name="tabela_regiao[' + indice + '][]" value="' + item['value'] + '" /></div>');
		}
	});
}

$('.zoneautocomplete-i').each(function(index, element) {
	zoneautocomplete(index, 'i');
	
	$('#zone-box-i' + (index + 1)).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});		
});

$('.zoneautocomplete-n').each(function(index, element) {
	zoneautocomplete(index, 'n');
	
	$('#zone-box-n' + (index + 1)).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});		
});

var faixa_index = <?php echo $faixa_index; ?>;

function addFaixaCepLocal() {
	var cep_inicio = $('input[name="cep_local_inicio"]').val();
	var cep_fim = $('input[name="cep_local_fim"]').val();
	
	if(!cep_inicio || !cep_fim) {
		alert("Faixa de CEP incorreta!");
	} else {
		$('input[name="cep_local_inicio"]').val('');
		$('input[name="cep_local_fim"]').val('');

		$('#faixa-cep-local' + faixa_index).remove();
		$('#faixa-cep-local-box').append('<div id="faixa-cep-local' + faixa_index + '"><i class="fa fa-minus-circle"></i> ' + cep_inicio + ' até ' + cep_fim + '<input type="hidden" name="faixa_cep_local[]" value="' + cep_inicio + '*' + cep_fim + '" /></div>');	

		faixa_index++;
	}
}

$('#faixa-cep-local-box').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script> 

<style type="text/css">
.panel-body {
	padding: 5px;
}

.form-control {
    padding: 8px 4px;
}

.well .form-control {
    display: inline-block;
    width: 100px;
    height: 30px;
    padding: 4px 4px;
}

.well span {
    display: inline-block;
    width: 20px;
}

.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
	padding: 4px;	
}
.well {
	height: 150px; 
	overflow: auto;
	margin-bottom: 5px;
}
.btn-faixa-cep {
	padding: 2px 6px;
}
#faixa-cep-local-box {
	padding: 2px;
	font-size: 12px;
    font-weight: normal;
	text-align: left;
}
</style>			
<?php echo $footer; ?>
