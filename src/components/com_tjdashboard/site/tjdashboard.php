<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Tjdashboard', JPATH_COMPONENT);
JLoader::register('TjdashboardController', JPATH_COMPONENT . '/controller.php');

$document = Factory::getDocument();
$script  = 'const root_url = "' . Juri::root() . '";';
$document->addScriptDeclaration($script, 'text/javascript');

// Get Tjdashboard params
$tjdashboardparams = ComponentHelper::getParams('com_tjdashboard');

if ($tjdashboardparams->get('load_bootstrap') == 1)
{
	// Load bootstrap CSS and JS.
	HTMLHelper::stylesheet('media/techjoomla_strapper/bs3/css/bootstrap.css');

	HTMLHelper::_('bootstrap.framework');
}

define('COM_TJDASHBOARD_WRAPPER_DIV', 'tjBs3');


// Execute the task.
$controller = BaseController::getInstance('Tjdashboard');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
