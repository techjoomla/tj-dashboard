<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Tjdashboard API widgets class
 *
 * @since  1.0.0
 */
class TjdashboardApiResourceWidget extends ApiResource
{
	/**
	 * Function to get widgets record.
	 *
	 * @return boolean
	 */
	public function post()
	{
		$app      = JFactory::getApplication();
		$jinput   = $app->input;
		$formData = $jinput->post->getArray();
		$widgetId = $jinput->getInt('id');
		$widget   = TjdashboardWidget::getInstance($widgetId);
		$save     = $widget->save($formData);
		$renderObject     = new stdclass;

		if ($save)
		{
			$renderObject->data   = $widget->dashboard_widget_id;
			$renderObject->status = JText::_("COM_TJDASHBOARD_DASHBOARD_DATA_SAVED_SUCCESSFULLY");
		}
		else
		{
			ApiError::raiseError(400, JText::_($widget->getError()));
		}

		$this->plugin->setResponse($renderObject);

		return;
	}

	/**
	 * Function to get dashboard data.
	 *
	 * @return boolean
	 */
	public function get()
	{
		$app         = JFactory::getApplication();
		$jinput      = $app->input;

		$dashboardId = $jinput->getInt('id');

		if (!empty($dashboardId))
		{
			$dashboard   = TjdashboardWidget::getInstance($dashboardId);
		}
		else
		{
			ApiError::raiseError(400, JText::_("COM_TJDASHBOARD_DASHBOARD_ID_NOT_SET"));
		}

		$this->plugin->setResponse($dashboard);

		return;
	}
}
