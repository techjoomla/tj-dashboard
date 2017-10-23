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
		$app = JFactory::getApplication();
		$jinput = $app->input;

		$dashboardId = $jinput->get('id', 0, 'INT');

		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*');
		$query->from($db->quoteName('#__tj_dashboards'));

		// Filter by dashboard_id
		$id = $this->getState('filter.dashboard_id');

		if (!empty($id))
		{
			$query->where($db->quoteName('dashboard_id') . ' = ' . $db->escape($id));
		}

		// Filter by created_by
		$created_by = $this->getState('filter.created_by');

		if (!empty($user_id))
		{
			$query->where($db->quoteName('created_by') . ' = ' . $db->escape($created_by));
		}

		// Filter by state
		$state = $this->getState('filter.state');

		if (!empty($status))
		{
			$query->where($db->quoteName('state') . ' = ' . $db->escape($state));
		}

		// Filter by context
		$context = $this->getState('filter.context');

		if (!empty($status))
		{
			$query->where($db->quoteName('context') . ' = ' . $db->escape($context));
		}
		// Filter by core
		$core = $this->getState('filter.core');

		if (!empty($status))
		{
			$query->where($db->quoteName('core') . ' = ' . $db->escape($core));
		}
		// Filter by parent
		$parent = $this->getState('filter.parent');

		if (!empty($status))
		{
			$query->where($db->quoteName('parent') . ' = ' . $db->escape($parent));
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
