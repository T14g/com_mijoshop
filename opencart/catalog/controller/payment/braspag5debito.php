<?php
require_once DIR_SYSTEM."../app/braspag5/loja5.php";
class ControllerPaymentBraspag5Debito extends Controller {
	
	public function index() {
		$this->load->model('checkout/order');
		$this->load->language('payment/cod');	
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		//ativa os cartoes
		$meios = $this->config->get('braspag5_provide_debito');
		if(empty($meios)){
		$meios = array();
		}
		$data['cpf'] = $this->config->get('braspag5_cpf');
		$data['afiliacao'] = $this->config->get('braspag5_id');
		$data['fiscal_ativo'] = false;
		$data['ativa_electron'] = in_array('electron',$meios)?1:0;
		$data['ativa_maestro'] = in_array('maestro',$meios)?1:0;
		$data['total'] = $order_info['total'];
		$data['pedido'] = $order_info;
		$data['criar'] = $this->url->link('payment/braspag5debito/criar','','SSL');
		$data['token'] = '';
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		return $this->load->view('payment/braspag5debito.tpl', $data);;
		}else{
		return $this->load->view('default/template/payment/braspag5debito.tpl', $data);
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
		$total_frete = $this->getFrete($pedido);
		$minimo = (float)$this->config->get('braspag5_minimo');
		$desconto = (float)$this->config->get('braspag5_desconto_credito');
		//corrije bug erro etapa2
		$total = number_format($order['total'], 2, '.', '');
		//inicio
		$linhas[''] = "-- Selecione o valor --";
		//pagamento a vista
		if($desconto>0){
			$desconto_valor = (($total-$total_frete)/100)*$desconto;
			$linhas[base64_encode('1|1|'.number_format(($total-$desconto_valor), 2, '.', '').'|'.base64_encode($_GET['id']).'|'.base64_encode($total).'|'.crypt(number_format($total-$desconto_valor, 2, '.', ''),$this->config->get('braspag5_id')))] = "&Agrave; vista por ".$this->formatar(number_format(($total-$desconto_valor), 2, '.', ''))." (j&aacute; com ".$desconto."% off)";
		}else{
			$linhas[base64_encode('1|1|'.$total.'|'.base64_encode($_GET['id']).'|'.base64_encode($total).'|'.crypt(number_format($total, 2, '.', ''),$this->config->get('braspag5_id')))] = "&Agrave; vista por ".$this->formatar(number_format(($total), 2, '.', ''))."";
		}
		}else{
			$linhas[''] = "(!acesso negado!)";
		}
		echo json_encode($linhas);
	}
	
	public function criar() {
		//dados do pedido
		$this->load->model('checkout/order');
		$pedidoloja = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		//verifica a parcela
		$quebrar = explode('|',base64_decode($_POST['parcelas']));
		$num_parcelas = $quebrar[0];//numero
		$tipo_parcela = $quebrar[1];//parcelado loja ou operadora
		$total_pagar  = $quebrar[2];//total final do pedido
		$hash_seguro  = $quebrar[5];
		$pedido_id = (int)$_POST['pedido'];
		$bandeira_cc = $_POST['bandeira'];
		switch($bandeira_cc){
			case 'electron':
			$bandeira = 'Visa';
			break;
			case 'maestro':
			$bandeira = 'Master';
			break;
			default:
			$bandeira = ucfirst($bandeira_cc);
		}
		
		//define o ambiente
		$operador = $this->config->get('braspag5_operadora');
		if($operador=='braspag'){
			if($this->config->get('braspag5_modo')=='0'){
			$provider = 'Simulado';
			$url_cielo = 'https://apisandbox.braspag.com.br/v2/';
			}else{
			$provider = 'Cielo';
			$url_cielo = 'https://api.braspag.com.br/v2/';
			}
		}else{
			if($this->config->get('braspag5_modo')=='0'){
			$provider = 'Simulado';
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

		//dados a enviar
		$dados = array(); 
		$dados['MerchantOrderId'] = $pedidoloja['order_id'];
		$dados['Customer'] = array(
			'Name'=>$pedidoloja['payment_firstname'].' '.$pedidoloja['payment_lastname'],
			'Email'=>$pedidoloja['email'],
		);	

		// $ipn = 'index.php?option=com_mijoshop&route=payment/braspag5debito/ipn&id='.$pedidoloja['order_id'].'&hash='.sha1(md5($pedidoloja['order_id'])).'';
		$ipn = $this->url->link('payment/braspag5debito/ipn','','SSL').'&id='.$pedidoloja['order_id'].'&hash='.sha1(md5($pedidoloja['order_id'])).'';
		$dados['Payment'] = array(
			'Type' => 'DebitCard',
			'Provider' => $provider,
			'Amount' => number_format($total_pagar, 2, '', ''),
            'ReturnUrl' => $ipn,
            'DebitCard' => array(  
				 "CardNumber" => preg_replace('/\D/', '', $_POST['numero']),
				 "Holder" => trim($_POST['titular']),
				 "ExpirationDate" => trim($_POST['validadem'].'/'.$_POST['validadea']),
				 "SecurityCode" => trim($_POST['codigo']),
				 "Brand" => $bandeira
			)
		);
		
		//print_r($dados);
		//exit;
		
		$response = $api->post("sales", json_encode($dados));
		
		//print_r($response);
		//exit;
		
		if(isset($response->status)){
			$dados_pedido = $this->obj2array($response->response);
			if($response->status==200 || $response->status==201){
				if(isset($dados_pedido['MerchantOrderId']) && $dados_pedido['Payment']['Status']==0){
					//log
					$log = 'Cart&atilde;o de D&eacute;bito '.ucfirst($dados_pedido['Payment']['DebitCard']['Brand']).' &agrave; Vista<br>';
					$tid = '';
					if(isset($dados_pedido['Payment']['Tid'])){
					$log .= 'TID: '.$dados_pedido['Payment']['Tid'].'<br>';
					$tid = $dados_pedido['Payment']['Tid'];
					}
					if(isset($dados_pedido['Payment']['AcquirerTransactionId'])){
					$log .= 'TID: '.$dados_pedido['Payment']['AcquirerTransactionId'].'<br>';
					$tid = $dados_pedido['Payment']['AcquirerTransactionId'];
					}
					if(isset($dados_pedido['Payment']['DebitCard']['CardNumber'])){
					$log .= 'Cart&atilde;o: '.$dados_pedido['Payment']['DebitCard']['CardNumber'].'<br>';
					}
					//pedido no banco de dados
					$sql = "INSERT INTO `" . DB_PREFIX . "braspag5_pedidos` (`id_braspag`, `id_pedido`, `total_pedido`, `total_pago`, `bandeira`, `parcelas`, `tid`, `lr`, `lr_log`, `status`, `bin`, `tipo`, `link`, `data`) VALUES (NULL, '".$pedidoloja['order_id']."', '".$pedidoloja['total']."', '".$pedidoloja['total']."', 'D&eacute;bito ".ucfirst($dados_pedido['Payment']['DebitCard']['Brand'])."', '1', '".$tid."', '', '', '".$dados_pedido['Payment']['Status']."', '".(isset($dados_pedido['Payment']['DebitCard']['CardNumber'])?$dados_pedido['Payment']['DebitCard']['CardNumber']:'')."', 'debito', '".$dados_pedido['Payment']['PaymentId']."', NOW());";
					$this->db->query($sql);	
					//cria o pedido na loja
					$this->model_checkout_order->addOrderHistory($pedidoloja['order_id'], $this->config->get('braspag5_in'), $log, true);
					//mota o json
					$json['erro'] = false;
					$cupom = $this->url->link('payment/braspag5tef/cupom', '', 'SSL').'&hash='.$dados_pedido['Payment']['PaymentId'];
					$json['href'] = isset($dados_pedido['Payment']['AuthenticationUrl'])?$dados_pedido['Payment']['AuthenticationUrl']:$cupom;
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
			default: 
			$status = "Desconhecido";
			$status_id = $this->config->get('braspag5_in');
			break;
		}
		return array('nome'=>$status,'id'=>$status_id);
	}
	
	public function ipn(){
		$this->load->model('checkout/order');
		//pega o pedido
		$linha = $this->db->query("SELECT * FROM `" . DB_PREFIX . "braspag5_pedidos` WHERE id_pedido = '".(int)$_GET['id']."' AND tipo = 'debito' LIMIT 1");
		//define o ambiente	
		if(isset($linha->row['link'])){
		if($this->config->get('braspag5_operadora')=='braspag'){
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
		$response = $api->get("sales/".$linha->row['link']."");
		if(isset($response->status)){
			$dados_pedido = $this->obj2array($response->response);
			if($response->status==200 || $response->status==201){
				$order_info = $this->model_checkout_order->getOrder($linha->row['id_pedido']);
				$statusatual = $order_info['order_status_id'];
				$status = $this->getStatus($dados_pedido['Payment']['Status']);
				if($statusatual != $status['id']){
					$this->model_checkout_order->addOrderHistory($linha->row['id_pedido'], $status['id'], $status['nome'], true);
				}
				// $redireciona = $this->url->link('index.php?option=com_mijoshop&route=payment/braspag5debito/cupom', '', 'SSL').'&hash='.$dados_pedido['Payment']['PaymentId'];
				$redireciona = $this->url->link('payment/braspag5debito/cupom', '', 'SSL').'&hash='.$dados_pedido['Payment']['PaymentId'];
			}else{
				$redireciona = $this->url->link('checkout/success', '', 'SSL');
			}
		}else{
			$redireciona = $this->url->link('checkout/success', '', 'SSL');
		}
		}else{
			$redireciona = $this->url->link('checkout/success', '', 'SSL');
		}
		$this->response->redirect($redireciona);
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