<?php 
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class ControllerToolEshop extends Controller {

	private $error = array(); 
    
  	public function index() {
		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),     		
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => 'MijoShop Migration Tools',
			'href'      => '',
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => 'Eshop',
			'href'      => $this->url->link('tool/eshop', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/eshop.tpl', $data));
  	} 
  	
  	public function importCategories(){
		if (!$this->validate()) {
			return $this->forward('error/eshop');
		}

		$this->load->model('tool/eshop');
		
		$this->model_tool_eshop->importCategories($this->request->post['eshop']);
	}
  	
  	public function importProducts() {
		if (!$this->validate()) {
			return $this->forward('error/permission');
		}

		$this->load->model('tool/eshop');
		
		$this->model_tool_eshop->importProducts($this->request->post['eshop']);
	}
	
	public function importManufacturers() {
		if (!$this->validate()) {
			return $this->forward('error/permission');
		}

		$this->load->model('tool/eshop');
		
		$this->model_tool_eshop->importManufacturers($this->request->post['eshop']);
	}
	
	public function copyImages() {
		if (!$this->validate()) {
			return $this->forward('error/permission');
		}

		$this->load->model('tool/eshop');
		
		$this->model_tool_eshop->copyImages($this->request->post['eshop']);
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/eshop')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		}
		else {
			return false;
		}		
	}
}