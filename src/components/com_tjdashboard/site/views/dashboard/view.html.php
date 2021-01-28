<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.0.0
 */
class TjdashboardViewDashboard extends JViewLegacy
{
	protected $item;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		$this->item->dashboard_id = $this->state->get('dashboard.dashboard_id');

		$this->getLanguageConstant();

		$dashboard = TjdashboardDashboard::getInstance($this->item->dashboard_id);
		$this->jsFiles = array();
		$this->cssFiles = array();

		if (isset($dashboard->widget_data) && !empty($dashboard->widget_data))
		{
			foreach ($dashboard->widget_data as $widgetData)
			{
				$renderer = explode('.', $widgetData->renderer_plugin);
				$rendererClass = 'PlgTjdashboardRenderer' . ucfirst($renderer[0]);
				$path = "plugins.tjdashboardrenderer.";
				$folderPath = $path . $renderer[0] . '.' . $renderer[0];
				JLoader::import($folderPath, JPATH_SITE);
				$rendererObj = new $rendererClass;

				$this->jsFiles = array_merge($this->jsFiles, $rendererObj->getJS());
				$this->jsFiles = array_unique($this->jsFiles);

				$this->cssFiles = array_merge($this->cssFiles, $rendererObj->getCSS());
				$this->cssFiles = array_unique($this->cssFiles);
			}
		}

		// @Todo - Add permission based view accessing code
		parent::display($tpl);
	}

	/**
	 * Get all jtext for javascript
	 *
	 * @return   void
	 *
	 * @since   1.0
	 */
	public static function getLanguageConstant()
	{
		JText::script('COM_TJDASHBOARD_WIDGETS_NOTSHOW_ERROR_MESSAGE');
		JText::script('COM_TJDASHBOARD_NO_DATA_AVAILABLE_MESSAGE');
	}
}
