<?php
require_once DIR_SYSTEM."../app/braspag5/loja5.php";
class ControllerPaymentBraspag5Tef extends Controller {
	
	public function index() {
		$this->load->model('checkout/order');
		$this->load->language('payment/cod');	
		$pedido = $this->session->data['order_id'];
		$pedidoloja = $this->model_checkout_order->getOrder($pedido);
		$data['titular'] = strtoupper($pedidoloja['firstname']).' '.strtoupper($pedidoloja['lastname']);
		$data['pedido'] = $pedidoloja;
		$data['criar'] = $this->url->link('payment/braspag5tef/criar','','SSL');
		$data['token'] = '';
		$data['banco'] = $this->config->get('braspag5_provide_tef');
		$total = $pedidoloja['total'];
		$data['total'] = $this->formatar($total);
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		return $this->load->view('payment/braspag5tef.tpl', $data);;
		}else{
		return $this->load->view('default/template/payment/braspag5tef.tpl', $data);
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

		//define o ambiente
		$operador = $this->config->get('braspag5_operadora');
		if($operador=='braspag'){
		if($this->config->get('braspag5_modo')=='0'){
		$provider = 'Simulado';
		$url_cielo = 'https://apisandbox.braspag.com.br/v2/';
		}else{
		$url_cielo = 'https://api.braspag.com.br/v2/';
		$provider = trim($_GET['banco']);
		}
		}else{
		if($this->config->get('braspag5_modo')=='0'){
		$provider = 'Simulado';
		$url_cielo = 'https://apisandbox.cieloecommerce.cielo.com.br/1/';
		}else{
		$url_cielo = 'https://api.cieloecommerce.cielo.com.br/1/';
		$provider = trim($_GET['banco']);
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
		$dados['Customer'] = array(
			'Name'=>$pedidoloja['payment_firstname'].' '.$pedidoloja['payment_lastname'],
			'Email'=>$pedidoloja['email'],
		);
		$ipn = $this->url->link('payment/braspag5tef/ipn','','SSL').'&id='.$pedido.'&hash='.sha1(md5($pedido)).'';
		$dados['Payment'] = array(
			'Type' => 'EletronicTransfer',
			'Amount' => number_format($pedidoloja['total'], 2, '', ''),
			'Currency' => $pedidoloja['currency_code'],
			'Country' => 'BRA',
            'Provider' => $provider,
			'ReturnUrl'=>$ipn,
		);
		//print_r($dados);
		//exit;
		
		$response = $api->post("sales", json_encode($dados));
		//print_r($response);
		//exit;
		
		$json = array();
		if(isset($response->status)){
			$dados_pedido = $this->obj2array($response->response);
			if($response->status==200 || $response->status==201){
				if(isset($dados_pedido['MerchantOrderId']) && $dados_pedido['Payment']['Status']==0){
					//log e segunda via
					$log = 'Transferência Online '.ucfirst($dados_pedido['Payment']['Provider']).'<br>';
					$log .= '<a class="button btn btn-success" href="'.$dados_pedido['Payment']['Url'].'" target="_blank">Pagar Online</a>';
					//pedido no banco de dados
					$sql = "INSERT INTO `" . DB_PREFIX . "braspag5_pedidos` (`id_braspag`, `id_pedido`, `total_pedido`, `total_pago`, `bandeira`, `parcelas`, `tid`, `lr`, `lr_log`, `status`, `bin`, `tipo`, `link`, `data`) VALUES (NULL, '".$pedidoloja['order_id']."', '".$pedidoloja['total']."', '".$pedidoloja['total']."', 'TEF ".$dados_pedido['Payment']['Provider']."', '1', '', '', '', '0', '', 'tef', '".$dados_pedido['Payment']['PaymentId']."', NOW());";
					$this->db->query($sql);	
					//cria o pedido na loja
					$this->model_checkout_order->addOrderHistory($pedidoloja['order_id'], $this->config->get('braspag5_in'), $log, true);
					//mota o json
					$json['erro'] = false;
					$json['href'] = $this->url->link('payment/braspag5tef/cupom', '', 'SSL').'&hash='.$dados_pedido['Payment']['PaymentId'];		
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
		if($this->config->get('braspag5_operadora')=='braspag'){
			if($this->config->get('braspag5_modo')=='0'){
			$url_cielo = 'https://apiquerysandbox.braspag.com.br/v2/';
			}else{
			$url_cielo = 'https://apiquery.braspag.com.br/v2/';
			}
		}else{
			if($this->config->get('braspag5_modo')=='0'){
			$provider = 'Simulado';
			$url_cielo = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/1/';
			}else{
			$url_cielo = 'https://apiquery.cieloecommerce.cielo.com.br/1/';
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
		
		$response = $api->get("sales/".$_GET['hash']."");
		//print_r($response);
		//exit;
		
		if(isset($response->status)){
			$dados_pedido = $this->obj2array($response->response);
			if($response->status==200 || $response->status==201){

			$data['braspag'] = $dados_pedido;
			$data['pedido'] = $this->model_checkout_order->getOrder($dados_pedido['MerchantOrderId']);
			$data['status'] = $this->getStatus($dados_pedido['Payment']['Status']);
			$data['iframe'] = $this->url->link('checkout/success','','SSL');

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