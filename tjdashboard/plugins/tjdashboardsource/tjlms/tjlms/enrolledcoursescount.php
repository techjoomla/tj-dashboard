<?php
/**
 * @package    Shika
 * @author     TechJoomla | <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2005 - 2014. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * Shika is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

$lang = JFactory::getLanguage();
$lang->load('plg_tjlmsdashboard_enrolledcoursescount', JPATH_ADMINISTRATOR);

/**
 * Enrolled courses count plugin for shika
 *
 * @since  1.0.0
 */

class TjlmsEnrolledcoursescountDatasource
{
	public $dataSourceName = "Enrolled courses count";

	/**
	 * Function to get data of the whole block
	 *
	 * @return  data.
	 *
	 * @since 1.0.0
	 */
	public function getData()
	{
		// Get count of enrolled courses for the user
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select('COUNT(u.id) as totalenrolledCourses');
		$query->from($db->quoteName('#__tjlms_enrolled_users') . ' as u');
		$query->join('INNER', $db->quoteName('#__tjlms_courses') . ' as c ON c.id=u.course_id');
		$query->where($db->quoteName('u.user_id') . ' = 171');
		$query->where($db->quoteName('u.state') . ' = 1');
		$query->where($db->quoteName('c.state') . ' = 1');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$totalEnrolledCourses = $db->loadresult();

		return $totalEnrolledCourses;
	}

	/**
	 * Get Data for Plain Html bar
	 * 
	 * @return json dataArray 
	 *
	 * @since   1.0
	 * */
	public function getDataTjdashhtmlPlainhtml()
	{
		$coursesData = $this->getData();

		return json_encode($coursesData);
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
		return array('tjdashhtml.plainhtml' => 'TJLMS Plain Text Box', 'tjdashhtml.iconhtml' => 'TJLMS Text With Icon Box');
	}
}
