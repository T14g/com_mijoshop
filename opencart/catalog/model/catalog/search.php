<?php
/**
* @package        MijoShop
* @copyright      2009-2016 Miwisoft LLC, miwisoft.com
* @license        GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

class ModelCatalogSearch extends Model
{
    
    public function add($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_search` SET `store_id` = '" . (int) $this->config->get('config_store_id') . "', `language_id` = '" . (int) $this->session->data['language'] . "', `customer_id` = '" . (int) $data['customer_id'] . "', `keyword` = '" . $this->db->escape($data['keyword']) . "', `category_id` = '" . (int) $data['category_id'] . "', `sub_category` = '" . (int) $data['sub_category'] . "', `description` = '" . (int) $data['description'] . "', `products` = '" . (int) $data['products'] . "', `ip` = '" . $this->db->escape($data['ip']) . "', `date_added` = NOW()");
    }
}