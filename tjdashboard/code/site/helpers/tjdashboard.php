<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  2017 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Class TjdashboardFrontendHelper
 *
 * @since  1.6
 */
class TjdashboardFrontendHelper
{
	/**
	 * Method to get widget Data as er rendered.
	 *
	 * @param   Array  $widgetDetails  widget Data
	 *
	 * @return Array
	 */
	public function getWidgetRendererData($widgetDetails)
	{
		// @TODO Need to review this code
		if (count($widgetDetails))
		{
			$responce = array();

			if ($widgetDetails[0]->data_plugin)
			{
				$dataPlugin = explode(".", $widgetDetails[0]->data_plugin);

				$path = "/plugins/tjdashboardsource/";

				$folderPath = $path . $dataPlugin[0] . "/" . $dataPlugin[0];

				if (JFolder::exists(JPATH_SITE . $folderPath))
				{
					$filePath = $folderPath . "/" . $dataPlugin[1] . ".php";

					if (JFile::exists(JPATH_SITE . $filePath))
					{
						JLoader::import($folderPath . "/" . $dataPlugin[1], JPATH_SITE);
						$className = 'TjdashboardData' . ucfirst($dataPlugin[0]) . ucfirst($dataPlugin[1]);

						if (class_exists($className))
						{
							$pluginClass = new $className;
							$rendererPlugin = explode(".", $widgetDetails[0]->renderer_plugin);

							$methodName = 'getData' . ucfirst($rendererPlugin[0]) . ucfirst($rendererPlugin[1]);

							if (method_exists($pluginClass, $methodName))
							{
								$widgetRealData = $pluginClass->$methodName($widgetDetails);

								$responce['status'] = 1;
								$responce['msg'] = JText::_("COM_TJDASHBOARD_SUCCESS_TEXT");
								$responce['data'] = $widgetRealData;
							}
							else
							{
								$responce['status'] = 0;
								$responce['msg'] = JText::_("COM_TJDASHBOARD_ERROR_TEXT_METHOD_NOT_FOUND");
							}
						}
						else
						{
							$responce['status'] = 0;
							$responce['msg'] = JText::_("COM_TJDASHBOARD_ERROR_TEXT_CLASS_NOT_FOUND");
						}
					}
					else
					{
						$responce['status'] = 0;
						$responce['msg'] = JText::_("COM_TJDASHBOARD_ERROR_TEXT_FILE_NOT_FOUND");
					}
				}
				else
				{
					$responce['status'] = 0;
					$responce['msg'] = JText::_("COM_TJDASHBOARD_ERROR_TEXT_FOLDER_NOT_FOUND");
				}
			}

			return $responce;
		}
	}
}
