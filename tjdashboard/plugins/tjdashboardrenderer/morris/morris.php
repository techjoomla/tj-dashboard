<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * plugin of TJDashboardRendererMorris
 *
 * @since  1.0.0
 */
class PlgTjdashboardRendererMorris
{
	/**
	 * Get the widget JS files
	 *
	 * @return	Array JS files paths
	 *
	 * @since 	1.0
	 **/
	public function getJS()
	{
		$J2 = Juri::root() . '/plugins/tjdashboardrenderer/morris/assets/js/raphael.min.js';
		$J3 = Juri::root() . '/plugins/tjdashboardrenderer/morris/assets/js/morris.min.js';
		$J4 = Juri::root() . '/plugins/tjdashboardrenderer/morris/assets/js/renderer.js';

		return array($J3,$J2,$J4);
	}

	/**
	 * Get the widget CSS files
	 *
	 * @return	Array CSS files paths
	 *
	 * @since 	1.0
	 **/
	public function getCSS()
	{
		return array(Juri::root() . '/plugins/tjdashboardrenderer/morris/assets/css/morris.min.css');
	}
}
