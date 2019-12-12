<?php defined('_JEXEC') or die('Restricted access');

class ModelCatalogCtsem extends Model {
	public function getCtsemlocal($category_id) {			

	$sql = "SELECT DISTINCT manufacturer_id FROM ". DB_PREFIX ."product_to_category LEFT JOIN ". DB_PREFIX ."product ON ". DB_PREFIX ."product_to_category.product_id =  ". DB_PREFIX ."product.product_id WHERE `category_id`= ".$category_id." ORDER BY manufacturer_id";
	$query = $this->db->query($sql);
	return $query->rows;

	}

	public function getCtsemlocalname($category_id) {

		$sql = "SELECT DISTINCT m.name FROM ". DB_PREFIX ."manufacturer m inner join ". DB_PREFIX ."product p on m.manufacturer_id = p.manufacturer_id inner join ". DB_PREFIX ."product_to_category c on p.product_id = c.product_id WHERE c.category_id = ". $category_id." ORDER BY p.manufacturer_id";

		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getCtsemdatas($category_id) {

		$sql = "SELECT DISTINCT DATE_FORMAT(date_added, '%m-%Y') as dataNova FROM ". DB_PREFIX ."product_to_category LEFT JOIN ". DB_PREFIX ."product ON ". DB_PREFIX ."product_to_category.product_id =  ". DB_PREFIX ."product.product_id WHERE `category_id`= ".$category_id." AND status = 1 ORDER BY date_added ASC";
		$query = $this->db->query($sql);
		return $query->rows;

	}

	

}

?>