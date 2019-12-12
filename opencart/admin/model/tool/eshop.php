<?php 
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class ModelToolEshop extends Model {
  	
  	public function importCategories($post){
        static $lang_cache = array();
		$db = JFactory::getDBO();
		$jstatus = MijoShop::get('base')->is30();
		
		$oc_lang_id = (int)$this->config->get('config_language_id');
	 
		$cat = "SELECT d.category_id, d.category_name, d.category_desc, c.created_date, c.modified_date, c.category_parent_id, c.products_per_row, c.category_image, c.ordering, c.published, d.category_alias, d.meta_key, d.meta_desc, d.language ".
			" FROM #__eshop_categories AS c LEFT JOIN #__eshop_categorydetails AS d ON d.category_id = c.id ORDER BY d.category_id";
		$db->setQuery($cat);
		$cats = $db->loadAssocList();
		
		if (empty($cats)) {
			echo '<strong>No category to import.</strong>';
			exit;
		}
		
		$i = 0;
		foreach($cats as $cat) {
            if (!isset($lang_cache[$cat['language']])) {
                $lang_cache[$cat['language']] = self::_getJoomlaLanguage($cat['language']);
            }

			$datec = ($jstatus) ? JFactory::getDate($cat['created_date'])->toSql() : JFactory::getDate($cat['created_date'])->toMySQL();
			$datem = ($jstatus) ? JFactory::getDate($cat['modified_date'])->toSql() : JFactory::getDate($cat['modified_date'])->toMySQL();
			
			$cat_image = empty($cat['category_image']) ? '' : (($jstatus) ? 'catalog/'.$db->escape($cat['category_image']) : 'catalog/'.$db->getEscaped($cat['category_image']));
			
			$cat_name = ($jstatus) ? $db->escape(htmlspecialchars($cat['category_name'])) : $db->getEscaped(htmlspecialchars($cat['category_name']));
			$cat_desc = ($jstatus) ? $db->escape(htmlspecialchars($cat['category_desc'])) : $db->getEscaped(htmlspecialchars($cat['category_desc']));
			$meta_key = ($jstatus) ? $db->escape(htmlspecialchars($cat['meta_key'])) : $db->getEscaped(htmlspecialchars($cat['meta_key']));
			$meta_desc = ($jstatus) ? $db->escape(htmlspecialchars($cat['meta_desc'])) : $db->getEscaped(htmlspecialchars($cat['meta_desc']));
			
			$q = "INSERT IGNORE INTO `#__mijoshop_category` ( `category_id` , `image` , `parent_id` , `sort_order` , `column` , `date_added` , `date_modified` , `status`) VALUES ('". $cat['category_id']."', '".$cat_image."', '".$cat['category_parent_id']."', '".$cat['ordering']."', '".$cat['products_per_row']."', '".$datec."', '".$datem."', '".$cat['published']."')";
			$db->setQuery($q);
			$db->query();
			
			$q = "INSERT IGNORE INTO `#__mijoshop_category_description` (`category_id`, `language_id`, `name`, `description`, `meta_description`, `meta_keyword`) VALUES ('". $cat['category_id']."', '".$lang_cache[$cat['language']]."', '".$cat_name."', '".$cat_desc."', '".$meta_desc."', '".$meta_key."')";
			$db->setQuery($q);
			$db->query();
			
			$q = "INSERT IGNORE INTO `#__mijoshop_category_to_store` (`category_id` , `store_id`) VALUES ('".$cat['category_id']."' , '0')";
			$db->setQuery($q);
			$db->query();

			echo 'Importing <i>' . $cat['category_name'] .'</i> : Completed.<br />';
			$i++;
		}
		
		self::_addCategoryPath();
		
		echo '<strong>Categories has been imported successfully.</strong><br />';
		exit;
	}

  	public function _getJoomlaLanguage($jlang) {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT lang_id FROM `#__languages` WHERE `lang_code` = '{$jlang}'");
        return $db->loadResult();
    }

  	public function _addCategoryPath() {
        $db = JFactory::getDBO();
		$db->setQuery("SELECT id FROM `#__eshop_categories`");
		$categories = $db->loadObjectList();

		if (!empty($categories)){
			foreach($categories as $category){
				$path = self::_getPath($category->id, array($category->id));
				$path = array_reverse($path);
				
				foreach($path as $key => $_path){
                    $db->setQuery("INSERT IGNORE INTO `#__mijoshop_category_path` (`category_id`, `path_id`, `level`) VALUES ('{$category->id}','{$_path}','{$key}')");
					$db->query();
				}
			}
		}
	}

    private function _getPath($cat_id, $path = array()){
        $db = JFactory::getDBO();
        $db->setQuery("SELECT category_parent_id FROM `#__eshop_categories` WHERE id = ".$cat_id);
        $parent_id = $db->loadResult();

        if ((int)$parent_id != 0) {
            $path[] = $parent_id;
            $path = self::_getPath($parent_id, $path);
        }

        return $path;
    }
  	
  	public function importProducts($post) {
        static $lang_cache = array();
		$db = JFactory::getDBO();
		$jstatus = MijoShop::get('base')->is30();
		
		$oc_lang_id = (int)$this->config->get('config_language_id');
	 
		$q = "SELECT d.product_id, d.product_name, d.product_alias, d.product_desc, d.meta_key, d.meta_desc, d.language, p.manufacturer_id, p.product_sku, p.product_weight, p.product_weight_id,".
		" p.product_length, p.product_width, p.product_height, p.product_length_id, p.product_price, p.product_taxclass_id, p.product_quantity, p.product_shipping, p.product_image, ".
		" p.product_available_date, p.published, p.ordering, p.hits, p.created_date, p.modified_date FROM #__eshop_products AS p LEFT JOIN #__eshop_productdetails AS d ON d.product_id = p.id ORDER BY p.id";
		$db->setQuery($q);
		$pros = $db->loadAssocList();
		
		if (empty($pros)) {
			echo '<strong>No product to import.</strong>';
			exit;
		}
		
		foreach($pros as $pro){
            if (!isset($lang_cache[$pro['language']])) {
                $lang_cache[$pro['language']] = self::_getJoomlaLanguage($pro['language']);
            }

			if($pro['product_weight_id'] == '3'){
				$pro['product_weight_id'] = '6';
			}
			else if($pro['product_weight_id'] == '4'){
				$pro['product_weight_id'] = '5';
			}
			if($pro['product_length_id'] == '2'){
				$pro['product_length_id'] = '3';
			}
			else if($pro['product_length_id'] == '3'){
				$pro['product_length_id'] = '2';
			}
			if($pro['product_taxclass_id'] == '1'){
				$pro['product_taxclass_id'] = '10';
			}
			else if($pro['product_taxclass_id'] == '2'){
				$pro['product_taxclass_id'] = '9';
			}
			
			$datec = ($jstatus) ? JFactory::getDate($pro['created_date'])->toSql() : JFactory::getDate($pro['created_date'])->toMySQL();
			$datem = ($jstatus) ? JFactory::getDate($pro['modified_date'])->toSql() : JFactory::getDate($pro['modified_date'])->toMySQL();
			$datep = ($jstatus) ? JFactory::getDate($pro['product_available_date'])->toSql() : JFactory::getDate($pro['product_available_date'])->toMySQL();
			
			$pro_image = empty($pro['product_image']) ? '' : (($jstatus) ? 'catalog/'.$db->escape($pro['product_image']) : 'catalog/'.$db->getEscaped($pro['product_image']));
			
			$pro_name = ($jstatus) ? $db->escape(htmlspecialchars($pro['product_name'])) : $db->getEscaped(htmlspecialchars($pro['product_name']));
			$pro_desc = ($jstatus) ? $db->escape(htmlspecialchars($pro['product_desc'])) : $db->getEscaped(htmlspecialchars($pro['product_desc']));
			$meta_desc = ($jstatus) ? $db->escape(htmlspecialchars($pro['meta_desc'])) : $db->getEscaped(htmlspecialchars($pro['meta_desc']));
			$meta_key = ($jstatus) ? $db->escape(htmlspecialchars($pro['meta_key'])) : $db->getEscaped(htmlspecialchars($pro['meta_key']));
			
			$q = "INSERT IGNORE INTO `#__mijoshop_product` (`product_id`, `model`, `sku`, `location`, `quantity`, `stock_status_id`, `image`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `weight`, `weight_class_id`, `length`, `length_class_id`,".
				" `width`, `height`, `status`, `date_added`, `date_modified`, `date_available`, `sort_order`, `viewed`)".
				" VALUES ('".$pro['product_id']."', '".$pro['product_sku']."', '".$pro['product_sku']."', '', '".$pro['product_quantity']."', '7', '".$pro_image."', '".$pro['manufacturer_id']."', '".$pro['product_shipping']."', '".$pro['product_price'].
				"', '".$pro['product_taxclass_id']."', '".$pro['product_weight']."', '".$pro['product_weight_id']."', '".$pro['product_length']."', '".$pro['product_length_id']."', '".$pro['product_width']."', '".$pro['product_height']."',  '".$pro['published'].
				"', '".$datec."', '".$datem."',  '".$datep."', '".$pro['ordering']."', '".$pro['hits']."')";
			$db->setQuery($q);
			$db->query();
			
			$q = "INSERT IGNORE INTO `#__mijoshop_product_description` (`product_id` , `language_id` , `name` , `description` , `meta_description` , `meta_keyword`) VALUES ('".$pro['product_id']."' , '".$lang_cache[$pro['language']]."' , '".$pro_name."' , '".$pro_desc."', '".$meta_desc."', '".$meta_key."')";
			$db->setQuery($q);
			$db->query();
			
			$q = "INSERT IGNORE INTO `#__mijoshop_product_to_store` (`product_id`, `store_id`) VALUES ('".$pro['product_id']."' , '0')";
			$db->setQuery($q);
			$db->query();
	    
			echo 'Importing <i>' . $pro['product_name'] .'</i> : Completed.<br />';
		}
	  
		$q = "SELECT * FROM `#__eshop_productcategories`";
		$db->setQuery($q);
		$results = $db->loadAssocList();
		
		if (!empty($results)) {
			foreach($results as $ptcs) {
				$ptjc = "INSERT IGNORE INTO `#__mijoshop_product_to_category` (`product_id`, `category_id`) VALUES ('".$ptcs['product_id']."', '".$ptcs['category_id']."')";
				$db->setQuery($ptjc);
				$db->query();
			}
		}
		
		$q = "SELECT * FROM `#__eshop_productrelations`";
		$db->setQuery($q);
		$relresults = $db->loadAssocList();
		
		if (!empty($relresults)) {
			foreach($relresults as $relres) {
				$ptjc = "INSERT IGNORE INTO `#__mijoshop_product_related` (`related_id`, `product_id`) VALUES ('".$relres['related_product_id']."', '".$relres['product_id']."')";
				$db->setQuery($ptjc);
				$db->query();
			}
		}
		
		$q = "SELECT * FROM `#__eshop_productimages`";
		$db->setQuery($q);
		$imgresults = $db->loadAssocList();
		
		if (!empty($imgresults)) {
			foreach($imgresults as $imgres) {
				$ptjc = "INSERT IGNORE INTO `#__mijoshop_product_image` (`product_image_id`, `product_id`, `image`, `sort_order`) VALUES ('".$imgres['id']."', '".$imgres['product_id']."', 'catalog/".$imgres['image']."', '".$imgres['ordering']."')";
				$db->setQuery($ptjc);
				$db->query();
			}
		}
		
		$q = "SELECT * FROM `#__eshop_productdiscounts`";
		$db->setQuery($q);
		$discresults = $db->loadAssocList();
		
		if (!empty($discresults)) {
			foreach($discresults as $discres) {
				$date_start = ($jstatus) ? JFactory::getDate($discres['date_start'])->toSql() : JFactory::getDate($discres['date_start'])->toMySQL();
				$date_end = ($jstatus) ? JFactory::getDate($discres['date_end'])->toSql() : JFactory::getDate($discres['date_end'])->toMySQL();
				$ptjc = "INSERT IGNORE INTO `#__mijoshop_product_discount` (`product_discount_id`, `product_id`, `customer_group_id`, `quantity`, `priority`, `price`, `date_start`, `date_end`) VALUES ('".$discres['id']."', '".$discres['product_id']."', '".$discres['customergroup_id']."', '".$discres['quantity']."', '".$discres['priority']."', '".$discres['price']."', '".$date_start."', '".$date_end."')";
				$db->setQuery($ptjc);
				$db->query();
			}
		}

		$q = "SELECT id FROM `#__eshop_products` WHERE `product_featured` = '1'";
		$db->setQuery($q);
		$featcresults = $db->loadAssocList();

		if (!empty($featcresults)) {
            $feat_id = '';
			foreach($featcresults as $featcresult) {
                $feat_id .= ','.$featcresult['id'];
			}

            if(!empty($feat_id)){
                $feat_id = ltrim($feat_id, ',');
                $ptjc = "UPDATE `#__mijoshop_setting` SET `value` = '".$feat_id."' WHERE `key` LIKE 'featured_product' AND `group` LIKE 'featured'";
                $db->setQuery($ptjc);
                $db->query();
            }
		}

		echo '<strong>Products has been imported successfully.</strong><br />';
		exit;
	}
	
	public function importManufacturers($post) {
		$db = JFactory::getDBO();
		$jstatus = MijoShop::get('base')->is30();
		
		$oc_lang_id = (int)$this->config->get('config_language_id');
	  
		$q = "SELECT d.manufacturer_id, d.manufacturer_name, m.manufacturer_image FROM `#__eshop_manufacturers` AS m LEFT JOIN `#__eshop_manufacturerdetails` AS d ON d.manufacturer_id = m.id";
		$db->setQuery($q);
		$mans = $db->loadAssocList();
		
		if (empty($mans)) {
			echo '<strong>No manufacturer to import.</strong>';
			exit;
		}
		
		foreach($mans as $man) {		
			$man_image = empty($man['manufacturer_image']) ? '' : (($jstatus) ? 'catalog/'.$db->escape($man['manufacturer_image']) : 'catalog/'.$db->getEscaped($man['manufacturer_image']));
			$man_name = ($jstatus) ? $db->escape(htmlspecialchars($man['manufacturer_name'])) : $db->getEscaped(htmlspecialchars($man['manufacturer_name']));
			
			$q = "INSERT IGNORE INTO `#__mijoshop_manufacturer` (`manufacturer_id`, `name`, `image`) VALUES ('".$man['manufacturer_id']."', '".$man_name."', '".$man_image."')";
			$db->setQuery($q);
			$db->query();
			
			$q = "INSERT IGNORE INTO `#__mijoshop_manufacturer_to_store` (`manufacturer_id` , `store_id`) VALUES ('".$man['manufacturer_id']."' , '0')";
			$db->setQuery($q);
			$db->query();
			
			echo 'Importing <i>' . $man['manufacturer_name'] .'</i> : Completed.<br />';
		}
		
		echo '<strong>Manufacturers has been imported successfully.</strong><br />';
		exit;
	}
	
	public function copyImages($post) {
		$cat_images = JPATH_SITE.'/media/com_eshop/categories/';
		$pro_images = JPATH_SITE.'/media/com_eshop/products/';
		$man_images = JPATH_SITE.'/media/com_eshop/manufacturers/';
		
		self::_copyImages($cat_images);
		self::_copyImages($pro_images);
		self::_copyImages($man_images);
	  
		echo '<strong>Images has been copied successfully.</strong>';
		exit;
	}
	
	public function _copyImages($dir) {
		foreach (glob($dir . "*") as $filename) {
			if (JFolder::exists($filename)) {
				continue;
			}
			
			if (!JFile::copy($filename, DIR_IMAGE . 'catalog/' . basename($filename))){
				echo 'Failed to copy <i>' . $filename . '</i> to image directory.<br />';
			}
			else {
				echo 'Copying <i>' . $filename . '</i> : Completed.<br />';
			}
		}
	}
}