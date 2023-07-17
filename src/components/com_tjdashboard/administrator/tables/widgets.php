<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

/**
 * Tj-Dashboard widgets table class
 *
 * @since  1.0.0
 */
class TjdashboardTableWidgets extends Table
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
		if ((!empty($this->params)) && (json_decode($this->params) === null))
		{
			$this->setError(Text::_('COM_TJDASHBOARD_WIDGET_INVALID_JSON_VALUE'));

			return false;
		}

		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->dashboard_widget_id == 0)
		{
			$this->ordering = self::getNextOrder();
		}

		if ($this->dashboard_widget_id == 0)
		{
			$this->created_by = Factory::getUser()->id;
			$this->created_on = Factory::getDate("now", "UTC")->tosql();
		}

		$this->modified_by = Factory::getUser()->id;
		$this->modified_on = Factory::getDate("now", "UTC")->tosql();

		return parent::check();
	}

	/**
	 * Method to delete a Widget.
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
			$widget = TjdashboardWidget::getInstance($pk);

			if ($widget->core != 1)
			{
				return parent::delete($pk);
			}
			elseif ($widget->core == 1)
			{
				$this->setError(Text::_('COM_TJDASHBOARD_DEFAULT_WIDGETS_DELETE_ERROR_MESSAGE'));

				return false;
			}
		}
		catch (Exception $e)
		{
			$this->setError(Text::_('COM_TJDASHBOARD_WIDGETS_DELETE_ERROR_MESSAGE'));

			return false;
		}
	}
}
