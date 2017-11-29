<?php
/**
 * @package    Com_TjDashboard
 *
 * @author     Techjoomla <contact@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Methods supporting a list of records.
 *
 * @since  1.6
 */
class TjdashboardModelDashboards extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'dashboard_id', 'dash.dashboard_id',
				'title', 'dash.title',
				'created_by', 'dash.created_by',
				'ordering', 'dash.ordering',
				'state', 'dash.state',
				'published', 'dash.published',
				'search',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = 'dash.ordering', $direction = 'desc')
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Set ordering.
		$orderCol = $app->getUserStateFromRequest($this->context . '.filter_order', 'filter_order');

		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'dash.ordering';
		}

		$this->setState('list.ordering', $orderCol);

		// Set ordering direction.
		$listOrder = $app->getUserStateFromRequest($this->context . 'filter_order_Dir', 'filter_order_Dir');

		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}

		// Load the filter search
		$search = $app->getUserStateFromRequest($this->context . 'filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Load the filter state
		$published = $app->getUserStateFromRequest($this->context . 'filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_tjdashboard');
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*');
		$query->from($db->quoteName('#__tj_dashboards', 'dash'));

		// Filter by dashboard_id
		$id = $this->getState('filter.dashboard_id');

		if (!empty($id))
		{
			$query->where($db->quoteName('dash.dashboard_id') . ' = ' . (int) $id);
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('dash.dashboard_id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(dash.title LIKE ' . $search . ' OR dash.alias LIKE ' . $search . ')');
			}
		}

		// Filter by created_by
		$created_by = $this->getState('filter.created_by');

		if (!empty($created_by))
		{
			$query->where($db->quoteName('dash.created_by') . ' = ' . (int) $created_by);
		}

		// Filter by state
		$state = $this->getState('filter.state');

		if (!empty($state))
		{
			$query->where($db->quoteName('dash.state') . ' = ' . (int) $state);
		}

		// Filter by context
		$context = $this->getState('filter.context');

		if (!empty($context))
		{
			$query->where($db->quoteName('dash.context') . ' = ' . $db->escape($context));
		}

		// Filter by core
		$core = $this->getState('filter.core');

		if (!empty($core))
		{
			$query->where($db->quoteName('dash.core') . ' = ' . (int) $core);
		}

		// Filter by parent
		$parent = $this->getState('filter.parent');

		if (!empty($parent))
		{
			$query->where($db->quoteName('dash.parent') . ' = ' . (int) $parent);
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get a list of dashboards.
	 * Overridden to add a check for access levels.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}
}
