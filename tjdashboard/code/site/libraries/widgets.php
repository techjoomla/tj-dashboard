		// Get a db connection.
		$app = JFactory::getApplication();
		$jinput = $app->input;

		$widget_id = $jinput->get('id', 0, 'INT');
		$dashboard_id = $jinput->get('dashboard_id', 0, 'INT');

		$widgetModel = TjdashboardFactory::model("Widgets");

		if ($widget_id)
		{
			$widgetModel->setState('filter.dashboard_widget_id', $widget_id);
		}

		if ($dashboard_id)
		{
			$widgetModel->setState('filter.dashboard_id', $dashboard_id);
		}

		$widgetData = $widgetModel->getItems();

		if (count($widgetData))
		{
			$i = 0;

			foreach ($widgetData as $data)
			{
				$sourceDetails = explode(".", $data->data_plugin);

				$path = JPATH_BASE . '/plugins/tjdashboardsource/' . $sourceDetails[0] . '/' . $sourceDetails[0];

				if (JFolder::exists($path))
				{
					$filePath = $path . '/' . $sourceDetails[1] . '.php';

					if (JFile::exists($filePath))
					{
						if ($i != 1)
						{
							if (require_once $filePath)
							{
								$i++;
							}
						}

						$sourceClass = 'TjDashboardData' . ucfirst($sourceDetails[0]) . ucfirst($sourceDetails[1]);
						$dataPlugin = new $sourceClass;

						if (!empty($dataPlugin))
						{
							$splitRenderer  = explode(".", $data->renderer_plugin);
							$methodName = 'getData' . ucfirst($splitRenderer[0]) . ucfirst($splitRenderer[1]);
							$data->widget_data_details = $dataPlugin->$methodName($data);
						}
					}
				}
			}

			$this->plugin->setResponse($widgetData);
