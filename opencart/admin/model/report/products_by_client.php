<?php
class ModelReportProductsByClient extends Model{
	public function getProductsByClientsTotal($data = array())	{	
	//SELECT * FROM `final_mijoshop_order_product` op INNER JOIN `final_mijoshop_order` o ON (op.order_id = o.order_id) WHERE date_added >= "2017-05-04" AND date_added <= "2017-05-11" AND `order_status_id` = 5 ORDER BY `o`.`firstname` ASC	
		$sql = "SELECT count(*) as qtd FROM `" . DB_PREFIX . "order_product` op INNER JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) %s group by o.order_id";
		
		$where = "";		
		if(isset($data["filter_product_id"]) && !empty($data["filter_product_id"]))	{
			$where .= "op.product_id = {$data["filter_product_id"]}";
		}		
		if(isset($data["filter_product_name"]) && !empty($data["filter_product_name"])){
			if(!empty($where)){
				$where .= " and op.name LIKE '%{$data["filter_product_name"]}%'";
			} else	{
				$where .= " op.name LIKE '%{$data["filter_product_name"]}%'";
			}
		}		
		if(isset($data["filter_customer_name"]) && !empty($data["filter_customer_name"]))
		{
			if(!empty($where))
			{
				$where .= " and (o.firstname LIKE '%{$data["filter_customer_name"]}%' or o.lastname LIKE '%{$data["filter_customer_name"]}%')";
			}
			else
			{
				$where .= " (o.firstname LIKE '%{$data["filter_customer_name"]}%' or o.lastname LIKE '%{$data["filter_customer_name"]}%')";
			}
		}
		
		if(isset($data["filter_customer_email"]) && !empty($data["filter_customer_email"]))
		{
			if(!empty($where))
			{
				$where .= " and o.email LIKE '%{$data["filter_customer_email"]}%'";
			}
			else
			{
				$where .= " o.email LIKE '%{$data["filter_customer_email"]}%'";
			}
		}
		
		if(isset($data["filter_order_status_id"]) && $data["filter_order_status_id"] != "")	{
			if(!empty($where)){
				$where .= " and o.order_status_id = {$data["filter_order_status_id"]}";
			}	else	{
				$where .= " o.order_status_id = {$data["filter_order_status_id"]}";
			}
		}
		
		if(isset($data["filter_date_start"]) && !empty($data["filter_date_start"]))	{
			if(!empty($where))
			{
				$where .= " and DATE(o.date_added) >= '{$data["filter_date_start"]}'";
			}
			else
			{
				$where .= " DATE(o.date_added) >= '{$data["filter_date_start"]}'";
			}
		}
		
		if(isset($data["filter_date_end"]) && !empty($data["filter_date_end"]))	{
			if(!empty($where)){
				$where .= " and DATE(o.date_added) <= '{$data["filter_date_end"]}'";
			}else	{
				$where .= " DATE(o.date_added) <= '{$data["filter_date_end"]}'";
			}
		}
		
		if(!empty($where)){
			$where = "where {$where}";
		}
		
		$sql = sprintf($sql, $where);
		$query = $this->db->query($sql);
		return $query->num_rows;
	}

	public function getProductsByClients($data = array()){
		
		$sql = "SELECT o.order_id, o.date_added, o.date_modified, op.product_id, op.name, CONCAT(coalesce(o.firstname,''), ' ', coalesce(o.lastname, '')) as fullname, o.customer_id, o.email, o.order_status_id, o.telephone, 
		(SELECT " . DB_PREFIX . "customer.custom_field FROM `" . DB_PREFIX . "customer` INNER JOIN `" . DB_PREFIX . "order` ON " . DB_PREFIX . "customer.customer_id=" . DB_PREFIX . "order.customer_id WHERE " . DB_PREFIX . "order.order_id= o.order_id LIMIT 1) AS personalizados, 
		(SELECT " . DB_PREFIX . "address.custom_field FROM `" . DB_PREFIX . "address` INNER JOIN `" . DB_PREFIX . "order` ON " . DB_PREFIX . "address.customer_id=" . DB_PREFIX . "order.customer_id WHERE " . DB_PREFIX . "order.order_id= o.order_id LIMIT 1) AS end_personalizados,
		SUM(op.quantity) as quantity, SUM(op.total) as total FROM `" . DB_PREFIX . "order_product` op INNER JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) %s group by o.order_id";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit']))
		{
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$where = "";
		
		if(isset($data["filter_product_id"]) && !empty($data["filter_product_id"]))
		{
			$where .= "op.product_id = {$data["filter_product_id"]}";
		}
		
		if(isset($data["filter_product_name"]) && !empty($data["filter_product_name"]))
		{
			if(!empty($where))
			{
				$where .= " and op.name LIKE '%{$data["filter_product_name"]}%'";
			}
			else
			{
				$where .= " op.name LIKE '%{$data["filter_product_name"]}%'";
			}
		}
		
		if(isset($data["filter_customer_name"]) && !empty($data["filter_customer_name"]))
		{
			if(!empty($where))
			{
				$where .= " and (o.firstname LIKE '%{$data["filter_customer_name"]}%' or o.lastname LIKE '%{$data["filter_customer_name"]}%')";
			}
			else
			{
				$where .= " (o.firstname LIKE '%{$data["filter_customer_name"]}%' or o.lastname LIKE '%{$data["filter_customer_name"]}%')";
			}
		}
		
		if(isset($data["filter_customer_email"]) && !empty($data["filter_customer_email"]))
		{
			if(!empty($where))
			{
				$where .= " and o.email LIKE '%{$data["filter_customer_email"]}%'";
			}
			else
			{
				$where .= " o.email LIKE '%{$data["filter_customer_email"]}%'";
			}
		}
		
		if(isset($data["filter_order_status_id"]) && $data["filter_order_status_id"] != "")
		{
			if(!empty($where))
			{
				$where .= " and o.order_status_id = {$data["filter_order_status_id"]}";
			}
			else
			{
				$where .= " o.order_status_id = {$data["filter_order_status_id"]}";
			}
		}
		
		if(isset($data["filter_date_start"]) && !empty($data["filter_date_start"]))
		{
			if(!empty($where))
			{
				$where .= " and DATE(o.date_added) >= '{$data["filter_date_start"]}'";
			}
			else
			{
				$where .= " DATE(o.date_added) >= '{$data["filter_date_start"]}'";
			}
		}
		
		if(isset($data["filter_date_end"]) && !empty($data["filter_date_end"]))
		{
			if(!empty($where))
			{
				$where .= " and DATE(o.date_added) <= '{$data["filter_date_end"]}'";
			}
			else
			{
				$where .= " DATE(o.date_added) <= '{$data["filter_date_end"]}'";
			}
		}

		if(!empty($where)){
			$where = "where {$where}";
		}
		
		$sql = sprintf($sql, $where);
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getDetails($data){
		
		$sql = "SELECT c.customer_id, CONCAT(coalesce(c.firstname,''), ' ', coalesce(c.lastname, '')) as fullname, c.email, c.telephone, (SELECT pd.name FROM `" . DB_PREFIX . "product_description` pd WHERE pd.product_id = {$data['product_id']} and pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS product_name FROM `" . DB_PREFIX . "customer` c where c.customer_id = '{$data['customer_id']}'";

		$query = $this->db->query($sql);

		return $query->row;
	}
	
	public function getProductsByClientDetailsTotal($data)	{
		
		$sql = "SELECT o.order_id, o.order_status_id, o.payment_method, o.total, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order_product` op INNER JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) where o.customer_id = {$data['customer_id']} and op.product_id = {$data['product_id']} %s ";
		
		$where = "";
		
		if(isset($data["filter_order_status_id"]) && !empty($data["filter_order_status_id"]))
		{
			$where .= " and o.order_status_id = {$data["filter_order_status_id"]}";
		}
		
		$sql = sprintf($sql, $where);

		$query = $this->db->query($sql);

		return $query->num_rows;
	}
	
	public function getProductsByClientDetails($data){
		$sql = "SELECT o.order_id, o.order_status_id, o.payment_method, o.total, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order_product` op INNER JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) where o.customer_id = {$data['customer_id']} and op.product_id = {$data['product_id']} %s ";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$where = "";
		
		if(isset($data["filter_order_status_id"]) && !empty($data["filter_order_status_id"]))
		{
			$where .= " and o.order_status_id = {$data["filter_order_status_id"]}";
		}
		
		$sql = sprintf($sql, $where);
		$query = $this->db->query($sql);
		return $query->rows;
	}


public function getProductsByClientsQuanti($data = array()){
		
		$sql = "SELECT o.order_id, o.date_added, o.date_modified, op.product_id, op.name, CONCAT(coalesce(o.firstname,''), ' ', coalesce(o.lastname, '')) as fullname, o.customer_id, o.email, o.order_status_id, o.telephone, 		 
		(SELECT " . DB_PREFIX . "address.custom_field FROM `" . DB_PREFIX . "address` INNER JOIN `" . DB_PREFIX . "order` ON " . DB_PREFIX . "address.customer_id=" . DB_PREFIX . "order.customer_id WHERE " . DB_PREFIX . "order.order_id= o.order_id LIMIT 1) AS end_personalizados, 
		(SELECT COUNT(`product_id`) FROM `" . DB_PREFIX . "order_product` op
			INNER JOIN " . DB_PREFIX . "order o ON op.`order_id` = o.`order_id` 
		WHERE `order_status_id` = 5) AS completos,
		SUM(op.quantity) as quantity, SUM(op.total) as total FROM `" . DB_PREFIX . "order_product` op INNER JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) %s group by o.order_id";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit']))
		{
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$where = "";
		
		if(isset($data["filter_product_id"]) && !empty($data["filter_product_id"]))
		{
			$where .= "op.product_id = {$data["filter_product_id"]}";
		}
		
		if(isset($data["filter_product_name"]) && !empty($data["filter_product_name"]))
		{
			if(!empty($where))
			{
				$where .= " and op.name LIKE '%{$data["filter_product_name"]}%'";
			}
			else
			{
				$where .= " op.name LIKE '%{$data["filter_product_name"]}%'";
			}
		}
		
		if(isset($data["filter_customer_name"]) && !empty($data["filter_customer_name"]))
		{
			if(!empty($where))
			{
				$where .= " and (o.firstname LIKE '%{$data["filter_customer_name"]}%' or o.lastname LIKE '%{$data["filter_customer_name"]}%')";
			}
			else
			{
				$where .= " (o.firstname LIKE '%{$data["filter_customer_name"]}%' or o.lastname LIKE '%{$data["filter_customer_name"]}%')";
			}
		}
		
		if(isset($data["filter_customer_email"]) && !empty($data["filter_customer_email"]))
		{
			if(!empty($where))
			{
				$where .= " and o.email LIKE '%{$data["filter_customer_email"]}%'";
			}
			else
			{
				$where .= " o.email LIKE '%{$data["filter_customer_email"]}%'";
			}
		}
		
		if(isset($data["filter_order_status_id"]) && $data["filter_order_status_id"] != "")
		{
			if(!empty($where))
			{
				$where .= " and o.order_status_id = {$data["filter_order_status_id"]}";
			}
			else
			{
				$where .= " o.order_status_id = {$data["filter_order_status_id"]}";
			}
		}
		
		if(isset($data["filter_date_start"]) && !empty($data["filter_date_start"]))
		{
			if(!empty($where))
			{
				$where .= " and DATE(o.date_added) >= '{$data["filter_date_start"]}'";
			}
			else
			{
				$where .= " DATE(o.date_added) >= '{$data["filter_date_start"]}'";
			}
		}
		
		if(isset($data["filter_date_end"]) && !empty($data["filter_date_end"]))
		{
			if(!empty($where))
			{
				$where .= " and DATE(o.date_added) <= '{$data["filter_date_end"]}'";
			}
			else
			{
				$where .= " DATE(o.date_added) <= '{$data["filter_date_end"]}'";
			}
		}

		if(!empty($where)){
			$where = "where {$where}";
		}
		
		$sql = sprintf($sql, $where);
		$query = $this->db->query($sql);
		return $query->rows;
	}

}