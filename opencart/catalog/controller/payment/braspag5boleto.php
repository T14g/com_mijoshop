<?php
require_once DIR_SYSTEM."../app/braspag5/loja5.php";
class ControllerPaymentBraspag5Boleto extends Controller {
	
	public function index() {
		$this->load->model('checkout/order');
		$this->load->language('payment/cod');	
		$pedido = $this->session->data['order_id'];
		$pedidoloja = $this->model_checkout_order->getOrder($pedido);
		$data['titular'] = strtoupper($pedidoloja['firstname']).' '.strtoupper($pedidoloja['lastname']);
		$fiscal = '';
		$custom_fiscal = $this->config->get('braspag5_cpf');
		if(isset($pedidoloja['custom_field'][$custom_fiscal])){
		$fiscal = preg_replace('/\D/', '', $pedidoloja['custom_field'][$custom_fiscal]);	
		}
		$data['cpf'] = $fiscal;
		$data['pedido'] = $pedidoloja;
		$data['criar'] = $this->url->link('payment/braspag5boleto/criar','','SSL');
		$data['token'] = '';
		$total = $pedidoloja['total'];
		$data['total'] = $this->formatar($total);

		if(version_compare(VERSION, '2.2.0.0', '>=')){
		return $this->load->view('payment/braspag5boleto.tpl', $data);;
		}else{
		return $this->load->view('default/template/payment/braspag5boleto.tpl', $data);
		}
	}
	
	public function formatar($valor){
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		return $this->currency->format($valor,$this->session->data['currency']);
		}else{
		return $this->currency->format($valor);
		}
	}
	
	public function criar() {
		//dados do pedido
		$this->load->model('checkout/order');
		$pedido = $this->session->data['order_id'];
		$pedidoloja = $this->model_checkout_order->getOrder($pedido);
		$fiscal = '';
		if(isset($_POST['fiscal']) && !empty($_POST['fiscal'])){
			$fiscal = preg_replace('/\D/', '', $_POST['fiscal']);
		}
		//define o ambiente
		$operador = $this->config->get('braspag5_operadora');
		if($operador=='braspag'){
			if($this->config->get('braspag5_modo')=='0'){
			$provider = 'Simulado';
			$url_cielo = 'https://apisandbox.braspag.com.br/v2/';
			}else{
			$url_cielo = 'https://api.braspag.com.br/v2/';
			$provider = trim($this->config->get('braspag5_provide_boleto'));
			}
		}else{
			if($this->config->get('braspag5_modo')=='0'){
			$provider = 'Simulado';
			$url_cielo = 'https://apisandbox.cieloecommerce.cielo.com.br/1/';
			}else{
			$url_cielo = 'https://api.cieloecommerce.cielo.com.br/1/';
			$provider = trim($this->config->get('braspag5_provide_boleto'));
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
		$dados['Customer'] = array(
			'Name'=>$pedidoloja['payment_firstname'].' '.$pedidoloja['payment_lastname'],
			'Identity'=>$fiscal,
			'Email'=>$pedidoloja['email'],
			'Address'=>array(
				'Street'=>$pedidoloja['payment_address_1'],
				'Number'=>!empty($numero_logradouroc)?$numero_logradouroc:'s/n',
				'Complement'=>$complemento_logradouroc,
				'District'=>$pedidoloja['payment_address_2'],
				'ZipCode'=>$pedidoloja['payment_postcode'],
				'City'=>$pedidoloja['payment_city'],
				'State'=>$pedidoloja['payment_zone_code'],
				'Country'=>substr($pedidoloja['payment_iso_code_3'],0,2),
			),
			'DeliveryAddress'=>array(
				'Street'=>$pedidoloja['shipping_address_1'],
				'Number'=>!empty($numero_logradouroe)?$numero_logradouroe:'s/n',
				'Complement'=>$complemento_logradouroe,
				'ZipCode'=>$pedidoloja['shipping_postcode'],
				'District'=>$pedidoloja['shipping_address_2'],
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
		$dados['Customer'] = array(
			'Name'=>$pedidoloja['payment_firstname'].' '.$pedidoloja['payment_lastname'],
			'Identity'=>$fiscal,
			'Email'=>$pedidoloja['email'],
			'Address'=>array(
				'Street'=>$pedidoloja['payment_address_1'],
				'Number'=>!empty($numero_logradouroc)?$numero_logradouroc:'',
				'Complement'=>$complemento_logradouroc,
				'ZipCode'=>$pedidoloja['payment_postcode'],
				'District'=>$pedidoloja['payment_address_2'],
				'City'=>$pedidoloja['payment_city'],
				'State'=>$pedidoloja['payment_zone_code'],
				'Country'=>substr($pedidoloja['payment_iso_code_3'],0,2),
			)
		);	
		}
		//datas php
		$dataVenda = strtotime($pedidoloja['date_added']);
		$dataExtra = (int)(60*60*24*$this->config->get('braspag5_boleto_dias'));
		$dados['Payment'] = array(
			'Type' => 'Boleto',
			'Amount' => number_format($pedidoloja['total'], 2, '', ''),
			'Currency' => $pedidoloja['currency_code'],
			'Country' => 'BRA',
            'Provider' => $provider,
			'Address'=>$pedidoloja['payment_address_1'].' '.$numero_logradouroc,
			'BoletoNumber'=> $pedidoloja['order_id'],
			'Assignor'=> $this->config->get('braspag5_boleto_cedente'),
			'Demonstrative'=> $this->config->get('braspag5_boleto_demo'),
			'ExpirationDate'=>date('Y-m-d',($dataVenda+$dataExtra)),
			'Identification'=>$fiscal,
			'Instructions'=>$this->config->get('braspag5_boleto_instrucoes'),
		);
		
		//print_r($dados);
		//exit;
			
		$response = $api->post("sales", json_encode($dados));
		
		//print_r($response);
		//exit;
		
		$json = array();
		if(isset($response->status)){
			$dados_pedido = $this->obj2array($response->response);
			//print_r($dados_pedido);
			//exit;
			if($response->status==200 || $response->status==201){
				if(isset($dados_pedido['MerchantOrderId']) && ($dados_pedido['Payment']['Status']==1 || $dados_pedido['Payment']['Status']==0)){
					//log e segunda via
					$log = 'Boleto '.ucfirst($dados_pedido['Payment']['Provider']).' com vencimento em '.date('d/m/Y',strtotime($dados_pedido['Payment']['ExpirationDate'])).' - ';
					$log .= '<a class="button btn btn-success btn-xs" href="'.$dados_pedido['Payment']['Url'].'" target="_blank">Imprimir</a>';
					//pedido no banco de dados
					$sql = "INSERT INTO `" . DB_PREFIX . "braspag5_pedidos` (`id_braspag`, `id_pedido`, `total_pedido`, `total_pago`, `bandeira`, `parcelas`, `tid`, `lr`, `lr_log`, `status`, `bin`, `tipo`, `link`, `data`) VALUES (NULL, '".$pedidoloja['order_id']."', '".$pedidoloja['total']."', '".$pedidoloja['total']."', 'Boleto ".$dados_pedido['Payment']['Provider']."', '1', '', '', '', '0', '', 'boleto', '".$dados_pedido['Payment']['PaymentId']."', NOW());";
					$this->db->query($sql);	
					//cria o pedido na loja
					$this->model_checkout_order->addOrderHistory($pedidoloja['order_id'], $this->config->get('braspag5_in'), $log, true);
					//mota o json
					$json['erro'] = false;
					$json['href'] = $this->url->link('payment/braspag5boleto/cupom', '', 'SSL').'&hash='.$dados_pedido['Payment']['PaymentId'].'&op='.$operador;
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
			$json['erro'] = true;
			$json['log'] = 'Erro ao processar transação junto a '.ucfirst($operador).'!<br>Verifique se o servidor esta online e funcionando.';
		}
		echo json_encode($json);
	}
	
	public function obj2array($obj){
		return @json_decode($obj,true);
	}

	public function getStatus($status) {
		switch($status) {
			case "0": 
			case "1": 
			$status = "Aguardando Pagamento";
			$status_id = $this->config->get('braspag5_in');
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
		//print_r($response);
		
		if(isset($response->status)){
			$dados_pedido = $this->obj2array($response->response);
			if($response->status==200 || $response->status==201){

			$data['braspag'] = $dados_pedido;
			$data['pedido'] = $this->model_checkout_order->getOrder($dados_pedido['MerchantOrderId']);
			$data['status'] = $this->getStatus($dados_pedido['Payment']['Status']);
			$data['iframe'] = $this->url->link('checkout/success','','SSL');
			//$this->cart->clear();

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