<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\Path;

/**
 * TjDashboard helper.
 *
 * @since  1.0.0
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
		$layout = Factory::getApplication()->input->get('layout', '', 'STRING');

		if ($layout != "default")
		{
			JHtmlSidebar::addEntry(
				Text::_('COM_TJDASHBOARD_VIEW_DASHBOARDS'),
				'index.php?option=com_tjdashboard&view=dashboards',
				$vName == 'dashboards'
			);
			JHtmlSidebar::addEntry(
				Text::_('COM_TJDASHBOARD_VIEW_WIDGETS'),
				'index.php?option=com_tjdashboard&view=widgets',
				$vName == 'widgets'
			);
		}
		else
		{
			$client = Factory::getApplication()->input->get('client', '', 'STRING');

			// Set ordering.
			$full_client = explode('.', $client);

			// Eg com_jgive
			$component = $full_client[0];
			$eName = str_replace('com_', '', $component);
			$file = Path::clean(JPATH_ADMINISTRATOR . '/components/' . $component . '/helpers/' . $eName . '.php');

			if (file_exists($file))
			{
				require_once $file;

				$prefix = ucfirst(str_replace('com_', '', $component));
				$cName = $prefix . 'Helper';

				if (class_exists($cName))
				{
					if (is_callable(array($cName, 'addSubmenu')))
					{
						$lang = Factory::getLanguage();

						// Loading language file from the administrator/language directory then
						// Loading language file from the administrator/components/*extension*/language directory
						$lang->load($component, JPATH_BASE, null, false, false)
						|| $lang->load($component, Path::clean(JPATH_ADMINISTRATOR . '/components/' . $component), null, false, false)
						|| $lang->load($component, JPATH_BASE, $lang->getDefault(), false, false)
						|| $lang->load($component, Path::clean(JPATH_ADMINISTRATOR . '/components/' . $component), $lang->getDefault(), false, false);

						// Call_user_func(array($cName, 'addSubmenu'), 'categories' . (isset($section) ? '.' . $section : ''));
						call_user_func(array($cName, 'addSubmenu'), $vName);
					}
				}
			}
		}
	}
}
