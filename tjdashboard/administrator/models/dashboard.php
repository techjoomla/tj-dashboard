<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;
JLoader::import('components.com_tjdashboard.includes.tjdashboard', JPATH_ADMINISTRATOR);
/**
 * Item Model for an Dashboard.
 *
 * @since  1.6
 */
class TjdashboardModelDashboard extends JModelAdmin
{
	private $item = null;

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
		$this->widgetModel = TjdashboardFactory::model("widget");
		$this->widgetsModel = TjdashboardFactory::model("widgets");
		$this->widgetTable = TjdashboardFactory::table("widgets");
		parent::__construct($config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm|boolean  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_tjdashboard.dashboard', 'dashboard', array('control' => 'jform', 'load_data' => $loadData));

		return $form = empty($form) ? false : $form;
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 */
	public function getTable($type = 'Dashboards', $prefix = 'TjdashboardTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	$data  The data for the form.
	 *
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_tjdashboard.edit.dashboard.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			if (!empty($data->dashboard_id))
			{
				$dashboardWidgetsData = $this->getDashboardWidgetDetails($data->dashboard_id);
				$widgets = array();

				foreach ($dashboardWidgetsData as $widget)
				{
					$widgets[] = $widget;
				}

				$data->widgets = $widgets;
			}
		}

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 1.6
	 */
	public function save($data)
	{
		$table = $this->getTable();

		if ($table->save($data) === true)
		{
			$this->saveWidgetDetails($data['widgets'], $table->dashboard_id);
			$this->setState('dashboard_id', $table->dashboard_id);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Method to save the widget form data.
	 *
	 * @param   array  $postedWidgets  The widget form data
	 * @param   array  $dashboard_id   The dashboard id
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 1.6
	 */
	public function saveWidgetDetails($postedWidgets, $dashboard_id)
	{
		// First need to delete widgets that not posted from form by comparing widgets in db and posted widgets
		$widgetsInDB = $this->getDashboardWidgetDetails($dashboard_id);

		$array = array_values($postedWidgets);
		$widgetsIdsInDB = $widgetIdsPosted = array();
		$count = count($array);

		foreach ($widgetsInDB as $key => $value)
		{
			$widgetsIdsInDB[$key] = $value->dashboard_widget_id;

			if ($key < $count)
			{
				$widgetIdsPosted[$key] = $array[$key]['dashboard_widget_id'];
			}
		}

		$widgetsToDelete = array_diff($widgetsIdsInDB, $widgetIdsPosted);

		foreach ($widgetsToDelete as $value)
		{
			$this->widgetTable->delete($value);
		}

		foreach ($postedWidgets as $widget)
		{
			$widget['dashboard_id'] = $dashboard_id;
			$this->widgetModel->save($widget);
		}
	}

	/**
	 * Method to get the data of widget for provided dashboard.
	 *
	 * @param   int  $dashboard_id  dashboard for which widget data needed
	 * 
	 * @return  mixed  $data          The data for the provide dashboard id .
	 *
	 * @since	1.6
	 */
	public function getDashboardWidgetDetails($dashboard_id)
	{
		$this->widgetsModel->setState('filter.dashboard_id', $dashboard_id);

		return $this->widgetsModel->getItems();
	}
}
