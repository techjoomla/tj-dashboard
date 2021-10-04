<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();
use Joomla\CMS\Table\Table;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

JLoader::discover("Tjdashboard", JPATH_ADMINISTRATOR . '/components/com_tjdashboard/libraries');

/**
 * Tjdashboard factory class.
 *
 * This class perform the helpful operation for truck app
 *
 * @since  1.0.0
 */
class TjdashboardFactory
{
	/**
	 * Retrieves a table from the table folder
	 *
	 * @param   string  $name  The table file name
	 *
	 * @return	JTable object
	 *
	 * @since 	1.0.0
	 **/
	public static function table($name)
	{
		// @TODO Improve file loading with specific table file.

		Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tjdashboard/tables');

		// @TODO Add support for cache
		$table = Table::getInstance($name, 'TjdashboardTable');

		return $table;
	}

	/**
	 * Retrieves a model from the model folder
	 *
	 * @param   string  $name    The model name to instantiate
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return	JModel object
	 *
	 * @since 	1.0.0
	 **/
	public static function model($name, $config = array())
	{
		BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tjdashboard/models');

		// @TODO Add support for cache
		$model = BaseDatabaseModel::getInstance($name, 'TjdashboardModel', $config);

		return $model;
	}
}
