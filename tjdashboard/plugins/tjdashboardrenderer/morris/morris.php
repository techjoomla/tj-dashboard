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
		return array('asets/js/raphael.min.js', 'asets/js/morris.min.js', 'asets/js/renderer.js');
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
		return array('asets/js/morris.css');
	}
}
