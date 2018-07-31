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

// Execute the task.
$controller = JControllerLegacy::getInstance('Tjdashboard');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
