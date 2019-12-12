<?php
/*
* @package       MijoShop
* @copyright     2009-2017 Miwisoft LLC, miwisoft.com
* @license       GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license       GNU/GPL based on AceShop www.joomace.net
*/

// No Permission
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once(JPATH_ROOT.'/components/com_mijoshop/mijoshop/mijoshop.php');

class MijoShopInstall {
    public function createTables() {
        $db = MijoShop::get('db')->getDbo();

        $tables    = $db->getTableList();
        $mijoshop_address = $db->getPrefix().'mijoshop_address';

        if (!is_array($tables) || in_array($mijoshop_address, $tables)) {
            return;
        }

        $this->_runSqlFile(JPATH_MIJOSHOP_ADMIN.'/install.sql');
    }

    public function createUserTables() {
        $db = MijoShop::get('db');
        $jdb = MijoShop::get('db')->getDbo();

        $this->_createUserMapTables();

        $tables    = $jdb->getTableList();
        $mijoshop_user = $jdb->getPrefix().'mijoshop_user';

        if (!is_array($tables) || in_array($mijoshop_user, $tables)) {
            return;
        }

        $jdb->setQuery("CREATE TABLE IF NOT EXISTS `#__mijoshop_user` (
          `user_id` int(11) NOT NULL AUTO_INCREMENT,
          `user_group_id` int(11) NOT NULL,
          `username` varchar(100) COLLATE utf8_general_ci NOT NULL DEFAULT '',
          `password` varchar(100) COLLATE utf8_general_ci NOT NULL DEFAULT '',
          `salt` varchar(9) COLLATE utf8_general_ci NOT NULL DEFAULT '',
          `firstname` varchar(32) COLLATE utf8_general_ci NOT NULL DEFAULT '',
          `lastname` varchar(32) COLLATE utf8_general_ci NOT NULL DEFAULT '',
          `email` varchar(96) COLLATE utf8_general_ci NOT NULL DEFAULT '',
          `image` varchar(255) NOT NULL,
          `code` varchar(32) COLLATE utf8_general_ci NOT NULL,
          `ip` varchar(40) COLLATE utf8_general_ci NOT NULL DEFAULT '',
          `status` tinyint(1) NOT NULL,
          `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (`user_id`),
          INDEX `user_group_id` (`user_group_id`),
          INDEX `email` (`email`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
        $jdb->query();

        /*
        $users = MijoShop::get('db')->run('SELECT u.* FROM #__users AS u, #__user_usergroup_map AS uum WHERE uum.group_id IN (7, 8) AND u.id = uum.user_id AND u.block = 0', 'loadObjectList');

        if (empty($users)) {
            return;
        }

        foreach ($users as $user) {
            $name = explode(' ', $user->name);

            $firstname = $name[0];
            $lastname = MijoShop::get('user')->getLastName($name);

            $password = $user->password;
            if (strpos($password, ':')) {
                $a = explode(':', $password);
                $password = $a[0];
            }

            $db->run("INSERT IGNORE INTO #__mijoshop_user SET ".
                            "username = '" . $user->username . "', ".
                            "password = '" . $password . "', ".
                            "firstname = '" . $firstname . "', ".
                            "lastname = '" . $lastname . "', ".
                            "email = '" . $user->email . "', ".
                            "image = '', ".
                            "user_group_id = '1', ".
                            "status = '1', ".
                            "date_added = NOW()"
                        , 'query'
                        );
        }
        */
    }

    public function _createUserMapTables() {
        $db = MijoShop::get('db');
        $jdb = MijoShop::get('db')->getDbo();

        $tables    = $jdb->getTableList();
        $mijoshop_juser_ocustomer_map = $jdb->getPrefix().'mijoshop_juser_ocustomer_map';

        if (!is_array($tables) || in_array($mijoshop_juser_ocustomer_map, $tables)) {
            return;
        }

        $jdb->setQuery("CREATE TABLE IF NOT EXISTS `#__mijoshop_juser_ocustomer_map` (
          `juser_id` INT(11) NOT NULL,
          `ocustomer_id` INT(11) NOT NULL,
          PRIMARY KEY (`juser_id`),
          UNIQUE (`ocustomer_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
        $jdb->query();

        $jdb->setQuery("CREATE TABLE IF NOT EXISTS `#__mijoshop_juser_ouser_map` (
          `juser_id` INT(11) NOT NULL,
          `ouser_id` INT(11) NOT NULL,
          PRIMARY KEY (`juser_id`),
          UNIQUE (`ouser_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
        $jdb->query();
    }

    public function createApiUser() {
        $jdb = MijoShop::get('db')->getDbo();

        // create order API user
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $api_username = '';
        $api_password = '';

        for ($i = 0; $i < 64; $i++) {
            $api_username .= $characters[rand(0, strlen($characters) - 1)];
        }

        for ($i = 0; $i < 256; $i++) {
            $api_password .= $characters[rand(0, strlen($characters) - 1)];
        }

        $jdb->setQuery("INSERT INTO `#__mijoshop_api` SET username = '" . $jdb->escape($api_username) . "', `password` = '" . $jdb->escape($api_password) . "', status = 1, date_added = NOW(), date_modified = NOW()");
        $jdb->query();

        $api_id = $jdb->insertid();

        $jdb->setQuery("DELETE FROM `#__mijoshop_setting` WHERE `key` = 'config_api_id'");
        $jdb->query();

        $jdb->setQuery("INSERT INTO `#__mijoshop_setting` SET `code` = 'config', `key` = 'config_api_id', value = '" . (int)$api_id . "'");
        $jdb->query();
    }

    public function createGroupTables() {
        $db = MijoShop::get('db');
        $jdb = MijoShop::get('db')->getDbo();

        $tables    = $jdb->getTableList();
        $mijoshop_jgroup_cgroup_map = $jdb->getPrefix().'mijoshop_jgroup_cgroup_map';

        if (!is_array($tables) || in_array($mijoshop_jgroup_cgroup_map, $tables)) {
            return;
        }

        $registered = 2;
        $publisher = 5;
        $administrator = 8;

        $jdb->setQuery("CREATE TABLE IF NOT EXISTS `#__mijoshop_jgroup_cgroup_map` (
          `jgroup_id` INT(11) NOT NULL,
          `cgroup_id` INT(11) NOT NULL,
          PRIMARY KEY (`cgroup_id`),
          INDEX `jgroup_id` (`jgroup_id`)
        ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");

        $jdb->query();

        $customer_groups = $db->run('SELECT customer_group_id FROM #__mijoshop_customer_group', 'loadColumn');

        if (!empty($customer_groups)) {
            foreach ($customer_groups as $customer_group) {
                $j_group = $registered;

                if ($customer_group == 6) {
                    $j_group = $publisher;
                }

                $db->run("INSERT INTO #__mijoshop_jgroup_cgroup_map SET jgroup_id = '{$j_group}', cgroup_id = '{$customer_group}'", 'query');
            }
        }

        $jdb->setQuery("CREATE TABLE IF NOT EXISTS `#__mijoshop_jgroup_ugroup_map` (
                `jgroup_id` INT(11) NOT NULL,
                `ugroup_id` INT(11) NOT NULL,
                PRIMARY KEY (`ugroup_id`),
                INDEX `jgroup_id` (`jgroup_id`)
              ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
        $jdb->query();

        $user_groups = $db->run('SELECT user_group_id FROM #__mijoshop_user_group', 'loadColumn');

        if (!empty($user_groups)) {
            foreach ($user_groups as $user_group) {
                $db->run("INSERT INTO #__mijoshop_jgroup_ugroup_map SET jgroup_id = '{$administrator}', ugroup_id = '{$user_group}'", 'query');
            }
        }
    }

    public function createIntegrationTables() {
        $jdb = MijoShop::get('db')->getDbo();

        $tables    = $jdb->getTableList();
        $mijoshop_j_integrations = $jdb->getPrefix().'mijoshop_j_integrations';

        if (!is_array($tables) || in_array($mijoshop_j_integrations, $tables)) {
            return;
        }

        $jdb->setQuery("CREATE TABLE IF NOT EXISTS `#__mijoshop_j_integrations` (
            `product_id` INT NOT NULL,
            `content` TEXT NOT NULL,
            INDEX `product_id` (`product_id`)
            ) CHARSET=utf8 COLLATE=utf8_general_ci;");
        $jdb->query();
    }

    public function _runSqlFile($sql_file) {
        $db = MijoShop::get('db')->getDbo();

        if (!file_exists($sql_file)) {
            return;
        }

        $buffer = file_get_contents($sql_file);

        if ($buffer === false) {
            return;
        }

        $queries = $db->splitSql($buffer);

        if (count($queries) == 0) {
            return;
        }

        foreach ($queries as $query) {
            $query = trim($query);

            if ($query != '' && $query{0} != '#') {
                $db->setQuery($query);

                if (!$db->query()) {
                    JError::raiseWarning(1, 'JInstaller::install: '.JText::_('SQL Error')." ".$db->stderr(true));
                    return;
                }
            }
        }
    }

    public function checkLanguage() {
        $db = MijoShop::get('db');

        $oc_langs   = self::getOcLanguages();
        $j_langs    = self::getInstalledJoomlaLanguages();
        $j_contents = self::getLanguageList();

        foreach ($oc_langs as $key => $oc_lang) {
            if(isset($j_langs[$key]) and !isset($j_contents[$key])) {
                $db->run("INSERT INTO #__languages SET lang_code = '".$j_langs[$key]['tag']."', title = '".$j_langs[$key]['name']."', title_native = '".$j_langs[$key]['name']."', sef ='".$j_langs[$key]['code']."', image ='".$j_langs[$key]['code']."', published = 1, access = 1, ordering = 0", 'query');
            }
        }
    }

    public function getOcLanguages() {
        $language_data = array();

        $results = MijoShop::get('db')->run("SELECT * FROM #__mijoshop_language WHERE status = 1 ORDER BY sort_order, name", 'loadAssocList');

        foreach ($results as $result) {
            $language_data[$result['code']] = array(
                'language_id' => $result['language_id'],
                'name'        => $result['name'],
                'code'        => $result['code'],
                'locale'      => $result['locale'],
                'image'       => $result['image'],
                'directory'   => $result['directory'],
                'filename'    => $result['filename'],
                'sort_order'  => $result['sort_order'],
                'status'      => $result['status']
            );
        }

        return $language_data;
    }

    public function getInstalledJoomlaLanguages($client = 0) {

        $langlist = array();

        $results = MijoShop::get('db')->run("SELECT name, element FROM #__extensions WHERE type = 'language' AND state = 0 AND enabled = 1 AND client_id= ". (int) $client, 'loadAssocList');

        foreach ($results as $result) {
            $_result = explode('-', $result['element']);

            if($result['element'] == 'pt-BR'){
                $_result[0] = strtolower($result['element']);
            }

            $langlist[$_result[0]] = array(
                'code' => $_result[0],
                'tag'  => $result['element'],
                'name'  => $result['name']
            );
        }

        return $langlist;
    }

    public function getLanguageList() {

        $language_data = array();

        $results = MijoShop::get('db')->run("SELECT * FROM #__languages ORDER BY ordering, title", 'loadAssocList');

        foreach ($results as $result) {
            $language_data[$result['sef']] = array(
                'language_id' => $result['lang_id'],
                'name'        => $result['title_native'],
                'code'        => $result['sef'],
                'locale'      => $result['lang_code'],
                'image'       => $result['image'].'.gif',
                'directory'   => 'english',
                'filename'    => 'english',
                'sort_order'  => $result['ordering'],
                'status'      => $result['published']
            );
        }

        return $language_data;
    }

    private function _getPath($cat_id, $path = array()) {
        $jdb = MijoShop::get('db')->getDbo();

        $jdb->setQuery("SELECT parent_id FROM `#__mijoshop_category` WHERE category_id = ".$cat_id);
        $parent_id = $jdb->loadResult();

        if ((int)$parent_id != 0) {
            $path[] = $parent_id;
            $path = self::_getPath($parent_id, $path);
        }

        return $path;
    }

    public function upgrade302() {
        $jdb = MijoShop::get('db')->getDbo();

        //check layout_id=14 is using
        $jdb->setQuery("SELECT * FROM `#__mijoshop_layout` WHERE `layout_id` = 14");
        $layout = $jdb->loadResult();

        if(empty($layout)) {
            $jdb->setQuery("INSERT INTO `#__mijoshop_layout` (`layout_id`, `name`) VALUES(14, 'Joomla Module')");
        }
        else {
            $jdb->setQuery("UPDATE `#__mijoshop_layout` SET `name` = 'Joomla Module' WHERE `layout_id` = 14");
        }

        $jdb->query();
    }

    public function upgrade310() {
        $jdb = MijoShop::get('db')->getDbo();

        $tables    = $jdb->getTableList();

        if (!is_array($tables)) {
            return;
        }

        $table = $jdb->getPrefix().'paypal_iframe_order';

        if (in_array($table, $tables)) {
            $jdb->setQuery("ALTER TABLE #__mijoshop_paypal_iframe_order CHANGE `created` `date_added` DATETIME NOT NULL;");
            $jdb->query();

            $jdb->setQuery("ALTER TABLE #__mijoshop_paypal_iframe_order CHANGE `modified` `date_modified` DATETIME NOT NULL;");
            $jdb->query();
        }

        $table = $jdb->getPrefix().'paypal_order_transaction';

        if (in_array($table, $tables)) {
            $jdb->setQuery("ALTER TABLE #__mijoshop_paypal_order_transaction CHANGE `created` `date_added` DATETIME NOT NULL;");
            $jdb->query();
        }

        $table = $jdb->getPrefix().'paypal_iframe_order_transaction';

        if (in_array($table, $tables)) {
            $jdb->setQuery("ALTER TABLE #__mijoshop_paypal_iframe_order_transaction CHANGE `created` `date_added` DATETIME NOT NULL;");
            $jdb->query();
        }

        $config_file_ext_allowed = 'txt\r\npng\r\njpe\r\njpeg\r\njpg\r\ngif\r\nbmp\r\nico\r\ntiff\r\ntif\r\nsvg\r\nsvgz\r\nzip\r\nrar\r\nmsi\r\ncab\r\nmp3\r\nqt\r\nmov\r\npdf\r\npsd\r\nai\r\neps\r\nps\r\ndoc\r\nrtf\r\nxls\r\nppt\r\nodt\r\nods\r\nmobi\r\nepub';

        $jdb->run("UPDATE #__mijoshop_setting SET `value` = '" . $config_file_ext_allowed . "' WHERE `key` = 'config_file_ext_allowed'", "query");

        $config_file_mine_allowed = 'text/plain\r\nimage/png\r\nimage/jpeg\r\nimage/gif\r\nimage/bmp\r\nimage/vnd.microsoft.icon\r\nimage/tiff\r\nimage/svg+xml\r\napplication/zip\r\napplication/x-rar-compressed\r\napplication/x-msdownload\r\napplication/vnd.ms-cab-compressed\r\naudio/mpeg\r\nvideo/quicktime\r\napplication/pdf\r\nimage/vnd.adobe.photoshop\r\napplication/postscript\r\napplication/msword\r\napplication/rtf\r\napplication/vnd.ms-excel\r\napplication/vnd.ms-powerpoint\r\napplication/vnd.oasis.opendocument.text\r\napplication/vnd.oasis.opendocument.spreadsheet\r\napplication/x-mobipocket-ebook \r\napplication/epub+zip\r\napplication/octet-stream';

        $jdb->run("UPDATE #__mijoshop_setting SET `value` = '" . $config_file_mine_allowed . "' WHERE `key` = 'config_file_mime_allowed'", "query");

        $user_groups = MijoShop::get('db')->run("SELECT DISTINCT * FROM #__mijoshop_user_group", "loadAssocList");

        foreach ($user_groups as $user_group) {
            $user_group['permission'] = unserialize($user_group['permission']);

            $user_group['permission']['access'][] = 'feed/facebook_store';
            $user_group['permission']['modify'][] = 'feed/facebook_store';

            $jdb->run("UPDATE #__mijoshop_user_group SET name = '" . $user_group['name'] . "', permission = '" . serialize($user_group['permission']) . "' WHERE user_group_id = '" . (int)$user_group['user_group_id'] . "'", "query");
        }

        $modules = MijoShop::get('db')->run("SELECT * FROM `#__mijoshop_module`", "loadAssocList");

        foreach ($modules as $module) {
            $module_setting = unserialize($module['setting']);

            if (isset($module_setting['product']) || $module['code'] == 'featured') {
                $module_setting['feed'] = 1;

                $jdb->run("UPDATE `#__mijoshop_module` SET `name` = '" . $module['name'] . "', `setting` = '" . serialize($module_setting) . "' WHERE `module_id` = '" . (int)$module['module_id'] . "'", "query");
            }
        }

        $settings = MijoShop::get('db')->run("SELECT `value` FROM `#__mijoshop_setting` WHERE `key` = 'config_mijoshop'", "loadResult");

        $settings = unserialize($settings);

        $settings['firewall_lfi'] = 'get';
        $settings['firewall_rfi'] = 'get';
        $settings['firewall_sql'] = 'get';
        $settings['firewall_xss'] = 'post';

        $settings = serialize($settings);

        MijoShop::get('db')->run("UPDATE `#__mijoshop_setting` SET `value` = '{$settings}' WHERE `key` = 'config_mijoshop'", "query");
    }

    public function upgrade312() {
        $jdb = MijoShop::get('db')->getDbo();

        $tables    = $jdb->getTableList();

        if (!is_array($tables)) {
            return;
        }

        $users = MijoShop::get('db')->run("SELECT * FROM `#__mijoshop_user`", "loadAssocList");

        foreach ($users as $user) {
            if(!isset($user['image'])) {
                MijoShop::get('db')->run("ALTER TABLE `#__mijoshop_user` ADD `image` varchar(255) NOT NULL AFTER `email`", "query");
                break;
            }
        }
    }

    public function upgrade314() {
        $jdb = MijoShop::get('db')->getDbo();

        $jdb->setQuery("INSERT INTO `#__mijoshop_setting` SET `code` = 'config', `key` = 'config_category_name_limit', value = '20'");
        $jdb->query();
    }

    public function upgrade315() {
        $jdb = MijoShop::get('db')->getDbo();

        $user_groups = MijoShop::get('db')->run("SELECT DISTINCT * FROM #__mijoshop_user_group", "loadAssocList");

        foreach ($user_groups as $user_group) {
            $user_group['permission'] = unserialize($user_group['permission']);

            $user_group['permission']['access'][] = 'module/amazon_login';
            $user_group['permission']['modify'][] = 'module/amazon_login';

            $user_group['permission']['access'][] = 'module/amazon_pay';
            $user_group['permission']['modify'][] = 'module/amazon_pay';

            $user_group['permission']['access'][] = 'payment/amazon_login_pay';
            $user_group['permission']['modify'][] = 'payment/amazon_login_pay';

            $jdb->run("UPDATE #__mijoshop_user_group SET name = '" . $user_group['name'] . "', permission = '" . serialize($user_group['permission']) . "' WHERE user_group_id = '" . (int)$user_group['user_group_id'] . "'", "query");
        }
    }

    public function upgrade320() {
        $jdb = MijoShop::get('db')->getDbo();

        $jdb->setQuery("CREATE TABLE IF NOT EXISTS `#__mijoshop_customer_search` (
  `customer_search_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `category_id` int(11),
  `sub_category` tinyint(1) NOT NULL,
  `description` tinyint(1) NOT NULL,
  `products` int(11) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`customer_search_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
        $jdb->query();

        $user_groups = MijoShop::get('db')->run("SELECT DISTINCT * FROM #__mijoshop_user_group", "loadAssocList");

        foreach ($user_groups as $user_group) {
            $user_group['permission'] = unserialize($user_group['permission']);

            $user_group['permission']['access'][] = 'report/customer_search';
            $user_group['permission']['modify'][] = 'report/customer_search';

            $user_group['permission']['access'][] = 'payment/stripe';
            $user_group['permission']['modify'][] = 'payment/stripe';

            $user_group['permission']['access'][] = 'payment/eway';
            $user_group['permission']['modify'][] = 'payment/eway';

            $user_group['permission']['access'][] = 'payment/g2apay';
            $user_group['permission']['modify'][] = 'payment/g2apay';

            $user_group['permission']['access'][] = 'payment/paysondirect';
            $user_group['permission']['modify'][] = 'payment/paysondirect';

            $user_group['permission']['access'][] = 'payment/paysoninvoice';
            $user_group['permission']['modify'][] = 'payment/paysoninvoice';

            $user_group['permission']['access'][] = 'payment/globalpay';
            $user_group['permission']['modify'][] = 'payment/globalpay';

            $user_group['permission']['access'][] = 'payment/globalpay_remote';
            $user_group['permission']['modify'][] = 'payment/globalpay_remote';

            $user_group['permission']['access'][] = 'report/graph';
            $user_group['permission']['modify'][] = 'report/graph';

            $jdb->run("UPDATE #__mijoshop_user_group SET name = '" . $user_group['name'] . "', permission = '" . serialize($user_group['permission']) . "' WHERE user_group_id = '" . (int)$user_group['user_group_id'] . "'", "query");
        }
    }

    public function refreshModification() {
        if (file_exists(JPATH_MIJOSHOP_OC)) {
            $dir_modification = JPATH_MIJOSHOP_OC.'/system/modification/';

            if(!is_dir($dir_modification)) {
                mkdir($dir_modification, 0777);
            }

            MijoShop::get('base')->createOverrides();    
        }
    }
}