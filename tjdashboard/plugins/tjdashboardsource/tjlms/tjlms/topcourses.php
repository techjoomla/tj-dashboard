<?php
/**
 * @version    CVS: 1.0.0
 * @package    TjlmsDashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * plugin of TjlmsTopcoursesDatasource
 *
 * @since  1.0.0
 */
class TjlmsTopcoursesDatasource
{
	public $dataSourceName = "Top Courses";

	/**
	 * Get Topcourses data
	 * 
	 * @return STRING html 
	 *
	 * @since   1.0
	 * */
	private function getData()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('COUNT(eu.user_id) as totalenrolledusers, c.id, c.title');
		$query->from($db->quoteName('#__tjlms_enrolled_users') . ' as eu');
		$query->join('INNER', $db->quoteName('#__tjlms_courses') . ' as c ON c.id = eu.course_id');
		$query->where($db->quoteName('eu.state') . ' = 1');
		$query->where($db->quoteName('c.state') . ' = 1');
		$query->order('totalenrolledusers ASC');
		$query->group($db->quoteName('eu.course_id'));

		// #$query->setLimit(4);

		$db->setQuery($query);
		$enrolledUserToCourse = $db->loadObjectList();

		return $enrolledUserToCourse;
	}

	/**
	 * Get Data for morris donut
	 * 
	 * @param   ARRAY  $widgetData  widgetData
	 * 
	 * @return json dataArray 
	 *
	 * @since   1.0
	 * */
	public function getDataMorrisDonut($widgetData)
	{
		$coursesData = $this->getData();

		if (count($coursesData))
		{
			$dataArray = array();

			foreach ($coursesData as $key => $value)
			{
				$dataArray[$key] = new stdclass;

				$dataArray[$key]->label = $value->title;
				$dataArray[$key]->value = ($value->totalenrolledusers);
			}

			return json_encode($dataArray);
		}
	}

	/**
	 * Get Data for morris bar
	 * 
	 * @param   ARRAY  $widgetData  widgetData
	 * 
	 * @return json dataArray 
	 *
	 * @since   1.0
	 * */
	public function getDataMorrisBar($widgetData)
	{
		$coursesData = $this->getData();

		if (count($coursesData))
		{
			$dataArray = array();

			foreach ($coursesData as $key => $value)
			{
				$dataArray[$key] = new stdclass;

				$dataArray[$key]->x = $value->title;
				$dataArray[$key]->y = $value->totalenrolledusers;
			}

			return json_encode($dataArray);
		}
	}

	/**
	 * Get supported Renderers List
	 * 
	 * @return array supported renderes for this data source 
	 *
	 * @since   1.0
	 * */
	public function getSupportedRenderers()
	{
		return array('morris.bar' => 'Morris Bar', 'morris.donut' => 'Morris Donut');
	}
}
