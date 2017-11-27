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
 * Tjdashboard API dashboards class
 *
 * @since  1.0.0
 */
class TjdashboardApiResourceDashboards extends ApiResource
{
	/**
	 * Function post for dashboards record.
	 *
	 * @return boolean
	 */
	public function post()
	{
		$dashboardmodel = TjdashboardFactory::model("Dashboards");
		$dashboardData = $dashboardmodel->getItems();
		//@ Todo - Check for errors and empty object
		$this->plugin->setResponse($dashboardData);
	}
}
