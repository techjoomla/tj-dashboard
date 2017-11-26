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
 * Methods supporting a list of TjDashboards.
 *
 * @since  1.0.0
 */
class TjdashboardModelDashboards extends JModelList
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
				'state',
				'created_by',
				'context',
				'core',
				'parent'
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
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*');
		$query->from($db->quoteName('#__tj_dashboards'));

		// Filter by dashboard_id
		$id = $this->getState('filter.dashboard_id');

		if (!empty($id))
		{
			$query->where($db->quoteName('dashboard_id') . ' = ' . (int) $id);
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('dashboard_id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(title LIKE ' . $search . ' OR alias LIKE ' . $search . ')');
			}
		}

		// Filter by created_by
		$created_by = $this->getState('filter.created_by');

		if (!empty($created_by))
		{
			$query->where($db->quoteName('created_by') . ' = ' . (int) $created_by);
		}

		// Filter by state
		$state = $this->getState('filter.state');

		if (!empty($state))
		{
			$query->where($db->quoteName('state') . ' = ' . (int) $state);
		}

		// Filter by context
		$context = $this->getState('filter.context');

		if (!empty($context))
		{
			$query->where($db->quoteName('context') . ' = ' . $db->escape($context));
		}

		// Filter by core
		$core = $this->getState('filter.core');

		if (!empty($core))
		{
			$query->where($db->quoteName('core') . ' = ' . (int) $core);
		}

		// Filter by parent
		$parent = $this->getState('filter.parent');

		if (!empty($parent))
		{
			$query->where($db->quoteName('parent') . ' = ' . (int) $parent);
		}

		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		if (count($items))
		{
			foreach ($items as $item)
			{
				$widgetModel = TjdashboardFactory::model("Widgets");
				$widgetModel->setState('filter.dashboard_id', $item->dashboard_id);
				$item->widgets_data  = $widgetModel->getItems();
				$item->no_of_widgets = (count($item->widgets_data) != 0) ? count($item->widgets_data) : '0';
			}
		}

		return $items;
	}
}
