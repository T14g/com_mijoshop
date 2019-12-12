<?php
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');
 
class ControllerPaymentEasysocialpoints extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/easysocialpoints');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('easysocialpoints', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
	
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$data['entry_points'] = $this->language->get('entry_points');
		$data['entry_points_id'] = $this->language->get('entry_points_id');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['entry_total_id'] = $this->language->get('entry_total_id');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['points'])) {
			$data['error_points'] = $this->error['points'];
		} else {
			$data['error_points'] = '';
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
			'href'      => $this->url->link('payment/easysocialpoints', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$data['action'] = $this->url->link('payment/easysocialpoints', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['easysocialpoints_points'])) {
			$data['easysocialpoints_points'] = $this->request->post['easysocialpoints_points'];
		} else {
			$data['easysocialpoints_points'] = $this->config->get('easysocialpoints_points');
		}
		
		if (isset($this->request->post['easysocialpoints_total'])) {
			$data['easysocialpoints_total'] = $this->request->post['easysocialpoints_total'];
		} else {
			$data['easysocialpoints_total'] = $this->config->get('easysocialpoints_total'); 
		} 
				
		if (isset($this->request->post['easysocialpoints_order_status_id'])) {
			$data['easysocialpoints_order_status_id'] = $this->request->post['easysocialpoints_order_status_id'];
		} else {
			$data['easysocialpoints_order_status_id'] = $this->config->get('easysocialpoints_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['easysocialpoints_geo_zone_id'])) {
			$data['easysocialpoints_geo_zone_id'] = $this->request->post['easysocialpoints_geo_zone_id'];
		} else {
			$data['easysocialpoints_geo_zone_id'] = $this->config->get('easysocialpoints_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['easysocialpoints_status'])) {
			$data['easysocialpoints_status'] = $this->request->post['easysocialpoints_status'];
		} else {
			$data['easysocialpoints_status'] = $this->config->get('easysocialpoints_status');
		}
		
		if (isset($this->request->post['easysocialpoints_sort_order'])) {
			$data['easysocialpoints_sort_order'] = $this->request->post['easysocialpoints_sort_order'];
		} else {
			$data['easysocialpoints_sort_order'] = $this->config->get('easysocialpoints_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/easysocialpoints.tpl', $data));
				
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/easysocialpoints')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['easysocialpoints_points']) {
			$this->error['points'] = $this->language->get('error_points');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>