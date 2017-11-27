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
 * Tjdashboard API dashboard class
 *
 * @since  1.0.0
 */
class TjdashboardApiResourceDashboard extends ApiResource
{
	/**
	 * Function save dashboard.
	 *
	 * @return boolean
	 */
	public function post()
	{
		$app            = JFactory::getApplication();
		$jinput         = $app->input;
		$formData       = $jinput->post->getArray();
		$dashboardId    = $jinput->getInt('id');
		$dashboard      = TjdashboardDashboard::getInstance($dashboardId);
		$responceObject = new stdclass;

		if ($dashboard->save($formData))
		{
			$responceObject->data   = $dashboard->dashboard_id;
			$responceObject->status = JText::_("COM_TJDASHBOARD_DASHBOARD_DATA_SAVED_SUCCESSFULLY");
		}
		else
		{
			ApiError::raiseError(400, JText::_($dashboard->getError()));
		}

		$this->plugin->setResponse($responceObject);

		return;
	}

	/**
	 * Function get dashboard data.
	 *
	 * @return boolean
	 */
	public function get()
	{
		$dashboardId = JFactory::getApplication()->input->getInt('id');
		$dashboard = new stdClass;

		if (!empty($dashboardId))
		{
			// @Todo- Check if object id empty ->set record not found if object have error raise it
			$dashboard   = TjdashboardDashboard::getInstance($dashboardId);
		}
		else
		{
			ApiError::raiseError(400, JText::_("COM_TJDASHBOARD_DASHBOARD_ID_NOT_SET"));
		}

		$this->plugin->setResponse($dashboard);

		return;
	}
}
