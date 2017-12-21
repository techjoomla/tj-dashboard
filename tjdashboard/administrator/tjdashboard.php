<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
JLoader::registerPrefix('Tjdashboard', JPATH_ADMINISTRATOR);
JLoader::register('TjdashboardController', JPATH_ADMINISTRATOR . '/controller.php');


// Execute the task.
$controller = JControllerLegacy::getInstance('Tjdashboard');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
