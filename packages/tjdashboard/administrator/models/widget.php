<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;
JLoader::import('components.com_tjdashboard.includes.tjdashboard', JPATH_ADMINISTRATOR);
/**
 * Tjdashboard model class for widget
 *
 * @since  1.0.0
 */
class TjdashboardModelWidget extends JModelAdmin
{
	/**
	 * @var		object	The widget data.
	 * @since   1.0
	 */
	protected $data;

	/**
	 * Method to get the job form data.
	 *
	 * @param   int  $pk  An optional array of data for the form to interogate.
	 *
	 * @return  mixed  	Data object on success, false on failure.
	 *
	 * @since   1.0
	 */
	public function getItem($pk = null)
	{
		if ($this->data === null)
		{
			return parent::getItem($pk);
		}

		return $this->data;
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
	public function getTable($type = 'widgets', $prefix = 'TjDashboardTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_tjdashboard/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML and then an event is fired
	 * for users plugins to extend the form with extra fields.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since   1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_tjdashboard.widget', 'widget', array('control' => 'jform', 'load_data' => $loadData));

		return (empty($form)) ? false : $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.0
	 */
	protected function loadFormData()
	{
		return $this->getItem();
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
		// $pk   = (!empty($data['widget_dashboard_id'])) ? $data['widget_dashboard_id'] : (int) $this->getState('widget.widget_dashboard_id');
		$widget = TjdashboardWidget::getInstance();

		// Bind the data.
		if (!$widget->bind($data))
		{
			$this->setError($widget->getError());

			return false;
		}

		// Store the data.
		if (!$widget->save())
		{
			$this->setError($widget->getError());

			return false;
		}

		$this->setState('widget.dashboard_widget_id', $widget->dashboard_widget_id);

		return true;
	}

	public function addAdminWidgets($dashboardId){
		$data = [];
		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Active Courses",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.totalcoursescount",
		    "renderer_plugin" => "numbercardbox.tjdashnumbercardbox",
		    "size" => 6,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Students Enrolled",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.totalstudentsenrolled",
		    "renderer_plugin" => "numbercardbox.tjdashnumbercardbox",
		    "size" => 6,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Paid Courses",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.paidcourses",
		    "renderer_plugin" => "numbercardbox.tjdashnumbercardbox",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Free Courses",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.freecoursescount",
		    "renderer_plugin" => "numbercardbox.tjdashnumbercardbox",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Orders",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.totalorderscount",
		    "renderer_plugin" => "numbercardbox.tjdashnumbercardbox",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Revenue",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.totalrevenuecount",
		    "renderer_plugin" => "numbercardbox.tjdashnumbercardbox",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Activities",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.activities",
		    "renderer_plugin" => "chartjs.tjdashgraph",
		    "size" => 12,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		foreach ($data as $key => $value) {
			$this->save($value);
		}

		return true;
	}

	public function addUserWidgets($dashboardId){

		// $widgets
		$data = [];
		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Enrolled Courses",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.enrolledcoursescount",
		    "renderer_plugin" => "countbox.tjdashcount",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Pending Enrollment",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.pendingenrolledcount",
		    "renderer_plugin" => "countbox.tjdashcount",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Incomplete Courses",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.inprogresscoursescount",
		    "renderer_plugin" => "countbox.tjdashcount",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Complete Courses",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.completedcoursescount",
		    "renderer_plugin" => "countbox.tjdashcount",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Total Time Spent",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.totaltimespent",
		    "renderer_plugin" => "countbox.tjdashcount",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Total Ideal Time",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.totalidealtime",
		    "renderer_plugin" => "countbox.tjdashcount",
		    "size" => 3,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);


		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Enrolled Courses",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.enrolledcourses",
		    "renderer_plugin" => "tabulator.tjdashtable",
		    "size" => 12,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Liked Courses",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.likedcourses",
		    "renderer_plugin" => "tabulator.tjdashtable",
		    "size" => 6,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Recommended Courses",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.recommendedcourses",
		    "renderer_plugin" => "tabulator.tjdashtable",
		    "size" => 6,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Liked Lessons",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.likedlesson",
		    "renderer_plugin" => "tabulator.tjdashtable",
		    "size" => 6,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id
		);


		// $data[] = array(
		//     "dashboard_id" => $dashboardId,
		//     "title" => "Activity Donut Chart",
		//     "primary_text" => "",
		//     "secondary_text" => "",
		//     "color" => "",
		//     "data_plugin" => "tjlms.activitydonut",
		//     "renderer_plugin" => "chartjs.tjdashdonut",
		//     "size" => 12,
		//     "autorefresh" => 1,
		//     "state" => 1,
		//     "params" => "",
		//     "ordering" => "",
		//     "created_by" =>  JFactory::getUser()->id,
		//     "tags" => ""
		// );
		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "My Activity",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.myactivities",
		    "renderer_plugin" => "tabulator.tjdashtable",
		    "size" => 12,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id,
		    "tags" => ""
		);

		$data[] = array(
		    "dashboard_id" => $dashboardId,
		    "title" => "Activity",
		    "primary_text" => "",
		    "secondary_text" => "",
		    "color" => "",
		    "data_plugin" => "tjlms.activitygraph",
		    "renderer_plugin" => "chartjs.tjdashgraph",
		    "size" => 12,
		    "autorefresh" => 1,
		    "state" => 1,
		    "params" => "",
		    "ordering" => "",
		    "created_by" =>  JFactory::getUser()->id,
		    "tags" => ""
		);

		foreach ($data as $key => $value) {
			$this->save($value);
		}

		return true;
	}
}
