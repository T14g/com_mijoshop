<?php
include(DIR_SYSTEM.'class_fiscal.php');
class ControllerCheckoutCompraExpress extends Controller {
    
    public function ver_itens_html(){
        $this->response->setOutput($this->ver_itens());
    }
    
    public function ver_itens(){
        $this->load->model('tool/image');
		$this->load->model('tool/upload');
        //produtos
        $data['products'] = array();
        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();
            foreach ($product['option'] as $option) {
                if ($option['type'] != 'file') {
                    $value = $option['value'];
                } else {
                    $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);
                    if ($upload_info) {
                        $value = $upload_info['name'];
                    } else {
                        $value = '';
                    }
                }
                $option_data[] = array(
                    'name'  => $option['name'],
                    'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                );
            }
            $data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'quantity'   => $product['quantity'],
                'price'      => $this->formatar($product['price']),
                'recurring'  => '',
                'total'      => $this->formatar($product['total']),
                'href'       => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                'imagem' => $this->model_tool_image->resize($product['image'], 80, 80),
            );
        }
        //gift
		$data['vouchers'] = array();
        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $data['vouchers'][] = array(
                    'description' => $voucher['description'],
                    'amount'      => $this->formatar($voucher['amount'])
                );
            }
        }
        //totais
        $order_data = array();
        if(version_compare(VERSION, '2.2.0.0', '>=')){
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);
			$this->load->model('extension/extension');
			$sort_order = array();
			$results = $this->model_extension_extension->getExtensions('total');
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			array_multisort($sort_order, SORT_ASC, $results);
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
					$this->{'model_total_' . $result['code']}->getTotal($total_data);
				}
			}
			$sort_order = array();
			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			array_multisort($sort_order, SORT_ASC, $totals);
			$order_data['totals'] = $totals;
        }else{
			$order_data['totals'] = array();
			$total = 0;
			$taxes = $this->cart->getTaxes();
			$this->load->model('extension/extension');
			$sort_order = array();
			$results = $this->model_extension_extension->getExtensions('total');
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			array_multisort($sort_order, SORT_ASC, $results);
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					$this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
				}
			}
			$sort_order = array();
			foreach ($order_data['totals'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			array_multisort($sort_order, SORT_ASC, $order_data['totals']);
        }
        foreach ($order_data['totals'] as $total) {
            $data['totals'][] = array(
                'title' => $total['title'],
                'text'  => $this->formatar($total['value']),
            );
        }
        //template 
        $this->load->language('checkout/checkout');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_quantity'] = $this->language->get('column_quantity');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_total'] = $this->language->get('column_total');
        $data['payment'] = '';
        if(version_compare(VERSION, '2.2.0.0', '>=')){
            return $this->load->view('checkout/confirm.tpl', $data);
        }else{
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
                return $this->load->view($this->config->get('config_template') . '/template/checkout/confirm.tpl', $data);
            } else {
                return $this->load->view('default/template/checkout/confirm.tpl', $data);
            }
        }
    }
    
    public function formatar($valor){
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		return $this->currency->format($valor,$this->session->data['currency']);
		}else{
		return $this->currency->format($valor);
		}
	}
	
	public function formsenha(){
		$data['email'] = isset($this->session->data['email_rapido'])?$this->session->data['email_rapido']:'';
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		$this->response->setOutput($this->load->view('compraexpress/formsenha.tpl', $data));
		}else{
		$this->response->setOutput($this->load->view('default/template/compraexpress/formsenha.tpl', $data));	
		}
	}
	
	public function formlogin(){
		$data['email'] = isset($this->session->data['email_rapido'])?$this->session->data['email_rapido']:'';
		$data['facebook_ativado'] = $this->config->get('compraexpress_fb');
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		$this->response->setOutput($this->load->view('compraexpress/formlogin.tpl', $data));
		}else{
		$this->response->setOutput($this->load->view('default/template/compraexpress/formlogin.tpl', $data));	
		}
	}
	
	public function formcupom(){
		$data['cupom'] = isset($this->session->data['coupon'])?$this->session->data['coupon']:'';
		$data['vale'] = isset($this->session->data['voucher'])?$this->session->data['voucher']:'';
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		$this->response->setOutput($this->load->view('compraexpress/formcupom.tpl', $data));
		}else{
		$this->response->setOutput($this->load->view('default/template/compraexpress/formcupom.tpl', $data));	
		}
	}
	
	public function shipping() {
		$this->load->language('checkout/shipping');
		$json = array();
		if (!empty($this->request->post['shipping_method'])) {
			$shipping = explode('.', $this->request->post['shipping_method']);
			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$json['warning'] = $this->language->get('error_shipping');
			}
		} else {
			$json['warning'] = $this->language->get('error_shipping');
		}
		if (!$json) {
			$shipping = explode('.', $this->request->post['shipping_method']);
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			$this->session->data['success'] = $this->language->get('text_success');
			$json['redirect'] = $this->url->link('checkout/cart');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function atualizarenderecoform(){
		$this->load->model('account/customer');
        $this->load->model('account/compraexpress');
		$this->load->model('account/compraexpress');
		$this->load->model('localisation/zone');
		$this->load->model('localisation/country');
		$this->load->model('account/address');
		if($this->customer->isLogged()){
        //custom da loja
		$this->load->model('account/custom_field');
		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->customer->getGroupId());
		//lista de enderecos
		$data['enderecos'] = $this->model_account_address->getAddresses();
		//dados custom_field
		$data['numero'] = $this->config->get('compraexpress_numero');
		$data['complemento'] = $this->config->get('compraexpress_complemento');
		//lista os estados padrao
		$data['lista_estados'] =$this->model_localisation_zone->getZonesByCountryId($this->config->get('config_country_id'));
		//paises
		$data['countries'] = $this->model_localisation_country->getCountries();
		$data['pais_padrao'] = $this->config->get('config_country_id');
		$data['tipocad'] = $_GET['tipo'];
		$data['tipo'] = $this->config->get('compraexpress_tipo');
		$data['acao'] = 'atualizar';
		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
		$data['id_inicial'] = (isset($_GET['id'])?$_GET['id']:$customer_info['address_id']);
        $end = $this->model_account_compraexpress->getAddressById($data['id_inicial']);
        $data['custom'] = isset($end['custom_field'])?$end['custom_field']:array();

		if(version_compare(VERSION, '2.2.0.0', '>=')){
            $this->response->setOutput($this->load->view('compraexpress/formendereco.tpl', $data));
		}else{
            $this->response->setOutput($this->load->view('default/template/compraexpress/formendereco.tpl', $data));	
		}
		
		}else{
		die('Acesso negado!');
		}
	}
	
	public function atualizarclienteform(){
		$this->load->model('account/customer');
		if($this->customer->isLogged()){
		//dados custom_field
        $data['exibir_erro'] = $this->config->get('compraexpress_erro');
		$data['fiscal'] = $this->config->get('compraexpress_fiscal');
        $data['fiscalpj'] = $this->config->get('compraexpress_fiscal');
		$data['tipo'] = $this->config->get('compraexpress_tipo');
		$data['dados_cliente'] = $this->model_account_customer->getCustomer($this->customer->getId());
        
        //custom da loja
		$this->load->model('account/custom_field');
		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->customer->getGroupId());
		
		if (version_compare(VERSION, '2.0.3.1', '<=')) {
			$data['custom'] = @unserialize($data['dados_cliente']['custom_field']);
		} else {
			$data['custom'] = @json_decode($data['dados_cliente']['custom_field'],true);
		}
		
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		$this->response->setOutput($this->load->view('compraexpress/formcliente.tpl', $data));
		}else{
		$this->response->setOutput($this->load->view('default/template/compraexpress/formcliente.tpl', $data));	
		}
		
		}else{
		die('Acesso negado!');
		}
	}
	
	public function customcss(){
        @header("Content-Type: text/css");	
        echo htmlspecialchars_decode($this->config->get('compraexpress_css'));
        exit;
	}
	
	public function customjs(){
        @header("Content-type: text/javascript");
        echo htmlspecialchars_decode($this->config->get('compraexpress_js'));
        exit;
	}
	
	public function index() {
		//valida se ah produtos e possui estoque
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart'));
		}

		//valida as quantidades minimas
		$products = $this->cart->getProducts();
		
		//cria session comentario inicial
		if(!isset($this->session->data['comment'])){
            $this->session->data['comment'] = '';
		}
		$this->session->data['agree'] = true;
		
		//ativa ou nao o fb
		$data['facebook_ativado'] = $this->config->get('compraexpress_fb');

		//ver se os produtos estao com qtd minima
		foreach ($products as $product) {
			$product_total = 0;
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}
			if ($product['minimum'] > $product_total) {
				$this->response->redirect($this->url->link('checkout/cart'));
			}
		}
		$this->load->language('checkout/checkout');
		$this->document->setTitle($this->language->get('heading_title'));

		//scripts e css
		$this->document->addScript('catalog/view/checkoutexpress/libs.jquery.js');
		$this->document->addScript('catalog/view/checkoutexpress/bootbox.min.js');
		$this->document->addScript('catalog/view/checkoutexpress/compraexpress.js');
		//$this->document->addScript('index.php?route=checkout/compraexpress/customjs');
		if($this->config->get('compraexpress_te')==0){
            $this->document->addStyle('catalog/view/checkoutexpress/stylesheet.css');
            $tema = $this->config->get('compraexpress_tema');
            if(!empty($tema)){
                $this->document->addStyle($this->config->get('compraexpress_tema'));	
            }
		}
		//$this->document->addStyle('index.php?route=checkout/compraexpress/customcss');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_cart'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Finalizar Compra',
			'href' => $this->url->link('checkout/compraexpress', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_checkout_option'] = $this->language->get('text_checkout_option');
		$data['text_checkout_account'] = $this->language->get('text_checkout_account');
		$data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
		$data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
		$data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
		$data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
		$data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		$data['logged'] = $this->customer->isLogged();

		if (isset($this->session->data['account'])) {
			$data['account'] = $this->session->data['account'];
		} else {
			$data['account'] = '';
		}

		$data['shipping_required'] = $this->cart->hasShipping();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		
        $data['exibir_erro'] = $this->config->get('compraexpress_erro');
		if($this->config->get('compraexpress_te')==0){
		$data['footer'] = $this->load->controller('common/rodapecompraexpress');
		$data['header'] = $this->load->controller('common/topocompraexpress');	
		}else{
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		}
		
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		$this->response->setOutput($this->load->view('compraexpress/compraexpress.tpl', $data));
		}else{
		$this->response->setOutput($this->load->view('default/template/compraexpress/compraexpress.tpl', $data));	
		}
	}
	
	public function jsonendereco(){
		$this->load->model('account/customer');
		$this->load->model('account/compraexpress');
		$data['campo_fiscal'] = $this->config->get('compraexpress_fiscal');
		$data['campo_numero'] = $this->config->get('compraexpress_numero');
		$data['campo_complemento'] = $this->config->get('compraexpress_complemento');
		if($this->customer->isLogged()){
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$end = $this->model_account_compraexpress->getAddressById((isset($_GET['id'])?$_GET['id']:$customer_info['address_id']));
			$jsoncliente = $end['custom_field'];
			$dados['id']=$end['address_id'];
			$dados['nome']=$end['firstname'];
			$dados['sobrenome']=$end['lastname'];
			$dados['logradouro']=$end['address_1'];
			$dados['bairro']=$end['address_2'];
			$dados['cidade']=$end['city'];
			$dados['uf']=$end['zone_id'];
			$dados['cep']=$end['postcode'];
			$dados['numero']=isset($jsoncliente[$data['campo_numero']])?$jsoncliente[$data['campo_numero']]:'';
			$dados['complemento']=isset($jsoncliente[$data['campo_complemento']])?$jsoncliente[$data['campo_complemento']]:'';
			die(json_encode($dados));
		}else{
			die(json_encode(0));
		}
	}
	
	public function atualizarend(){
        $dados = array();
		$this->load->model('account/address');
		$this->load->model('account/customer');
		if($this->customer->isLogged()){
			
			//dados custom_field
			$data['fiscal'] = $this->config->get('compraexpress_fiscal');
			$data['numero'] = $this->config->get('compraexpress_numero');
			$data['complemento'] = $this->config->get('compraexpress_complemento');
			
			//atualiza um endereço
			if(isset($_POST['acao']) && $_POST['acao']=='atualizar'){
			
			if($_POST['tipo']=='entrega'){
                $this->session->data['id_endereco_e'] = (int)$_POST['idendereco'];	
			}elseif($_POST['tipo']=='cobranca'){
                $this->session->data['id_endereco_c'] = (int)$_POST['idendereco'];	
			}
			
			$nome_completo = explode(' ',preg_replace('/\s+/', ' ',trim($this->request->post['nome_completo'])),2);
			$dados['firstname']=strtoupper(trim($nome_completo[0]));
			$dados['lastname']=strtoupper(trim($nome_completo[1]));
			$dados['company']='';
			$dados['address_1']=$_POST['logradouro'];
			$dados['address_2']=$_POST['bairro'];
			$dados['postcode']=$_POST['cep'];
			$dados['city']=$_POST['cidade'];
			$dados['zone_id']=$_POST['uf'];
			$dados['custom_field'][$data['numero']]=$_POST['numero'];
			$dados['custom_field'][$data['complemento']]=$_POST['complemento'];
			$dados['country_id'] = $_POST['pais'];
            
            //merge campos Address
            if(isset($this->request->post['custom_field'])){
                foreach($this->request->post['custom_field'] as $key=>$valor){
                    $dados['custom_field'][$key]=$valor;
                }
            }
            //die(print_r($dados));
            
			die(json_encode($this->model_account_address->editAddress($_POST['idendereco'], $dados)));
			
			}elseif(isset($_POST['acao']) && $_POST['acao']=='adicionar'){
				
			$nome_completo = explode(' ',preg_replace('/\s+/', ' ',trim($this->request->post['nome_completo'])),2);
			$dados['firstname']=strtoupper(trim($nome_completo[0]));
			$dados['lastname']=strtoupper(trim($nome_completo[1]));
			$dados['company']='';
			$dados['address_1']=$_POST['logradouro'];
			$dados['address_2']=$_POST['bairro'];
			$dados['postcode']=$_POST['cep'];
			$dados['city']=$_POST['cidade'];
			$dados['zone_id']=$_POST['uf'];
			$dados['custom_field'][$data['numero']]=$_POST['numero'];
			$dados['custom_field'][$data['complemento']]=$_POST['complemento'];
			$dados['country_id'] = $_POST['pais'];
            
            //merge campos Address
            if(isset($this->request->post['custom_field'])){
                foreach($this->request->post['custom_field'] as $key=>$valor){
                    $dados['custom_field'][$key]=$valor;
                }
            }
            //die(print_r($dados));
				
			$novo_add_id = $this->model_account_address->addAddress($dados);
			
			if($_POST['tipo']=='entrega'){
                $this->session->data['id_endereco_e'] = $novo_add_id;	
			}elseif($_POST['tipo']=='cobranca'){
                $this->session->data['id_endereco_c'] = $novo_add_id;	
			}
			
			die(json_encode(1));
			
			}
		}else{
			die(json_encode(0));
		}
	}

	public function atualizarcliente(){
        $data = array();
		$this->load->model('account/customer');
		if($this->customer->isLogged()){
            
            //nome completo
			$nome_completo = explode(' ',preg_replace('/\s+/', ' ',trim($this->request->post['nome'])),2);
            
            //valida o fiscal
			$campo_fiscal = $this->config->get('compraexpress_fiscal');
            $campo_fiscalpj = $this->config->get('compraexpress_fiscal');
            $cpf_cnpj = new ValidaCPFCNPJ($this->request->post['fiscal']); 
            $tipo_cad = $cpf_cnpj->valida_tipo();
            if($tipo_cad['tipo']=='pj'){
            $data['custom_field'][$campo_fiscalpj]=$this->request->post['fiscal'];
            $data['customer_group_id'] = $this->config->get('compraexpress_grupopj');
            }else{
            $data['customer_group_id'] = $this->config->get('compraexpress_grupopf');
            $data['custom_field'][$campo_fiscal]=$this->request->post['fiscal'];  
            }
            
            //merge campos extras conta
            if(isset($this->request->post['custom_field'])){
                foreach($this->request->post['custom_field'] as $key=>$valor){
                    $data['custom_field'][$key]=$valor;
                }
            }
            //die(print_r($data));

            if (version_compare(VERSION, '2.0.3.1', '<=')) {
                $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '".$data['customer_group_id']."', firstname = '" . $this->db->escape(trim((isset($nome_completo[0])?$nome_completo[0]:''))) . "', lastname = '" . $this->db->escape(trim((isset($nome_completo[1])?$nome_completo[1]:''))) . "', telephone = '" . $this->db->escape($_POST['telefone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . $this->customer->getId() . "'");
            } else {
                $this->db->query("UPDATE " . DB_PREFIX . "customer SET customer_group_id = '".$data['customer_group_id']."', firstname = '" . $this->db->escape(trim((isset($nome_completo[0])?$nome_completo[0]:''))) . "', lastname = '" . $this->db->escape(trim((isset($nome_completo[1])?$nome_completo[1]:''))) . "', telephone = '" . $this->db->escape($_POST['telefone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE customer_id = '" . $this->customer->getId() . "'");
            }
			echo $this->customer->getId();
            
		}else{
			echo '0';
		}		
	}
	
	public function estalogado(){
		$this->load->model('account/customer');
		$this->load->model('account/address');
		$this->load->model('localisation/zone');

		//ativa ou nao o fb
		$data['facebook_ativado'] = $this->config->get('compraexpress_fb');	
        $data['exibir_erro'] = $this->config->get('compraexpress_erro');

		//se o carrinho estiver vazio ou produtos sem estoque
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {

		if(version_compare(VERSION, '2.2.0.0', '>=')){
            $this->response->setOutput($this->load->view('compraexpress/carrinho_vazio.tpl', $data));
		}else{
            $this->response->setOutput($this->load->view('default/template/compraexpress/carrinho_vazio.tpl', $data));
		}
		
		//se o carrinho estiver OK
		}else{
			
		//se o cliente estiver logado pega os dados dele
		if($this->customer->isLogged()){
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			
			//dados custom_field
			$data['fiscal'] = $this->config->get('compraexpress_fiscal');
            $data['fiscalpj'] = $this->config->get('compraexpress_fiscal');
			$data['numero'] = $this->config->get('compraexpress_numero');
			$data['complemento'] = $this->config->get('compraexpress_complemento');
			$data['obrigar'] = true;
			$data['com'] = $this->config->get('compraexpress_com');
			
			//custom
			$data['tipo'] = $this->config->get('compraexpress_tipo');
			$data['nomedaloja'] = $this->config->get('config_name');
			
			//dados do cliente
			if($customer_info){
                
            //verifica o endereco do cliente
            if($customer_info['address_id']==0){
                $this->load->model('account/compraexpress');
                $endereco = $this->model_account_compraexpress->getAddress($customer_info['customer_id']);
                if($endereco['address_id']==0){
                    $this->session->data['success'] = 'Cadastre um endere&ccedil;o para continuar!';
                    die('<script>location.href="index.php?route=account/address/add";</script>');
                }else{
                    $customer_info['address_id'] = $endereco['address_id'];
                }
            }
			
			//define os ids de endereco padrao
			$data['id_endereco_c'] = isset($this->session->data['id_endereco_c'])?$this->session->data['id_endereco_c']:$customer_info['address_id'];
			$data['id_endereco_e'] = isset($this->session->data['id_endereco_e'])?$this->session->data['id_endereco_e']:$customer_info['address_id'];
			
			//dados do cliente
			$data['dados_cliente'] = $customer_info;
			
			if (version_compare(VERSION, '2.0.3.1', '<=')) {
				$data['jsoncliente'] = @unserialize($customer_info['custom_field']);
			} else {
				$data['jsoncliente'] = @json_decode($customer_info['custom_field'],true);
			}
			
			//forcar cliente atualizar o cadastro
			$clientevazio = 0;
			$clienteobrigatorios = array('email','firstname','telephone');
			if(is_array($customer_info)){
			foreach($customer_info AS $k=>$v){
				
			if (version_compare(VERSION, '2.0.3.1', '<=')) {
				$this->session->data['guest'][$k] = ($k=='custom_field')?@unserialize($v):$v;
			} else {
				$this->session->data['guest'][$k] = ($k=='custom_field')?@json_decode($v,true):$v;
			}
			
			//campos fixos
			if(in_array($k,$clienteobrigatorios) && empty($v)){
				$clientevazio++;
			}
			//campos custons
			if($k=='custom_field'){
				
			if (version_compare(VERSION, '2.0.3.1', '<=')) {
				$jsoncliente = @unserialize($v);
			} else {
				$jsoncliente = @json_decode($v,true);
			}
			
				if(!isset($jsoncliente[$data['fiscal']]) || empty($jsoncliente[$data['fiscal']])){
					$clientevazio++;
				}
			}
			}
			}
			
			//pega o email da session
			$data['email'] = isset($this->session->data['email_rapido'])?$this->session->data['email_rapido']:'';
			
			//pega os endereços do cliente
			$data['endereco_entrega'] = $this->model_account_address->getAddress($data['id_endereco_e']);
			$data['endereco_cobranca'] = $this->model_account_address->getAddress($data['id_endereco_c']);
			
			//forca atualizar o endereço de entrega
			$endvazio = 0;
			$endobrigatorios = array('address_1','firstname','address_2','city','zone_code','postcode');
			if(is_array($data['endereco_entrega'])){
			foreach($data['endereco_entrega'] AS $k=>$v){
				//campos fixos
				if(in_array($k,$endobrigatorios) && empty($v)){
				$endvazio++;
				}
				//campos custons
				if($k=='custom_field' && $data['numero']>0){
				$jsonend = $v;
				if(!isset($jsonend[$data['numero']]) || empty($jsonend[$data['numero']])){
					$endvazio++;
				}
				}
			}
			}
			
			//total vazios
			$data['total_vazios'] = ($data['obrigar'])?($endvazio+$clientevazio):0;
			$data['total_vazios_end'] = ($data['obrigar'])?($endvazio):0;
			$data['total_vazios_cli'] = ($data['obrigar'])?($clientevazio):0;

			//salva as sessions dos endereços
			$this->session->data['payment_address'] = $data['endereco_cobranca'];
			$this->session->data['shipping_address'] = $data['endereco_entrega'];
			
			//pega os meios de entrega baseado no endereço
			$data['requer_entrega'] = $this->cart->hasShipping();
			if($data['requer_entrega']){
			$data['meios_entrega'] = $this->getvaloresdeentrega($data['endereco_entrega']);
			}else{
			$data['meios_entrega'] = false;	
			}
			$this->session->data['shipping_methods'] = $data['meios_entrega'];
			
			//ver se ja existe um meio de entrega salvo
			$data['meio_escolhido'] = isset($this->session->data['shipping_method']['code'])?$this->session->data['shipping_method']['code']:false;
			
			//pega os meios de pagamento
			$data['meios_pagamento'] = $this->getmeiospagamento($data['endereco_entrega']);
			$this->session->data['payment_methods'] = $data['meios_pagamento'];
			
			//ver se ja existe um meio de pagamento salvo
			$data['pagamento_escolhido'] = isset($this->session->data['payment_method'])?$this->session->data['payment_method']:false;
			
			//comentario
			$data['comentario'] = @$this->session->data['comment'];
			
			//carrinho topo
			$data['cart'] = $this->load->controller('common/cart');
			
			//fix erro campos extras
			$data['campos_extras']=$data;
            $data['tabela_produto'] = $this->ver_itens();
			
			//aplica o layout
            $modelo = $this->config->get('compraexpress_modelo');
            if($modelo==1){
                if(version_compare(VERSION, '2.2.0.0', '>=')){
                    $this->response->setOutput($this->load->view('compraexpress/dados_express_logado_padrao.tpl', $data));
                }else{
                    $this->response->setOutput($this->load->view('default/template/compraexpress/dados_express_logado_padrao.tpl', $data));	
                }
            }else{
                if(version_compare(VERSION, '2.2.0.0', '>=')){
                    $this->response->setOutput($this->load->view('compraexpress/dados_express_logado.tpl', $data));
                }else{
                    $this->response->setOutput($this->load->view('default/template/compraexpress/dados_express_logado.tpl', $data));	
                }
            }
			
			}
				
		}else{
				$data['email'] = '';
				if(isset($this->session->data['email_rapido'])){
				$data['email'] = $this->session->data['email_rapido'];
				}
				
				if($this->config->get('compraexpress_log')){
				if(version_compare(VERSION, '2.2.0.0', '>=')){
				$this->response->setOutput($this->load->view('compraexpress/informar_email.tpl', $data));
				}else{
				$this->response->setOutput($this->load->view('default/template/compraexpress/informar_email.tpl', $data));	
				}
				}else{
				if(version_compare(VERSION, '2.2.0.0', '>=')){
				$this->response->setOutput($this->load->view('compraexpress/informar_email_senha.tpl', $data));
				}else{
				$this->response->setOutput($this->load->view('default/template/compraexpress/informar_email_senha.tpl', $data));	
				}
				}
		}
		}
	}
	
	public function limparemail(){
		$this->load->model('account/customer');
		if(isset($this->session->data['email_rapido'])){
			unset($this->session->data['email_rapido']);
		}
		if ($this->customer->isLogged()) {
			$this->customer->logout();
			$this->cart->clear();
			unset($this->session->data['wishlist']);
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_address']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
		}
		echo 'OK';
	}
	
	public function atualizarsenhaemail(){
		$this->load->model('account/customer');
		$this->load->language('mail/forgotten');
		if($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])){
			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
			$this->model_account_customer->editPassword($this->request->post['email'], $password);
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			$message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_password') . "\n\n";
			$message .= $password;
			$mail = new Mail();		
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
			$data['erro'] = false;
			$data['msg'] = 'Uma nova senha foi enviada a seu e-mail de cadastro!';	
		}else{
			$data['erro'] = true;
			$data['msg'] = 'Ops, e-mail invalido ou não encontrado no cadastro!';
		}
		echo json_encode($data);
	}
	
	public function entrar(){
		$this->load->model('account/customer');
		if ($this->customer->isLogged()) {
			echo '1';
		}else{
		$logado = $this->customer->login($this->request->post['email'], $this->request->post['senha']);	
		if($logado){
			echo '1';
		}else{
			echo '0';
		}
		}
	}
	
	public function entrarcomemail(){
		$this->load->model('account/customer');
		$this->load->model('account/compraexpress');
		$this->load->model('localisation/zone');
		
		if(!isset($this->request->post['email'])){
			die('Ops, qual seu email?');
		}
		
		//ativa ou nao o fb
		$data['facebook_ativado'] = $this->config->get('compraexpress_fb');
		
		//verifica se tem carrinho e estoque
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			
		if(version_compare(VERSION, '2.2.0.0', '>=')){
            $this->response->setOutput($this->load->view('compraexpress/carrinho_vazio.tpl', $data));
		}else{
            $this->response->setOutput($this->load->view('default/template/compraexpress/carrinho_vazio.tpl', $data));	
		}
		
		}else{
		
		//pega dados de acordo email
		$this->session->data['email_rapido'] = $this->request->post['email'];
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		
		//dados custom_field
		$data['campo_fiscal'] = $this->config->get('compraexpress_fiscal');
		$data['campo_numero'] = $this->config->get('compraexpress_numero');
		$data['campo_complemento'] = $this->config->get('compraexpress_complemento');
		$data['obrigar'] = $this->config->get('compraexpress_obriga');
		$data['com'] = $this->config->get('compraexpress_com');
		
		//salva session email rapido
		$data['email'] = isset($this->session->data['email_rapido'])?$this->session->data['email_rapido']:'';
		
		if (version_compare(VERSION, '2.0.3.1', '<=')) {
			$data['jsoncliente'] = @unserialize($customer_info['custom_field']);
		} else {
			$data['jsoncliente'] = @json_decode($customer_info['custom_field'],true);
		}
		
		//se existe o cliente com email especificado
		if($customer_info){
            //roberto
            if($customer_info['custom_field'] == ""){
                $this->session->data['success'] = 'Atenção! Complete seu cadastro para continuar sua inscrição';
                die('<script>location.href="index.php?route=account/edit/";</script>');
                
             }

             $verificaEnd = $this->model_account_compraexpress->getAddress($customer_info['customer_id']);             	
            
             if($verificaEnd['custom_field'][8] == ""){
                $this->session->data['success'] = 'Atenção! Informe o complemento do seu endereço para continuar sua inscrição!';
                die('<script>location.href="index.php?route=account/address/add/";</script>');
             }

            //
            //verifica o endereco do cliente
            if($customer_info['address_id']==0){
                $endereco = $this->model_account_compraexpress->getAddress($customer_info['customer_id']);
                
                if($endereco['address_id']==0){
                    $this->session->data['success'] = 'Cadastre um endere&ccedil;o para continuar!';
                    die('<script>location.href="index.php?route=account/address/add";</script>');
                }else{
                    $customer_info['address_id'] = $endereco['address_id'];
                }
            }

			//dados do cliente
			$clienteobrigatorios = array('email','firstname','telephone');
			$clientevazio = 0;
			$data['dados_cliente'] = $customer_info;
			foreach($customer_info AS $k=>$v){
                if (version_compare(VERSION, '2.0.3.1', '<=')) {
                    $this->session->data['guest'][$k] = ($k=='custom_field')?@unserialize($v):$v;
                } else {
                    $this->session->data['guest'][$k] = ($k=='custom_field')?@json_decode($v,true):$v;
                }
                //campos fixos
                if(in_array($k,$clienteobrigatorios) && empty($v)){
                    $clientevazio++;
                }
                //campos custons
                if($k=='custom_field'){
                    if (version_compare(VERSION, '2.0.3.1', '<=')) {
                        $jsoncliente = @unserialize($v);
                    } else {
                        $jsoncliente = @json_decode($v,true);
                    }
                    if(!isset($jsoncliente[$data['campo_fiscal']]) || empty($jsoncliente[$data['campo_fiscal']])){
                        $clientevazio++;
                    }
                }
			}
			
			//enderco do cliente principal
			$data['endereco_entrega'] = $this->model_account_compraexpress->getAddress($customer_info['customer_id']);
			$endvazio = 0;
			$endobrigatorios = array('address_1','firstname','address_2','city','zone_code','postcode');
			foreach($data['endereco_entrega'] AS $k=>$v){
				//campos fixos
				if(in_array($k,$endobrigatorios) && empty($v)){
                    $endvazio++;
				}
				//campos custons
				if($k=='custom_field'){
				$jsonend = $v;
				if(!isset($jsonend[$data['campo_numero']]) || empty($jsonend[$data['campo_numero']])){
					$endvazio++;
				}
				}
			}
			
			//total vazios
			$data['total_vazios'] = ($data['obrigar'])?($endvazio+$clientevazio):0;
			
			//salva session dos enderecos
			$this->session->data['payment_address'] = $data['endereco_entrega'];
			$this->session->data['shipping_address'] = $data['endereco_entrega'];
			
			//ver se precisa de meios de entrega
			$data['requer_entrega'] = $this->cart->hasShipping();
			
			//session meios de frete
			$meios_entrega = $this->getvaloresdeentrega($data['endereco_entrega']);
			$data['meios_entrega'] = $meios_entrega;
			$this->session->data['shipping_methods'] = $meios_entrega;
			$data['meio_escolhido'] = isset($this->session->data['shipping_method']['code'])?$this->session->data['shipping_method']['code']:false;
			
			//session meios de pagamento
			$meios_pagamento = $this->getmeiospagamento($data['endereco_entrega']);
			$data['meios_pagamento'] = $meios_pagamento;
			$this->session->data['payment_methods'] = $meios_pagamento;
			$data['pagamento_escolhido'] = isset($this->session->data['payment_method'])?$this->session->data['payment_method']:false;
			
			$data['comentario'] = @$this->session->data['comment'];
            $data['tabela_produto'] = $this->ver_itens();
			
			//aplica no layout
            $modelo = $this->config->get('compraexpress_modelo');
            if($modelo==1){
                if(version_compare(VERSION, '2.2.0.0', '>=')){
                    $this->response->setOutput($this->load->view('compraexpress/dados_express_mascarado_padrao.tpl', $data));
                }else{
                    $this->response->setOutput($this->load->view('default/template/compraexpress/dados_express_mascarado_padrao.tpl', $data));	
                }
            }else{
                if(version_compare(VERSION, '2.2.0.0', '>=')){
                    $this->response->setOutput($this->load->view('compraexpress/dados_express_mascarado.tpl', $data));
                }else{
                    $this->response->setOutput($this->load->view('default/template/compraexpress/dados_express_mascarado.tpl', $data));	
                }
            }
			
		}else{
			$this->session->data['redirecionar'] = $this->url->link('checkout/compraexpress', '', 'SSL');
			die('0');
		}
		}

	}
	
	public function salvarcomentario(){
		$this->load->model('account/customer');
		if(isset($this->request->post['comentario'])){
			$this->session->data['comment'] = $this->request->post['comentario'];
		}
	}
	
	public function getvaloresdeentrega($para){
		if (isset($para)) {
			$method_data = array();
			$this->load->model('extension/extension');
			$results = $this->model_extension_extension->getExtensions('shipping');
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('shipping/' . $result['code']);

					$quote = $this->{'model_shipping_' . $result['code']}->getQuote($para);

					if ($quote) {
						$method_data[$result['code']] = array(
							'title'      => $quote['title'],
							'quote'      => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error'      => $quote['error']
						);
					}
				}
			}
			$sort_order = array();
			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			array_multisort($sort_order, SORT_ASC, $method_data);
			return $method_data;
			
		}
	}
	
	public function getmeiospagamento($para){
		if (isset($para)) {
            $pagamento = $this->config->get('compraexpress_pagamentos');
			$total = $this->cart->getSubTotal();
			$method_data = array();
			$this->load->model('extension/extension');
			$results = $this->model_extension_extension->getExtensions('payment');
			$recurring = $this->cart->hasRecurringProducts();
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('payment/' . $result['code']);
					$method = $this->{'model_payment_' . $result['code']}->getMethod($para, $total);
					if ($method) {
						if ($recurring) {
							if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						} else {
							$method_data[$result['code']] = $method;
						}
                        //imagem 
                        if(isset($pagamento[$result['code']]) && !empty($pagamento[$result['code']])){
                            $method_data[$result['code']]['img'] = $pagamento[$result['code']];
                        }else{
                            $method_data[$result['code']]['img'] = '';
                        }
					}
				}
			}
			$sort_order = array();
			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			array_multisort($sort_order, SORT_ASC, $method_data);
			return $method_data;
		}
	}

	public function country() {
		$json = array();
		$this->load->model('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		if ($country_info) {
			$this->load->model('localisation/zone');
			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customfield() {
		$json = array();
		$this->load->model('account/custom_field');
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);
		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}