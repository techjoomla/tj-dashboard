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
 * Methods supporting a list of TjDashboard's widgets.
 *
 * @since  1.0.0
 */
class TjdashboardModelWidgets extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelList
	 * @since   1.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'dashboard_id',
				'dashboard_widget_id',
				'size'
				);
		}

		// Set the filters as default filters
		parent::__construct($config);
	}

	/**
	 * Get the master query for retrieving a list of requests to the senior.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.0
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*');
		$query->from($db->quoteName('#__tj_dashboard_widgets'));

		// Filter by dashboard_widget_id
		$id = $this->getState('filter.dashboard_widget_id');

		if (!empty($id))
		{
			$query->where($db->quoteName('dashboard_widget_id') . ' = ' . $db->escape($id));
		}

		// Filter by dashboard_id
		$dashboard_id = $this->getState('filter.dashboard_id');

		if (!empty($dashboard_id))
		{
			$query->where($db->quoteName('dashboard_id') . ' = ' . $db->escape($dashboard_id));
		}

		// Filter by size
		$size  = $this->getState('filter.size');

		if (!empty($size))
		{
			$query->where($db->quoteName('size') . ' = ' . $db->escape($size));
		}

		// Add the list ordering clause.
		$orderCol  = $this->getState('list.ordering');
		$orderDirn = $this->getState('list.direction');

		if (empty($orderCol))
		{
			$orderCol  = "ordering";
		}

		if (empty($orderDirn))
		{
			$orderDirn = "asc";
		}

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}
}
