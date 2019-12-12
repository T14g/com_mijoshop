<?php
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class ControllerPaymentAlertPay extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/alertpay');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('alertpay', $this->request->post);
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
	
		$data['entry_total_key'] 	= $this->language->get('entry_total_key');	

		$data['heading_title'] = $this->language->get('heading_title');

        #mijoshop-start
        $data['button_savenew'] = $this->language->get('button_savenew');
        $data['button_saveclose'] = $this->language->get('button_saveclose');
        #mijoshop-end
            
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_security'] = $this->language->get('entry_security');
		$data['entry_callback'] = $this->language->get('entry_callback');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['entry_security_key'] = $this->language->get('entry_security_key');	
		$data['entry_total_key'] = $this->language->get('entry_total_key');	
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
  		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

 		if (isset($this->error['security'])) {
			$data['error_security'] = $this->error['security'];
		} else {
			$data['error_security'] = '';
		}
		
  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/alertpay', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$data['action'] = $this->url->link('payment/alertpay', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['alertpay_merchant'])) {
			$data['alertpay_merchant'] = $this->request->post['alertpay_merchant'];
		} else {
			$data['alertpay_merchant'] = $this->config->get('alertpay_merchant');
		}

		if (isset($this->request->post['alertpay_security'])) {
			$data['alertpay_security'] = $this->request->post['alertpay_security'];
		} else {
			$data['alertpay_security'] = $this->config->get('alertpay_security');
		}
		
		$data['callback'] = HTTP_CATALOG . 'index.php?route=payment/alertpay/callback';
		
		if (isset($this->request->post['alertpay_total'])) {
			$data['alertpay_total'] = $this->request->post['alertpay_total'];
		} else {
			$data['alertpay_total'] = $this->config->get('alertpay_total'); 
		} 
				
		if (isset($this->request->post['alertpay_order_status_id'])) {
			$data['alertpay_order_status_id'] = $this->request->post['alertpay_order_status_id'];
		} else {
			$data['alertpay_order_status_id'] = $this->config->get('alertpay_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['alertpay_geo_zone_id'])) {
			$data['alertpay_geo_zone_id'] = $this->request->post['alertpay_geo_zone_id'];
		} else {
			$data['alertpay_geo_zone_id'] = $this->config->get('alertpay_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['alertpay_status'])) {
			$data['alertpay_status'] = $this->request->post['alertpay_status'];
		} else {
			$data['alertpay_status'] = $this->config->get('alertpay_status');
		}
		
		if (isset($this->request->post['alertpay_sort_order'])) {
			$data['alertpay_sort_order'] = $this->request->post['alertpay_sort_order'];
		} else {
			$data['alertpay_sort_order'] = $this->config->get('alertpay_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('payment/alertpay.tpl', $data));		
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/alertpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['alertpay_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['alertpay_security']) {
			$this->error['security'] = $this->language->get('error_security');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>