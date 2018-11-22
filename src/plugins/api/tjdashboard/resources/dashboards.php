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
		$this->plugin->setResponse($dashboardData);
	}
}
