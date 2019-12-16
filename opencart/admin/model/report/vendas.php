<?php
class ModelReportVendas extends Model {

	public function getTotalVendas() {
		$sql  = "SELECT COUNT(p.product_id) AS total FROM `" . DB_PREFIX . "product` p WHERE 1 = 1 ";

		if (!empty($data['filter_estado_curso']) && $data['filter_estado_curso'] == "1") {
			$sql .= " AND p.status = '1'";
		}elseif(!empty($data['filter_estado_curso']) && $data['filter_estado_curso'] == "-1"){
			$sql .= " AND p.status = '0' ";
		}else{
			$sql .= " AND p.status = '1'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
    }
    
    public function getVendas($data = array()){
		// var_dump($data);
		$sql  = "SELECT pd.name,p.date_added, SUM(IF(o.order_status_id = 0,1,0)) AS abandonados,SUM(IF(o.order_status_id = 5,1,0)) AS completos,SUM(IF(o.order_status_id = 1,1,0)) AS pendentes, SUM(IF(o.order_status_id = 2,1,0)) AS processando, SUM(IF(o.order_status_id = 7,1,0)) AS cancelados  FROM " . DB_PREFIX . "order o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (op.order_id = o.order_id) RIGHT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) INNER JOIN `" . DB_PREFIX . "product_description` pd ON (pd.product_id = p.product_id) WHERE 1 = 1";
		

		if (!empty($data['filter_estado_curso']) && $data['filter_estado_curso'] == "1") {
			$sql .= " AND p.status = '1'";
		}elseif(!empty($data['filter_estado_curso']) && $data['filter_estado_curso'] == "-1"){
			$sql .= " AND p.status = '0' ";
		}else{
			$sql .= " AND p.status = '1'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}


		if (!empty($data['filter_date_start'])) {
		
			$sql .= " AND p.date_added >= '" . $data['filter_date_start'] . "'";
		}

		if (!empty($data['filter_date_end'])) {
			
			$sql .= " AND p.date_added <= '" . $data['filter_date_end'] . "'";
		}

		$sql .=" group by p.product_id ORDER BY p.date_added ASC";


        $query = $this->db->query($sql);
		// var_dump($query->rows); 
		return $query->rows;
        
    }
		
}