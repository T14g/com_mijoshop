<?php
/*
* @package		MijoShop
* @copyright	2009-2014 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die ('Restricted access');

JLoader::register('CategoryHelperAssociation', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/association.php');

abstract class MijoshopHelperAssociation extends CategoryHelperAssociation
{

	public static function getAssociations($id = 0, $view = null)
	{
		jimport('helper.route', JPATH_COMPONENT_SITE);

        require_once(JPATH_ROOT.'/components/com_mijoshop/mijoshop/mijoshop.php');

        $option = JRequest::getCmd('option', '');
        if ($option != 'com_mijoshop') {
            return array();
        }

        $languages = JLanguageHelper::getLanguages();

        foreach ($languages as $tag => $item) {
            $vars = $_REQUEST;

            if (JFactory::getConfig()->get('sef') == '1') {
                $vars['_lang'] = $item->lang_id;
                $vars['_lang_code'] = $item->lang_code;
            }
            else {
                unset($vars['sort']);
                unset($vars['order']);
                unset($vars['filter_name']);
                unset($vars['filter_tag']);
                unset($vars['limit']);
                unset($vars['page']);
                unset($vars['mijoshop_store_id']);
                unset($vars['remove']);
            }

            unset($vars['language']);

            $return[$item->lang_code] = 'index.php?'.http_build_query($vars);//Mijoshop::get('router')->route(, $item->sef);
        }

		return $return;
	}
}