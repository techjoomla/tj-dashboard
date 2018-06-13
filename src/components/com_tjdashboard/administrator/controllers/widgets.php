<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
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
