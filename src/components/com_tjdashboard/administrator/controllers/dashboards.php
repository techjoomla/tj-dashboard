<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;
/**
 * Dashboards list controller class.
 *
 * @since  1.0.0
 */
class TjDashboardControllerDashboards extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   STRING  $name    model name
	 * @param   STRING  $prefix  model prefix
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function getModel($name = 'Dashboard', $prefix = 'TjdashboardModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to delete a dashboard.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function delete()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		try
		{
			return parent::delete();
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_TJDASHBOARD_DASHBOARDS_DELETE_ERROR_MESSAGE'), 'error');
			$this->setRedirect('index.php?option=com_tjdashboard&view=dashboards');
		}
	}
}
