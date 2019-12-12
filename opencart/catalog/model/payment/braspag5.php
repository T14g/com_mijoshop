<?php
class ModelPaymentBraspag5 extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/cod');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('braspag5_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('braspag5_total_cartao') > 0 && $this->config->get('braspag5_total_cartao') > $total) {
			$status = false;
		} elseif (!$this->config->get('braspag5_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
		
		$st = $this->config->get('braspag5_status_cartao');
		if(!$st){
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'braspag5',
				'title'      => $this->config->get('braspag5_titulo_cartao'),
				'terms'      => '',
				'sort_order' => $this->config->get('braspag5_ordem_cartao')
			);
		}

		return $method_data;
	}
}