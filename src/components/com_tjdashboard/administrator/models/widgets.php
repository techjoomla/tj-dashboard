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
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Tjdashboard model class for widgets
 *
 * @since  1.0.0
 */
class TjdashboardModelWidgets extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelList
	 * @since   1.0.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'dashboard_widget_id', 'wid.dashboard_widget_id',
				'dashboard_id', 'dash.dashboard_id',
				'state', 'wid.state',
				'title', 'wid.title',
				'dashboard_name','dashboard_name',
				'renderer_plugin','wid.renderer_plugin',
				'data_plugin','wid.data_plugin',
				'created_by', 'wid.created_by',
				'ordering', 'wid.ordering',
				'size','wid.size'
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
	 * @since   1.0.0
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('wid.*');
		$query->select(array($db->quoteName('dash.title', 'dashboard_name'), $db->quoteName('users.name')));
		$query->from($db->quoteName('#__tj_dashboard_widgets', 'wid'));
		$query->join('LEFT',
			$db->quoteName('#__tj_dashboards', 'dash') . ' ON (' . $db->quoteName('wid.dashboard_id') . ' = ' . $db->quoteName('dash.dashboard_id') . ')');
		$query->join('LEFT', $db->quoteName('#__users', 'users') . ' ON (' . $db->quoteName('wid.created_by') . ' = ' . $db->quoteName('users.id') . ')');

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where($db->quoteName('wid.dashboard_widget_id') . ' = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(wid.title LIKE ' . $search . ')');
			}
		}

		// Filter by dashboard_id
		$dashboard_id = $this->getState('filter.dashboard_id');

		if (!empty($dashboard_id))
		{
			$query->where($db->quoteName('wid.dashboard_id') . ' = ' . (int) $dashboard_id);
		}

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where($db->quoteName('wid.state') . ' = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where($db->quoteName('wid.state') . ' IN (0,1) ');
		}

		// Filter by size
		$size  = $this->getState('filter.size');

		if (!empty($size))
		{
			$query->where($db->quoteName('wid.size') . ' = ' . (int) $size);
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}
		else
		{
			$query->order(array($db->quoteName('wid.dashboard_id'),$db->quoteName('wid.ordering')));
		}

		return $query;
	}
}
