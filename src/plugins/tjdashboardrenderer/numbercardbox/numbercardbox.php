<?php
/**
 * @package     TJDashboard
 * @subpackage  com_tjdashboard
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

/**
 * plugin of TJDashboardRendererTjdash
 *
 * @since  1.0.0
 */
class PlgTjdashboardRendererNumbercardbox
{
	/**
	 * Get the widget JS files
	 *
	 * @return	Array JS files paths
	 *
	 * @since 	1.0.0
	 **/
	public function getJS()
	{
		$JS1 = Juri::root() . 'plugins/tjdashboardrenderer/numbercardbox/assets/js/renderer.js';

		return array($JS1);
	}

	/**
	 * Get the widget CSS files
	 *
	 * @return	Array CSS files paths
	 *
	 * @since 	1.0.0
	 **/
	public function getCSS()
	{
		return array();
	}
}
