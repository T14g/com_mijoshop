<?php
class ControllerReportProdutosFrete extends Controller {
	var $version = "2.0.5";
	public function index() {
		$data = array();
		$this->language->load('report/produtos_frete');
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['filter_product_id'])) {
			$data['filter_product_id'] = $this->request->get['filter_product_id'];
		} else {
			$data['filter_product_id'] = '';
		}
		
		if (isset($this->request->get['filter_product_name'])) {
			$data['filter_product_name'] = $this->request->get['filter_product_name'];
		} else {
			$data['filter_product_name'] = '';
		}
		
		if (isset($this->request->get['filter_customer_name'])) {
			$data['filter_customer_name'] = $this->request->get['filter_customer_name'];
		} else {
			$data['filter_customer_name'] = '';
		}
		
		if (isset($this->request->get['filter_customer_email'])) {
			$data['filter_customer_email'] = $this->request->get['filter_customer_email'];
		} else {
			$data['filter_customer_email'] = '';
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$data['filter_date_start'] = $this->request->get['filter_date_start'];
		} else {
			$data['filter_date_start'] = date('Y-m-d', strtotime('-7 day'));
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$data['filter_date_end'] = $this->request->get['filter_date_end'];
		} else {
			$data['filter_date_end'] = date('Y-m-d', time());
		}
		
		if (isset($this->request->get['sort'])) {
			$data['sort'] = $this->request->get['sort'];
		} else {
			$data['sort'] = 'o.date_modified';
		}

		if (isset($this->request->get['order'])) {
			$data['order'] = $this->request->get['order'];
		} else {
			$data['order'] = 'DESC';
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$data['filter_order_status_id'] = $this->request->get['filter_order_status_id'];
		} else {
			$data['filter_order_status_id'] = '';
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$data['filter_date_start'] = $this->request->get['filter_date_start'];
		} else {
			$data['filter_date_start'] = date('Y-m-d', strtotime('-7 day'));
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$data['filter_date_end'] = $this->request->get['filter_date_end'];
		} else {
			$data['filter_date_end'] = date('Y-m-d', time());
		}
		
		$data['filter_product_id'] = $data['filter_product_id'];
		$data['filter_product_name'] = $data['filter_product_name'];
		$data['filter_customer_name'] = $data['filter_customer_name'];
		$data['filter_customer_email'] = $data['filter_customer_email'];
		$data['filter_order_status_id'] = $data['filter_order_status_id'];
		$data['filter_date_start'] = $data['filter_date_start'];
		$data['filter_date_end'] = $data['filter_date_end'];
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}		
		$product_total = 0;
		$url = '';

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		// Custom Fields
		$this->load->model('sale/custom_field');

		$data['custom_fields'] = array();

		$custom_fields = $this->model_sale_custom_field->getCustomFields();

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_sale_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location']
			);
		}

		if (isset($this->request->post['custom_field'])) {
			$data['account_custom_field'] = $this->request->post['custom_field'];
		} elseif (!empty($customer_info)) {
			$data['account_custom_field'] = unserialize($customer_info['custom_field']);
		} else {
			$data['account_custom_field'] = array();
		}
		
		$this->load->model('report/produtos_frete');
		$data['products'] = array();				
		
		$product_total = $this->model_report_produtos_frete->getProdutosFretesTotal($data);
		$produtos_frete_list = $this->model_report_produtos_frete->getProdutosFretes($data);

		$data["products"] = $produtos_frete_list;			

		$data['heading_title'] = $this->language->get('heading_title');
		$data['txt_column_id'] = $this->language->get('txt_column_id');
		$data['txt_column_name_product'] = $this->language->get('txt_column_name_product');
		$data['txt_column_name_cliente'] = $this->language->get('txt_column_name_cliente');
		$data['txt_column_situation'] = $this->language->get('txt_column_situation');
		$data['txt_column_date'] = $this->language->get('txt_column_date');
		$data['txt_column_quantity_purchased'] = $this->language->get('txt_column_quantity_purchased');
		$data['txt_column_total'] = $this->language->get('txt_column_total');
		$data['txt_column_action'] = $this->language->get('txt_column_action');
		$data['txt_column_email'] = $this->language->get('txt_column_email');
		$data['text_view_details'] = $this->language->get('text_view_details');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_status_not_found'] = $this->language->get('text_status_not_found');		
		$data['text_of'] = $this->language->get('text_of');
		$data['text_to'] = $this->language->get('text_to');		
		$data['button_export'] = $this->language->get('button_export');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		array_unshift($data['order_statuses'], array('order_status_id' => 0, 'name' => $this->language->get('text_status_not_found')));

		$data['all_statuses'] = array();
		foreach($data['order_statuses'] as $order_statuses)	{
			$data['all_statuses'][$order_statuses['order_status_id']] = $order_statuses['name'];
		}		
		$url = '';		
		
		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		
		if (isset($this->request->get['filter_product_name'])) {
			$url .= '&filter_product_name=' . $this->request->get['filter_product_name'];
		}
		
		if (isset($this->request->get['filter_customer_name'])) {
			$url .= '&filter_customer_name=' . $this->request->get['filter_customer_name'];
		}
		
		if (isset($this->request->get['filter_customer_email'])) {
			$url .= '&filter_customer_email=' . $this->request->get['filter_customer_email'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if ($data['order'] == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		$data['sort_id_product'] = $this->url->link('report/produtos_frete', 'token=' . $this->session->data['token'] . '&sort=op.product_id' . $url, 'SSL');
		$data['sort_name_product'] = $this->url->link('report/produtos_frete', 'token=' . $this->session->data['token'] . '&sort=op.name' . $url, 'SSL');
		$data['sort_client'] = $this->url->link('report/produtos_frete', 'token=' . $this->session->data['token'] . '&sort=fullname' . $url, 'SSL');
		$data['sort_email'] = $this->url->link('report/produtos_frete', 'token=' . $this->session->data['token'] . '&sort=o.email' . $url, 'SSL');
		$data['sort_quantity_purchased'] = $this->url->link('report/produtos_frete', 'token=' . $this->session->data['token'] . '&sort=quantity' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('report/produtos_frete', 'token=' . $this->session->data['token'] . '&sort=total' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('report/produtos_frete', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		$data['url_export'] = $this->url->link('report/produtos_frete/export', 'token=' . $this->session->data['token'].$url, 'SSL');

		$data['url_info'] = $this->url->link('report/produtos_frete/info', 'token=' . $this->session->data['token'].$url, 'SSL');
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/produtos_frete', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
			
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $data['sort'];
		$data['order'] = $data['order'];
				 
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('report/produtos_frete.tpl', $data));
	}
	public function customfield() {
		$json = array();

		$this->load->model('sale/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id'])) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_sale_custom_field->getCustomFields(array('filter_customer_group_id' => $customer_group_id));

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function export() {
		$data = array();

		$this->language->load('report/produtos_frete');
		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['filter_product_id'])) {
			$data['filter_product_id'] = $this->request->get['filter_product_id'];
		} else {
			$data['filter_product_id'] = '';
		}
		
		if (isset($this->request->get['filter_product_name'])) {
			$data['filter_product_name'] = $this->request->get['filter_product_name'];
		} else {
			$data['filter_product_name'] = '';
		}
		
		if (isset($this->request->get['filter_customer_name'])) {
			$data['filter_customer_name'] = $this->request->get['filter_customer_name'];
		} else {
			$data['filter_customer_name'] = '';
		}
		
		if (isset($this->request->get['filter_customer_email'])) {
			$data['filter_customer_email'] = $this->request->get['filter_customer_email'];
		} else {
			$data['filter_customer_email'] = '';
		}
		
		if (isset($this->request->get['sort'])) {
			$data['sort'] = $this->request->get['sort'];
		} else {
			$data['sort'] = 'op.product_id';
		}

		if (isset($this->request->get['order'])) {
			$data['order'] = $this->request->get['order'];
		} else {
			$data['order'] = 'DESC';
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$data['filter_order_status_id'] = $this->request->get['filter_order_status_id'];
		} else {
			$data['filter_order_status_id'] = '';
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$data['filter_date_start'] = $this->request->get['filter_date_start'];
		} else {
			$data['filter_date_start'] = date('Y-m-d', strtotime('-7 day'));
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$data['filter_date_end'] = $this->request->get['filter_date_end'];
		} else {
			$data['filter_date_end'] = date('Y-m-d', time());
		}
		
		$data['filter_product_id'] = $data['filter_product_id'];
		$data['filter_product_name'] = $data['filter_product_name'];
		$data['filter_customer_name'] = $data['filter_customer_name'];
		$data['filter_customer_email'] = $data['filter_customer_email'];
		$data['filter_order_status_id'] = $data['filter_order_status_id'];
		$data['filter_date_start'] = $data['filter_date_start'];
		$data['filter_date_end'] = $data['filter_date_end'];

		$product_total = 0;

		$data['breadcrumbs'] = array();
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
   		// Custom Fields
		$this->load->model('sale/custom_field');

		$data['custom_fields'] = array();

		$custom_fields = $this->model_sale_custom_field->getCustomFields();

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_sale_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location']
			);
		}

		if (isset($this->request->post['custom_field'])) {
			$data['account_custom_field'] = $this->request->post['custom_field'];
		} elseif (!empty($customer_info)) {
			$data['account_custom_field'] = unserialize($customer_info['custom_field']);
		} else {
			$data['account_custom_field'] = array();
		}
		
		$this->load->model('report/produtos_frete');
		$produtos_frete_list = $this->model_report_produtos_frete->getProdutosFretes($data); 

		$data["products"] = $produtos_frete_list;
				
		$this->load->model('localisation/order_status');		
		$order_statuses = $this->model_localisation_order_status->getOrderStatuses();

		$data['all_statuses'] = array();
		foreach($order_statuses as $order_status){
			$data['all_statuses'][$order_status['order_status_id']] = $order_status['name'];
		}
		
		$data['txt_column_id'] = $this->language->get('txt_column_id');
		$data['txt_column_name_product'] = $this->language->get('txt_column_name_product');
		$data['txt_column_name_cliente'] = $this->language->get('txt_column_name_cliente');
		$data['txt_column_situation'] = $this->language->get('txt_column_situation');
		$data['txt_column_quantity_purchased'] = $this->language->get('txt_column_quantity_purchased');
		$data['txt_column_total'] = $this->language->get('txt_column_total');
		$data['txt_column_action'] = $this->language->get('txt_column_action');
		$data['txt_column_email'] = $this->language->get('txt_column_email');
		$data['text_view_details'] = $this->language->get('text_view_details');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_status_not_found'] = $this->language->get('text_status_not_found');
		
		$data_export = date('d-m-Y');				
		$arquivo = 'relatorio_'.$data_export.'.xls';
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Content-Type: text/plain");
		header("Content-Disposition: attachment; filename={$arquivo}" );
		header("Content-Description: PHP Generated Data" );
		
		if(defined('JPATH_MIJOSHOP_OC')){
			JRequest::setVar('format', 'raw');
		}
		$this->response->setOutput($this->load->view('report/produtos_frete_export.tpl', $data));
	}
}
?>