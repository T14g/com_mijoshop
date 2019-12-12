<?php
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceShop www.joomace.net
* @author 		hcasatti
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class ControllerPaymentmercadopago extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/mercadopago');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
                        
				if(isset($this->request->post['mercadopago_methods'])){
					$names = $this->request->post['mercadopago_methods'];
					$this->request->post['mercadopago_methods'] = '';
					foreach ($names as $name){
						$this->request->post['mercadopago_methods'] .= $name.',';
					}            
				}
			$this->model_setting_setting->editSetting('mercadopago', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            #mijoshop-start
            if (isset($this->request->post['button']) and $this->request->post['button'] == 'save') {
                $route = JRequest::getString('route');
                $module_id = '';
                if (isset($this->request->get['module_id'])) {
                    $module_id = '&module_id=' . $this->request->get['module_id'];
                }
	            elseif (MijoShop::get('db')->getDbo()->insertid()) {
		            $module_id = '&module_id=' . MijoShop::get('db')->getDbo()->insertid();
	            }
                $this->response->redirect($this->url->link($route, 'token=' . $this->session->data['token'] . $module_id, 'SSL'));
            }
            #mijoshop-end
            

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

        #mijoshop-start
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        #mijoshop-end
            

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_mercadopago'] = $this->language->get('text_mercadopago');
		$data['entry_client_id'] = $this->language->get('entry_client_id');
		$data['entry_client_id_text'] = $this->language->get('entry_client_id_text');
		$data['entry_client_secret'] = $this->language->get('entry_client_secret');
		$data['entry_client_secret_text'] = $this->language->get('entry_client_secret_text');
		$data['entry_installments'] = $this->language->get('entry_installments');
		$data['entry_payments_not_accept'] = $this->language->get('entry_payments_not_accept');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_sonda_key'] = $this->language->get('entry_sonda_key');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_ipn_status'] = $this->language->get('entry_ipn_status');
		$data['entry_url'] = $this->language->get('entry_url');
		$data['entry_url_text'] = $this->language->get('entry_url_text');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_sandbox'] = $this->language->get('entry_sandbox');
		$data['entry_type_checkout'] = $this->language->get('entry_type_checkout');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_ipn'] = $this->language->get('text_ipn');
		$data['entry_order_status_completed'] = $this->language->get('entry_order_status_completed');
		$data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
		$data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
		$data['entry_order_status_in_process'] = $this->language->get('entry_order_status_in_process');
		$data['entry_order_status_rejected'] = $this->language->get('entry_order_status_rejected');
		$data['entry_order_status_refunded'] = $this->language->get('entry_order_status_refunded');
		$data['entry_order_status_in_mediation'] = $this->language->get('entry_order_status_in_mediation');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_order_status'] = $this->language->get('tab_order_status');
                
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['acc_id'])) {
			$data['error_acc_id'] = $this->error['acc_id'];
		} else {
			$data['error_acc_id'] = '';
		}

 		if (isset($this->error['token'])) {
			$data['error_token'] = $this->error['token'];
		} else {
			$data['error_token'] = '';
		}
		
		if (isset($this->error['error_client_id'])) {
			$data['error_client_id'] = $this->error['error_client_id'];
		} else {
			$data['error_client_id'] = '';
		}
		
		if (isset($this->error['error_client_secret'])) {
			$data['error_client_secret'] = $this->error['error_client_secret'];
		} else {
			$data['error_client_secret'] = '';
		}
		
		if (isset($this->error['error_mercadopago_url'])) {
			$data['error_mercadopago_url'] = $this->error['error_mercadopago_url'];
		} else {
			$data['error_mercadopago_url'] = '';
		}

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
   		);

   		$data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_payment'),
			'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/mercadopago&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$data['action'] = $this->url->link('payment/mercadopago', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['mercadopago_client_id'])) {
			$data['mercadopago_client_id'] = $this->request->post['mercadopago_client_id'];
		} else {
			$data['mercadopago_client_id'] = $this->config->get('mercadopago_client_id');
		}

		if (isset($this->request->post['mercadopago_client_secret'])) {
			$data['mercadopago_client_secret'] = $this->request->post['mercadopago_client_secret'];
		} else {
			$data['mercadopago_client_secret'] = $this->config->get('mercadopago_client_secret');
		}

		if (isset($this->request->post['mercadopago_status'])) {
			$data['mercadopago_status'] = $this->request->post['mercadopago_status'];
		} else {
			$data['mercadopago_status'] = $this->config->get('mercadopago_status');
		}
		
		$data['category_list'] = $this->getCategoryList();
		
		if (isset($this->request->post['mercadopago_category_id'])) {
			$data['mercadopago_category_id'] = $this->request->post['mercadopago_category_id'];
		} else {
			$data['mercadopago_category_id'] = $this->config->get('mercadopago_category_id');
		}
				
		if (isset($this->request->post['mercadopago_url'])) {
			$data['mercadopago_url'] = $this->request->post['mercadopago_url'];
		} else {
			$data['mercadopago_url'] = $this->config->get('mercadopago_url');
		}
                
		if (isset($this->request->post['mercadopago_debug'])) {
			$data['mercadopago_debug'] = $this->request->post['mercadopago_debug'];
		} else {
			$data['mercadopago_debug'] = $this->config->get('mercadopago_debug');
		}                
                
		if (isset($this->request->post['mercadopago_sandbox'])) {
			$data['mercadopago_sandbox'] = $this->request->post['mercadopago_sandbox'];
		} else {
			$data['mercadopago_sandbox'] = $this->config->get('mercadopago_sandbox');
		}
			
		$data['type_checkout'] = $this->getTypeCheckout();
		
		if (isset($this->request->post['mercadopago_type_checkout'])) {
			$data['mercadopago_type_checkout'] = $this->request->post['mercadopago_type_checkout'];
		} else {
			$data['mercadopago_type_checkout'] = $this->config->get('mercadopago_type_checkout');
		}
                
		$data['countries'] = $this->getCountries();
		$data['installments'] = $this->getInstallments();

		if ($this->config->get('mercadopago_country')){
		    $data['methods'] = $this->getMethods($this->config->get('mercadopago_country'));    
		}
              
		if (isset($this->request->post['mercadopago_methods'])) {
			$data['mercadopago_methods'] = $this->request->post['mercadopago_methods'];
		} else {
			 $methods_excludes = preg_split("/[\s,]+/",$this->config->get('mercadopago_methods')); 
			 
			 foreach ($methods_excludes as $exclude ){
				$data['mercadopago_methods'][] = $exclude;     
			 }   
             //    var_dump($data['mercadopago_methods']);die;                  
			// $data['mercadopago_methods'] = $this->config->get('mercadopago_methods');
		}
                
		if (isset($this->request->post['mercadopago_country'])) {
			$data['mercadopago_country'] = $this->request->post['mercadopago_country'];
		} else {
			$data['mercadopago_country'] = $this->config->get('mercadopago_country');
		}
                
		if (isset($this->request->post['mercadopago_installments'])) {
			$data['mercadopago_installments'] = $this->request->post['mercadopago_installments'];
		} else {
			$data['mercadopago_installments'] = $this->config->get('mercadopago_installments');
		}

		if (isset($this->request->post['mercadopago_order_status_id'])) {
			$data['mercadopago_order_status_id'] = $this->request->post['mercadopago_order_status_id'];
		} else {
			$data['mercadopago_order_status_id'] = $this->config->get('mercadopago_order_status_id');
		}

		if (isset($this->request->post['mercadopago_sort_order'])) {
			$data['mercadopago_sort_order'] = $this->request->post['mercadopago_sort_order'];
		} else {
			$data['mercadopago_sort_order'] = $this->config->get('mercadopago_sort_order');
		}
		
		if (isset($this->request->post['mercadopago_order_status_id_completed'])) {
			$data['mercadopago_order_status_id_completed'] = $this->request->post['mercadopago_order_status_id_completed'];
		} else {
			$data['mercadopago_order_status_id_completed'] = $this->config->get('mercadopago_order_status_id_completed');
		}

		if (isset($this->request->post['mercadopago_order_status_id_pending'])) {
			$data['mercadopago_order_status_id_pending'] = $this->request->post['mercadopago_order_status_id_pending'];
		} else {
			$data['mercadopago_order_status_id_pending'] = $this->config->get('mercadopago_order_status_id_pending');
		}

		if (isset($this->request->post['mercadopago_order_status_id_canceled'])) {
			$data['mercadopago_order_status_id_canceled'] = $this->request->post['mercadopago_order_status_id_canceled'];
		} else {
			$data['mercadopago_order_status_id_canceled'] = $this->config->get('mercadopago_order_status_id_canceled');
		}
		
		if (isset($this->request->post['mercadopago_order_status_id_in_process'])) {
			$data['mercadopago_order_status_id_in_process'] = $this->request->post['mercadopago_order_status_id_in_process'];
		} else {
			$data['mercadopago_order_status_id_in_process'] = $this->config->get('mercadopago_order_status_id_in_process');
		}
		
		if (isset($this->request->post['mercadopago_order_status_id_rejected'])) {
			$data['mercadopago_order_status_id_rejected'] = $this->request->post['mercadopago_order_status_id_rejected'];
		} else {
			$data['mercadopago_order_status_id_rejected'] = $this->config->get('mercadopago_order_status_id_rejected');
		}
		
		if (isset($this->request->post['mercadopago_order_status_id_refunded'])) {
			$data['mercadopago_order_status_id_refunded'] = $this->request->post['mercadopago_order_status_id_refunded'];
		} else {
			$data['mercadopago_order_status_id_refunded'] = $this->config->get('mercadopago_order_status_id_refunded');
		}
		
		if (isset($this->request->post['mercadopago_order_status_id_in_mediation'])) {
			$data['mercadopago_order_status_id_in_mediation'] = $this->request->post['mercadopago_order_status_id_in_mediation'];
		} else {
			$data['mercadopago_order_status_id_in_mediation'] = $this->config->get('mercadopago_order_status_id_in_mediation');
		}
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/mercadopago.tpl', $data));
	}

	private function getCountries() {
		$url = 'https://api.mercadolibre.com/sites/';
		$countries = $this->callJson($url);
		
   		return $countries;
	}
        
	private function getMethods($country_id) {
		$url = "https://api.mercadolibre.com/sites/" . $country_id .  "/payment_methods";

		$methods = $this->callJson($url);
		return $methods;
	}
       
    private function callJson($url,$posts = null){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//returns the transference value like a string
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'));//sets the header
		curl_setopt($ch, CURLOPT_URL, $url); //oauth API
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		//curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
		//curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		
		if (isset($posts)){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $posts);
		}
		
		$jsonresult = curl_exec($ch);//execute the conection
		curl_close($ch);
		$result = json_decode($jsonresult,true);
		return  $result;          
   }
	
	private function getCategoryList(){
		$url = "https://api.mercadolibre.com/item_categories";
		$category = $this->callJson($url);
		return $category;
	}
	
	private function getTypeCheckout(){	
		//Redirect not working because ajax request not redirect this page..
		$type_checkout = array("Lightbox", "Iframe");
		
		return $type_checkout;
	}
	
	private function getInstallments (){	
		
			$installments = array();
			
			$installments[] = array(
				'value' => 'maximum',
				'id' => '24');
			
			$installments[] = array(
				'value' => '18',
				'id' => '18');
			$installments[] = array(
				'value' => '15',
				'id' => '15');
						  
			$installments[] = array(
				'value' => '12',
				'id' => '12');
			   
			$installments[] = array(
				'value' => '9',
				'id' => '9');
			   
			$installments[] = array(
				'value' => '6',
				'id' => '6');
			   
			 $installments[] = array(
				'value' => '3',
				'id' => '3');
			   
			 $installments[] = array(
				'value' => '1',
				'id' => '1');
		
			 return $installments;
	  }
                
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/mercadopago')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['mercadopago_client_id']) {
			$this->error['error_client_id'] = $this->language->get('error_client_id');
		}

		if (!$this->request->post['mercadopago_client_secret']) {
			$this->error['error_client_secret'] = $this->language->get('error_client_secret');
		}
		
		if (!$this->request->post['mercadopago_url']) {
			$this->error['error_mercadopago_url'] = $this->language->get('error_mercadopago_url');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>