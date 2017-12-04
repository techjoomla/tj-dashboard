<?php
/**
 * @package    Com_Tjdashboard
 *
 * @author     Techjoomla <contact@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

JLoader::discover("tjdashboard", JPATH_ADMINISTRATOR . '/components/com_tjdashboard/libraries');

/* load language file*/
$lang = JFactory::getLanguage();

$extension = 'com_tjdashboard';
$base_dir = JPATH_SITE;
$language_tag = 'en-GB';
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

/**
 * Tjdashboard factory class.
 *
 * This class perform the helpful operation for truck app
 *
 * @since  1.0
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
	 * @since 	1.0
	 **/
	public static function table($name)
	{
		// @TODO Improve file loading with specific table file.

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tjdashboard/tables');

		// @TODO Add support for cache
		$table = JTable::getInstance($name, 'TjdashboardTable');

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
	 * @since 	1.0
	 **/
	public static function model($name, $config = array())
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tjdashboard/models');

		// @TODO Add support for cache
		$model = JModelLegacy::getInstance($name, 'TjdashboardModel', $config);

		return $model;
	}
}
