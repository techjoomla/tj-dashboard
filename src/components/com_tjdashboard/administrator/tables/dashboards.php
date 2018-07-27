<?php
/**
 * @package    Com_TjDashboard
 *
 * @author     Techjoomla <contact@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JLoader::import('components.com_tjdashboard.includes.tjdashboard', JPATH_ADMINISTRATOR);
/**
 * Tj-Dashboard dashboard table class
 *
 * @since  1.0.0
 */
class TjdashboardTableDashboards extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  Database object
	 *
	 * @since  1.0.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__tj_dashboards', 'dashboard_id', $db);

		// Set the alias since the column is called state
		$this->setColumnAlias('published', 'state');
	}

	/**
	 * Overloaded check function
	 *
	 * @return  check
	 *
	 * @since  1.0.0
	 */
	public function check()
	{
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->dashboard_id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		if (!$this->created_by)
		{
			$this->created_by = JFactory::getUser()->id;
		}

		$this->alias = trim($this->alias);

		if (empty($this->alias))
		{
			$this->alias = $this->title;
		}

		if ($this->alias)
		{
			if (JFactory::getConfig()->get('unicodeslugs') == 1)
			{
				$this->alias = JFilterOutput::stringURLUnicodeSlug($this->alias);
			}
			else
			{
				$this->alias = JFilterOutput::stringURLSafe($this->alias);
			}
		}

		// Check if course with same alias is present
		$table = TjdashboardFactory::table("dashboards");

		if ($table->load(array('alias' => $this->alias)) && ($table->dashboard_id != $this->dashboard_id || $this->dashboard_id == 0))
		{
			$msg = JText::_('COM_TJDASHBOAD_DASHBOARD_SAVE_ALIAS_ALREADY_EXIST_WARNING');

			while ($table->load(array('alias' => $this->alias)))
			{
				$this->alias = JString::increment($this->alias, 'dash');
			}

			JFactory::getApplication()->enqueueMessage($msg, 'warning');
		}

		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
		}

		return parent::check();
	}

	/**
	 * Method to delete a dashboard.
	 * 
	 * @param   int  $pk  Primary key value to delete. Optional
	 * 
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function delete($pk = null)
	{
		try
		{
			$result = parent::delete($pk);

			return $result;
		}
		catch (Exception $e)
		{
			$this->setError(JText::_('COM_TJDASHBOARD_DASHBOARDS_DELETE_ERROR_MESSAGE'));

			return false;
		}
	}
}
