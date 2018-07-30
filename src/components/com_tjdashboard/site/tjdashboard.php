<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Tjdashboard', JPATH_COMPONENT);
JLoader::register('TjdashboardController', JPATH_COMPONENT . '/controller.php');

$document = JFactory::getDocument();
$script  = 'const root_url = "' . Juri::root() . '";';
$document->addScriptDeclaration($script, 'text/javascript');

// Get Tjdashboard params
$tjdashboardparams = JComponentHelper::getParams('com_tjdashboard');

if ($tjdashboardparams->get('load_bootstrap') == 1)
{
	// Load bootstrap CSS and JS.
	JHtml::stylesheet('media/techjoomla_strapper/bs3/css/bootstrap.css');

	JHtml::_('bootstrap.framework');
}

if (JVERSION < '3.0')
{
	define('COM_TJDASHBOARD_WRAPPER_DIV', 'techjoomla-bootstrap tjlms-wrapper');
}
else
{
	define('COM_TJDASHBOARD_WRAPPER_DIV', 'tjlms-wrapper');
}

// Execute the task.
$controller = JControllerLegacy::getInstance('Tjdashboard');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
