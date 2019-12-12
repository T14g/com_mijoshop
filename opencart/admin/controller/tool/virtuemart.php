<?php 
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class ControllerToolVirtuemart extends Controller {

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
       		'text'      => 'VirtueMart',
			'href'      => $this->url->link('tool/virtuemart', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/virtuemart.tpl', $data));
  	} 
  	
  	public function importCategories(){
		if (!$this->validate()) {
			return $this->forward('error/permission');
		}

		$this->load->model('tool/virtuemart');
		
		$this->model_tool_virtuemart->importCategories($this->request->post['virtuemart']);
	}
  	
  	public function importProducts() {
		if (!$this->validate()) {
			return $this->forward('error/permission');
		}

		$this->load->model('tool/virtuemart');
		
		$this->model_tool_virtuemart->importProducts($this->request->post['virtuemart']);
	}
	
	public function importManufacturers() {
		if (!$this->validate()) {
			return $this->forward('error/permission');
		}

		$this->load->model('tool/virtuemart');
		
		$this->model_tool_virtuemart->importManufacturers($this->request->post['virtuemart']);
	}

	public function importUsers() {
		if (!$this->validate()) {
			return $this->forward('error/permission');
		}

		$this->load->model('tool/virtuemart');

		$this->model_tool_virtuemart->importUsers($this->request->post['virtuemart']);
	}
	
	public function copyImages() {
		if (!$this->validate()) {
			return $this->forward('error/permission');
		}

		$this->load->model('tool/virtuemart');
		
		$this->model_tool_virtuemart->copyImages($this->request->post['virtuemart']);
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/virtuemart')) {
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