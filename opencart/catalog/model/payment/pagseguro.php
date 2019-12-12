<?php 
class ModelPaymentPagseguro extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/pagseguro');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('pagseguro_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('pagseguro_total') > 0 && $this->config->get('pagseguro_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('pagseguro_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'pagseguro',
        		'title'      => $this->language->get('text_title'),
        		'terms'      => '',
				'sort_order' => $this->config->get('pagseguro_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
	
  	public function getFieldValue($fieldname) {
		$custom_fields = array();
		
		$custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field` cf LEFT JOIN `" . DB_PREFIX . "custom_field_description` cfd ON (cf.custom_field_id = cfd.custom_field_id) WHERE cf.status = '1' AND cfd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cf.status = '1' ORDER BY cf.sort_order ASC");

		foreach ($custom_field_query->rows as $custom_field) {
			$custom_field_value_data = array();

			if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio' || $custom_field['type'] == 'checkbox') {
				$custom_field_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value cfv LEFT JOIN " . DB_PREFIX . "custom_field_value_description cfvd ON (cfv.custom_field_value_id = cfvd.custom_field_value_id) WHERE cfv.custom_field_id = '" . (int)$custom_field['custom_field_id'] . "' AND cfvd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cfv.sort_order ASC");

				foreach ($custom_field_value_query->rows as $custom_field_value) {
					$custom_field_value_data[] = array(
						'custom_field_value_id' => $custom_field_value['custom_field_value_id'],
						'name'                  => $custom_field_value['name']
					);
				}
			}

			$custom_fields[] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $custom_field_value_data,
				'name'               => $custom_field['name'],
				'type'               => $custom_field['type'],
				'value'              => $custom_field['value'],
				'location'           => $custom_field['location'],
				'required'           => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true,
				'sort_order'         => $custom_field['sort_order']
			);
		}
		$value = '';

		foreach ($custom_fields as $custom_field) {
			if(isset($order_info['payment_custom_field'][$custom_field['custom_field_id']]) && $custom_field['name'] == $fieldname){
				$value =  $order_info['payment_custom_field'][$custom_field['custom_field_id']];
			} else if(isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']]) && $custom_field['name'] == $fieldname){
				$value =  $order_info['shipping_custom_field'][$custom_field['custom_field_id']];
			}
		}
		return $value;
  	}	
}
?>