<?php 
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class ControllerToolAceshop extends Controller {

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
       		'text'      => 'AceShop',
			'href'      => $this->url->link('tool/aceshop', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/aceshop.tpl', $data));
  	}
  	
  	public function migrateDatabase(){
		$this->load->model('tool/aceshop');
        $post = $this->request->post['aceshop'];
        $post['component'] = 'aceshop';
		
		$this->model_tool_aceshop->migrateDatabase($post);
	}
  	
  	public function migrateFiles() {
		$this->load->model('tool/aceshop');
        $post = $this->request->post['aceshop'];
        $post['component'] = 'aceshop';
		
		$this->model_tool_aceshop->migrateFiles($post);
	}
	
	public function fixMenus() {
		$this->load->model('tool/aceshop');
        $post = $this->request->post['aceshop'];
        $post['component'] = 'aceshop';
		
		$this->model_tool_aceshop->fixMenus($post);
	}
	
	public function fixModules() {
		$this->load->model('tool/aceshop');
        $post = $this->request->post['aceshop'];
        $post['component'] = 'aceshop';
		
		$this->model_tool_aceshop->fixModules($post);
	}
}