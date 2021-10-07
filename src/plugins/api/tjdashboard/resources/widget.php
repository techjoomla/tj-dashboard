<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Tjdashboard API widgets class
 *
 * @since  1.0.0
 */
class TjdashboardApiResourceWidget extends ApiResource
{
	/**
	 * Function get for widgets record.
	 *
	 * @return boolean
	 */
	public function post()
	{
		$app      = Factory::getApplication();
		$jinput   = $app->input;
		$formData = $jinput->post->getArray();
		$widgetId = $jinput->getInt('id');
		$widget   = TjdashboardWidget::getInstance($widgetId);

		$renderObject     = new stdclass;

		if ($widget->bind($formData))
		{
			if ($widget->save())
			{
				$renderObject->data   = $widget->dashboard_widget_id;
				$renderObject->status = Text::_("COM_TJDASHBOARD_DASHBOARD_DATA_SAVED_SUCCESSFULLY");
			}
			else
			{
				ApiError::raiseError(400, Text::_($widget->getError()));
			}
		}
		else
		{
				ApiError::raiseError(400, Text::_($widget->getError()));
		}

		$this->plugin->setResponse(/** @scrutinizer ignore-type */ $renderObject);
	}

	/**
	 * Function get dashboard data.
	 *
	 * @return boolean
	 */
	public function get()
	{
		$app         = Factory::getApplication();
		$jinput      = $app->input;
		$widget = new stdClass;
		$widgetId = $jinput->getInt('id');

		if (!empty($widgetId))
		{
			$widget   = TjdashboardWidget::getInstance($widgetId);
		}
		else
		{
			ApiError::raiseError(400, Text::_("COM_TJDASHBOARD_DASHBOARD_ID_NOT_SET"));
		}

		$this->plugin->setResponse(/** @scrutinizer ignore-type */ $widget);
	}
}
