<?php
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceShop www.joomace.net
*/

//No Permision
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT.'/components/com_mijoshop/mijoshop/mijoshop.php');

jimport('joomla.form.formfield');

class JFormFieldOcVersion extends JFormField {

    protected $type = 'OcVersion';

    protected function getInput() {
        return MijoShop::get('base')->getOcVersion();
    }
}
