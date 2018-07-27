<?php
/**
 * @package    Com_TjDashboard
 *
 * @author     Techjoomla <contact@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Tj-Dashboard widgets table class
 *
 * @since  1.0.0
 */
class TjdashboardTableWidgets extends JTable
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
		parent::__construct('#__tj_dashboard_widgets', 'dashboard_widget_id', $db);
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
		if (property_exists($this, 'ordering') && $this->dashboard_widget_id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		if ($this->dashboard_widget_id == 0)
		{
			$this->created_by = JFactory::getUser()->id;
			$this->created_on = JFactory::getDate("now", "UTC")->tosql();
		}

		$this->modified_by = JFactory::getUser()->id;
		$this->modified_on = JFactory::getDate("now", "UTC")->tosql();

		return parent::check();
	}

	/**
	 * Method to delete a Widget.
	 *
	 * @param   mixed  $pk  Primary key value to delete. Optional
	 * 
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function delete($pk = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$result = 0;

		if (!empty($pk))
		{
			try
			{
				$db = JFactory::getDbo();

				// Check if widget is not default
				$table = JTable::getInstance('widgets', 'TjdashboardTable', array('dbo', $db));

				if ($table->load(array('dashboard_widget_id' => $pk)) && ($table->core != 1))
				{
					$result = parent::delete($pk);
				}
				elseif ($table->load(array('dashboard_widget_id' => $pk)) && ($table->core == 1))
				{
					JFactory::getApplication()->enqueueMessage(JText::_('COM_TJDASHBOARD_DEFAULT_WIDGETS_DELETE_ERROR_MESSAGE'), 'error');
				}

				return $result;
			}
			catch (Exception $e)
			{
				JFactory::getApplication()->enqueueMessage(JText::_('COM_TJDASHBOARD_WIDGETS_DELETE_ERROR_MESSAGE'), 'error');
			}
		}
	}
}
