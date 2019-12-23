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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Component\ComponentHelper;

// Include dependancies
jimport('joomla.application.component.controller');

$document = Factory::getDocument();
$script  = 'const root_url = "' . Uri::root() . '";';
$document->addScriptDeclaration($script, 'text/javascript');

$tjdashboardParams = ComponentHelper::getParams('com_tjdashboard');
$document->addScriptDeclaration("var tjDashWidgetIcon = '{$tjdashboardParams->get('show_widget_icon')}';");
$document->addScriptDeclaration("var tjDashWidgetTitle = '{$tjdashboardParams->get('show_widget_title')}';");

JLoader::registerPrefix('Tjdashboard', JPATH_ADMINISTRATOR);
JLoader::register('TjdashboardController', JPATH_ADMINISTRATOR . '/controller.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Tjdashboard');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
