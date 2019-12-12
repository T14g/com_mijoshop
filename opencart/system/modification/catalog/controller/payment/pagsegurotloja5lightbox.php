<?php
include(DIR_SYSTEM."../app/pagsegurolightbox/serviceREST.php");
class ControllerPaymentPagSeguroTLoja5Lightbox extends Controller {
	public function index() {	
		$this->load->model('checkout/order');
		$this->language->load('payment/pagsegurotloja5lightbox');
		$data['button_confirm'] = $this->language->get('button_confirm');

		//opcoes
		$data['modo'] = ($this->config->get('pagsegurotloja5lightbox_modo'))?'':'sandbox.';

		//retorno
		$data['url_criar'] = $this->url->link('payment/pagsegurotloja5lightbox/criar','','SSL');
		
		//dados pedido
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		//tratamento do telefone
		$ddd = $tel = '';
		$tell_full = substr(preg_replace('/\D/', '', $order_info['telephone']),-11);
		if(strlen($tell_full)==10){
		$ddd = substr($tell_full,0,2);
		$tel = substr($tell_full,-8);
		}else if(strlen($tell_full)==11){
		$ddd = substr($tell_full,0,2);
		$tel = substr($tell_full,-9);
		}
		
		//se ja vai com fiscal
		$fiscal = '';
		$custom_fiscal = $this->config->get('pagsegurotloja5lightbox_fiscal');
		if(isset($order_info['custom_field'][$custom_fiscal])){
		$fiscal = preg_replace('/\D/', '', $order_info['custom_field'][$custom_fiscal]);	
		}
			
		//dados checkout
		$com = $this->config->get('pagsegurotloja5lightbox_com');
		$email_pagseguro = trim($this->config->get('pagsegurotloja5lightbox_email'));
		$token_pagseguro = trim($this->config->get('pagsegurotloja5lightbox_token'));
		$xmlps['email'] = trim($email_pagseguro);
		$xmlps['token'] = trim($token_pagseguro);
		$xmlps['currency'] = 'BRL';
		$xmlps['reference'] = $order_info['order_id'];
		$xmlps['senderName'] = utf8_decode(preg_replace('/\s+/', ' ',trim($order_info['firstname']).' '.trim($order_info['lastname'])));
		$xmlps['senderEmail'] = $order_info['email'];
		$xmlps['senderAreaCode'] = $ddd;
		$xmlps['senderPhone'] = $tel;
		if(strlen($fiscal)==11){
		$xmlps['senderCPF'] = $fiscal;	
		}elseif(strlen($fiscal)==14){
		$xmlps['senderCNPJ'] = $fiscal;	
		}
		$xmlps['notificationURL'] = $this->url->link('payment/pagsegurotloja5lightbox/ipn','','SSL');
		$xmlps['redirectURL'] = $this->url->link('payment/pagsegurotloja5lightbox/cupom','','SSL');
		$xmlps['extraAmount'] = number_format($this->getDescontos(), 2, '.', '');

		//produtos
		$i=1;
		$total_produtos = 0;
		foreach($this->cart->getProducts() AS $produto){
		$total_produtos+=$produto['price']*$produto['quantity'];
		$xmlps['itemId'.$i] = $produto['product_id'];
		$xmlps['itemDescription'.$i] = substr(utf8_decode($produto['name']), 0, 99);
		$xmlps['itemAmount'.$i] = number_format($produto['price'], 2, '.', '');
		$xmlps['itemQuantity'.$i] = $produto['quantity'];
		$i++;
		}

		//endereco de entrega se nao houver entrega
		if($order_info['shipping_zone_id']==0){
		$numero = $this->config->get('pagsegurotloja5lightbox_numero');
		$numero_logradouro = (isset($order_info['payment_custom_field'][$numero]))?$order_info['payment_custom_field'][$numero]:preg_replace('/\D/', '', $order_info['payment_address_1']);
		$xmlps['shippingType'] = 3;
		$xmlps['shippingCost'] = number_format($this->getFrete(), 2, '.', '');
		$xmlps['shippingAddressStreet'] = utf8_decode(trim(str_replace(',','',preg_replace('/\d+/','',$order_info['payment_address_1']))));
		$xmlps['shippingAddressNumber'] = $numero_logradouro;
		$xmlps['shippingAddressComplement'] = (isset($order_info['payment_custom_field'][$com]))?$order_info['payment_custom_field'][$com]:'';
		$xmlps['shippingAddressDistrict'] = utf8_decode((empty($order_info['payment_address_2'])?'Bairro':$order_info['payment_address_2']));
		$xmlps['shippingAddressPostalCode'] = preg_replace('/\D/', '', $order_info['payment_postcode']);
		$xmlps['shippingAddressCity'] = utf8_decode($order_info['payment_city']);
		$xmlps['shippingAddressState'] = $order_info['payment_zone_code'];	
		}else{
		//se houver
		$numero = $this->config->get('pagsegurotloja5lightbox_numero');
		$numero_logradouro = (isset($order_info['shipping_custom_field'][$numero]))?$order_info['shipping_custom_field'][$numero]:preg_replace('/\D/', '', $order_info['shipping_address_1']);
		$xmlps['shippingType'] = 3;
		$xmlps['shippingCost'] = number_format($this->getFrete(), 2, '.', '');
		$xmlps['shippingAddressStreet'] = utf8_decode(trim(str_replace(',','',preg_replace('/\d+/','',$order_info['shipping_address_1']))));
		$xmlps['shippingAddressNumber'] = $numero_logradouro;
		$xmlps['shippingAddressComplement'] =(isset($order_info['shipping_custom_field'][$com]))?$order_info['shipping_custom_field'][$com]:'';
		$xmlps['shippingAddressDistrict'] = utf8_decode((empty($order_info['shipping_address_2'])?'Bairro':$order_info['shipping_address_2']));
		$xmlps['shippingAddressPostalCode'] = preg_replace('/\D/', '', $order_info['shipping_postcode']);
		$xmlps['shippingAddressCity'] = utf8_decode($order_info['shipping_city']);
		$xmlps['shippingAddressState'] = $order_info['shipping_zone_code'];		
		}
		$xmlps['shippingAddressCountry'] = 'BRA';
		
		//email teste
		if($this->config->get('pagsegurotloja5lightbox_modo')==0){
		$xmlps['senderEmail'] = 'sandbox'.time().'@sandbox.pagseguro.com.br';	
		}
		
		//conecta ao pagseguro
		$data['erro'] = false;
		$sandbox = ($this->config->get('pagsegurotloja5lightbox_modo') == "0" ? 'sandbox.' : '');
		$aut = "https://ws.".$sandbox."pagseguro.uol.com.br/v2/checkout";
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL,$aut);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($xmlps));		
		$response = curl_exec($curl); 
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string($response);
		if($xml){
		if(isset($xml->error) || isset($xml->errors)){
		$data['erro'] = true;
		$data['msg'] = (isset($xml->error)?var_export($xml->error,true):var_export($xml->errors,true));
		}else{
		$xml = $this->object2array($xml);
		$data['pagseguro'] = $xml;
		}
		}else{
		$data['erro'] = true;
		switch($response){
		case 'Unauthorized':
		$data['msg'] = 'Token/email não autorizado pelo PagSeguro '.$sandbox.'. Verifique suas configurações no painel.';
		break;
		case 'Forbidden':
		$data['msg'] = 'Acesso não autorizado à Api Pagseguro '.$sandbox.'. Verifique se você tem permissão para usar este serviço. Retorno: ' . var_export($response,true);
		break;
		default:
		$data['msg'] = 'Retorno inesperado do PagSeguro '.$sandbox.'. Retorno: ' . var_export($response,true);
		}	
		}
		
		//se erro
		if($data['erro']){
			$this->log->write("Erro Pagseguro CHECKOUT - ".$data['msg']);
			echo '<div class="error alert alert-danger">'.$data['msg'].'</div>';
			exit;
		}
		
		if(version_compare(VERSION, '2.1.9.9', '<=')){
            return $this->load->view('default/template/payment/pagsegurotloja5lightbox.tpl', $data);
		}else{
            return $this->load->view('payment/pagsegurotloja5lightbox.tpl', $data);	
		}
	}
	
	public function criar() {
		$this->load->model('checkout/order');
		$c = new clientREST();
		$email_pagseguro = trim($this->config->get('pagsegurotloja5lightbox_email'));
		$token_pagseguro = trim($this->config->get('pagsegurotloja5lightbox_token'));
		$sandbox = ($this->config->get('pagsegurotloja5lightbox_modo') == 0 ? 'sandbox.' : '');
		$response = $c->execRequest('https://ws.'.$sandbox.'pagseguro.uol.com.br/v3/transactions/'.$_GET['id'].'','get','email='.$email_pagseguro.'&token='.$token_pagseguro.'');
		libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response);
		$data['erro'] = false;
		//se tem erro
		if($xml){
			if(isset($xml->error) || isset($xml->errors)){
				$data['erro'] = true;
				$data['msg'] = (isset($xml->error)?$xml->error->message:var_export($xml->errors,true));
				$this->log->write("Erro Pagseguro CRIAR - ".$data['msg']);
			}else{
				//inicia
				$dados_pagseguro = $this->object2array($xml);
				$pedido_original = $dados_pagseguro['reference'];
				$pedidos = $this->model_checkout_order->getOrder($pedido_original);
				$status_id = $this->getStatusPagamento($dados_pagseguro['status']);
				//se o pedido for zerado cria ou atualizar
				if($status_id['id']){
					$meio = $this->tipoPagamento($dados_pagseguro['paymentMethod']['type']);
					$log = $meio.' - '.$dados_pagseguro['code'];
					if(isset($dados_pagseguro['paymentLink']) && $dados_pagseguro['status']==1){
					$log .= ' - <a href="'.$dados_pagseguro['paymentLink'].'" target="_blank">[link de pagamento]</a>';
					}
					if($pedidos['order_status_id']==0){
					$this->model_checkout_order->addOrderHistory($pedido_original,$status_id['id'],$log,true);
					}	
				}
				?>
				<script>location.href='<?php echo $this->url->link('payment/pagsegurotloja5lightbox/cupom','','SSL');?>&id=<?php echo $dados_pagseguro['code'];?>';</script>
			<?php
			}
		}else{
			$data['erro'] = true;
			switch($response){
			case 'Unauthorized':
			$data['msg'] = 'Token/email não autorizado pelo PagSeguro '.$sandbox.'. Verifique suas configurações no painel.';
			break;
			case 'Forbidden':
			$data['msg'] = 'Acesso não autorizado à Api Pagseguro '.$sandbox.'. Verifique se você tem permissão para usar este serviço. Retorno: ' . var_export($response,true);
			break;
			default:
			$data['msg'] = 'Retorno inesperado do PagSeguro '.$sandbox.'. Retorno: ' . var_export($response,true);
			}
		}
		//se erro
		if($data['erro']){
			echo '<div style="color:red">'.$data['msg'].'<br>- atualize a p&aacute;gina</div>';
			exit;
		}

	}
	
	public function tipoPagamento($tipo){
		if($tipo==1){
			return 'Cartão de Crédito';
		}elseif($tipo==2){
			return 'Boleto Bancário';
		}elseif($tipo==3){
			return 'Débito Online';
		}elseif($tipo==4){
			return 'Saldo Pagseguro';
		}elseif($tipo==5){
			return 'Oi Paggo';
		}elseif($tipo==7){
			return 'Deposito Bancário';
		}
	}
	
	public function getDescontos(){
		$query = $this->db->query("SELECT SUM(value) AS desconto FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$this->session->data['order_id'] . "' AND value < 0");
		if(!isset($query->row['desconto'])){
		return 0;	
		}
		return $query->row['desconto'];
	}
	
	public function getFrete(){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$this->session->data['order_id'] . "' AND code = 'shipping'");
		if(!isset($query->row['value'])){
		return 0;	
		}
		return $query->row['value'];
	}
	
	public function cupom(){
		$this->load->model('checkout/order');
		$this->document->setTitle('Resultado da Transa&ccedil;&atilde;o');
		$this->document->setDescription('');
		$this->document->setKeywords('');
		//pega a id da transacao
		if(isset($_GET['id'])){
		$idps = $_GET['id'];	
		}elseif(isset($_GET['transaction_id'])){
		$idps = $_GET['transaction_id'];	
		}else{
		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();
		}
		?>
		<script>location.href='<?php echo $this->url->link('account/order','','SSL');?>';</script>
		<?php	
		exit;
		}
		$email_pagseguro = trim($this->config->get('pagsegurotloja5lightbox_email'));
		$token_pagseguro = trim($this->config->get('pagsegurotloja5lightbox_token'));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$c = new clientREST();
		$sandbox = ($this->config->get('pagsegurotloja5lightbox_modo') == 0 ? 'sandbox.' : '');
		$response = $c->execRequest('https://ws.'.$sandbox.'pagseguro.uol.com.br/v3/transactions/'.$idps.'','get','email='.$email_pagseguro.'&token='.$token_pagseguro.'');
		libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response);
		$dados_pagseguro = $this->object2array($xml);
		$order_info = $this->model_checkout_order->getOrder($dados_pagseguro['reference']);
		$data['iframe'] = $this->url->link('checkout/success','','SSL');
		$data['msg'] = '';
		$data['dados'] = $dados_pagseguro;
		$data['order'] = $order_info;
		$data['pedido'] = $order_info['order_id'];
		$data['status'] = $this->getStatusPagamento($dados_pagseguro['status']);
		if(version_compare(VERSION, '2.1.9.9', '<=')){
		$this->response->setOutput($this->load->view('default/template/payment/pagsegurotloja5lightbox_recibo.tpl', $data));
		}else{
		$this->response->setOutput($this->load->view('payment/pagsegurotloja5lightbox_recibo.tpl', $data));
		}
	}
	
	public function object2array($object) {
	return @json_decode(@json_encode($object),true); 
	}
	
	public function so_numeros($num){
		return preg_replace('/\D/', '', $num);
	}
	
	public function ipn(){
		$this->load->model('checkout/order');
		if(isset($_REQUEST['notificationType']) && $_REQUEST['notificationType']=='transaction'){	
			$c = new clientREST();
			$sandbox = ($this->config->get('pagsegurotloja5lightbox_modo') == "0" ? 'sandbox.' : '');
			$dados['email'] = trim($this->config->get('pagsegurotloja5lightbox_email'));
			$dados['token'] = trim($this->config->get('pagsegurotloja5lightbox_token'));	
			$response = $c->execRequest('https://ws.'.$sandbox.'pagseguro.uol.com.br/v2/transactions/notifications/'.$_REQUEST['notificationCode'].'','get',$dados);
			libxml_use_internal_errors(true);
			$xml = simplexml_load_string($response);
			$ret = $this->object2array($xml);
			
			//se encontrou o code
			if(isset($ret['code'])){
				
				$pedidoId = $this->so_numeros($ret['reference']);
				$status = $ret['status'];
				$transacao = $ret['code'];
				
				//verifica se e um pedido valido
				if($pedidoId>0){
				//dados do pedido e status
				$pedidos = $this->model_checkout_order->getOrder($pedidoId);
				$status_id = $this->getStatusPagamento($status);
				
				//se o pedido for zerado cria ou atualizar
				if($status_id['id']){
				$meio = $this->tipoPagamento($ret['paymentMethod']['type']);
				$log = $meio.' - '.$ret['code'];
				if(isset($ret['paymentLink']) && $status==1){
				$log .= ' - <a href="'.$ret['paymentLink'].'" target="_blank">[link de pagamento]</a>';
				}
				if($pedidos['order_status_id']!=$status_id['id']){
				$this->model_checkout_order->addOrderHistory($pedidoId,$status_id['id'],$log,true);
				}
				}
				echo 'OK';
				}
			}
		}
    }
	
	private function getStatusPagamento($status) {
			switch($status) {
				case "1": 
				$status = "Aguardando Pagamento";
				$status_id = $this->config->get('pagsegurotloja5lightbox_in');
				break;
				case "2":				
				$status = "Em Analise";
				$status_id = $this->config->get('pagsegurotloja5lightbox_pe');
				break;
				case "3": 
				$status = "Pago";
				$status_id = $this->config->get('pagsegurotloja5lightbox_pg');
				break;
				case "7":
				$status = "Cancelado";
				$status_id = $this->config->get('pagsegurotloja5lightbox_ca');
				break;	
				case "6":
				$status = "Devolvido";
				$status_id = $this->config->get('pagsegurotloja5lightbox_de');
				break;	
				case "9":
				$status = "Em contestacao";
				$status_id = $this->config->get('pagsegurotloja5lightbox_di');
				break;	
				default:
				$status = false;
				$status_id = false;
			}
			return array('nome'=>$status,'id'=>$status_id);
	}
}
?>