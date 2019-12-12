<?php
include(DIR_SYSTEM.'class_fiscal.php'); 
class ControllerAccountRegistroExpress extends Controller {
	private $error = array();

	public function index() {
		//ja logado redireciona a minha conta
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}
		$this->load->language('account/register');
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
		$this->load->model('account/customer');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_register'),
			'href' => $this->url->link('account/register', '', 'SSL')
		);
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
		$data['text_your_details'] = $this->language->get('text_your_details');
		$data['text_your_address'] = $this->language->get('text_your_address');
		$data['text_your_password'] = $this->language->get('text_your_password');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_newsletter'] = $this->language->get('entry_newsletter');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_confirm'] = $this->language->get('entry_confirm');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_upload'] = $this->language->get('button_upload');
		$this->load->model('localisation/zone');
		$data['lista_estados'] = $this->model_localisation_zone->getZonesByCountryId($this->config->get('config_country_id'));
		$data['action'] = $this->url->link('account/register', '', 'SSL');
		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();
		$data['pais_padrao'] = $this->config->get('config_country_id');
        
		// Custom Fields
		$this->load->model('account/custom_field');
		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));
        
		//salva session email
		$data['email'] = isset($this->session->data['email_rapido'])?$this->session->data['email_rapido']:'';
        
		//custom
		$data['tipo'] = $this->config->get('compraexpress_tipo');
        $data['exibir_erro'] = $this->config->get('compraexpress_erro');
		$data['nomedaloja'] = $this->config->get('config_name');
        $data['campo_fiscal'] = $this->config->get('compraexpress_fiscal');
        $data['campo_fiscalpj'] = $this->config->get('compraexpress_fiscal');
		$data['campo_numero'] = $this->config->get('compraexpress_numero');
		$data['campo_complemento'] = $this->config->get('compraexpress_complemento');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		
		if($this->config->get('compraexpress_te')==0){
		$data['footer'] = $this->load->controller('common/rodapecompraexpress');
		$data['header'] = $this->load->controller('common/topocompraexpress');	
		}else{
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		}
        $data['produtos'] = $this->cart->hasProducts();

		//aplica ao layout de registro customizado
		if(version_compare(VERSION, '2.2.0.0', '>=')){
		$this->response->setOutput($this->load->view('compraexpress/registroexpress.tpl', $data));
		}else{
		$this->response->setOutput($this->load->view('default/template/compraexpress/registroexpress.tpl', $data));	
		}
	}
	
	//carrega os estados pelo pais padrao
	public function estados(){
		$this->load->model('localisation/zone');
		$json = $this->model_localisation_zone->getZonesByCountryId($this->request->get['pais']);
		echo json_encode($json);
	}
	
	//cadastra um novo cliente
	public function novocliente(){
        $data = array();
		$this->load->language('account/register');
		$this->load->model('account/customer');
		$erro['erro'] = false;

        //se ja logado
		if ($this->customer->isLogged()) {
			$erro['erro'] = false;
			$erro['href'] = $this->url->link('account/account', '', 'SSL');
			die(json_encode($erro));
		}		
		
		//dados custom_field
		$campo_fiscal = $this->config->get('compraexpress_fiscal');
        $campo_fiscalpj = $this->config->get('compraexpress_fiscal');
		$campo_numero = $this->config->get('compraexpress_numero');
		$campo_complemento = $this->config->get('compraexpress_complemento');
		
		//validacoes
        $totalEmail = $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email']);
		if(empty($this->request->post['email']) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)){
			$erro['erro'] = true;
			$erro['msg'] = 'E-mail digitado inv&aacute;lido!';
			die(json_encode($erro));
		}
		if($totalEmail>=1){
			$erro['erro'] = true;
			$erro['msg'] = 'E-mail digitado j&aacute; encontra-se cadastrado na loja, acesse sua conta ou recupere sua senha!';
			die(json_encode($erro));
		}
		if(empty($this->request->post['nome'])){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite o nome/empresa do cliente!';
			die(json_encode($erro));
		}
		$cpf_cnpj = new ValidaCPFCNPJ($this->request->post['fiscal']);
		if(!$cpf_cnpj->valida()){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite cpf/cnpj v&aacute;lido!';
			die(json_encode($erro));
		}
		$telefone = preg_replace('/\D/', '', $this->request->post['telefone']);
		if(strlen($telefone)<10 || strlen($telefone)>11){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite telefone com ddd v&aacute;lido!';
			die(json_encode($erro));
		}
		if(strlen($this->request->post['senha'])<6){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite uma senha v&aacute;lido!';
			die(json_encode($erro));
		}
		if($this->request->post['senha']!=$this->request->post['senha2']){
			$erro['erro'] = true;
			$erro['msg'] = 'Confirme sua senha!';
			die(json_encode($erro));
		}
		$cep = preg_replace('/\D/', '', $this->request->post['cep']);
		if(strlen($cep)!=8){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite um CEP v&aacute;lido!';
			die(json_encode($erro));
		}
		if(strlen($this->request->post['logradouro'])<5){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite logradouro v&aacute;lido!';
			die(json_encode($erro));
		}
		if(strlen($this->request->post['numero'])<1){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite n&uacute;mero v&aacute;lido!';
			die(json_encode($erro));
		}
		
		if(strlen($this->request->post['bairro'])<2){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite bairro v&aacute;lido!';
			die(json_encode($erro));
		}
		
		if(strlen($this->request->post['cidade'])<2){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite cidade v&aacute;lido!';
			die(json_encode($erro));
		}
		
		if(strlen($this->request->post['uf'])<2){
			$erro['erro'] = true;
			$erro['msg'] = 'Digite seu estado v&aacute;lido!';
			die(json_encode($erro));
		}
		
		//trata o nome completo e divide em dois
		$nome_completo = explode(' ',preg_replace('/\s+/', ' ',trim($this->request->post['nome'])),2);
		
		//se nenhum erro cria o cliente
		$data['email'] = trim($this->request->post['email']);
		$data['firstname'] = isset($nome_completo[0])?strtoupper(trim($nome_completo[0])):'';
		$data['lastname'] = isset($nome_completo[1])?strtoupper(trim($nome_completo[1])):'';
		$data['telephone'] = $this->request->post['telefone'];
		$data['fax'] = '';
		$data['newsletter'] = isset($this->request->post['novidades'])?'1':'0';
        
        //tipo do cliente 
        $tipo_cad = $cpf_cnpj->valida_tipo();
        if($tipo_cad['tipo']=='pj'){
        $data['custom_field']['account'][$campo_fiscalpj]=$this->request->post['fiscal'];
        $data['customer_group_id'] = $this->config->get('compraexpress_grupopj');
        }else{
        $data['customer_group_id'] = $this->config->get('compraexpress_grupopf');
        $data['custom_field']['account'][$campo_fiscal]=$this->request->post['fiscal'];  
        }
        
        //merge campos extras conta
        if(isset($_POST['custom_field'])){
            foreach($_POST['custom_field'] as $key=>$valor){
                $data['custom_field']['account'][$key]=$valor;
            }
        }
        
        //endereco customizado
		$data['company'] = '';	
		$data['address_1'] = strtoupper($this->request->post['logradouro']);
		$data['address_2'] =  strtoupper($this->request->post['bairro']);
		$data['city'] =  strtoupper($this->request->post['cidade']);
		$data['postcode'] =  $this->request->post['cep'];
		$data['country_id'] =  $this->config->get('config_country_id');
		$data['zone_id'] =  $this->request->post['uf'];
		$data['password'] = $this->request->post['senha'];
        
        //extras endereco 
        $data['custom_field']['address'][$campo_numero] = $this->request->post['numero'];
		$data['custom_field']['address'][$campo_complemento] = strtoupper($this->request->post['complemento']);	
        
        //merge campos extras conta
        if(isset($_POST['custom_field_endereco'])){
            foreach($_POST['custom_field_endereco'] as $key=>$valor){
                $data['custom_field']['address'][$key]=$valor;
            }
        }
        
        //$erro['erro'] = true;
        //$erro['msg'] = print_r($data,true);
        //die(json_encode($erro));

		//cadastra o gury na loja
		$this->model_account_customer->addCustomer($data);
        
		//auto login
		$this->customer->login(trim($this->request->post['email']), $this->request->post['senha']);
		if(isset($this->session->data['guest'])){
            unset($this->session->data['guest']);
		}
        
		//cria um log de registro
		$this->load->model('account/activity');
		$activity_data = array(
			'customer_id' => $this->customer->getId(),
			'name'        => $this->request->post['nome']
		);
		$this->model_account_activity->addActivity('register', $activity_data);
        
        //cadastro ok
		$erro['erro'] = false;
		$produtos = isset($this->request->post['produtos'])?$this->request->post['produtos']:0;
		if($this->cart->hasProducts() || $produtos>0){
            $erro['href'] = $this->url->link('checkout/checkout', '', 'SSL');	
		}elseif(isset($this->session->data['redirecionar'])){
            $erro['href'] = $this->session->data['redirecionar'];
            unset($this->session->data['redirecionar']);
		}else{
            $erro['href'] = $this->url->link('account/account', '', 'SSL');
		}
		die(json_encode($erro));
		
	}
	
	//loga via fb
	public function entrarfb(){
		$sign = $this->request->post['sign'];
		$user = $this->request->post['user'];
		$user_id = $this->parse_signed_request($sign);
		if($user==$user_id){
		$logado_sucesso = $this->customer->login($this->request->post['email'], '', true);
		if($logado_sucesso){
			$this->session->data['success'] = 'Cliente logado com sucesso via facebook!';
			$erro['erro'] = false;
			if($this->cart->hasProducts()){
			$erro['href'] = $this->url->link('checkout/cart', '', 'SSL');	
			}else{
			$erro['href'] = $this->url->link('account/account', '', 'SSL');
			}
			//cria um log de registro
			$this->load->model('account/activity');
			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->request->post['email'].' (via facebook) '
			);
			$this->model_account_activity->addActivity('login', $activity_data);
			die(json_encode($erro));
		}else{
			$erro['erro'] = true;
			die(json_encode($erro));
		}
		}else{
			$erro['erro'] = true;
			die(json_encode($erro));
		}
	}
	
	//pega o id do cliente logado via fb
	public function parse_signed_request($signed_request) {
		$secret = trim($this->config->get('compraexpress_fbsec'));
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
		$sig = $this->base64_url_decode($encoded_sig);
		$data = json_decode($this->base64_url_decode($payload), true);
		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
		error_log('Bad Signed JSON signature!');
		return null;
		}
		return $data['user_id'];
	}

	public function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
	
}
?>