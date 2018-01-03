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

jimport('joomla.filesystem.folder');
jimport('joomla.plugin.plugin');

$lang = JFactory::getLanguage();
$lang->load('plg_tjlmsdashboard_enrolledcourses', JPATH_ADMINISTRATOR);

/**
 * Vimeo plugin from techjoomla
 *
 * @since  1.0.0
 */

class TjlmsEnrolledcoursesDatasource
{
	/**
	 * Function to get data of the whole block
	 *
	 * @return Array data.
	 *
	 * @since 1.0.0
	 */
	public function getData()
	{
		// @Todo This can be come throught plugins params
		$no_of_courses = 5;
		$userId = 171;
		$db    = JFactory::getDBO();
		$utc_now = $db->quote(JFactory::getDate('now', 'UTC')->format('Y-m-d'));
		$query = $db->getQuery(true);

		$query->select(array('c.id as id', 'c.title', 'c.image', 'c.certificate_term', 'eu.user_id'));
		$query->select('IF(cert.exp_date < ' . $utc_now . ' AND cert.exp_date <> "0000-00-00 00:00:00", 1, 0) as cert_expired');
		$query->from($db->qn('#__tjlms_courses', 'c'));
		$query->join('LEFT',
							$db->qn('#__tjlms_enrolled_users', 'eu') . 'ON ( ' . $db->qn('eu.course_id') . '=' . $db->qn('c.id') . ')'
					);
		$joinCondition  = $db->qn('cert.course_id') . '=' . $db->qn('c.id');
		$joinCondition .= ' AND ' . $db->qn('cert.user_id') . ' = ' . $db->qn('eu.user_id');
		$query->join('LEFT',
							$db->qn('#__tjlms_certificate', 'cert') . 'ON ( ' . $joinCondition . ')'
					);
		$query->join('LEFT',
							$db->qn('#__categories', 'cat') . 'ON ( ' . $db->qn('cat.id') . ' = ' . $db->qn('c.cat_id') . ')'
					);
		$userCondition = $db->qn('eu.user_id') . ' = ' . (int) $userId;
		$query->where(
						$userCondition . ' AND ' . $db->qn('c.state') . '=1 AND' . $db->qn('eu.state') . '=1 AND ' . $db->qn('cat.published') . '=1'
						);
		$query->order('eu.id DESC');
		$query->group($db->qn('c.id'));

		$db->setQuery($query);
		$total_rows = $db->query();

		// Get total number of rows
		$total_rows = $db->getNumRows();

		$query->setLimit($no_of_courses);

		// Set the query for execution.
		$db->setQuery($query);
		$userCourseinfo = $db->loadObjectList();

		require_once JPATH_SITE . '/components/com_tjlms/helpers/courses.php';
		$tjlmsCoursesHelper = new tjlmsCoursesHelper;
		$record = array();

		// Include helper file to get todoid and contentid
		$path = JPATH_SITE . '/components/com_jlike/helper.php';
		$ComjlikeHelper = "";

		if (JFile::exists($path))
		{
			if (!class_exists('ComjlikeHelper'))
			{
				JLoader::register('ComjlikeHelper', $path);
				JLoader::load('ComjlikeHelper');
			}

			$ComjlikeHelper = new ComjlikeHelper;
		}

		foreach ($userCourseinfo as $onecourseinfo)
		{
			$content_id      = $ComjlikeHelper->getContentId($onecourseinfo->id, 'com_tjlms.course');

			if (!empty($content_id) && !empty($onecourseinfo->user_id))
			{
				$query = $db->getQuery(true);

				$query->select($db->quoteName(array('td.id', 'td.start_date', 'td.due_date', 'u.name', 'td.assigned_to')));
				$query->from($db->quoteName('#__jlike_todos', 'td'));
				$query->join('INNER', $db->quoteName('#__users', 'u') . ' ON (' . $db->quoteName('u.id') . ' = ' . $db->quoteName('td.assigned_by') . ')');
				$query->where($db->quoteName('td.assigned_to') . " = " . $db->quote($onecourseinfo->user_id));
				$query->where($db->quoteName('td.content_id') . " = " . $db->quote($content_id));
				$query->where($db->quoteName('td.type') . " = " . $db->quote('assign'));

				$db->setQuery($query);
				$todo = $db->loadObject();
			}

			$record_data                    = new stdclass;
			$record_data->id                = $onecourseinfo->id;
			$record_data->title             = $onecourseinfo->title;
			$record_data->certificate_term  = $onecourseinfo->certificate_term;
			$record_data->cert_expired  	= $onecourseinfo->cert_expired;
			$record_data->image             = $onecourseinfo->image;
			$record_data->last_accessed_lesson = $tjlmsCoursesHelper->getLessonBycondition($onecourseinfo->id, 'last_accessed_on', 'DESC', $userId);
			$record_data->module_data       = $tjlmsCoursesHelper->getCourseProgress($onecourseinfo->id, $userId);

			// Assignment dates
			if (!empty($todo))
			{
				$record_data->assign_start_date = $todo->start_date;
				$record_data->assign_due_date   = $todo->due_date;
				$record_data->assigned_by   = $todo->name;
			}

			$record[]                       = $record_data;
			unset($todo);
		}

		$data['totalRows'] = $total_rows;
		$data['courseData'] = $record;

		return $data;
	}

	/**
	 * Get Data for Plain Html bar
	 * 
	 * @return json dataArray 
	 *
	 * @since   1.0
	 * */
	public function getDataTabulatorTjdashtable()
	{
		$items = $this->getData();

		return json_encode($items['courseData']);
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
		return array('tabulator.tjdashtable' => 'TjLms Table');
	}
}
