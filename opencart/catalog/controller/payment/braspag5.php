<?php
require_once DIR_SYSTEM."../app/braspag5/loja5.php";
class ControllerPaymentBraspag5 extends Controller {
	
	public function index() {
		$this->load->model('checkout/order');
        
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "braspag5_fraude` (
			`id` INT(15) NOT NULL AUTO_INCREMENT,
            `venda` VARCHAR(50) NOT NULL,
			`log` TEXT NOT NULL,
			PRIMARY KEY (`id`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=17;");
        
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		//ativa os cartoes
		$data['anti_fraude_ativado'] = $this->config->get('braspag5_antifraude');
		$meios = $this->config->get('braspag5_meios');
		if(empty($meios)){
            $meios = array();
		}
		$data['cpf'] = $this->config->get('braspag5_cpf');
		$data['fraudeorgid'] = $this->config->get('braspag5_orgid');
		$data['fraudemid'] = $this->config->get('braspag5_mid');
		$data['afiliacao'] = $this->config->get('braspag5_id');
		$data['fiscal_ativo'] = true;
		$data['ativa_visa'] = in_array('visa',$meios)?1:0;
		$data['ativa_mastercard'] = in_array('mastercard',$meios)?1:0;
		$data['ativa_elo'] = in_array('elo',$meios)?1:0;
		$data['ativa_diners'] = in_array('diners',$meios)?1:0;
		$data['ativa_discover'] = in_array('discover',$meios)?1:0;
		$data['ativa_amex'] = in_array('amex',$meios)?1:0;
		$data['ativa_jcb'] = in_array('jcb',$meios)?1:0;
		$data['ativa_aura'] = in_array('aura',$meios)?1:0;
		$data['ativa_hiper'] = in_array('hiper',$meios)?1:0;
		$data['ativa_hipercard'] = in_array('hipercard',$meios)?1:0;
		$data['total'] = $order_info['total'];
		$data['pedido'] = $order_info;
		$data['criar'] = $this->url->link('payment/braspag5/criar','','SSL');
		$data['token'] = '';
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		return $this->load->view('payment/braspag5.tpl', $data);;
		}else{
		return $this->load->view('default/template/payment/braspag5.tpl', $data);
		}
	}
	
	public function formatar($valor){
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		return $this->currency->format($valor,$this->session->data['currency']);
		}else{
		return $this->currency->format($valor);
		}
	}
	
	public function parcelas(){
		$this->load->model('checkout/order');
		$pedido = (int)$_GET['pedido'];	
		if(md5(sha1($pedido))==$_GET['hash']){
		$order = $this->model_checkout_order->getOrder($pedido);
		$enviar_juros_inclusos = true;
		$total_frete = $this->getFrete($pedido);
		$minimo = (float)$this->config->get('braspag5_minimo');
		$desconto = (float)$this->config->get('braspag5_desconto_credito');
		$divmax = $this->config->get('braspag5_div');
		$divsem = $this->config->get('braspag5_sem');
		$juros  = ((float)$this->config->get('braspag5_juros')==0)?'3.50':$this->config->get('braspag5_juros');
		$total = $total_limpo = (float)$order['total'];
		if($_GET['id']=='discover' || $_GET['id']=='jcb' || $_GET['id']=='maestro' || $_GET['id']=='electron'){
		$divmax = '1';
		}
		//corrije bug erro etapa2
		$total = number_format($total, 2, '.', '');
		//calcula os minimos
		$split = (int)$total/$minimo;
		if($split>=$divmax){
		$div = (int)$divmax;
		}elseif($split<$divmax){
		$div = (int)$split;
		}elseif($total<=$minimo){
		$div = 1;
		}
		//seleta o tipo de parcelamento
		if($this->config->get('braspag5_tipo_parcelamento')==0){
		$pcom = 3;
		$psem = 2;
		}elseif($this->config->get('braspag5_tipo_parcelamento')==2){
		$pcom = 2;
		$psem = 2;
		}elseif($this->config->get('braspag5_tipo_parcelamento')==3){
		$pcom = 3;
		$psem = 3;
		}
		//inicio
		$linhas[''] = "-- Selecione o valor --";
		//pagamento a vista
		if($desconto>0){
		$desconto_valor = (($total-$total_frete)/100)*$desconto;
		$linhas[base64_encode('1|1|'.number_format(($total-$desconto_valor), 2, '.', '').'|'.base64_encode($_GET['id']).'|'.base64_encode($total).'|'.crypt(number_format($total-$desconto_valor, 2, '.', ''),$this->config->get('braspag5_id')))] = "&Agrave; vista por ".$this->formatar(number_format(($total-$desconto_valor), 2, '.', ''))." (j&aacute; com ".$desconto."% off)";
		}else{
		$linhas[base64_encode('1|1|'.$total.'|'.base64_encode($_GET['id']).'|'.base64_encode($total).'|'.crypt(number_format($total, 2, '.', ''),$this->config->get('braspag5_id')))] = "&Agrave; vista por ".$this->formatar(number_format(($total), 2, '.', ''))."";
		}
		//se tiver parcelado
		if($div>=2){
		for($i=1;$i<=$div;$i++){
		if($i>1){
		if($i<=$divsem){
		//parcelado sem juros
		$linhas[base64_encode(''.$i.'|'.$psem.'|'.$total.'|'.base64_encode($_GET['id']).'|'.base64_encode($total).'|'.crypt(number_format($total, 2, '.', ''),$this->config->get('braspag5_id')))] = $i."x de ".$this->formatar(number_format(($total/$i), 2, '.', ''))." sem juros";
		}else{
		//parcelado com juros
		$parcela_com_juros = $this->juros($total_limpo, $juros, $i);
		if($enviar_juros_inclusos){
		$total = number_format(($parcela_com_juros*$i), 2, '.', '');
		}
		$linhas[base64_encode(''.$i.'|'.$pcom.'|'.$total.'|'.base64_encode($_GET['id']).'|'.base64_encode($total).'|'.crypt(number_format($total, 2, '.', ''),$this->config->get('braspag5_id')))] = $i."x de ".$this->formatar(number_format(($parcela_com_juros), 2, '.', ''))." com juros";
		}
		}
		}
		}
		}else{
			$linhas[''] = "(!acesso negado!)";
		}
		echo json_encode($linhas);
	}
	
	public function juros($valorTotal, $taxa, $nParcelas){
		$taxa = $taxa/100;
		$cadaParcela = ($valorTotal*$taxa)/(1-(1/pow(1+$taxa, $nParcelas)));
		return round($cadaParcela, 2);
	}
	
	public function criar() {
		//dados do pedido
		$this->load->model('checkout/order');
        if(!isset($this->session->data['order_id'])){
            die('Ops, erro de session!');
        }
		$pedidoloja = $this->model_checkout_order->getOrder((int)$this->session->data['order_id']);
        
        if($_POST['bandeira']=='Mastercard' || $_POST['bandeira']=='mastercard'){
            $_POST['bandeira'] = 'Master';
        }
        
        //seguraca
        $sql = "SELECT * FROM `" . DB_PREFIX . "braspag5_pedidos` WHERE `id_pedido` = '".$pedidoloja['order_id']."';";
		$transacoes = $this->db->query($sql);
        if($transacoes->num_rows >= 2){
            $json['erro'] = true;
            $json['log'] = 'Número de tentativas de pagamento esgostadas para este pedido, recarregue a página e tente novamente!<br>';
            echo json_encode($json);
            exit;
        }
        //seguranca
		
		//verifica a parcela
		$quebrar = explode('|',base64_decode($_POST['parcelas']));
		$num_parcelas = $quebrar[0];//numero
		$tipo_parcela = $quebrar[1];//parcelado loja ou operadora
		$total_pagar  = $quebrar[2];//total final do pedido
		$hash_seguro  = $quebrar[5];
		$pedido_id = (int)$this->session->data['order_id'];
		$bandeira_cc = $_POST['bandeira'];
		$bandeira = ucfirst($bandeira_cc);
				
		//define o ambiente
		$operador = $this->config->get('braspag5_operadora');
		if($operador=='braspag'){
		if($this->config->get('braspag5_modo')=='0'){
		$provider = 'Simulado';
		$_POST['numero'] = '4024007197692931';
		$url_cielo = 'https://apisandbox.braspag.com.br/v2/';
		}else{
		$provider = trim($this->config->get('braspag5_provide_cartao'));
		$url_cielo = 'https://api.braspag.com.br/v2/';
		}
		}else{
		if($this->config->get('braspag5_modo')=='0'){
		$provider = 'Simulado';
		$_POST['numero'] = '4024007197692931';
		$url_cielo = 'https://apisandbox.cieloecommerce.cielo.com.br/1/';
		}else{
		$provider = 'Cielo';
		$url_cielo = 'https://api.cieloecommerce.cielo.com.br/1/';
		}	
		}
		
		//header
		$headers = array(
			"Content-Type" => "application/json",
			"Accept" => "application/json",
			"MerchantId" =>trim($this->config->get('braspag5_id')),
			"MerchantKey" => trim($this->config->get('braspag5_senha')),
			"RequestId" => "",
		);

		//inicia a api
		$api = new RestClient(array(
			'base_url' => $url_cielo, 
			'headers' => $headers,           
		));
        
        //produtos 
        $produtos = array();
        foreach($this->cart->getProducts() AS $produto){
            $produtos[] = array(
                'GiftCategory' => 'No',
                'HostHedge' => 'Normal',
                'NonSensicalHedge' => 'Normal',
                'ObscenitiesHedge' => 'Normal',
                'PhoneHedge' => 'Normal',
                'Type' => 'Default',
                'Name' => $produto['name'],
                'Quantity' => $produto['quantity'],
                'Sku' => $produto['product_id'],
                "TimeHedge" => "Normal",
                'UnitPrice' => number_format($produto['price'], 2, '', ''),
                'Risk' => 'Normal',
            );
		}

		//dados a enviar
		$dados = array();
		$dados['MerchantOrderId'] = $pedidoloja['order_id'];
		if($pedidoloja['shipping_zone_id']>0){
		$numero = $this->config->get('braspag5_numero');
		$numero_logradouroc = (isset($pedidoloja['payment_custom_field'][$numero]))?$pedidoloja['payment_custom_field'][$numero]:preg_replace('/\D/', '', $pedidoloja['payment_address_1']);
		$numero_logradouroe = (isset($pedidoloja['shipping_custom_field'][$numero]))?$pedidoloja['shipping_custom_field'][$numero]:preg_replace('/\D/', '', $pedidoloja['shipping_address_1']);
		$complemento = $this->config->get('braspag5_complemento');
		$complemento_logradouroc = (isset($pedidoloja['payment_custom_field'][$complemento]))?$pedidoloja['payment_custom_field'][$complemento]:'';
		$complemento_logradouroe = (isset($pedidoloja['shipping_custom_field'][$complemento]))?$pedidoloja['shipping_custom_field'][$complemento]:'';
        
        $campofiscal = $this->config->get('braspag5_cpf');
        $fiscal_end = (isset($pedidoloja['custom_field'][$campofiscal]))? preg_replace('/\D/', '', $pedidoloja['custom_field'][$campofiscal]):'';
        if(empty($fiscal_end)){
            $fiscal_valor = preg_replace('/\D/', '', $_POST['fiscal']); 
        }else{
            $fiscal_valor = $fiscal_end;
        }
		
		$dados['Customer'] = array(
			'Name'=>$pedidoloja['payment_firstname'].' '.$pedidoloja['payment_lastname'],
			'Email'=>$pedidoloja['email'],
			"Identity" => $fiscal_valor,
			"IdentityType" => (strlen($fiscal_valor)==11?'CPF':'CNPJ'),
			'Address'=>array(
				'Street'=>$pedidoloja['payment_address_1'],
				'Number'=>(!empty($numero_logradouroc)?$numero_logradouroc:'s/n'),
				'Complement'=>$complemento_logradouroc,
				'ZipCode'=>$pedidoloja['payment_postcode'],
				'City'=>$pedidoloja['payment_city'],
				'State'=>$pedidoloja['payment_zone_code'],
				'Country'=>substr($pedidoloja['payment_iso_code_3'],0,2),
			),
			'DeliveryAddress'=>array(
				'Street'=>$pedidoloja['shipping_address_1'],
				'Number'=>(!empty($numero_logradouroe)?$numero_logradouroe:'s/n'),
				'Complement'=>$complemento_logradouroe,
				'ZipCode'=>$pedidoloja['shipping_postcode'],
				'City'=>$pedidoloja['shipping_city'],
				'State'=>$pedidoloja['shipping_zone_code'],
				'Country'=>substr($pedidoloja['shipping_iso_code_3'],0,2),
			)
		);
		}else{
		$numero = $this->config->get('braspag5_numero');
		$numero_logradouroc = (isset($pedidoloja['payment_custom_field'][$numero]))?$pedidoloja['payment_custom_field'][$numero]:preg_replace('/\D/', '', $pedidoloja['payment_address_1']);
		$complemento = $this->config->get('braspag5_complemento');
		$complemento_logradouroc = (isset($pedidoloja['payment_custom_field'][$complemento]))?$pedidoloja['payment_custom_field'][$complemento]:'';
		$fiscal_valor = preg_replace('/\D/', '', $_POST['fiscal']);
		$dados['Customer'] = array(
			'Name'=>$pedidoloja['payment_firstname'].' '.$pedidoloja['payment_lastname'],
			'Email'=>$pedidoloja['email'],
			"Identity" => $fiscal_valor,
			"IdentityType" => (strlen($fiscal_valor)==11?'CPF':'CNPJ'),
			'Address'=>array(
				'Street'=>$pedidoloja['payment_address_1'],
				'Number'=>(!empty($numero_logradouroc)?$numero_logradouroc:'s/n'),
				'Complement'=>$complemento_logradouroc,
				'ZipCode'=>$pedidoloja['payment_postcode'],
				'City'=>$pedidoloja['payment_city'],
				'State'=>$pedidoloja['payment_zone_code'],
				'Country'=>substr($pedidoloja['payment_iso_code_3'],0,2),
			)
		);	
		}
		$anti_fraude = $this->config->get('braspag5_antifraude');
		if($anti_fraude){
		$dados['Payment'] = array(
			'Type' => 'CreditCard',
			'Amount' => number_format($total_pagar, 2, '', ''),
			'Currency' => $pedidoloja['currency_code'],
			'Country' => 'BRA',
            'Provider' => $provider,
            'ServiceTaxAmount' => 0,
            'Installments' => $num_parcelas,
            'Interest' => (($tipo_parcela==2)?'ByMerchant':'ByIssuer'),
            'Capture' =>  $this->config->get('braspag5_tipo_captura'),
            'Authenticate' => 'false',    
            'Recurrent' => 'false',
            'SoftDescriptor' => trim($this->config->get('braspag5_soft')),
            'CreditCard' => array(  
				 "CardNumber" => preg_replace('/\D/', '', $_POST['numero']),
				 "Holder" => trim($_POST['titular']),
				 "ExpirationDate" => trim($_POST['validadem'].'/'.$_POST['validadea']),
				 "SecurityCode" => trim($_POST['codigo']),
				 "SaveCard" => "false",
				 "Brand" => $bandeira
			),
			'FraudAnalysis' => array(
				"Provider" => "cybersource",
				"Sequence" => "AuthorizeFirst",
				"SequenceCriteria" => "OnSuccess",
				"CaptureOnLowRisk" => 'true',
				"VoidOnHighRisk" => 'true',
				"TotalOrderAmount" => number_format($total_pagar, 2, '', ''),
				"FingerPrintId" => md5($pedidoloja['order_id']),
				"Browser" => array(
					"CookiesAccepted" => true,
					"Email" => $pedidoloja['email'],
					"IpAddress" => $pedidoloja['ip'],
					"Type" => substr($pedidoloja['user_agent'],0,39)
				),
                'Cart' => array(
                    'IsGift' => 'false',
                    'ReturnsAccepted' => 'true',
                    'Items' => $produtos,
                )
			)
		);
		}else{
			$dados['Payment'] = array(
				'Type' => 'CreditCard',
				'Amount' => number_format($total_pagar, 2, '', ''),
				'Currency' => $pedidoloja['currency_code'],
				'Country' => 'BRA',
				'Provider' => $provider,
				'ServiceTaxAmount' => 0,
				'Installments' => $num_parcelas,
				'Interest' => (($tipo_parcela==2)?'ByMerchant':'ByIssuer'),
				'Capture' =>  $this->config->get('braspag5_tipo_captura'),
				'Authenticate' => 'false',    
				'Recurrent' => 'false',
				'SoftDescriptor' => trim($this->config->get('braspag5_soft')),
				'CreditCard' => array(  
					 "CardNumber" => preg_replace('/\D/', '', $_POST['numero']),
					 "Holder" => trim($_POST['titular']),
					 "ExpirationDate" => trim($_POST['validadem'].'/'.$_POST['validadea']),
					 "SecurityCode" => trim($_POST['codigo']),
					 "SaveCard" => "false",
					 "Brand" => $bandeira
				)
			);	
		}
        
        if($this->config->get('braspag5_debug')){
            $this->log->write('Requisição:');
            $this->log->write(json_encode($dados));
        }
        
		//print_r($dados);
		//exit;
		$response = $api->post("sales", json_encode($dados));
		//print_r($response);
		//exit;
        
        if($this->config->get('braspag5_debug')){
            $this->log->write('Resposta:');
            $this->log->write($response->response);
        }
		
		if(isset($response->status)){
			$dados_pedido = $this->obj2array($response->response);
			if($response->status==200 || $response->status==201){
				if(isset($dados_pedido['Payment']['Status'])){
					//monta o log
					$log = 'Cart&atilde;o de Cr&eacute;dito '.ucfirst($dados_pedido['Payment']['CreditCard']['Brand']).' ('.$dados_pedido['Payment']['CreditCard']['CardNumber'].') em '.$dados_pedido['Payment']['Installments'].'x<br>';
					$tid = '';
					if(isset($dados_pedido['Payment']['Tid'])){
					$log .= 'TID: '.$dados_pedido['Payment']['Tid'].'<br>';
					$tid = $dados_pedido['Payment']['Tid'];
					}
					if(isset($dados_pedido['Payment']['AcquirerTransactionId'])){
					$log .= 'TID: '.$dados_pedido['Payment']['AcquirerTransactionId'].'<br>';
					$tid = $dados_pedido['Payment']['AcquirerTransactionId'];
					}
					//cria no banco de dados
					$code = $codemen = '';
					if(isset($dados_pedido['Payment']['ReturnCode'])){
						$code = $dados_pedido['Payment']['ReturnCode'];
					}
					if(isset($dados_pedido['Payment']['ProviderReturnCode'])){
						$code = $dados_pedido['Payment']['ProviderReturnCode'];
					}
					if(isset($dados_pedido['Payment']['ReturnMessage'])){
						$codemen = $dados_pedido['Payment']['ReturnMessage'];
					}
					if(isset($dados_pedido['Payment']['ProviderReturnMessage'])){
						$codemen = $dados_pedido['Payment']['ProviderReturnMessage'];
					}
					$sql = "INSERT INTO `" . DB_PREFIX . "braspag5_pedidos` (`id_braspag`, `id_pedido`, `total_pedido`, `total_pago`, `bandeira`, `parcelas`, `tid`, `lr`, `lr_log`, `status`, `bin`, `tipo`, `link`, `data`) VALUES (NULL, '".$pedidoloja['order_id']."', '".$pedidoloja['total']."', '".$total_pagar."', '".$dados_pedido['Payment']['CreditCard']['Brand']."', '".$dados_pedido['Payment']['Installments']."', '".$tid."', '".$code."', '".$codemen."', '".$dados_pedido['Payment']['Status']."', '".$dados_pedido['Payment']['CreditCard']['CardNumber']."', 'credito', '".$dados_pedido['Payment']['PaymentId']."', NOW());";
					$this->db->query($sql);	
                    
                    //log fraude
                    $fraude = '';
                    if(isset($dados_pedido['Payment']['FraudAnalysis'])){
                        $fraude = json_encode($dados_pedido['Payment']['FraudAnalysis']);
                    }
                    $sql = "INSERT INTO `" . DB_PREFIX . "braspag5_fraude` (`id`, `venda`, `log`) VALUES (NULL, '".$dados_pedido['Payment']['PaymentId']."', '".$fraude."');";
					$this->db->query($sql);
                    /////////////////// 
                    
					//cria o pedido de acordo com o status
					$status_pedido = $this->getStatus($dados_pedido['Payment']['Status']);
					$this->model_checkout_order->addOrderHistory($pedidoloja['order_id'], $status_pedido['id'], $log, true);
					//mota o json
					$json['erro'] = false;
					$cupom = '/index.php?option=com_mijoshop&route=payment/braspag5/cupom&hash='.$dados_pedido['Payment']['PaymentId'].'&op='.$operador;
					// $cupom = $this->url->link('payment/braspag5/cupom', '', 'SSL').'&hash='.$dados_pedido['Payment']['PaymentId'].'&op='.$operador;
					$json['href'] = $cupom;	
				}else{
					$json['erro'] = true;
					$log = 'Erro ao processar transação junto a '.ucfirst($operador).'!<br>';
					if(isset($dados_pedido['Payment']['ReasonMessage'])){
					$log .= $dados_pedido['Payment']['ReasonMessage'];
					}
					$json['log'] = $log;
				}
			}else{
				$json['erro'] = true;
				$log = 'Erro ao processar transação junto a '.ucfirst($operador).'!<br>';
				if(is_array($dados_pedido)){
					foreach($dados_pedido AS $k=>$v){
						$log .= $v['Code'].' - '.$v['Message'].'<br>';
					}
				}
				$json['log'] = $log;
			}
		}else{
            $this->log->write('Resposta erro:');
            $this->log->write($response->response);
			$json['erro'] = true;
			$json['log'] = 'Erro ao processar transação junto a '.ucfirst($operador).'!<br>Verifique se o servidor esta online e funcionando.';
		}
		echo json_encode($json);
	}
	
	public function getFrete($pedido){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$pedido . "' AND code = 'shipping'");
		if(isset($query->row['value'])){
			return $query->row['value'];
		}
		return 0;
	}
	
	public function obj2array($obj){
		return @json_decode($obj,true);
	}

	public function getStatus($status) {
		switch($status) {
			case "0": 
			$status = "Aguardando Pagamento";
			$status_id = $this->config->get('braspag5_in');
			break;
			case "1": 
			$status = "Autorizada";
			$status_id = $this->config->get('braspag5_au');
			break;
			case "2": 
			$status = "Aprovada";
			$status_id = $this->config->get('braspag5_pg');
			break;
			case "3": 
			$status = "Negada";
			$status_id = $this->config->get('braspag5_ne');
			break;
			case "10": 
			$status = "Cancelada";
			$status_id = $this->config->get('braspag5_ca');
			break;
			case "12": 
			$status = "Pendente";
			$status_id = $this->config->get('braspag5_pe');
			break;
            case "13":
			$status = "Abortado pela Operadora";
			$status_id = $this->config->get('braspag5_ca');
			break;
			default: 
			$status = "Desconhecido";
			$status_id = $this->config->get('braspag5_in');
			break;
		}
		return array('nome'=>$status,'id'=>$status_id);
	}
	
	public function cupom(){
		$this->load->model('checkout/order');
		$this->language->load('checkout/success');
		
		//define o ambiente	
		$operador = $this->config->get('braspag5_operadora');
		if($operador=='braspag'){
			if($this->config->get('braspag5_modo')=='0'){
			$url_cielo = 'https://apiquerysandbox.braspag.com.br/v2/';
			}else{
			$url_cielo = 'https://apiquery.braspag.com.br/v2/';
			}
		}else{
			if($this->config->get('braspag5_modo')=='0'){
			$url_cielo = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/1/';
			}else{
			$url_cielo = 'https://apiquery.cieloecommerce.cielo.com.br/1/';
			}	
		}
		
		//header
		$headers = array(
			"Content-Type" => "application/json",
			"Accept" => "application/json",
			"MerchantId" =>trim($this->config->get('braspag5_id')),
			"MerchantKey" => trim($this->config->get('braspag5_senha')),
			"RequestId" => "",
		);

		//inicia a api
		$api = new RestClient(array(
			'base_url' => $url_cielo, 
			'headers' => $headers, 
		));
		
		$response = $api->get("sales/".$_GET['hash']."");
		
		if(isset($response->status)){
			$dados_pedido = $this->obj2array($response->response);
			if($response->status==200 || $response->status==201){

			$data['braspag'] = $dados_pedido;
			$data['pedido'] = $this->model_checkout_order->getOrder($dados_pedido['MerchantOrderId']);
			$data['status'] = $this->getStatus($dados_pedido['Payment']['Status']);
			$data['repagamento'] = $this->config->get('braspag5_repagamento');
			$data['iframe'] = $this->url->link('checkout/success','','SSL');

			if ($this->customer->isLogged()) {
			$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
			} else {
			$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
			}

			$this->document->setTitle('Resultado da Transa&ccedil;&atilde;o');
			$this->document->setDescription('');
			$this->document->setKeywords('');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if(version_compare(VERSION, '2.2.0.0', '>=')){ 
			$this->response->setOutput($this->load->view('payment/braspag5_recibo.tpl', $data));
			}else{
			$this->response->setOutput($this->load->view('default/template/payment/braspag5_recibo.tpl', $data));
			}
			
			}else{
				echo 'Ocorre um problema ao consultar a transa&ccedil;&atilde;o! Atualiza a p&aacute;gina.';	
				echo '<pre>'.print_r($response->response,true).'</pre>';
			}
		}else{
			echo 'Ocorre um problema ao consultar a transa&ccedil;&atilde;o! Atualiza a p&aacute;gina.<br>';	
			echo '<pre>'.print_r($response,true).'</pre>';
		}
	}

}
?>