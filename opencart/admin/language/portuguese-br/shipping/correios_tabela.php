<?php
// Heading
$_['heading_title']       			= 'Correios Pro';
$_['heading_title_tabela']       	= 'Tabela Offline - Correios Pro';

// Text
$_['text_edit']           			= 'Editar tabela offline dos Correios para o serviço %s';
$_['text_shipping']       			= 'Formas de Envio';
$_['text_extension']   	  			= 'Extensões';
$_['text_faixa_peso']         		= 'Faixa de peso (gr)';
$_['text_ix']         				= 'Capital - Interior * Interior - Capital * Interior - Interior';
$_['text_nx']         				= 'Capital - Capital';
$_['text_autocomplete']         	= 'Pesquisar';
$_['text_cep_local_inicio']    		= 'CEP inicial';
$_['text_cep_local_fim']       		= 'CEP final';

// Entry
$_['entry_max_peso_real']         	= 'Peso cúbico máximo sem tarifação cúbica (kg)';
$_['entry_ad_valorem']         		= 'Declarar valor (%)';
$_['entry_aviso_recebimento']		= 'Aviso de recebimento (R$)';
$_['entry_mao_propria']      		= 'Mão própria (R$)';
$_['entry_a_cobrar_vpne']         	= 'Pagamento na entrega (R$)';
$_['entry_taxa_emergencial']        = 'Cobrança emergencial para capial RJ (R$)';

$_['entry_peso_inicial']         	= 'Peso inicial';
$_['entry_peso_final']         		= 'Peso final';
$_['entry_adicional_kg']         	= 'Kg adicional:';
$_['entry_lx']         				= 'Local Lx';
$_['entry_ex']         				= 'Estadual Ex';
$_['entry_i1']         				= 'Grupo de Estados i1';
$_['entry_i2']         				= 'Grupo de Estados i2';
$_['entry_i3']         				= 'Grupo de Estados i3';
$_['entry_i4']         				= 'Grupo de Estados i4';
$_['entry_i5']         				= 'Grupo de Estados i5';
$_['entry_i6']         				= 'Grupo de Estados i6';
$_['entry_n1']         				= 'Grupo de Capitais n1';
$_['entry_n2']         				= 'Grupo de Capitais n2';
$_['entry_n3']         				= 'Grupo de Capitais n3';
$_['entry_n4']         				= 'Grupo de Capitais n4';
$_['entry_n5']         				= 'Grupo de Capitais n5';
$_['entry_n6']         				= 'Grupo de Capitais n6';

// Button
$_['button_add_faixa_peso']   		= 'Adicionar faixa de peso';
$_['button_add_faixa_cep_local']   	= 'Adicionar faixa de CEP';

// Help
$_['help_max_peso_real'] 			= 'Peso cúbico máximo em que a tarifação é feita exclusivamente pelo peso real. Em kg.';
$_['help_ad_valorem'] 				= 'Declarar Valor só será usado se estiver habilidado nas configurações do módulo. É a taxa Ad-Valorem. Em %.  Use o link [2] para consultar a taxa.';
$_['help_aviso_recebimento'] 		= 'Aviso de Recebimento só será usado se estiver habilidado nas configurações do módulo. Em Reais. Use o link [2] para consultar o valor.';
$_['help_mao_propria'] 				= 'Mão Própria só será usado se estiver habilidado nas configurações do módulo Em Reais. Use o link [2] para consultar o valor.';
$_['help_a_cobrar_vpne'] 			= 'Taxa para serviço a cobrar. Só será usado se o serviço dos Correios for a cobrar. Em Reais.';
$_['help_taxa_emergencial'] 		= 'Taxa aplicada para entregas realizadas na cidade do Rio de Janeiro. Em Reais.';
$_['help_faixa_peso'] 				= 'Clique no botão azul (+) para adicionar novas faixas de peso. Em gramas';
$_['help_ix'] 						= 'Para cada grupo *i*, digite o estado para pesquisar e adicioná-lo ao grupo.';
$_['help_nx'] 						= 'Você *NÃO* precisa preencher os grupos *n* se o CEP de origem *NÃO* é de uma Capital. Para cada grupo, digite o estado para pesquisar e adicioná-lo ao grupo.';
$_['help_lx'] 						= 'Entregas na mesma cidade do CEP de origem. Escolha uma coluna L (L1 até L4) da tabela dos Correios para sua região. Para identificarmos se um CEP de destino pertence a mesma cidade do CEP de origem é necessário indicar abaixo a(s) faixa(s) de CEP de sua cidade. Por exemplo, para cidade de Itajubá - MG, tem-se a faixa 37500001 até 37506999. Não precisa configurar faixas se o CEP de origem é de Capital. Use o link [1] para pesquisar a(s) faixa(s) de CEP de sua cidade.';
$_['help_ex'] 						= 'Entregas dentro do estado, ou seja, CEP de origem e CEP de destino pertencem ao mesmo estado. Escolha uma coluna E (E1 até E4) da tabela dos Correios para sua região.';

// Error
$_['error_permission']   			= 'Atenção: Você não possui permissão para modificar o módulo Correios!';
$_['error_required']      			= 'Atenção: Há campos obrigatórios vazios ou nenhum serviço foi adicionado!';

?>
