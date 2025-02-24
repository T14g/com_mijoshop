<?php
class ControllerTotalDesconto5 extends Controller {
    private $error = array();
    
    public function index() {
		$this->language->load('total/desconto5');

        $this->document->setTitle($this->language->get('heading_title'));
        
		$this->load->model('extension/extension');
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('desconto5', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('total/desconto5', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
					
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_total'),
            'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('total/desconto5', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
                
		$data['action'] = $this->url->link('total/desconto5', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		$data['countries'] = array();

		//meios de pagamento
		$data['meios'] = $this->model_extension_extension->getInstalled('payment');
		
        if (isset($this->request->post['desconto5_descontos'])) {
            $data['desconto5_descontos'] = $this->request->post['desconto5_descontos'];
        } else {
            $data['desconto5_descontos'] = $this->config->get('desconto5_descontos');
        }
		
		if (isset($this->request->post['desconto5_sort_order'])) {
            $data['desconto5_sort_order'] = $this->request->post['desconto5_sort_order'];
        } else {
            $data['desconto5_sort_order'] = $this->config->get('desconto5_sort_order');
        }
		
		if (isset($this->request->post['desconto5_status'])) {
            $data['desconto5_status'] = $this->request->post['desconto5_status'];
        } else {
            $data['desconto5_status'] = $this->config->get('desconto5_status');
        }
             
        $this->load->model('localisation/tax_class');   
		
        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        $tema = 'total/desconto5.tpl';
        //lay
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($tema, $data));
    }

    private function validate() {
        return true;
    }
}
?>