<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        
            <button type="submit" onclick="save('save')" form="form-correios" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Save"><i class="fa fa-save"></i></button>
            <button type="submit" form="form-correios" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Save & Close"><i class="fa fa-sign-out"></i></button>
            
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?> - <?php echo $correios_version; ?></h3>
      </div>
      <div class="panel-body">
      	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-correios" class="form-horizontal">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab-servico" data-toggle="tab"><?php echo $tab_servico; ?></a></li>
			<li><a href="#tab-frete-gratis" data-toggle="tab"><?php echo $tab_frete_gratis; ?></a></li>
			<li><a href="#tab-restricao" data-toggle="tab"><?php echo $tab_restricao; ?></a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab-servico">		
				<div class="table-responsive">
					<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_servicos; ?> <a href="https://www.correios.com.br/precisa-de-ajuda/limites-de-dimensoes-e-de-peso" target="_blank">Clique aqui para mais informações sobre limites de dimensões e peso no site dos Correios</a></div>
					<table id="servicos" class="table table-striped table-bordered table-hover">
					  <thead>
						<tr>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_codigo; ?>"><?php echo $entry_codigo; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_nome; ?>"><?php echo $entry_nome; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_a_cobrar; ?>"><?php echo $entry_a_cobrar; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_postcode; ?>"><?php echo $entry_postcode; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_contrato_codigo; ?>"><?php echo $entry_contrato_codigo; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_contrato_senha; ?>"><?php echo $entry_contrato_senha; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_max_declarado; ?>"><?php echo $entry_max_declarado; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_min_declarado; ?>"><?php echo $entry_min_declarado; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_max_soma_lados; ?>"><?php echo $entry_max_soma_lados; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_min_soma_lados; ?>"><?php echo $entry_min_soma_lados; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_max_lado; ?>"><?php echo $entry_max_lado; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_max_peso; ?>"><?php echo $entry_max_peso; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_mao_propria; ?>"><?php echo $entry_mao_propria; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_aviso_recebimento; ?>"><?php echo $entry_aviso_recebimento; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_declarar_valor; ?>"><?php echo $entry_declarar_valor; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_prazo_adicional; ?>"><?php echo $entry_prazo_adicional; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_adicional; ?>"><?php echo $entry_adicional; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_adicional_manuseio_especial; ?>"><?php echo $entry_adicional_manuseio_especial; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_operacao; ?>"><?php echo $entry_operacao; ?></span></label></td>
						  <td class="text-left"><label><span data-toggle="tooltip" title="<?php echo $help_tabela; ?>"><?php echo $entry_tabela; ?></span></label></td>						  
						  <td></td>
						</tr>
					  </thead>
					  <tbody>
						<?php $servico_row = 0; ?>
						<?php foreach ($correios_servicos as $servico_info) { ?>
						<tr id="servico-row<?php echo $servico_row; ?>">
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][codigo]" value="<?php echo $servico_info['codigo']; ?>" class="form-control" style="width:80px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][nome]" value="<?php echo $servico_info['nome']; ?>" class="form-control" /></td>
						  
						  <td class="text-left">
						  <select name="correios_servicos[<?php echo $servico_row; ?>][a_cobrar]" class="form-control" style="width:50px; padding:8px 0;">
							<?php if ($servico_info['a_cobrar']) { ?>
							<option value="1" selected="selected"><?php echo $text_yes; ?></option>
							<option value="0"><?php echo $text_no; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_yes; ?></option>
							<option value="0" selected="selected"><?php echo $text_no; ?></option>
							<?php } ?>
						  </select>					  
						  </td>
						  
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][postcode]" value="<?php echo $servico_info['postcode']; ?>" class="form-control" style="width:90px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][contrato_codigo]" value="<?php echo $servico_info['contrato_codigo']; ?>" class="form-control" style="width:90px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][contrato_senha]" value="<?php echo $servico_info['contrato_senha']; ?>" class="form-control" style="width:90px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][max_declarado]" value="<?php echo $servico_info['max_declarado']; ?>" class="form-control" style="width:80px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][min_declarado]" value="<?php echo $servico_info['min_declarado']; ?>" class="form-control" style="width:80px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][max_soma_lados]" value="<?php echo $servico_info['max_soma_lados']; ?>" class="form-control" style="width:50px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][min_soma_lados]" value="<?php echo $servico_info['min_soma_lados']; ?>" class="form-control" style="width:50px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][max_lado]" value="<?php echo $servico_info['max_lado']; ?>" class="form-control" style="width:50px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][max_peso]" value="<?php echo $servico_info['max_peso']; ?>" class="form-control" style="width:50px" /></td>
						  
						  <td class="text-left">
						  <select name="correios_servicos[<?php echo $servico_row; ?>][mao_propria]" class="form-control" style="width:50px; padding:8px 0;">
							<?php if ($servico_info['mao_propria']) { ?>
							<option value="1" selected="selected"><?php echo $text_yes; ?></option>
							<option value="0"><?php echo $text_no; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_yes; ?></option>
							<option value="0" selected="selected"><?php echo $text_no; ?></option>
							<?php } ?>
						  </select>					  
						  </td>
						  <td class="text-left">
						  <select name="correios_servicos[<?php echo $servico_row; ?>][aviso_recebimento]" class="form-control" style="width:50px; padding:8px 0;">
							<?php if ($servico_info['aviso_recebimento']) { ?>
							<option value="1" selected="selected"><?php echo $text_yes; ?></option>
							<option value="0"><?php echo $text_no; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_yes; ?></option>
							<option value="0" selected="selected"><?php echo $text_no; ?></option>
							<?php } ?>
						  </select>					  
						  </td>
						  <td class="text-left">
						  <select name="correios_servicos[<?php echo $servico_row; ?>][declarar_valor]" class="form-control" style="width:50px; padding:8px 0;">
							<?php if ($servico_info['declarar_valor']) { ?>
							<option value="1" selected="selected"><?php echo $text_yes; ?></option>
							<option value="0"><?php echo $text_no; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_yes; ?></option>
							<option value="0" selected="selected"><?php echo $text_no; ?></option>
							<?php } ?>
						  </select>					  
						  </td>					  
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][prazo_adicional]" value="<?php echo $servico_info['prazo_adicional']; ?>" class="form-control" style="width:50px"  /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][adicional]" value="<?php echo $servico_info['adicional']; ?>" class="form-control" style="width:50px" /></td>
						  <td class="text-left"><input type="text" name="correios_servicos[<?php echo $servico_row; ?>][adicional_manuseio]" value="<?php echo isset($servico_info['adicional_manuseio']) ? $servico_info['adicional_manuseio'] : 0; ?>" class="form-control" style="width:50px" /></td>
						  <td class="text-left">
							  <select name="correios_servicos[<?php echo $servico_row; ?>][operacao]" class="form-control" style="width:80px; padding:8px 0;">
								<?php if (isset($servico_info['operacao'])) { ?>
								<option value="automatico" <?php if ($servico_info['operacao'] == 'automatico') { ?> selected="selected" <?php } ?>><?php echo $text_automatico; ?></option>
								<option value="online" <?php if ($servico_info['operacao'] == 'online') { ?> selected="selected" <?php } ?>><?php echo $text_online; ?></option>
								<option value="offline" <?php if ($servico_info['operacao'] == 'offline') { ?> selected="selected" <?php } ?>><?php echo $text_offline; ?></option>
								<?php } else { ?>
								<option value="automatico"><?php echo $text_automatico; ?></option>
								<option value="online"><?php echo $text_online; ?></option>
								<option value="offline"><?php echo $text_offline; ?></option>							
								<?php } ?>
							  </select>								
						  </td>	
						  <td class="text-left"><button type="button" onclick="editTabela(<?php echo $servico_row; ?>);" data-toggle="tooltip" title="<?php echo $button_tabela; ?>" class="btn btn-primary" id="btn-edit-<?php echo $servico_row; ?>"><i class="fa fa-table"></i></button></td>
						  <td class="text-left"><button type="button" onclick="$('#servico-row<?php echo $servico_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
						</tr>
						<?php $servico_row++; ?>
						<?php } ?>
					  </tbody>
					  <tfoot>
						<tr>
						  <td colspan="21" class="text-right"><button type="button" onclick="addServico();" data-toggle="tooltip" title="<?php echo $button_add_servico; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
						</tr>
					  </tfoot>
					</table>
				  </div>
			  </div>
			  <div class="tab-pane" id="tab-frete-gratis">
				<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_frete_gratis; ?></div>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-estado" data-toggle="tab"><?php echo $tab_gratis_estado; ?></a></li>
					<li><a href="#tab-produto" data-toggle="tab"><?php echo $tab_gratis_produto; ?></a></li>
					<li><a href="#tab-cep" data-toggle="tab"><?php echo $tab_gratis_cep; ?></a></li>
				</ul>
				<div class="tab-content">
				  <div class="tab-pane active" id="tab-estado">					
					<div class="table-responsive">
						<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_gratis_estado; ?></div>
						<table id="estados" class="table table-striped table-bordered table-hover">
						  <thead>
							<tr>
							  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_codigo; ?>"><?php echo $entry_codigo; ?></span></label></td>
							  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_estados_gratis; ?>"><?php echo $entry_estados_gratis; ?></span></label></td>
							  <td class="text-left"><?php echo $entry_descricao; ?></td>
							  <td></td>
							</tr>
						  </thead>
						  <tbody>
							<?php $estado_row = 0; ?>
							<?php foreach ($correios_gratis_estados as $estado_info) { ?>
							<tr id="estado-row<?php echo $estado_row; ?>">
							  <td class="text-left"><input type="text" name="correios_gratis_estados[<?php echo $estado_row; ?>][codigo]" value="<?php echo $estado_info['codigo']; ?>" class="form-control" /></td>
							  <td class="text-left estados-gratis">
								  <p><strong><?php echo $entry_estado; ?></strong> | <strong><?php echo $text_total; ?></strong></p>
								  <div class="well well-sm">
									<?php foreach ($zones as $zone) { ?>
									<div class="checkbox">
									  <label>
										<?php if (isset($estado_info['estados_gratis']) && in_array($zone['zone_id'], $estado_info['estados_gratis'])) { ?>
										<input type="checkbox" name="correios_gratis_estados[<?php echo $estado_row; ?>][estados_gratis][]" value="<?php echo $zone['zone_id']; ?>" checked="checked" /> 
										<span><?php echo $zone['code']; ?></span>
										<?php } else { ?>
										<input type="checkbox" name="correios_gratis_estados[<?php echo $estado_row; ?>][estados_gratis][]" value="<?php echo $zone['zone_id']; ?>" /> 
										<span><?php echo $zone['code']; ?></span>
										<?php } ?>
										 | 
										<?php if (isset($estado_info['total_minimo'][$zone['zone_id']])) { ?>
										<input type="text" name="correios_gratis_estados[<?php echo $estado_row; ?>][total_minimo][<?php echo $zone['zone_id']; ?>]" value="<?php echo $estado_info['total_minimo'][$zone['zone_id']]; ?>" class="form-control" />
										<?php } else { ?>
										<input type="text" name="correios_gratis_estados[<?php echo $estado_row; ?>][total_minimo][<?php echo $zone['zone_id']; ?>]" value="" class="form-control" />
										<?php } ?>
									  </label>
									</div>
									<?php } ?>
								  </div>
								  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_todos; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_nenhum; ?></a>				  
							  </td>
							  <td class="text-left"><input type="text" name="correios_gratis_estados[<?php echo $estado_row; ?>][descricao]" value="<?php echo $estado_info['descricao']; ?>" class="form-control" /></td>
							  <td class="text-left"><button type="button" onclick="$('#estado-row<?php echo $estado_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
							</tr>
							<?php $estado_row++; ?>
							<?php } ?>
						  </tbody>
						  <tfoot>
							<tr>
							  <td colspan="4" class="text-right"><button type="button" onclick="addEstado();" data-toggle="tooltip" title="<?php echo $button_add_servico; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
							</tr>
						  </tfoot>
						</table>
					</div>
				  </div>
				  <div class="tab-pane" id="tab-produto">					
					<div class="table-responsive">
						<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_gratis_produto; ?></div>
						<table id="produtos" class="table table-striped table-bordered table-hover">
						  <thead>
							<tr>
							  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_codigo; ?>"><?php echo $entry_codigo; ?></span></label></td>
							  <td class="text-left required"><label><?php echo $entry_categoria; ?></label></td>
							  <td class="text-left required"><label><?php echo $entry_marca; ?></label></td>
							  <td class="text-left required"><label><?php echo $entry_produto; ?></label></td>							  
							  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $text_total; ?>"><?php echo $entry_total; ?></span></label></td>							  
							  <td class="text-left"><?php echo $entry_descricao; ?></td>
							  <td></td>
							</tr>
						  </thead>
						  <tbody>
							<?php $produto_row = 0; ?>
							<?php foreach ($correios_gratis_produtos as $key => $gratis_produto) { ?>
							<tr id="produto-row<?php echo $produto_row; ?>">
							  <td class="text-left"><input type="text" name="correios_gratis_produtos[<?php echo $produto_row; ?>][codigo]" value="<?php echo $gratis_produto['codigo']; ?>" class="form-control" /></td>
							  <td class="text-left">
								  <input type="text" name="category<?php echo $produto_row; ?>" value="" placeholder="<?php echo $text_autocomplete; ?>" class="categorycomplete form-control" />
								  <div id="product-category-box<?php echo $produto_row; ?>" class="well well-sm" style="height: 100px; overflow: auto;"> 
								    <?php if (isset($product_categories[$key])) { ?>
										<?php foreach ($product_categories[$key] as $product_category) { ?>
										<div id="product-category<?php echo $produto_row; ?>-<?php echo $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_category['name']; ?>
										  <input type="hidden" name="correios_gratis_produtos[<?php echo $produto_row; ?>][categorias][]" value="<?php echo $product_category['category_id']; ?>" />
										</div>
										<?php } ?>
									<?php } ?>
								  </div>							  
							  </td>
							  <td class="text-left">
								  <input type="text" name="manufacturer<?php echo $produto_row; ?>" value="" placeholder="<?php echo $text_autocomplete; ?>" class="manufacturercomplete form-control" />
								  <div id="product-manufacturer-box<?php echo $produto_row; ?>" class="well well-sm" style="height: 100px; overflow: auto;"> 
								    <?php if (isset($product_manufacturers[$key])) { ?>
										<?php foreach ($product_manufacturers[$key] as $product_manufacturer) { ?>
										<div id="product-manufacturer<?php echo $produto_row; ?>-<?php echo $product_manufacturer['manufacturer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_manufacturer['name']; ?>
										  <input type="hidden" name="correios_gratis_produtos[<?php echo $produto_row; ?>][marcas][]" value="<?php echo $product_manufacturer['manufacturer_id']; ?>" />
										</div>
										<?php } ?>
									<?php } ?>
								  </div>							  
							  </td>	
							  <td class="text-left">
								  <input type="text" name="product<?php echo $produto_row; ?>" value="" placeholder="<?php echo $text_autocomplete; ?>" class="productcomplete form-control" />
								  <div id="product-product-box<?php echo $produto_row; ?>" class="well well-sm" style="height: 100px; overflow: auto;"> 
								    <?php if (isset($product_products[$key])) { ?>
										<?php foreach ($product_products[$key] as $product_product) { ?>
										<div id="product-product<?php echo $produto_row; ?>-<?php echo $product_product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_product['name']; ?>
										  <input type="hidden" name="correios_gratis_produtos[<?php echo $produto_row; ?>][produtos][]" value="<?php echo $product_product['product_id']; ?>" />
										</div>
										<?php } ?>
									<?php } ?>
								  </div>							  
							  </td>	
							  <td class="text-left"><input type="text" name="correios_gratis_produtos[<?php echo $produto_row; ?>][total_minimo]" value="<?php echo $gratis_produto['total_minimo']; ?>" class="form-control" /></td>
							  <td class="text-left"><input type="text" name="correios_gratis_produtos[<?php echo $produto_row; ?>][descricao]" value="<?php echo $gratis_produto['descricao']; ?>" class="form-control" /></td>
							  <td class="text-left"><button type="button" onclick="$('#produto-row<?php echo $produto_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
							</tr>
							<?php $produto_row++; ?>
							<?php } ?>
						  </tbody>
						  <tfoot>
							<tr>
							  <td colspan="7" class="text-right"><button type="button" onclick="addProduto();" data-toggle="tooltip" title="<?php echo $button_add_servico; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
							</tr>
						  </tfoot>
						</table>
					</div>
				  </div>
				  <div class="tab-pane" id="tab-cep">
					<div class="table-responsive">
						<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_faixa_ceps; ?> <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaFaixaCEP.cfm" target="_blank">Clique aqui para pesquisar faixas de CEPs</a></div>
						<table id="faixa-cep" class="table table-striped table-bordered table-hover">
						  <thead>
							<tr>
							  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_codigo; ?>"><?php echo $entry_codigo; ?></span></label></td>
							  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_postcode; ?>"><?php echo $entry_postcode; ?></span></label></td>
							  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_cep_inicial; ?>"><?php echo $entry_cep_inicial; ?></span></label></td>
							  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_cep_final; ?>"><?php echo $entry_cep_final; ?></span></label></td>
							  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $text_total; ?>"><?php echo $entry_total; ?></span></label></td>
							  <td class="text-left"><?php echo $entry_descricao; ?></td>
							  <td></td>
							</tr>
						  </thead>
						  <tbody>
							<?php $faixa_cep_row = 0; ?>
							<?php foreach ($correios_gratis_cep as $faixa_info) { ?>
							<tr id="faixa-cep-row<?php echo $faixa_cep_row; ?>">
							  <td class="text-left"><input type="text" name="correios_gratis_cep[<?php echo $faixa_cep_row; ?>][codigo]" value="<?php echo $faixa_info['codigo']; ?>" class="form-control" /></td>
							  <td class="text-left"><input type="text" name="correios_gratis_cep[<?php echo $faixa_cep_row; ?>][cep_origem]" value="<?php echo $faixa_info['cep_origem']; ?>" class="form-control" /></td>
							  <td class="text-left"><input type="text" name="correios_gratis_cep[<?php echo $faixa_cep_row; ?>][cep_inicial]" value="<?php echo $faixa_info['cep_inicial']; ?>" class="form-control" /></td>
							  <td class="text-left"><input type="text" name="correios_gratis_cep[<?php echo $faixa_cep_row; ?>][cep_final]" value="<?php echo $faixa_info['cep_final']; ?>" class="form-control" /></td>
							  <td class="text-left"><input type="text" name="correios_gratis_cep[<?php echo $faixa_cep_row; ?>][total_minimo]" value="<?php echo $faixa_info['total_minimo']; ?>" class="form-control" /></td>
							  <td class="text-left"><input type="text" name="correios_gratis_cep[<?php echo $faixa_cep_row; ?>][descricao]" value="<?php echo $faixa_info['descricao']; ?>" class="form-control" /></td>
							  <td class="text-left"><button type="button" onclick="$('#faixa-cep-row<?php echo $faixa_cep_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
							</tr>
							<?php $faixa_cep_row++; ?>
							<?php } ?>
						  </tbody>
						  <tfoot>
							<tr>
							  <td colspan="7" class="text-right"><button type="button" onclick="addFaixaCep();" data-toggle="tooltip" title="<?php echo $button_add_restricao; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
							</tr>
						  </tfoot>
						</table>
					  </div>			  
				  </div>					  
			    </div>				  
			  </div>			  
			  <div class="tab-pane" id="tab-restricao">
				<div class="table-responsive">
					<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_restricoes; ?> <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaFaixaCEP.cfm" target="_blank">Clique aqui para mais informações e pesquisa de faixas de CEPs</a></div>
					<table id="restricoes" class="table table-striped table-bordered table-hover">
					  <thead>
						<tr>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_codigo; ?>"><?php echo $entry_codigo; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_postcode; ?>"><?php echo $entry_postcode; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_cep_inicial; ?>"><?php echo $entry_cep_inicial; ?></span></label></td>
						  <td class="text-left required"><label><span data-toggle="tooltip" title="<?php echo $help_cep_final; ?>"><?php echo $entry_cep_final; ?></span></label></td>
						  <td class="text-left"><?php echo $entry_descricao; ?></td>
						  <td></td>
						</tr>
					  </thead>
					  <tbody>
						<?php $faixa_row = 0; ?>
						<?php foreach ($correios_faixas as $faixa_info) { ?>					  
						<tr id="faixa-row<?php echo $faixa_row; ?>">
						  <td class="text-left"><input type="text" name="correios_faixas[<?php echo $faixa_row; ?>][codigo]" value="<?php echo $faixa_info['codigo']; ?>" class="form-control" /></td>
						  <td class="text-left"><input type="text" name="correios_faixas[<?php echo $faixa_row; ?>][cep_origem]" value="<?php echo $faixa_info['cep_origem']; ?>" class="form-control" /></td>
						  <td class="text-left"><input type="text" name="correios_faixas[<?php echo $faixa_row; ?>][cep_inicial]" value="<?php echo $faixa_info['cep_inicial']; ?>" class="form-control" /></td>
						  <td class="text-left"><input type="text" name="correios_faixas[<?php echo $faixa_row; ?>][cep_final]" value="<?php echo $faixa_info['cep_final']; ?>" class="form-control" /></td>
						  <td class="text-left"><input type="text" name="correios_faixas[<?php echo $faixa_row; ?>][descricao]" value="<?php echo $faixa_info['descricao']; ?>" class="form-control" /></td>
						  <td class="text-left"><button type="button" onclick="$('#faixa-row<?php echo $faixa_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
						</tr>
						<?php $faixa_row++; ?>
						<?php } ?>
					  </tbody>
					  <tfoot>
						<tr>
						  <td colspan="6" class="text-right"><button type="button" onclick="addRestricao();" data-toggle="tooltip" title="<?php echo $button_add_restricao; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
						</tr>
					  </tfoot>
					</table>
				  </div>			  
			  </div>			  
		  </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-restricao"><span data-toggle="tooltip" title="<?php echo $help_msg_restricao; ?>"><?php echo $entry_msg_restricao; ?></span></label>
            <div class="col-sm-10">
              <select name="correios_msg_restricao" id="input-restricao" class="form-control">
                <?php if ($correios_msg_restricao) { ?>
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
            <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
            <div class="col-sm-10">
              <select name="correios_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $correios_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="correios_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $correios_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="correios_status" id="input-status" class="form-control">
                <?php if ($correios_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="correios_sort_order" value="<?php echo $correios_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>          
 		</form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var servico_row = <?php echo $servico_row; ?>;

function addServico() {
	html  = '<tr id="servico-row' + servico_row + '">';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][codigo]" value="" class="form-control" style="width:80px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][nome]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left">';
	  html += '  <select name="correios_servicos[' + servico_row  + '][a_cobrar]" class="form-control" style="width:50px; padding:8px 0;">';
		html += '  <option value="0"><?php echo $text_no; ?></option>';	  
		html += '  <option value="1"><?php echo $text_yes; ?></option>';
	  html += '  </select>';					  
	  html += '  </td>';	  
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][postcode]" value="" class="form-control" style="width:90px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][contrato_codigo]" value="" class="form-control" style="width:90px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][contrato_senha]" value="" class="form-control" style="width:90px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][max_declarado]" value="3000" class="form-control" style="width:80px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][min_declarado]" value="19" class="form-control" style="width:80px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][max_soma_lados]" value="200" class="form-control" style="width:50px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][min_soma_lados]" value="29" class="form-control" style="width:50px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][max_lado]" value="105" class="form-control" style="width:50px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][max_peso]" value="30" class="form-control" style="width:50px" /></td>';
	  
	  html += '  <td class="text-left">';
	  html += '  <select name="correios_servicos[' + servico_row  + '][mao_propria]" class="form-control" style="width:50px; padding:8px 0;">';
		html += '  <option value="0"><?php echo $text_no; ?></option>';	  
		html += '  <option value="1"><?php echo $text_yes; ?></option>';
	  html += '  </select>';					  
	  html += '  </td>';
	  html += '  <td class="text-left">';
	  html += '  <select name="correios_servicos[' + servico_row  + '][aviso_recebimento]" class="form-control" style="width:50px; padding:8px 0;">';
		html += '  <option value="0"><?php echo $text_no; ?></option>';	  
		html += '  <option value="1"><?php echo $text_yes; ?></option>';
	  html += '  </select>';					  
	  html += '  </td>';
	  html += '  <td class="text-left">';
	  html += '  <select name="correios_servicos[' + servico_row  + '][declarar_valor]" class="form-control" style="width:50px; padding:8px 0;">';
		html += '  <option value="0"><?php echo $text_no; ?></option>';
		html += '  <option value="1"><?php echo $text_yes; ?></option>';		
	  html += '  </select>';					  
	  html += '  </td>';					  
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][prazo_adicional]" value="" class="form-control" style="width:50px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][adicional]" value="" class="form-control" style="width:50px" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_servicos[' + servico_row  + '][adicional_manuseio]" value="79.00" class="form-control" style="width:50px" /></td>';
	  html += '  <td class="text-left">';
		  html += '  <select name="correios_servicos[' + servico_row  + '][operacao]" class="form-control" style="width:80px; padding:8px 0;">';
		  html += '  <option value="online"><?php echo $text_online; ?></option>';
		  html += '  <option value="offline"><?php echo $text_offline; ?></option>';
		  html += '  <option value="automatico"><?php echo $text_automatico; ?></option>';		  
		  html += '  </select>';								
	  html += '  </td>';
	  html += '  <td class="text-left"><button type="button" onclick="editTabela(' + servico_row  + ');" data-toggle="tooltip" title="<?php echo $button_tabela; ?>" class="btn btn-primary"><i class="fa fa-table"></i></button></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#servico-row' + servico_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#servicos tbody').append(html);
	
	servico_row++;
}

var faixa_row = <?php echo $faixa_row; ?>;

function addRestricao() {
	html  = '<tr id="faixa-row' + faixa_row + '">';
	  html += '  <td class="text-left"><input type="text" name="correios_faixas[' + faixa_row  + '][codigo]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_faixas[' + faixa_row  + '][cep_origem]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_faixas[' + faixa_row  + '][cep_inicial]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_faixas[' + faixa_row  + '][cep_final]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_faixas[' + faixa_row  + '][descricao]" value="" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#faixa-row' + faixa_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#restricoes tbody').append(html);
	
	faixa_row++;
}

var estado_row = <?php echo $estado_row; ?>;

function addEstado() {
	html  = '<tr id="estado-row' + estado_row + '">';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_estados[' + estado_row  + '][codigo]" value="" class="form-control" /></td>';
	  html += '    <td class="text-left estados-gratis"><p><strong><?php echo $entry_estado; ?></strong> | <strong><?php echo $text_total; ?></strong></p>';
	  html += '  	  <div class="well well-sm">';
						<?php foreach($zones as $zone) {?>
	  html += '  		<div class="checkbox">';
	  html += '  		  <label>';
	  html += '  			<input type="checkbox" name="correios_gratis_estados[' + estado_row  + '][estados_gratis][]" value="<?php echo $zone['zone_id']; ?>" /> <span><?php echo $zone['code']; ?></span>';
	  html += '  			 | <input type="text" name="correios_gratis_estados[' + estado_row  + '][total_minimo][<?php echo $zone['zone_id']; ?>]" value="" class="form-control" />';
	  html += '  		  </label>';
	  html += '  		</div>';
						<?php } ?>
	  html += '  	  </div>';
	  html += '  	  <a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', true);"><?php echo $text_todos; ?></a> / <a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', false);"><?php echo $text_nenhum; ?></a>';				  
	  html += '    </td>';	
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_estados[' + estado_row  + '][descricao]" value="" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#estado-row' + estado_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#estados tbody').append(html);
	
	estado_row++;
}

var produto_row = <?php echo $produto_row; ?>;

function addProduto() {
	html  = '<tr id="produto-row' + produto_row + '">';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_produtos[' + produto_row  + '][codigo]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left">';
		html += '    <input type="text" name="category' + produto_row  + '" value="" placeholder="<?php echo $text_autocomplete; ?>" class="categorycomplete form-control" />';
		html += '    <div id="product-category-box' + produto_row  + '" class="well well-sm" style="height: 100px; overflow: auto;">'; 
		html += '    </div>';							  
	  html += '  </td>';
	  html += '  <td class="text-left">';
		html += '    <input type="text" name="manufacturer' + produto_row  + '" value="" placeholder="<?php echo $text_autocomplete; ?>" class="manufacturercomplete form-control" />';
		html += '    <div id="product-manufacturer-box' + produto_row  + '" class="well well-sm" style="height: 100px; overflow: auto;">'; 
		html += '    </div>';							  
	  html += '  </td>';	
	  html += '  <td class="text-left">';
		html += '    <input type="text" name="product' + produto_row  + '" value="" placeholder="<?php echo $text_autocomplete; ?>" class="productcomplete form-control" />';
		html += '    <div id="product-product-box' + produto_row  + '" class="well well-sm" style="height: 100px; overflow: auto;">'; 
		html += '    </div>';							  
	  html += '  </td>';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_produtos[' + produto_row  + '][total_minimo]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_produtos[' + produto_row  + '][descricao]" value="" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#produto-row' + produto_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#produtos tbody').append(html);
	
	categoryautocomplete(produto_row);
	manufacturerautocomplete(produto_row);
	productautocomplete(produto_row);
	
	$('#product-category-box' + produto_row).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});	

	$('#product-manufacturer-box' + produto_row).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});	

	$('#product-product-box' + produto_row).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});		
	
	produto_row++;
}

var faixa_cep_row = <?php echo $faixa_cep_row; ?>;

function addFaixaCep() {
	html  = '<tr id="faixa-cep-row' + faixa_cep_row + '">';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_cep[' + faixa_cep_row  + '][codigo]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_cep[' + faixa_cep_row  + '][cep_origem]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_cep[' + faixa_cep_row  + '][cep_inicial]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_cep[' + faixa_cep_row  + '][cep_final]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_cep[' + faixa_cep_row  + '][total_minimo]" value="" class="form-control" /></td>';
	  html += '  <td class="text-left"><input type="text" name="correios_gratis_cep[' + faixa_cep_row  + '][descricao]" value="" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#faixa-cep-row' + faixa_cep_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#faixa-cep tbody').append(html);
	
	faixa_cep_row++;
}

function categoryautocomplete(attribute_row) {
	$('input[name="category' + attribute_row + '"]').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name="category' + attribute_row + '"]').val('');

			$('#product-category' + attribute_row + '-' + item['value']).remove();

			$('#product-category-box' + attribute_row).append('<div id="product-category' + attribute_row + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="correios_gratis_produtos[' + attribute_row + '][categorias][]" value="' + item['value'] + '" /></div>');
		}
	});
}

$('.categorycomplete').each(function(index, element) {
	categoryautocomplete(index);
	
	$('#product-category-box' + index).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});	
});

function manufacturerautocomplete(attribute_row) {
	$('input[name="manufacturer' + attribute_row + '"]').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name="manufacturer' + attribute_row + '"]').val('');

			$('#product-manufacturer' + attribute_row + '-' + item['value']).remove();

			$('#product-manufacturer-box' + attribute_row).append('<div id="product-manufacturer' + attribute_row + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="correios_gratis_produtos[' + attribute_row + '][marcas][]" value="' + item['value'] + '" /></div>');
		}
	});
}

$('.manufacturercomplete').each(function(index, element) {
	manufacturerautocomplete(index);
	
	$('#product-manufacturer-box' + index).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});	
});

function productautocomplete(attribute_row) {
	$('input[name="product' + attribute_row + '"]').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['product_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name="product' + attribute_row + '"]').val('');

			$('#product-product' + attribute_row + '-' + item['value']).remove();

			$('#product-product-box' + attribute_row).append('<div id="product-product' + attribute_row + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="correios_gratis_produtos[' + attribute_row + '][produtos][]" value="' + item['value'] + '" /></div>');
		}
	});
}

$('.productcomplete').each(function(index, element) {
	productautocomplete(index);
	
	$('#product-product-box' + index).delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});	
});

function editTabela(row) {
	var codigo = $('input[name="correios_servicos[' + row + '][codigo]"]').val();
	var nome = $('input[name="correios_servicos[' + row + '][nome]"]').val();
	var postcode = $('input[name="correios_servicos[' + row + '][postcode]"]').val();
	
	if(!codigo || !nome || !postcode) {
		alert("Os campos obrigatórios Código, Nome e CEP origem devem ser preenchidos antes de configurar a tabela offline.");
	} else {
		// salvando as configurações antes de abrir a tabela offline para edição
		$.ajax({
			url: 'index.php?route=shipping/correios&codigo=' + codigo + '&token=<?php echo $token; ?>',
			type: 'post',
			data: $("#form-correios").serialize(),
			beforeSend: function() {
				$('#btn-edit-' + row).button('loading');
			},
			complete: function() {
				$('#btn-edit-' + row).button('reset');
			},
			success: function() {
				location = 'index.php?route=shipping/correios/tabela&codigo=' + codigo + '&token=<?php echo $token; ?>';
			}
		});		
	}
}

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

.estados-gratis a:hover {
	cursor: pointer;
}
</style>			

        <script type="text/javascript"><!--
        function save(type){
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'button';
            input.value = type;
            form = $("form[id^='form-']").append(input);
            form.submit();
        }
        //--></script>
            
<?php echo $footer; ?>
