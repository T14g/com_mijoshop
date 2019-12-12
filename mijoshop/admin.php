<?php
/*
* @package		MijoShop
* @copyright	2009-2016 Miwisoft LLC, miwisoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT.'/components/com_mijoshop/mijoshop/mijoshop.php');

$ctrl = JRequest::getWord('ctrl');
$view = JRequest::getString('view');
$route = JRequest::getString('route');

$base = MijoShop::get('base');
$document = JFactory::getDocument();
$mainframe = JFactory::getApplication();
$toolbar = JToolBar::getInstance('toolbar');

if (!$base->checkRequirements('admin')) {
    return;
}

$dir_modification = JPATH_MIJOSHOP_OC.'/system/modification/';

if(!is_dir($dir_modification)) {
    mkdir($dir_modification, 0777);
}

if (!is_file(JPATH_MIJOSHOP_OC.'/system/modification/system/engine/action.php')) {
    MijoShop::get('base')->createOverrides();
}

$document->addStyleSheet('components/com_mijoshop/assets/css/mijoshop.css');
JToolBarHelper::title(JText::_('MijoShop'), 'mijoshop');

$installed_ms_version = $base->getMijoshopVersion();
$latest_ms_version = $base->getLatestMijoshopVersion();
$ms_version_status = version_compare($installed_ms_version, $latest_ms_version);

$admin_path = $mainframe->isAdmin() ? '' : 'administrator/';

if ($view == 'upgrade') {
	$mainframe->redirect('index.php?option=com_mijoshop&route=common/upgrade', '', '');
}
else if ($view == 'support') {
	$mainframe->redirect('index.php?option=com_mijoshop&route=common/support', '', '');
}

$wizard = $base->getConfig()->get('wizard', 0);
if (empty($wizard) and strpos($route, 'common/wizard') === false and $base->isAjax() != true) {
    $mainframe->redirect('index.php?option=com_mijoshop&route=common/wizard', '', '');
}

$redirected = JFactory::getSession()->get('mijoshop.login.redirected');
if ( !empty($wizard) and empty($ctrl) and !$redirected and ($base->getConfig()->get('account_sync_done', 0) == 0)) {
    JError::raiseWarning('100', JText::sprintf('COM_MIJOSHOP_ACCOUNT_SYNC_WARN', '<a href="index.php?option=com_mijoshop&ctrl=sync">', '</a>'));
}

$pid = $base->getConfig()->get('pid');
if(!empty($wizard) and empty($pid)){
    JError::raiseWarning('100', JText::sprintf('COM_MIJOSHOP_CPANEL_PID_NOTE', '<a href="http://miwisoft.com/my-profile">', '</a>', '<a href="index.php?option=com_mijoshop&route=setting/setting">', '</a>'));
}

if ($ctrl == 'sync') {
    MijoShop::get('user')->synchronizeAccountsManually();
}

if (isset($_GET['token'])) {
	$_SESSION['token'] = $_GET['token'];
}

if (isset($_SESSION['token']) && !isset($_GET['token'])) {
	$_GET['token'] = $_SESSION['token'];
}

ob_start();

require_once(JPATH_MIJOSHOP_OC.'/admin/index.php');
$output = ob_get_contents();

ob_end_clean();

$output = $base->replaceOutput($output, 'admin');

echo $output;

if ($base->isAjax($output) == true) {
	jexit();
}