<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * TjDashboard helper.
 *
 * @since  1.6
 */
class DashboardHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  view name string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_TJDASHBOARD_VIEW_DASHBOARDS'),
			'index.php?option=com_tjdashboard&view=dashboards',
			$vName == 'dashboards'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_TJDASHBOARD_VIEW_WIDGETS'),
			'index.php?option=com_tjdashboard&view=widgets',
			$vName == 'widgets'
		);
	}

	/**
	 * Method to get widget Data as er rendered.
	 *
	 * @param   Array  $widgetDetails  widget Data
	 *
	 * @return Array
	 */
}
