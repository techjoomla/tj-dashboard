<?php
/**
 * @package     TJDashboard
 * @subpackage  tjdashboardrenderer
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2019 Techjoomla. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Uri\Uri;

/**
 * plugin of TJDashboardRendererFilterbox
 *
 * @since  __DEPLOY_VERSION__
 */
class PlgTjdashboardRendererFilterbox
{
	/**
	 * Get the widget JS files
	 *
	 * @return	Array JS files paths
	 *
	 * @since  __DEPLOY_VERSION__
	 **/
	public function getJS()
	{
		$JS1 = Uri::root() . 'plugins/tjdashboardrenderer/filterbox/assets/js/renderer.min.js';

		return array($JS1);
	}

	/**
	 * Get the widget CSS files
	 *
	 * @return	Array CSS files paths
	 *
	 * @since 	__DEPLOY_VERSION__
	 **/
	public function getCSS()
	{
		return array();
	}
}
