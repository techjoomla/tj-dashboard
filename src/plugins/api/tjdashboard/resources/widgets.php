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
class TjdashboardApiResourceWidgets extends ApiResource
{
	/**
	 * Function get for widgets record.
	 *
	 * @return boolean
	 */
	public function post()
	{
		$app = JFactory::getApplication();
		$widgetmodel = TjdashboardFactory::model("Widgets");
		$wobj = new stdclass;
		$input = $app->input;
		$filterData = array();
		$filterData = $input->json->get('request', array(), 'ARRAY');
		$filtersClean = array_filter($filterData);

		if (isset($filtersClean['filters']))
		{
			$filter = array_filter($filtersClean['filters']);

			foreach ($filter as $key => $value)
			{
				$widgetmodel->setState('filter.' . $key, $value);
			}
		}

		if (isset($filtersClean['limit']))
		{
			$widgetmodel->setState('list.limit', $filterData['limit']);
		}

		if (isset($filtersClean['offset']))
		{
			$widgetmodel->setState('list.start', $filterData['offset']);
		}

		if (isset($filtersClean['search']))
		{
			$widgetmodel->setState('filter.search', $filterData['search']);
		}

		if (isset($filtersClean['dashboard_id']))
		{
			$widgetmodel->setState('filter.dashboard_id', $filterData['dashboard_id']);
		}

		// Set sorting
		if (isset($filtersClean['sort_by']))
		{
			$sort = array_filter($filterData['sort_by']);
			$keys = array_keys($sort);

			if (! empty($sort))
			{
				$widgetmodel->setState("list.ordering", $keys[0]);
				$widgetmodel->setState("list.direction", $sort[$keys[0]]);
			}
		}

		$wobj->request = $filterData;
		$wobj->data    = $widgetmodel->getItems();
		$this->plugin->setResponse(/** @scrutinizer ignore-type */ $wobj);
	}
}
