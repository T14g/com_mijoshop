<?php
class ControllerPaymentBraspag5Debito extends Controller {
	public function index() {
		$this->load->language('payment/braspag5debito');
		$this->document->setTitle('Braspag / Cielo 3.0 [Loja5]');
		$this->load->model('setting/setting');
		$this->response->redirect($this->url->link('payment/braspag5', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	public function orderAction() {
		return $this->orderCielo();
	}
	
	public function order() {
		return $this->orderCielo();
	}
	
	public function action() {
		return $this->orderCielo();
	}
	
	public function orderCielo() {
		$existe = $this->getOrderCielo($this->request->get['order_id']);
		if(isset($existe['link']) && !empty($existe['link'])){
		$data['operadora'] = ucfirst($this->config->get('braspag5_operadora'));
		$data['cielo'] = $this->getOrderCielo($this->request->get['order_id']);
		$data['link_consulta'] = $this->url->link('payment/braspag5/consultar', 'token=' . $this->session->data['token'].'&pedido='.$this->request->get['order_id'].'&tid='.$existe['link'], 'SSL');
		return $this->load->view('payment/braspag5_acoes.tpl', $data);
		}
	}
	
	public function getOrderCielo($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "braspag5_pedidos` WHERE `id_pedido` = '" . (int)$order_id . "' ORDER BY id_braspag DESC");
		return $order_query->row;
	}
	
	public function uninstall(){
		$this->load->model('extension/extension');
		$this->model_extension_extension->uninstall('payment', 'braspag5debito');
	}
	
	public function install() {
		$this->load->model('extension/extension');
		$this->model_extension_extension->install('payment', 'braspag5debito');
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "braspag5_pedidos` (
			`id_braspag` INT(15) NOT NULL AUTO_INCREMENT,
			`id_pedido` INT(15) NOT NULL,
			`total_pedido` FLOAT(10,2) NOT NULL,
			`total_pago` FLOAT(10,2) NOT NULL,
			`bandeira` VARCHAR(20) NOT NULL,
			`parcelas` INT(10) NOT NULL,
			`tid` VARCHAR(30) NOT NULL,
			`lr` VARCHAR(5) NOT NULL,
			`lr_log` TEXT NOT NULL,
			`status` INT(10) NOT NULL,
			`bin` VARCHAR(20) NOT NULL,
			`tipo` ENUM('credito','debito', 'tef', 'boleto') NOT NULL,
			`link` VARCHAR(255) NOT NULL,
			`data` DATETIME NOT NULL,
			PRIMARY KEY (`id_braspag`)
		)
		COLLATE='latin1_swedish_ci'
		ENGINE=InnoDB
		AUTO_INCREMENT=17
		;");
	}
}