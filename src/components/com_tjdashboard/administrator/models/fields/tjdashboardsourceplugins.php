<?php
/**
 * @package    Com_Tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('plugins');

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  1.0.0
 */
class JFormFieldTjdashboardSourcePlugins extends JFormFieldPlugins
{
	/**
	 * The field type.
	 *
	 * @var    string
	 * @since 1.0.0
	 */
	protected $type = 'tjdashboardsourceplugins';

	/**
	 * The path to folder for plugins.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $folder;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   1.0.0
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'folder':
				return $this->folder;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'folder':
				$this->folder = (string) $value;
				break;

			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     JFormField::setup()
	 * @since   1.0.0
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);

		if ($return)
		{
			$this->folder = (string) $element['folder'];
		}

		return $return;
	}

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array  An array of JHtml options.
	 *
	 * @since   1.0.0
	 */
	protected function getOptions()
	{
		$folder        = $this->folder;
		$tjDashboardSourcePlugins = array();

		if (empty($folder))
		{
			JLog::add(JText::_('JFRAMEWORK_FORM_FIELDS_PLUGINS_ERROR_FOLDER_EMPTY'), JLog::WARNING, 'jerror');

			return array_merge($tjDashboardSourcePlugins);
		}

		// Get list of plugins
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('element AS value, name AS text')
			->from('#__extensions')
			->where('folder = ' . $db->quote($folder))
			->where('enabled = 1')
			->order('ordering, name');

		$options   = $db->setQuery($query)->loadObjectList();
		$lang      = JFactory::getLanguage();
		$j = 0;

		foreach ($options as $item)
		{
			$sourcePath    = JPATH_PLUGINS . '/' . $folder . '/' . $item->value . '/' . $item->value;
			$extension = 'plg_' . $folder . '_' . $item->value;

			$lang->load($extension, JPATH_PLUGINS . '/' . $folder . '/' . $item->value, null, false, true) ||
			$lang->load($extension, JPATH_ADMINISTRATOR, null, false, true);

			// @Todo : Need to improve this code, Can be Move to Model
			$dataSources = array_diff(scandir($sourcePath), array('..', '.'));

			foreach ($dataSources as $dataSourceFile)
			{
				$j++;
				$className = ucfirst($item->value) . ucfirst(str_replace('.php', '', $dataSourceFile)) . 'Datasource';
				require_once $sourcePath . '/' . $dataSourceFile;
				$dataSourceClassObject = new $className;
				$dataSourceName 	 = $item->value . ' ' . JText::_($dataSourceClassObject->dataSourceName);
				$dataSourceNameValue = strtolower(trim($item->value)) . '.' . strtolower(str_replace(' ', '', JText::_($dataSourceClassObject->dataSourceName)));
				$tjDashboardSourcePlugins[$j]['text']   = $dataSourceName;
				$tjDashboardSourcePlugins[$j]['value']  = $dataSourceNameValue;
			}
		}

		return array_merge($tjDashboardSourcePlugins);
	}
}
