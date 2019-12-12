<?php
// Heading
$_['heading_title']       = 'Correios Pro';

// Text
$_['text_edit']           = 'Editar o frete pelos Correios';
$_['text_shipping']       = 'Formas de Envio';
$_['text_success']        = 'Módulo Correios atualizado com sucesso!';
$_['text_todos']          = 'Todos';
$_['text_nenhum']         = 'Nenhum';
$_['text_autocomplete']     = 'Digite para pesquisar';
$_['text_servicos']   		= 'Adicione/configure os serviços que irá usar e remove os que não serão usados.';
$_['text_restricoes']   	= 'O serviço adicionado não aparecerá para clientes que possuem CEP dentro da faixa. Por exemplo, se não deseja oferecer PAC para clientes de SP, coloque o código do PAC, CEP Inicial 01000000 e CEP Final 19999999. Você pode também adicionar várias faixas para o mesmo serviço. Muito útil para quem possui contrato com os Correios que restringe determinado serviço para uma determinada região/estado.';
$_['text_frete_gratis']     = 'Você pode configurar frete grátis por Estado, Faixa de CEP ou Produto. O frete grátis será aplicado para o primeiro que atender a condição configurada em Estado, Faixa de CEP ou Produto, ou seja, o sistema verifica se a compra do cliente atende uma condição de frete grátis Por Estado e, se positivo, aplica o frete grátis e ignora a verficação em Faixa de CEP ou Por Produto. Se negativo (não tem frete grátis Por Estado) o sistema verifica em Faixa de CEP e, por último, Por Produto.';
$_['text_gratis_estado']   	= 'Adicione o serviço e selecione os Estados que vai oferecer frete grátis. Coloque o total mínimo da compra (carrinho) para que o frete grátis seja habilitado para o Estado selecionado.';
$_['text_gratis_produto']   = 'Adicione o serviço e selecione os produtos que vai oferecer frete grátis. Você pode selecionar produtos por Categorias, Marcas ou adicionar produtos individualmente. Coloque o total mínimo da compra desses produtos para que o frete grátis seja habilitado, ou seja, o valor total mínimo desses produtos no carrinho para que o frete grátis seja habilitado. Se o carrinho tiver outros produtos que não foram selecionados para dar frete grátis, o frete será calculado somente para estes produtos.';
$_['text_total']         	= 'Total mínimo para frete grátis. Use ponto para separar centavos. Ex. 100.50';
$_['text_automatico']       = 'Automático';
$_['text_online']          	= 'Online';
$_['text_offline']          = 'Offline';
$_['text_faixa_ceps']   	= 'Adicione o serviço e a faixa de CEPs que vai oferecer frete grátis. Por exemplo, se deseja oferecer PAC grátis para clientes da cidade de Campinas-SP, coloque o código do PAC, CEP Inicial 13000-001 e CEP Final 13139-999. Você pode também adicionar várias faixas para o mesmo serviço.';

// Tab
$_['tab_servico']         	 	= 'Geral';
$_['tab_frete_gratis']          = 'Configuração de Frete Grátis';
$_['tab_restricao']         	= 'Limitação por Faixa de CEP';
$_['tab_gratis_estado']         = 'Por Estado';
$_['tab_gratis_produto']        = 'Por Produto';
$_['tab_gratis_cep']        	= 'Por faixa de CEP';

// Entry
$_['entry_codigo']         	 	= 'Código';
$_['entry_nome']         		= 'Nome';
$_['entry_a_cobrar']         	= 'A cobrar';
$_['entry_postcode']      		= 'CEP origem';
$_['entry_contrato_codigo']   	= 'Código ADM';
$_['entry_contrato_senha']    	= 'Senha';
$_['entry_msg_restricao']    	= 'Exibir avisos de restrição de entrega';

$_['entry_max_declarado']       = 'Max decla-rado';
$_['entry_min_declarado']       = 'Min decla-rado';

$_['entry_max_soma_lados']      = 'Max soma lados';
$_['entry_min_soma_lados']      = 'Min soma lados';
$_['entry_max_lado']      		= 'Lado Max';
$_['entry_max_peso']      		= 'Peso Max';

$_['entry_mao_propria']   		= 'Mão pró-pria';
$_['entry_aviso_recebimento']   = 'Aviso rec.';
$_['entry_declarar_valor']		= 'Decla-rar valor';

$_['entry_total']         	 	= 'Total mínimo para frete grátis.';
$_['entry_estados_gratis']      = 'Frete grátis para';													   
$_['entry_prazo_adicional']   	= 'Prazo adicio-nal';
$_['entry_adicional']   		= 'Valor adicio-nal (%)';
$_['entry_adicional_manuseio_especial'] = 'Adicional manuseio';
$_['entry_operacao']			= 'Modo';
$_['entry_tabela']				= 'Tabela';

$_['entry_status']        		= 'Situação';
$_['entry_tax_class']  	  		= 'Grupo de Impostos';
$_['entry_geo_zone']     		= 'Região Geográfica';
$_['entry_sort_order']    		= 'Ordem';

$_['entry_cep_inicial']    		= 'CEP inicial';
$_['entry_cep_final']    		= 'CEP final';
$_['entry_descricao']  			= 'Descrição';
$_['entry_estado']  			= 'Estado';
$_['entry_produto']  			= 'Produtos';
$_['entry_categoria']  			= 'Categorias';
$_['entry_marca']  				= 'Marcas';

// Button
$_['button_add_servico']   		= 'Adicionar serviço';
$_['button_add_restricao']   	= 'Adicionar faixa';
$_['button_tabela']   			= 'Configurar tabela offline';

// Help
$_['help_codigo'] 				= 'Código do serviço. Por exemplo, 04014 para Sedex sem contrato e 04510 para PAC sem contrato, etc';
$_['help_nome'] 				= 'Nome do Serviço. É o nome que irá aparecer para o cliente nas formas de frete disponíveis. Exemplo: Sedex, PAC, etc';
$_['help_a_cobrar'] 			= 'Habilite se o serviço é a cobrar. Atende que o código do serviço a cobrar é diferente do serviço comum. Por exemplo, o Código do Sedex a Cobrar é diferente do Sedex normal.';
$_['help_postcode'] 			= 'CEP de origem';
$_['help_contrato_codigo'] 		= 'Código administrativo do contrato com os Correios. Requerido somente para serviços com contrato com os Correios.';
$_['help_contrato_senha'] 		= 'Senha do contrato com os Correios. Requerido somente para serviços com contrato com os Correios.';
$_['help_msg_restricao']    	= 'Habilita a exibição de aviso para o cliente se o CEP de destino contém restrição de entrega dos Correios.';

$_['help_max_declarado'] 		= 'O Valor Declarado não deve exceder esse valor. É o limite máximo definido pelos Correios. Opção Declarar Valor deve estar habilitado.';
$_['help_min_declarado'] 		= 'O Valor Declarado não deve ser inferior a esse valor. É o mínimo definido pelos Correios. Opção Declarar Valor deve estar habilitado.';

$_['help_max_soma_lados'] 		= 'A soma resultante do comprimento + largura + altura não deve superar esse valor. É o limite máximo definido pelos Correios. Em cm';
$_['help_min_soma_lados'] 		= 'A soma resultante do comprimento + largura + altura não deve ser inferior a esse valor. É o mínimo definido pelos Correios. Em cm. Se a soma do pacote for inferior, este limite será usado.';
$_['help_max_lado'] 			= 'O comprimento ou largura ou altura do pacote não deve superar esse valor. É o maior lado do pacote definido pelos Correios. Em cm';
$_['help_max_peso'] 			= 'O peso do pacote não deve superar esse valor. É o maior peso definido pelos Correios. Em Kg';

$_['help_mao_propria'] 			= 'É o serviço opcional pelo qual o remetente recebe a garantia de que o objeto, por ele postado sob registro, será entregue somente ao próprio destinatário, através da confirmação de sua identidade.';
$_['help_aviso_recebimento'] 	= 'Aviso de Recebimento. É o serviço adicional que, através do preenchimento de formulário próprio, físico ou digital, permite comprovar, junto ao remetente, a entrega do objeto.';
$_['help_declarar_valor'] 		= 'É o serviço adicional pelo qual o cliente declara o valor de um objeto postado sob registro, para fins de ressarcimento, em caso de extravio ou espoliação. Alguns serviços exigem sua habilitação, como Sedex a Cobrar e PAC a Cobrar.';

$_['help_total'] 				= 'Total da compra (carrinho) para habilitar o frete grátis para esse serviço. Use ponto como separador de centavos. Exemplo: 500.50';
$_['help_estados_gratis'] 		= 'Selecione os estados que terão frete grátis e ao lado de cada o total mínimo da compra para habilitá-lo';
$_['help_prazo_adicional'] 		= 'Prazo de entrega adicional (em dias) somado ao prazo dos Correios.';
$_['help_adicional'] 			= 'Adicional somado ao valor final do frete. Em %';
$_['help_adicional_manuseio_especial'] = 'Cobrança adicional dos Correios para encomenda com uma dimensão superior a 70 cm. (em Reais)';
$_['help_operacao'] 			= 'Online: os valores do frete serão obtidos pelo Webservice dos Correios. Offline: os valores de frete serão obtidos pela tabela offline (se configurada). Automático: usa a tabela offline (se configurada) quando o sistema detectar que o Webservice está fora do ar.';
$_['help_tabela'] 				= 'Clique para configurar a tabela offline para esse serviço.';

$_['help_cep_inicial'] 			= 'O CEP inicial deve ser menor que o CEP final';
$_['help_cep_final'] 			= 'O CEP final deve ser maior que o CEP inicial';

// Error
$_['error_permission']    = 'Atenção: Você não possui permissão para modificar o módulo Correios!';
$_['error_required']      = 'Atenção: Há campos obrigatórios vazios ou nenhum serviço foi adicionado!';
?>
