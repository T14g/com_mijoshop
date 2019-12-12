<?php
class ModelShippingCorreios extends Model {
	
	private $peso_min = 0.3;// em kg
	private $comp_min = 16;	// em cm
	private $larg_min = 11; // em cm
	private $altu_min = 2; 	// em cm
	private $manuseio_especial = 70; // em cm
	
	private $url = '';
	
	private $quote_data = array();
	
	private $cep_destino;
	private $cep_origem;
	
	private $max_peso;
	private $max_cubagem;
	private $min_cubagem;
	private $max_lado;
	
	private $codigo;
	
	private $contrato_codigo = '';
	private $contrato_senha = '';

	private $mensagem_erro = array();
	
	private $total_compra;

	private $zone_id;
	
	private $mao_propria;
	private $aviso_recebimento;
	
	// função responsável pelo retorno à loja dos valores finais dos valores dos fretes
	public function getQuote($address) {
		
		$this->load->language('shipping/correios');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('correios_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!$this->config->get('correios_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}		
		
		$method_data = array();

		$produtos = $this->removeProdutosSemFrete($this->cart->getProducts());
		
		$correios_servicos = $this->config->get('correios_servicos');
		$correios_restricoes = $this->config->get('correios_faixas');
		
		$faixas = array();
		
		if(!empty($correios_restricoes)) {
			foreach($correios_restricoes as $faixa_info) {
				
				$codigo_servico = trim($faixa_info['codigo']);
				
				$faixas[$codigo_servico][] = array(
					'cep_origem'=> $faixa_info['cep_origem'],
					'inicio' 	=> $faixa_info['cep_inicial'],
					'fim' 	 	=> $faixa_info['cep_final']
				);
			}
		}
		
		// faixas de ceps para frete grátis
		$correios_faixas_cep_gratis = $this->config->get('correios_gratis_cep');
		
		$faixas_cep_gratis = array();
		
		if(!empty($correios_faixas_cep_gratis)) {
			foreach($correios_faixas_cep_gratis as $faixa_info) {
				
				$codigo_servico = trim($faixa_info['codigo']);
				
				$faixas_cep_gratis[$codigo_servico][] = array(
					'cep_origem' 	=> $faixa_info['cep_origem'],
					'inicio' 		=> $faixa_info['cep_inicial'],
					'fim' 	 		=> $faixa_info['cep_final'],
					'total_minimo' 	=> $faixa_info['total_minimo']
				);
			}
		}		

		if ($status && !empty($correios_servicos) && !empty($produtos)) {
			
			$this->cep_destino = preg_replace ("/[^0-9]/", '', $address['postcode']);
			$this->total_compra = $this->cart->getSubTotal();
			
			if((int)$address['zone_id']) {
				$this->zone_id = (int)$address['zone_id'];
			} else {
				$this->zone_id = $this->getZoneIdByPostcode($address['postcode']);
			}
			
			foreach($correios_servicos as $servico_info){
				
				$tem_restricao = false;
				$codigo_servico = trim($servico_info['codigo']);
				$this->cep_origem = preg_replace ("/[^0-9]/", '', $servico_info['postcode']);
				
				if(!empty($faixas) && isset($faixas[$codigo_servico])) {
					foreach($faixas[$codigo_servico] as $faixa_info) {
						
						$cep_origem_faixa = preg_replace ("/[^0-9]/", '', $faixa_info['cep_origem']);
						$cep_inicio = preg_replace ("/[^0-9]/", '', $faixa_info['inicio']);
						$cep_fim = preg_replace ("/[^0-9]/", '', $faixa_info['fim']);
						
						if($cep_origem_faixa == $this->cep_origem && (int)$this->cep_destino >= (int)$cep_inicio && (int)$this->cep_destino <= (int)$cep_fim) {
							$tem_restricao = true;
							break;
						}
					}
				}
				
				if(!$tem_restricao) {
					$this->contrato_codigo = $servico_info['contrato_codigo'];
					$this->contrato_senha = $servico_info['contrato_senha'];				
					$this->max_peso = $servico_info['max_peso'];
					$this->max_cubagem = pow(($servico_info['max_soma_lados'] / 3), 3);
					$this->min_cubagem = pow(($servico_info['min_soma_lados'] / 3), 3);
					$this->max_lado = $servico_info['max_lado'];
					$this->codigo = $servico_info['codigo'];
					
					// verificando se tem frete grátis por estado
					$tem_frete_gratis_estado = $this->temFreteGratisEstado($servico_info);
					
					if ($tem_frete_gratis_estado) {
						$prazo_adicional = (is_numeric($servico_info['prazo_adicional'])) ? $servico_info['prazo_adicional'] : 0 ;
						
						if($servico_info['operacao'] != 'offline') {
							$prazo = $this->getPrazo($this->codigo, $this->cep_origem, $this->cep_destino);
						}
						
						if (!empty($prazo)) {
							$title = $servico_info['nome'] . ' - ' . sprintf($this->language->get('text_prazo_estimado'), ($prazo['PrazoEntrega'] + $prazo_adicional));
						} else {
							$title = $servico_info['nome'];
						}
						
						if($this->config->get('correios_msg_restricao') && !empty($prazo) && !empty($prazo['MsgErro'])) {
							$title .= ' (<span style="color: red;">' . $prazo['MsgErro'] . '</span>)';
						}
						
						$this->quote_data[$this->codigo] = array(
							'code'         => 'correios.' . $this->codigo,
							'title'        => $title,
							'cost'         => 0,
							'tax_class_id' => $this->config->get('correios_tax_class_id'),
							'text'         => $this->language->get('text_free')
						);	
					} else if ($this->temFreteGratisCep($servico_info, $faixas_cep_gratis)) {
						$prazo_adicional = (is_numeric($servico_info['prazo_adicional'])) ? $servico_info['prazo_adicional'] : 0 ;
						
						if($servico_info['operacao'] != 'offline') {
							$prazo = $this->getPrazo($this->codigo, $this->cep_origem, $this->cep_destino);
						}
						
						if (!empty($prazo)) {
							$title = $servico_info['nome'] . ' - ' . sprintf($this->language->get('text_prazo_estimado'), ($prazo['PrazoEntrega'] + $prazo_adicional));
						} else {
							$title = $servico_info['nome'];
						}
						
						if($this->config->get('correios_msg_restricao') && !empty($prazo) && !empty($prazo['MsgErro'])) {
							$title .= ' (<span style="color: red;">' . $prazo['MsgErro'] . '</span>)';
						}						
						
						$this->quote_data[$this->codigo] = array(
							'code'         => 'correios.' . $this->codigo,
							'title'        => $title,
							'cost'         => 0,
							'tax_class_id' => $this->config->get('correios_tax_class_id'),
							'text'         => $this->language->get('text_free')
						);							
					} else {
						// processa os produtos para saber quais tem frete grátis
						$dados = $this->processaProdutosFreteGratis($produtos, $servico_info);
						
						$produtos_restantes = $dados['produtos'];
						$valor_total_produtos_removidos = $dados['total'];
						$total_minimo = $dados['total_minimo'];
						
						// Todos os produtos do carrinho tem frete grátis para esse serviço
						if (empty($produtos_restantes) && $valor_total_produtos_removidos >= $total_minimo) {
							$prazo_adicional = (is_numeric($servico_info['prazo_adicional'])) ? $servico_info['prazo_adicional'] : 0 ;
							
							if($servico_info['operacao'] != 'offline') {
								$prazo = $this->getPrazo($this->codigo, $this->cep_origem, $this->cep_destino);
							}
							
							if (!empty($prazo)) {
								$title = $servico_info['nome'] . ' - ' . sprintf($this->language->get('text_prazo_estimado'), ($prazo['PrazoEntrega'] + $prazo_adicional));
							} else {
								$title = $servico_info['nome'];
							}							
							
							if($this->config->get('correios_msg_restricao') && !empty($prazo) && !empty($prazo['MsgErro'])) {
								$title .= ' (<span style="color: red;">' . $prazo['MsgErro'] . '</span>)';
							}
							
							$this->quote_data[$this->codigo] = array(
								'code'         => 'correios.' . $this->codigo,
								'title'        => $title,
								'cost'         => 0,
								'tax_class_id' => $this->config->get('correios_tax_class_id'),
								'text'         => $this->language->get('text_free')
							);
						// O Carrinho tem o total de produtos para dar frete grátis e somente calcula o frete dos que não tem
						} else if (!empty($produtos_restantes) && $valor_total_produtos_removidos > 0 && $valor_total_produtos_removidos >= $total_minimo) {
							$caixas = $this->organizarEmCaixas($produtos_restantes);
							
							foreach ($caixas as $caixa) {
								$this->setQuoteData($caixa, $servico_info);
							}
						// Não tem produtos grátis ou não atingiu o total mínimo
						} else {
							$caixas = $this->organizarEmCaixas($produtos);
							
							// descomente a linha abaixo para visualizar em arquivos as caixas
							// file_put_contents('filename' . $servico_info['codigo'] . '.txt', print_r($caixas, true));
							
							foreach ($caixas as $caixa) {
								$this->setQuoteData($caixa, $servico_info);
							}							
						}
					}
				}
			}
			
			// ajustes finais
			if ($this->quote_data) {
				$method_data = array(
					'code'       => 'correios',
					'title'      => $this->language->get('text_title'),
					'quote'      => $this->quote_data,
					'sort_order' => $this->config->get('correios_sort_order'),
					'error'      => false
				);
			}
			else if(!empty($this->mensagem_erro)){
				$method_data = array(
					'code'       => 'correios',
					'title'      => $this->language->get('text_title'),
					'quote'      => $this->quote_data,
					'sort_order' => $this->config->get('correios_sort_order'),
					'error'      => implode('<br />', $this->mensagem_erro)
				);				
			}			
		}
		return $method_data;
	}
	
	// obtém os dados dos fretes para os produtos da caixa
	private function setQuoteData($caixa, $servico_info){

		// obtém o valor total da caixa
		$total_caixa = $this->getTotalCaixa($caixa['produtos']);
		
		if ($total_caixa < (float)$servico_info['min_declarado']) {
			$total_caixa =  $servico_info['min_declarado'];
		}
		
		if ($total_caixa > (float)$servico_info['max_declarado']) {
			$total_caixa = $servico_info['max_declarado'];
		} 
		
		// obtém preço do frete pela tabela offline ou online
		if($servico_info['operacao'] == 'offline') {
			$servicos = $this->getServicosOffline($servico_info, $total_caixa, $caixa['peso'], $caixa['cubagem']);
		} else if ($servico_info['operacao'] == 'automatico' || $servico_info['operacao'] == 'online') {
			$servicos = $this->getServicos($servico_info, $total_caixa, $caixa['peso'], $caixa['cubagem']);
		}
		
		foreach ($servicos as $servico) {

			//Roberto Frete Grátis se o produto tiver peso zero
				
			$frete_gratis = ($caixa['peso'] <= 0) ? true : false;
			// o site dos Correios retornou os dados sem erros.
			if($servico['Valor'] > 0) {
				
				// serviço a cobrar não soma o frete ao pedido.
				if((int)$servico_info['a_cobrar'] || $frete_gratis) {
					$cost = 0;
				} else {
					$adicional = (is_numeric($servico_info['adicional'])) ? $servico_info['adicional'] : 0;
					$cost = $servico['Valor'] + ($servico['Valor'] * ($adicional / 100));
					
					if($caixa['adicional_manuseio'] && is_numeric($servico_info['adicional_manuseio'])) {
						$cost += $servico_info['adicional_manuseio'];
					}						
				}
				
				// o valor do frete para a caixa atual é somado ao valor total já calculado para outras caixas 
				if (isset($this->quote_data[$servico['Codigo']])) {
					$cost += $this->quote_data[$servico['Codigo']]['cost'];
				}

				// obtendo o prazo adicional a ser somado com o dos Correios
				$prazo_adicional = (is_numeric($servico_info['prazo_adicional'])) ? $servico_info['prazo_adicional'] : 0 ;						

				if($servico['PrazoEntrega'] == 'indefinido') {
					$title = $servico_info['nome'];
				} else {
					$title = $servico_info['nome'] . ' - ' . sprintf($this->language->get('text_prazo_estimado'), ($servico['PrazoEntrega'] + $prazo_adicional));
				}
				
				if($this->config->get('correios_msg_restricao') && !empty($servico['MsgErro'])) {
					$title .= ' (<span style="color: red;">' . $servico['MsgErro'] . '</span>)';
				}
				
				if (version_compare(VERSION, '2.2') < 0) {
					$text = $this->currency->format($this->tax->calculate($cost, $this->config->get('correios_tax_class_id'), $this->config->get('config_tax')));
				} else {
					$text = $this->currency->format($this->tax->calculate($cost, $this->config->get('correios_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']);
				}
				
				$this->quote_data[$servico['Codigo']] = array(
					'code'         => 'correios.' . $servico['Codigo'],
					'title'        => $title,
					'cost'         => $cost,
					'tax_class_id' => $this->config->get('correios_tax_class_id'),
					'text'         => $text
				);
			}
			// grava no log de erros do OpenCart a mensagem de erro retornado pelos Correios
			else{
				$this->mensagem_erro[] = $servico_info['nome'] . ': ' . $servico['MsgErro'];
				$this->log->write($servico_info['nome'] . ': ' . $servico['MsgErro']);
			}
		}
	}
	
	// prepara a url de chamada ao site dos Correios
	private function setUrl($peso, $valor, $medida_lados){
		
		
//substitua a url correios pela correios offline
$regra = $this->config->get('correios_offline5_regra');
$status_off = $this->config->get('correios_offline5_status');
if($status_off==0){
$url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
}else{
$url = HTTPS_SERVER.'index.php?route=module/correios_offline5/calcular&';
}

		//$url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?"; // url alternativa disponibilizada pelos Correios.
		$url .=	"nCdEmpresa=" . $this->contrato_codigo;
		$url .=	"&sDsSenha=" . $this->contrato_senha;
		$url .=	"&sCepOrigem=%s";
		$url .=	"&sCepDestino=%s";
		$url .=	"&nVlPeso=%s";
		$url .=	"&nCdFormato=1";
		$url .=	"&nVlComprimento=%s";
		$url .=	"&nVlLargura=%s";
		$url .=	"&nVlAltura=%s";
		$url .=	"&sCdMaoPropria=" . $this->mao_propria;
		$url .=	"&nVlValorDeclarado=%s";
		$url .=	"&sCdAvisoRecebimento=" . $this->aviso_recebimento;
		$url .=	"&nCdServico=" . $this->codigo;
		$url .=	"&nVlDiametro=0";
		$url .=	"&StrRetorno=xml";
		
		$comp = ($medida_lados < $this->comp_min) ? $this->comp_min : $medida_lados;
		$larg = ($medida_lados < $this->larg_min) ? $this->larg_min : $medida_lados;
		$altu = ($medida_lados < $this->altu_min) ? $this->altu_min : $medida_lados;
		
		$this->url = sprintf($url, $this->cep_origem, $this->cep_destino, $peso, $comp, $larg, $altu, $valor);
	}
	
	// conecta ao sites dos Correios e obtém o arquivo XML com os dados do frete
	private function getXML($url){
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);			
		
		$result = curl_exec($ch);
		
		if(!$result){
			$error_msg = curl_error($ch);
			
			if($error_msg) {
				$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $error_msg);
				$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('error_conexao'));
			} else {
				$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('error_conexao'));
			}
			
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			
			$result = curl_exec($ch);
			
			if($result){
				$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('text_sucesso'));
			}
			else{
				$error_msg = curl_error($ch);
				
				if($error_msg) {
					$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $error_msg);
					$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('error_reconexao'));
				} else {
					$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('error_reconexao'));
				}
				
				$this->mensagem_erro[] = "Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('error_reconexao');				
			}
		}
		
		curl_close($ch);
	
		return $result;
	}
	
	// faz a chamada e lê os dados no arquivo XML retornado pelos Correios 
	public function getServicos($servico_info, $total_caixa, $peso, $cubagem){

		$dados = array();
		
		// troca o separador decimal de ponto para vírgula nos dados a serem enviados para os Correios
		$peso_tmp = ($peso >= $this->peso_min) ? $peso : $this->peso_min;
		$peso_tmp = str_replace('.', ',', $peso_tmp);
		
		// total é arredondado pois algumas vezes o WebService dos Correios não aceita centavos
		// $valor = round($total_caixa);
		
		// medida dos lados da caixa é a raiz cúbica da cubagem
		$medida_lados = ($cubagem >= $this->min_cubagem) ? $this->raizCubica($cubagem) : $this->raizCubica($this->min_cubagem);
		$medida_lados = str_replace('.', ',', $medida_lados);
		
		$this->mao_propria = ($servico_info['mao_propria']) ? 's' : 'n';
		$this->aviso_recebimento = ($servico_info['aviso_recebimento']) ? 's' : 'n';
		$valor = ($servico_info['declarar_valor']) ? round($total_caixa) : 0;
		
		// ajusta a url de chamada
		$this->setUrl($peso_tmp, $valor, $medida_lados);
		
		// habilite pra ver no log de erros a url com todos os parâmetros enviados para os Correios.
		// $this->log->write($this->url);		

		// faz a chamada e retorna o xml com os dados
		$xml = $this->getXML($this->url);

		// lendo o xml
		if ($xml) {
			$dom = new DOMDocument('1.0', 'ISO-8859-1');
			$result = $dom->loadXml($xml);
			
			if($result) {
				$servicos = $dom->getElementsByTagName('cServico');
				
				if ($servicos) {
					foreach ($servicos as $servico) {
						$codigo = $servico->getElementsByTagName('Codigo')->item(0)->nodeValue;
						
						$dados[$codigo] = array(
							"Codigo" => $codigo,
							"Valor" => str_replace(',', '.', $servico->getElementsByTagName('Valor')->item(0)->nodeValue),
							"PrazoEntrega" => ($servico->getElementsByTagName('PrazoEntrega')->item(0)->nodeValue),
							"Erro" => $servico->getElementsByTagName('Erro')->item(0)->nodeValue,
							"MsgErro" => $servico->getElementsByTagName('MsgErro')->item(0)->nodeValue,
							"ValorMaoPropria" => (isset($servico->getElementsByTagName('ValorMaoPropria')->item(0)->nodeValue)) ? str_replace(',', '.', $servico->getElementsByTagName('ValorMaoPropria')->item(0)->nodeValue) : 0,
							"ValorAvisoRecebimento" => (isset($servico->getElementsByTagName('ValorAvisoRecebimento')->item(0)->nodeValue)) ? str_replace(',', '.', $servico->getElementsByTagName('ValorAvisoRecebimento')->item(0)->nodeValue) : 0,
							"ValorValorDeclarado" => (isset($servico->getElementsByTagName('ValorValorDeclarado')->item(0)->nodeValue)) ? str_replace(',', '.', $servico->getElementsByTagName('ValorValorDeclarado')->item(0)->nodeValue) : 0
						);
					}
				}
			} else {
				$xml = false;
			}
		}
		
		if (!$xml && $servico_info['operacao'] == 'automatico') {
			$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('error_webservice'));
			
			$dados = $this->getServicosOffline($servico_info, $total_caixa, $peso, $cubagem);
			
			if(empty($dados)) {
				$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('error_tabela_offline'));
			}
		}
		
		return $dados;
	}
	
	// Obtem o preço da tabela offline
	public function getServicosOffline($servico_info, $total_caixa, $peso, $cubagem) {

		$faixas_cep_local_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "correios_faixa_cep_local WHERE codigo = '" . $this->db->escape($this->codigo) . "' ORDER BY cep_inicio ASC");
		
		$cep_destino_local = false;
		
		foreach($faixas_cep_local_query->rows as $faixa_cep_local){
			
			if((int)$this->cep_destino >= (int)$faixa_cep_local['cep_inicio'] && (int)$this->cep_destino <= (int)$faixa_cep_local['cep_fim']) {
				$cep_destino_local = true;
				
				break;
			}
		}		
		
		$origem_zone_id = $this->getZoneIdByPostcode($this->cep_origem);
		$destino_zone_id = $this->zone_id;	

		$origem_capital_zone_id = $this->getZoneIdCapital($this->cep_origem);
		$destino_capital_zone_id = $this->getZoneIdCapital($this->cep_destino);		
		
		$regiao = '';
		
		// Local cidade e Capital
		if ($cep_destino_local || ($origem_capital_zone_id && $destino_capital_zone_id && ($origem_capital_zone_id == $destino_capital_zone_id))) {
			$regiao = 'lx';
		// Cidades do mesmo estado - simplificado pois não inclui trechos de divisa conforme tabela Precificação de Divisa
		} else if ($origem_zone_id == $destino_zone_id) {
			$regiao = 'ex';			
		// Trecho entre capitais - não inclui cidades A+, conforme tabelas Precificação de Capital e Matriz de Origem-Destino
		} else if ($origem_capital_zone_id && $destino_capital_zone_id && ($origem_capital_zone_id != $destino_capital_zone_id)) {
			$tabela_regiao_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "correios_tabela_regiao WHERE codigo = '" . $this->db->escape($this->codigo) . "' AND zone_id = '" . (int)$this->zone_id . "' AND regiao LIKE 'n_'");
			
			if ($tabela_regiao_query->num_rows) {
				$regiao = $tabela_regiao_query->row['regiao'];
			}			

		// Demais trechos interestaduais
		} else {
			$tabela_regiao_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "correios_tabela_regiao WHERE codigo = '" . $this->db->escape($this->codigo) . "' AND zone_id = '" . (int)$this->zone_id . "' AND regiao LIKE 'i_'");
			
			if ($tabela_regiao_query->num_rows) {
				$regiao = $tabela_regiao_query->row['regiao'];
			}			
		}
		
		$dados = array();
		
		$servico_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "correios_servico WHERE codigo = '" . $this->db->escape($this->codigo) . "'");	
		
		if($regiao && $servico_query->num_rows) {
			
			$this->load->model('localisation/zone');
			
			$zone_info = $this->model_localisation_zone->getZone($this->zone_id);
			
			$peso_real = ($peso >= $this->peso_min) ? ceil($peso) : $this->peso_min;
			$peso_cubico = ($cubagem >= $this->min_cubagem) ? ceil($cubagem / 6000) : ceil($this->min_cubagem / 6000);
			$max_peso_real = (float)$servico_query->row['max_peso_real'];
			$declarar_valor = (int)$servico_info['declarar_valor'] ? ($total_caixa * (float)$servico_query->row['ad_valorem'] / 100) : 0;
			$aviso_recebimento = (int)$servico_info['aviso_recebimento'] ? (float)$servico_query->row['aviso_recebimento'] : 0;
			$mao_propria = (int)$servico_info['mao_propria'] ? (float)$servico_query->row['mao_propria'] : 0;
			$taxa_a_cobrar = (int)$servico_info['a_cobrar'] ? (float)$servico_query->row['a_cobrar_vpne'] : 0;
			$taxa_emergencial = ($destino_capital_zone_id && $zone_info['code'] == 'RJ') ? (float)$servico_query->row['taxa_emergencial'] : 0;
			$adicional_kg = (float)$servico_query->row[$regiao . '_adicional_kg'];
			
			$peso_considerado = $peso_real;
		
			if($peso_cubico > $max_peso_real) {
				$peso_considerado = $peso_cubico;
			}
	
			$valor_excedente = 0;
			
			if($peso_considerado > $max_peso_real) {
				$valor_excedente = ($peso_considerado - $max_peso_real) * $adicional_kg;
			}
			
			$tabela_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "correios_tabela WHERE codigo = '" . $this->db->escape($this->codigo) . "' ORDER BY peso_inicial ASC");
			
			// se o peso considerado for maior que a maior faixa de peso cadastrada, usa-se o maior peso cadastrado como peso considerado pois já foi calculado no valor excedente
			$maior_peso_query = $this->db->query("SELECT MAX(peso_final) as maior_peso FROM " . DB_PREFIX . "correios_tabela WHERE codigo = '" . $this->db->escape($this->codigo) . "'");
			
			$maior_peso_cadastrado = $max_peso_real;
			
			if($maior_peso_query->rows) {
				$maior_peso_cadastrado = (float)$maior_peso_query->row['maior_peso'] / 1000;
			}
			
			if($peso_considerado > $maior_peso_cadastrado) {
				$peso_considerado = $maior_peso_cadastrado;
			}
			// fim
			
			$valor_frete = 0;
			
			foreach($tabela_query->rows as $tabela_info) {
				$peso_inicial = (float)$tabela_info['peso_inicial'] / 1000;
				$peso_final = (float)$tabela_info['peso_final'] / 1000;
				
				if($peso_considerado >= $peso_inicial && $peso_considerado <= $peso_final) {
					
					$valor_frete = $tabela_info[$regiao];
					break;
				}
			}

			if ($valor_frete) {
			
				$dados[$this->codigo] = array(
					"Codigo" => $this->codigo,
					"Valor" => $valor_frete + $valor_excedente + $declarar_valor + $aviso_recebimento + $mao_propria + $taxa_a_cobrar + $taxa_emergencial,
					"PrazoEntrega" => 'indefinido',
					"Erro" => 0,
					"MsgErro" => '',
					"ValorMaoPropria" => $mao_propria,
					"ValorAvisoRecebimento" => $aviso_recebimento,
					"ValorValorDeclarado" => $declarar_valor
				);
			} else {
				$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('error_tabela_offline'));
			}
		} else {
			$this->log->write("Serviço dos Correios [". $this->codigo ."] - " . $this->language->get('error_tabela_offline'));
		}
		return $dados;
	}	

	// retorna a dimensão em centímetros
	private function getDimensaoEmCm($unidade_id, $dimensao){
		
		if(is_numeric($dimensao)){
			$length_class_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class mc LEFT JOIN " . DB_PREFIX . "length_class_description mcd ON (mc.length_class_id = mcd.length_class_id) WHERE mcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND mc.length_class_id =  '" . (int)$unidade_id . "'");
			
			if(isset($length_class_product_query->row['unit'])){
				if($length_class_product_query->row['unit'] == 'mm'){
					return $dimensao / 10;
				}		
			}
		}
		return $dimensao;
	}
	
	// retorna o peso em quilogramas
	private function getPesoEmKg($unidade_id, $peso){
		
		if(is_numeric($peso)) {
			$weight_class_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class wc LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) WHERE wcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND wc.weight_class_id =  '" . (int)$unidade_id . "'");
			
			if(isset($weight_class_product_query->row['unit'])){
				if($weight_class_product_query->row['unit'] == 'g'){
					return ($peso / 1000);
				}		
			}
		}
		return $peso;
	}	
	
	// pré-validação das dimensões e peso do produto 
  	private function validar($produto){
		
		if (empty($produto['height'])) { $produto['height'] = $this->altu_min; }
		if (empty($produto['width'])) { $produto['width'] = $this->larg_min; }
		if (empty($produto['length'])) { $produto['length'] = $this->comp_min; }	
		if (empty($produto['weight'])) { $produto['weight'] = $this->peso_min; }		
		
		$cubagem = (float)$produto['height'] * (float)$produto['width'] * (float)$produto['length'];
		
		if($produto['weight'] > $this->max_peso || $cubagem > $this->max_cubagem || $produto['height'] > $this->max_lado || $produto['width'] > $this->max_lado || $produto['length'] > $this->max_lado){
			$this->log->write(sprintf($this->language->get('error_limites'), $produto['name']));
			
			return false;
		}
 	
  		return true;
  	}
  	
    // 'empacota' os produtos do carrinho em caixas conforme peso, cubagem e dimensões limites dos Correios
  	private function organizarEmCaixas($produtos) {
  	
  		$caixas = array();
  	
  		foreach ($produtos as $prod) {
  	
  			$prod_copy = $prod;
  	
  			// muda-se a quantidade do produto para incrementá-la em cada caixa
  			$prod_copy['quantity'] = 1;
  			
  			// todas as dimensões da caixa serão em cm e kg
  			$prod_copy['width']	= $this->getDimensaoEmCm($prod_copy['length_class_id'], $prod_copy['width']);
  			$prod_copy['height']= $this->getDimensaoEmCm($prod_copy['length_class_id'], $prod_copy['height']);
  			$prod_copy['length']= $this->getDimensaoEmCm($prod_copy['length_class_id'], $prod_copy['length']);
  			
  			// O peso do produto não é unitário como a dimensão. É multiplicado pela quantidade. Se quisermos o peso unitário, teremos que dividir pela quantidade.
  			$prod_copy['weight']= $this->getPesoEmKg($prod_copy['weight_class_id'], $prod_copy['weight'])/$prod['quantity'];
  			
  			$prod_copy['length_class_id'] = $this->config->get('config_length_class_id');
  			$prod_copy['weight_class_id'] = $this->config->get('config_weight_class_id');
  	
  			$cx_num = 0;
  	
  			for ($i = 1; $i <= $prod['quantity']; $i++) {
  	
  				// valida as dimensões do produto com as dos Correios
  				if ($this->validar($prod_copy)){
  					 
   					// cria-se a caixa caso ela não exista.
					isset($caixas[$cx_num]['peso']) ? true : $caixas[$cx_num]['peso'] = 0;
					isset($caixas[$cx_num]['cubagem']) ? true : $caixas[$cx_num]['cubagem'] = 0;
					isset($caixas[$cx_num]['adicional_manuseio']) ? true : $caixas[$cx_num]['adicional_manuseio'] = false;
  	
  					$peso = $caixas[$cx_num]['peso'] + $prod_copy['weight'];
					$cubagem = $caixas[$cx_num]['cubagem'] + ($prod_copy['width'] * $prod_copy['height'] * $prod_copy['length']);
					
 					$peso_dentro_limite = ($peso <= $this->max_peso);
					$cubagem_dentro_limite = ($cubagem <= $this->max_cubagem);
					
  					// o produto dentro do peso e dimensões estabelecidos pelos Correios
  					if ($peso_dentro_limite && $cubagem_dentro_limite) {
  						
						if (version_compare(VERSION, '2.1') < 0) {
							// já existe o mesmo produto na caixa, assim incrementa-se a sua quantidade
							if (isset($caixas[$cx_num]['produtos'][$prod_copy['key']])) {
								$caixas[$cx_num]['produtos'][$prod_copy['key']]['quantity']++;
							}
							// adiciona o novo produto
							else {
								$caixas[$cx_num]['produtos'][$prod_copy['key']] = $prod_copy;
							}
						} else {
							if (isset($caixas[$cx_num]['produtos'][$prod_copy['cart_id']])) {
								$caixas[$cx_num]['produtos'][$prod_copy['cart_id']]['quantity']++;
							}
							else {
								$caixas[$cx_num]['produtos'][$prod_copy['cart_id']] = $prod_copy;
							}							
						}						
						
						$caixas[$cx_num]['peso'] = $peso;
						$caixas[$cx_num]['cubagem'] = $cubagem;
						
						if($prod_copy['width'] > $this->manuseio_especial || $prod_copy['height'] > $this->manuseio_especial || $prod_copy['length'] > $this->manuseio_especial) {
							$caixas[$cx_num]['adicional_manuseio'] = true;
						}						
  					}
  					// tenta adicionar o produto que não coube em uma nova caixa
  					else{
  						$cx_num++;
  						$i--;
  					}
  				}
  				// produto não tem as dimensões/peso válidos então abandona sem calcular o frete. 
  				else {
  					$caixas = array();
  					break 2;  // sai dos dois foreach
  				}
  			}
  		}
  		return $caixas;
  	}
  	// retorna o valor total dos produtos na caixa
  	private function getTotalCaixa($products) {
  		$total = 0;
  	
  		foreach ($products as $product) {
			if (version_compare(VERSION, '2.2') < 0) {
				$total += $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), '', '', false);
			} else {
				$total += $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
			}
  		}
  		return $total;
  	}

	private function raizCubica($n) {
		return pow($n, 1/3);
	}


  	private function removeProdutosSemFrete($products) {

		foreach ($products as $key => $product) {
  			
			if (!$product['shipping']) {
  				unset($products[$key]);
  			}	
  		}
  		return $products;
  	}

  	private function processaProdutosFreteGratis($products, $servico_info) {
		$produtos = $products;
		
		$this->load->model('catalog/product');
		
		$rows = $this->config->get('correios_gratis_produtos');
		
		$dados = array(
			'produtos'		=> $products,
			'total_minimo'	=> 0,
			'total' 		=> 0
		);

		if(isset($rows) && is_array($rows)) {
			foreach($rows as $key => $row) {
				$row_codigo = trim($row['codigo']);
				$servico_codigo = trim($servico_info['codigo']);

				isset($total[$row_codigo]) ? true : $total[$row_codigo] = 0;
				
				if ($row_codigo == $servico_codigo) {
					foreach ($produtos as $key => $product) {
						
						$results = $this->model_catalog_product->getCategories($product['product_id']);
						$categorias = array();
						
						foreach($results as $result) {
							$categorias[] = $result['category_id'];
						}
						
						$product_info = $this->model_catalog_product->getProduct($product['product_id']);
						
						if (isset($row['categorias']) && is_array($row['categorias'])) {
							foreach ($row['categorias'] as $category_id) {
								if (in_array($category_id, $categorias)) {
									if (version_compare(VERSION, '2.2') < 0) {
										$total[$row_codigo] += $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), '', '', false);
									} else {
										$total[$row_codigo] += $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
									}							
									unset($produtos[$key]);
									
									break; // sai do foreach e continua com o próximo produto
								}
							}
						}
						
						if (isset($row['marcas']) && is_array($row['marcas'])) {
							foreach ($row['marcas'] as $manufacturer_id) {
								if ($product_info['manufacturer_id'] == $manufacturer_id) {
									if (version_compare(VERSION, '2.2') < 0) {
										$total[$row_codigo] += $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), '', '', false);
									} else {
										$total[$row_codigo] += $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
									}
									unset($produtos[$key]);
									
									break; // sai do foreach e continua com o próximo produto
								}
							}
						}

						if (isset($row['produtos']) && is_array($row['produtos'])) {
							foreach ($row['produtos'] as $product_id) {
								if ($product_info['product_id'] == $product_id) {
									if (version_compare(VERSION, '2.2') < 0) {
										$total[$row_codigo] += $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), '', '', false);
									} else {
										$total[$row_codigo] += $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], '', false);
									}

									unset($produtos[$key]);
									
									break; // sai do foreach e continua com o próximo produto
								}
							}
						}
					}
					
					$dados = array(
						'produtos'		=> $produtos,
						'total_minimo'	=> (float)$row['total_minimo'],
						'total' 		=> $total[$row_codigo]
					);
				}
			}
		}
 		return $dados;
  	}
	
  	private function temFreteGratisEstado($servico_info) {
		$temFreteGratis = false;
		
		$rows = $this->config->get('correios_gratis_estados');			
		
		if(isset($rows) && is_array($rows)) {
			
			foreach($rows as $row) {
				$row_codigo = trim($row['codigo']);
				$servico_codigo = trim($servico_info['codigo']);
				
				if ($row_codigo == $servico_codigo) {
					$estados_gratis = isset($row['estados_gratis']) ? $row['estados_gratis'] : array();
					
					if (in_array($this->zone_id, $estados_gratis) && $this->total_compra >= (float)$row['total_minimo'][$this->zone_id]) {
						$temFreteGratis = true;
						break;
					}
				}				
			}
		}
		
  		return $temFreteGratis;
  	}	
	
	private function temFreteGratisCep($servico_info, $faixas) {
		$temFreteGratis = false;

		$codigo_servico = trim($servico_info['codigo']);
		
		if(!empty($faixas) && isset($faixas[$codigo_servico])) {
			foreach($faixas[$codigo_servico] as $faixa_info) {
				
				$cep_origem_faixa = preg_replace ("/[^0-9]/", '', $faixa_info['cep_origem']);
				$cep_inicio = preg_replace ("/[^0-9]/", '', $faixa_info['inicio']);
				$cep_fim = preg_replace ("/[^0-9]/", '', $faixa_info['fim']);
				
				if($cep_origem_faixa == $this->cep_origem && (int)$this->cep_destino >= (int)$cep_inicio && (int)$this->cep_destino <= (int)$cep_fim && $this->total_compra >= (float)$faixa_info['total_minimo']) {
					$temFreteGratis = true;
					break;
				}
			}
		}		
		
		return $temFreteGratis;		
	}
		
	private function getZoneIdByPostcode($postcode){
		
		$zone_id = 0;
		
		$postcode = preg_replace ("/[^0-9]/", '', $postcode); 
			
		$tabela['ac'] = array(
			'cepini' => '69900000',
			'cepfim' => '69999999' 
		);
		$tabela['al'] = array(
			'cepini' => '57000000',
			'cepfim' => '57999999' 
		);
		$tabela['am'] = array(
			'cepini' => '69000000',
			'cepfim' => '69299999' 
		);
		$tabela['am.2'] = array(
			'cepini' => '69400000',
			'cepfim' => '69899999' 
		);		
		$tabela['ap'] = array(
			'cepini' => '68900000',
			'cepfim' => '68999999' 
		);
		$tabela['ba'] = array(
			'cepini' => '40000000',
			'cepfim' => '48999999 '
		);
		$tabela['ce'] = array(
			'cepini' => '60000000',
			'cepfim' => '63999999' 
		);
		$tabela['df'] = array(
			'cepini' => '70000000',
			'cepfim' => '72799999'
		);
		$tabela['df.2'] = array(
			'cepini' => '73000000',
			'cepfim' => '73699999'
		);				
		$tabela['es'] = array(
			'cepini' => '29000000',
			'cepfim' => '29999999' 
		);
		$tabela['go'] = array(
			'cepini' => '72800000',
			'cepfim' => '72999999' 
		);
		$tabela['go.2'] = array(
			'cepini' => '73700000',
			'cepfim' => '76799999' 
		);		
		$tabela['ma'] = array(
			'cepini' => '65000000',
			'cepfim' => '65999999' 
		);
		$tabela['mg'] = array(
			'cepini' => '30000000',
			'cepfim' => '39999999' 
		);
		$tabela['ms'] = array(
			'cepini' => '79000000',
			'cepfim' => '79999999' 
		);
		$tabela['mt'] = array(
			'cepini' => '78000000',
			'cepfim' => '78899999' 
		);
		$tabela['pa'] = array(
			'cepini' => '66000000',
			'cepfim' => '68899999' 
		);
		$tabela['pb'] = array(
			'cepini' => '58000000',
			'cepfim' => '58999999' 
		);
		$tabela['pe'] = array(
			'cepini' => '50000000',
			'cepfim' => '56999999' 
		);		
		$tabela['pi'] = array(
			'cepini' => '64000000',
			'cepfim' => '64999999' 
		);		
		$tabela['pr'] = array(
			'cepini' => '80000000',
			'cepfim' => '87999999' 
		);		
		$tabela['rj'] = array(
			'cepini' => '20000000',
			'cepfim' => '28999999' 
		);		
		$tabela['rn'] = array(
			'cepini' => '59000000',
			'cepfim' => '59999999' 
		);		
		$tabela['ro'] = array(
			'cepini' => '76800000',
			'cepfim' => '76999999' 
		);		
		$tabela['rr'] = array(
			'cepini' => '69300000',
			'cepfim' => '69399999' 
		);		
		$tabela['rs'] = array(
			'cepini' => '90000000',
			'cepfim' => '99999999' 
		);
		$tabela['sc'] = array(
			'cepini' => '88000000',
			'cepfim' => '89999999' 
		);		
		$tabela['se'] = array(
			'cepini' => '49000000',
			'cepfim' => '49999999' 
		);
		$tabela['sp'] = array(
			'cepini' => '01000000',
			'cepfim' => '19999999'
		);
		$tabela['to'] = array(
			'cepini' => '77000000',
			'cepfim' => '77999999' 
		);
		
		foreach($tabela as $zone_code => $postcode_range){
			
			if((int)$postcode >= (int)$postcode_range['cepini'] && (int)$postcode <= (int)$postcode_range['cepfim']){
				$key = explode('.', $zone_code);
				
				$zone_info = $this->getZoneIdByCode($key[0]);
				
				$zone_id = $zone_info['zone_id'];
				
				break;
			}
		}
		
		return $zone_id;				
	}
	
	private function getZoneIdCapital($postcode) {
		
		$zone_id = 0;
		
		$postcode = preg_replace ("/[^0-9]/", '', $postcode); 
			
		$tabela['ac'] = array(
			'cep_cap_ini' => '69900001',
			'cep_cap_fim' => '69923999' 
		);
		$tabela['al'] = array(
			'cep_cap_ini' => '57000001',
			'cep_cap_fim' => '57099999' 
		);
		$tabela['am'] = array(
			'cep_cap_ini' => '69000001',
			'cep_cap_fim' => '69099999' 
		);
		$tabela['ap'] = array(
			'cep_cap_ini' => '68900001',
			'cep_cap_fim' => '68911999' 
		);
		$tabela['ba'] = array(
			'cep_cap_ini' => '40000001',
			'cep_cap_fim' => '42599999 '
		);
		$tabela['ce'] = array(
			'cep_cap_ini' => '60000001',
			'cep_cap_fim' => '61599999' 
		);
		$tabela['df'] = array(
			'cep_cap_ini' => '70000001',
			'cep_cap_fim' => '72799999'
		);
		$tabela['df.2'] = array(
			'cep_cap_ini' => '73000001',
			'cep_cap_fim' => '73699999'
		);				
		$tabela['es'] = array(
			'cep_cap_ini' => '29000001',
			'cep_cap_fim' => '29099999' 
		);
		$tabela['go'] = array(
			'cep_cap_ini' => '74000001',
			'cep_cap_fim' => '74899999' 
		);
		$tabela['ma'] = array(
			'cep_cap_ini' => '65000001',
			'cep_cap_fim' => '65109999' 
		);
		$tabela['mg'] = array(
			'cep_cap_ini' => '30000001',
			'cep_cap_fim' => '31999999' 
		);
		$tabela['ms'] = array(
			'cep_cap_ini' => '79000001',
			'cep_cap_fim' => '79124999' 
		);
		$tabela['mt'] = array(
			'cep_cap_ini' => '78000001',
			'cep_cap_fim' => '78099999' 
		);
		$tabela['pa'] = array(
			'cep_cap_ini' => '66000001',
			'cep_cap_fim' => '66999999' 
		);
		$tabela['pb'] = array(
			'cep_cap_ini' => '58000001',
			'cep_cap_fim' => '58099999' 
		);
		$tabela['pe'] = array(
			'cep_cap_ini' => '50000001',
			'cep_cap_fim' => '52999999' 
		);		
		$tabela['pi'] = array(
			'cep_cap_ini' => '64000001',
			'cep_cap_fim' => '64099999' 
		);		
		$tabela['pr'] = array(
			'cep_cap_ini' => '80000001',
			'cep_cap_fim' => '82999999' 
		);		
		$tabela['rj'] = array(
			'cep_cap_ini' => '20000001',
			'cep_cap_fim' => '23799999' 
		);		
		$tabela['rn'] = array(
			'cep_cap_ini' => '59000001',
			'cep_cap_fim' => '59139999' 
		);		
		$tabela['ro'] = array(
			'cep_cap_ini' => '76800001',
			'cep_cap_fim' => '76834999' 
		);		
		$tabela['rr'] = array(
			'cep_cap_ini' => '69300001',
			'cep_cap_fim' => '69339999' 
		);		
		$tabela['rs'] = array(
			'cep_cap_ini' => '90000001',
			'cep_cap_fim' => '91999999' 
		);
		$tabela['sc'] = array(
			'cep_cap_ini' => '88000001',
			'cep_cap_fim' => '88099999' 
		);		
		$tabela['se'] = array(
			'cep_cap_ini' => '49000001',
			'cep_cap_fim' => '49098999' 
		);
		$tabela['sp'] = array(
			'cep_cap_ini' => '01000001',
			'cep_cap_fim' => '05999999'
		);
		$tabela['sp.2'] = array(
			'cep_cap_ini' => '08000000',
			'cep_cap_fim' => '08499999'
		);				
		$tabela['to'] = array(
			'cep_cap_ini' => '77000001',
			'cep_cap_fim' => '77249999' 
		);
		
		foreach($tabela as $zone_code => $postcode_range) {
			
			if((int)$postcode >= (int)$postcode_range['cep_cap_ini'] && (int)$postcode <= (int)$postcode_range['cep_cap_fim']) {
				$key = explode('.', $zone_code);
				
				$zone_info = $this->getZoneIdByCode($key[0]);
				
				$zone_id = $zone_info['zone_id'];
				
				break;
			}
		}
		
		return $zone_id;				
	}		

	private function getZoneIdByCode($zone_code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE code = '" . $this->db->escape(strtoupper($zone_code)) . "' AND country_id = '" . (int)$this->config->get('config_country_id') . "' AND status = '1'");
		
		return $query->row;
	}

	private function getPrazo($nCdservico, $sCepOrigem, $sCepDestino) {
		
		$data = array();

		$url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrazo?nCdServico=" . $nCdservico . "&sCepOrigem=" . $sCepOrigem . "&sCepDestino=" . $sCepDestino;
		
		// faz a chamada e retorna o xml com os dados
		$xml = $this->getXML($url);
	
		// lendo o xml
		if ($xml) {
			$dom = new DOMDocument('1.0', 'ISO-8859-1');
			$result = $dom->loadXml($xml);
			
			if($result) {
				$servicos = $dom->getElementsByTagName('cServico');
				
				if ($servicos) {
					foreach ($servicos as $servico) {
						$prazo = $servico->getElementsByTagName('PrazoEntrega')->item(0)->nodeValue;
						
						if ((int)$prazo > 0) {
							$data['PrazoEntrega'] = $servico->getElementsByTagName('PrazoEntrega')->item(0)->nodeValue;
							$data['MsgErro'] = $servico->getElementsByTagName('MsgErro')->item(0)->nodeValue;
						}
					}
				}
			}
		}
		return $data;
	}	
}
?>
