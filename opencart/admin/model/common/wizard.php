<?php
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class ModelCommonWizard extends Model {
    public function save($data) {

        $mijo_settings = $this->getMijoSettings();

        $mijo_settings['wizard'] = 1;
		
		$mijo_settings['firewall_lfi'] = 'get';
		$mijo_settings['firewall_rfi'] = 'get';
		$mijo_settings['firewall_sql'] = 'get';
		$mijo_settings['firewall_xss'] = 'post';
		$mijo_settings['comments'] = '0';

        if(isset($data['pid'])) {
            $mijo_settings['pid'] = $data['pid'];
        }

        if(isset($data['mijoshop_display'])) {
            $mijo_settings['mijoshop_display'] = $data['mijoshop_display'];
        }

        if(empty($mijo_settings['account_sync_done'])) {
            MijoShop::get('user')->synchronizeAccountsManually(false);
            $mijo_settings['account_sync_done'] = 1;
        }

        $data['config']['config_mijoshop'] = serialize($mijo_settings);

        $this->saveConfig($data['config']);

        if(!empty($data['home_menu'])){
            $this->addMenu();
        }

        $result['success'] = 'Success';
        return $result;
    }

    public function getInstalledComponents($components) {
        $result = $this->db->query("SELECT DISTINCT element FROM #__extensions WHERE element IN('".implode("', '", $components)."') AND type='component'");
        return $result->rows;
    }

    public function getLanguageCount() {
        $result = $this->db->query("SELECT COUNT(element) as count FROM #__extensions WHERE type='language' AND enabled='1' AND client_id='1'");
        return $result->row['count'];
    }

    private function addMenu() {
        $data = array();
        $data['menutype'] = 'mainmenu';
        $data['title'] = 'Shop';
        $data['alias'] = 'shop';
        $data['path'] = 'shop';
        $data['link'] = 'index.php?option=com_mijoshop&view=home';
        $data['type'] = 'component';
        $data['published'] = 1;
        $data['parent_id'] = 1;
        $data['level'] = 1;
        $data['access'] = 1;
        $data['client_id'] = 0;
        $data['language'] = '*';
        $data['params'] = '{"mijoshop_store_id":"0","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","menu_text":1,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}';

        $db = JFactory::getDbo();
        $db->setQuery("SELECT `extension_id` FROM `#__extensions` WHERE `type`='component' and `element`='com_mijoshop'");
        $data['component_id'] = $db->loadResult();

        $db->setQuery("SELECT menutype FROM #__menu_types WHERE menutype='mainmenu'");
        $mainMenu = $db->loadResult();

        if(!empty($mainMenu)){
            JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_menus/tables/');
            $table = JTable::getInstance('Menu', 'MenusTable');

            $table->setLocation($data['parent_id'], 'last-child');

            if (!$table->bind($data)) {
                return false;
            }

            if (!$table->check()) {
                return false;
            }

            if (!$table->store()) {
                return false;
            }

            if (!$table->rebuildPath($table->id)) {
                return false;
            }
        }

        return true;
    }

    private function saveConfig($data) {
        $keys = array_keys($data);

        $result = $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` IN ('".implode("', '", $keys)."')");

        if(!$result) {
            return false;
        }

        $sql = "INSERT INTO " . DB_PREFIX . "setting (`store_id`, `code`, `key`, `value`, `serialized`) VALUES";

        foreach($data as $key => $value) {
            $serialized = 0;
            if($key == 'config_mijoshop') {
                $serialized = 1;
            }

            $values[]= "('0', 'config', '".$key."', '".$value."', '".$serialized."')";
        }

        $sql .= implode(',', $values);

        $this->db->query($sql);
    }

    private function getMijoSettings() {
        $query = $this->db->query("SELECT `value` FROM " . DB_PREFIX . "setting WHERE `key`='config_mijoshop' ");

        $result = array();

        if(!empty($query->row)) {
            $result = unserialize($query->row['value']);
        }

        return $result;
    }
}