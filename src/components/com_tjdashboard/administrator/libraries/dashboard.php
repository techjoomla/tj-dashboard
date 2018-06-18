<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <contact@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Unauthorized Access');

/**
 * Dashboard class.  Handles all application interaction with a Dashboard
 *
 * @since  1.0.0
 */
class TjdashboardDashboard extends JObject
{
	public $dashboard_id = null;

	public $asset_id = null;

	public $title = "";

	public $description = "";

	public $alias = "";

	public $ordering = 0;

	public $state = 1;

	public $checked_out = null;

	public $checked_out_time = null;

	public $created_on = null;

	public $created_by = 0;

	public $modified_on = null;

	public $modified_by = 0;

	public $context = "";

	public $core = null;

	public $parent = null;

	public $widget_data = array();

	protected static $dashboardObj = array();

	/**
	 * Constructor activating the default information of the Dashboard
	 *
	 * @param   int  $id  The unique event key to load.
	 *
	 * @since   1.0.0
	 */
	public function __construct($id = 0)
	{
		if (!empty($id))
		{
			$this->load($id);
		}
	}

	/**
	 * Returns the global Dashboard object
	 *
	 * @param   integer  $id  The primary key of the dashboard_id to load (optional).
	 *
	 * @return  TjdashboardDashboard  The Dashboard object.
	 *
	 * @since   1.0.0
	 */
	public static function getInstance($id = 0)
	{
		if (!$id)
		{
			return new TjdashboardDashboard;
		}

		if (empty(self::$dashboardObj[$id]))
		{
			$dashboard = new TjdashboardDashboard($id);
			self::$dashboardObj[$id] = $dashboard;
			self::$dashboardObj[$id]->widget_data = $dashboard->getWidgetDetails($id);
		}

		return self::$dashboardObj[$id];
	}

	/**
	 * Method to load a dashboard object by dashboard id
	 *
	 * @param   int  $id  The dashboard id
	 *
	 * @return  boolean  True on success
	 *
	 * @since 1.0.0
	 */
	public function load($id)
	{
		$table = TjdashboardFactory::table("dashboards");

		if (!$table->load($id))
		{
			return false;
		}

		$widgetData = $this->getWidgetDetails($id);

		$this->setProperties($table->getProperties());
		$this->set('widget_data', $widgetData);

		return true;
	}

	/**
	 * Method to save the Dashboard object to the database
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.0.0
	 * @throws  \RuntimeException
	 */
	public function save()
	{
		// Create the widget table object
		$table = TjdashboardFactory::table("dashboards");
		$table->bind($this->getProperties());

		// Allow an exception to be thrown.
		try
		{
			// Check and store the object.
			if (!$table->check())
			{
				$this->setError($table->getError());

				return false;
			}

			// Store the user data in the database
			if (!($table->store()))
			{
				$this->setError($table->getError());

				return false;
			}
		}
		catch (\Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		return $table->dashboard_id;
	}

	/**
	 * get the dashboard widget Data
	 *
	 * @return mixed An array of data on success, false on failure.
	 *
	 * @since 	1.0
	 **/
	protected function getWidgetDetails()
	{
		if ($this->dashboard_id)
		{
			$widgetModel = TjdashboardFactory::model("widgets", array("ignore_request" => 1));
			$widgetModel->setState('filter.dashboard_id', $this->dashboard_id);
			$widgetModel->setState('filter.state', 1);
			$widgetData = $widgetModel->getItems();

			return $widgetData;
		}
	}

	/**
	 * Method to bind an associative array of data to a dashboard object
	 *
	 * @param   array  &$array  The associative array to bind to the object
	 *
	 * @return  boolean  True on success
	 *
	 * @since 1.0.0
	 */
	public function bind(&$array)
	{
		if (empty ($array))
		{
			$this->setError(JText::_('COM_TJDASHBOARD_EMPTY_DATA'));

			return false;
		}

		// Bind the array
		if (!$this->setProperties($array))
		{
			$this->setError(\JText::_('COM_TJDASHBOARD_BINDING_ERROR'));

			return false;
		}

		// Make sure its an integer
		$this->dashboard_id = (int) $this->dashboard_id;

		return true;
	}
}
