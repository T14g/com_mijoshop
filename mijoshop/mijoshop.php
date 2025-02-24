<?php
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die ('Restricted access');

require_once(dirname(__FILE__).'/initialise.php');

abstract class MijoShop {

    public static function &get($class = 'base', $options = null) {
        static $instances = array();

		$class = empty($class) ? 'base' : $class;
			
		if (!isset($instances[$class])) {
			require_once(JPATH_MIJOSHOP_LIB.'/'.$class.'.php');

            $class_name = 'MijoShop'.ucfirst($class);
			
			$instances[$class] = new $class_name($options);
		}

		return $instances[$class];
    }

    public static function getButton() {
        $default = 'btn ';

        return self::get('base')->getConfig()->get('button_class', $default);
    }

    public static function getTmpl() {
		return JFactory::getApplication()->getTemplate();
    }

    public static function &getClass($class = 'base', $options = null) {
		return self::get($class, $options);
    }
}

#mijoshop
/*if (!MijoShop::get('base')->plgEnabled('system', 'mijoshopjquery')) {
    JError::raiseWarning(404, JText::_('COM_MIJOSHOP_JQUERY_PLUGIN'));
}*/