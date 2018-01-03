<?php
/**
 * @version    CVS: 1.0.0
 * @package    Plg_Tjdash
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * plugin of TJDashboardRendererTjdash
 *
 * @since  1.0.0
 */
class PlgTjdashboardRendererTabulator
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
		return array('assets/js/jquery-ui.min', 'assets/js/tabulator.min.js', 'assets/js/renderer.js');
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
		return array('assets/css/tabulator.min.css');
	}
}
