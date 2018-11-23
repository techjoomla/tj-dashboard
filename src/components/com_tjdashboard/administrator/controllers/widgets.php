<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Widgets controller class.
 *
 * @since  1.0.0
 */
class TjdashboardControllerWidgets extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   STRING  $name    model name
	 * @param   STRING  $prefix  model prefix
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	public function getModel($name = 'Widget', $prefix = 'TjdashboardModel')
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
}
