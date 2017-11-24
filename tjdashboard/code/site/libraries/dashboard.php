<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_truck
 *
 * @author      Techjoomla <contact@techjoomla.com>
 * @copyright   2017 Techjoomla
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Unauthorized Access');

/**
 * Event class.  Handles all application interaction with a Event
 *
 * @since  11.1
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
	 * Constructor activating the default information of the Event
	 *
	 * @param   int  $id  The unique event key to load.
	 *
	 * @since   1.0
	 */
	public function __construct($id = 0)
	{
		if (!empty($id))
		{
			$this->load($id);
		}
	}

	/**
	 * Returns the global job object
	 *
	 * @param   integer  $id  The primary key of the event_id to load (optional).
	 *
	 * @return  TjdashboardDashboard  The event object.
	 *
	 * @since   1.0
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
	 * @param   int  $id  The event id
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
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
	 * Save the current object properties to database
	 *
	 * @param   array  $data  The associative array to bind to the object
	 *
	 * @return	String
	 *
	 * @since 	1.0
	 **/
	public function save($data = array())
	{
		$data = array_filter($data);

		if (empty($data))
		{
			$this->setError(JText::_("COM_TJDASHBOARD_EMPTY_DATA"));

			return false;
		}

		$model = TjdashboardFactory::model("Dashboard");
		JForm::addFormPath(JPATH_SITE . '/components/com_tjdashboard/models/forms');
		$form = $model->getForm();

		if (!$form)
		{
			$this->setError($model->getError());

			return false;
		}

		$data = $model->validate($form, $data);

		if (!$data)
		{
			$this->setError($model->getError());

			return false;
		}

		$save = $model->save($data);

		if (!$save)
		{
			$this->setError($model->getError());

			return false;
		}
		else
		{
			return $this->load($model->getState("dashboard_id"));
		}
	}

	/**
	 * get the dashboard widget Data
	 *
	 * @param   INT  $dashboardId  dashboard Id
	 *
	 * @return mixed An array of data on success, false on failure.
	 *
	 * @since 	1.0
	 **/
	protected function getWidgetDetails($dashboardId)
	{
		if ($dashboardId)
		{
			$widgetModel = TjdashboardFactory::model("widgets", array("ignore_request" => 1));
			$widgetModel->setState('filter.dashboard_id', $dashboardId);
			$widgetData = $widgetModel->getItems();

			return $widgetData;
		}
	}
}
