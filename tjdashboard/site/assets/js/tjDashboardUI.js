/*
 * @version    SVN:<SVN_ID>
 * @package    com_tjlms
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

var TJDashboardUI = {

	initDashboard : function(id){

	/** global: TJDashboardService */
	var promise = TJDashboardService.getDashboard(id);

	promise.done(function(response) {
			if(!response.data.dashboard_id)
			{
				alert("no length");
				//TJDashboardUI.utility.displayMessage(Joomla.JText._('COM_TMT_TEST_FORM_MSG_NO_Q_FOUND'));
				return false;
			}

			jQuery('<h1><div data-dashboard-id="'+response.data.dashboard_id+'" class="tjdashboard-title">' + response.data.title + '</div></h1>').appendTo('.tjdashboard');

			if (response.data.widget_data.length <= 0)
			{
				jQuery('<div class="alert alert-info"> No widgets found to show</div>').appendTo('.tjdashboard');
				return false;
			}

			var divSpan = 0;
			var i = 0;
			var j = 1;
			jQuery('<div class="row dashboard-widget-row-'+j+'">').appendTo('.tjdashboard');
			jQuery.each (response.data.widget_data, function(index, value)
			{
				jQuery('<div class="widget-data span' +value.size+'"><div class="widget-title"><b>'+value.title+'</b></div><div data-dashboard-widget-id="'+value.dashboard_widget_id+'" id="dashboard-widget-'+value.dashboard_widget_id+'" style="min-height: 250px;"></div></div>').appendTo('.dashboard-widget-row-'+j);

				TJDashboardUI.initWidget(value.dashboard_widget_id);
				i++;
				divSpan = parseInt(divSpan) + parseInt(value.size);

				if (divSpan === 12 && response.data.widget_data.length !== i)
				{
					j++;
					jQuery('</div><div class="row dashboard-widget-row-'+j+'">').appendTo('.tjdashboard');
					divSpan = 0;
				}

				if (response.data.widget_data.length === i)
				{
					jQuery('</div>').appendTo('.tjdashboard');
				}
			});
		}
	});
},

	initWidget : function(id){
		/** global: TJDashboardService */
		var promise = TJDashboardService.getWidget(id);
		promise.done(function(response) {
			if(!response.data.dashboard_widget_id)
			{
				alert("no data");
				return false;
			}

			if (!TJDashboardUI._validWidget(response.data.widget_render_data))
			{
				jQuery('<div class="alert alert-info">No data to render</div>').appendTo('#dashboard-widget-'+response.data.dashboard_widget_id);
				return false;
			}

			var sourceData = [];
			sourceData['element'] = 'dashboard-widget-'+response.data.dashboard_widget_id;
			sourceData['data'] = response.data.widget_render_data;
			var redererDetail = response.data.renderer_plugin.split(".");
			var library = redererDetail[0];
			var method = redererDetail[1];

			if ((!sourceData) && (!response.data.renderer_plugin))
			{
				return false;
			}

			var libraryClassName = 'TJDashboard'+TJDashboardUI._jsLibraryUperCase(library);
			window[libraryClassName].renderData(method,sourceData); 

			return true;
			});
	},

	_validWidget: function (widgetJson) {
		try {
			JSON.parse(widgetJson);
		} catch (e) {
			return false;
		}
    return true;
	},

	_jsLibraryUperCase: function(library) 
	{
		return library.charAt(0).toUpperCase() + library.slice(1);
	},

	_setRenderers: function()
	{
		var selectedDataPlugin = jQuery('#jform_data_plugin').val();
		/** global: TJDashboardService */
		var promise = TJDashboardService.getRenderers(selectedDataPlugin);
		jQuery('#jform_renderer_plugin').find('option').not(':first').remove();
		promise.done(function(response) {
			// Append option to plugin dropdown list.
			var list = jQuery("#jform_renderer_plugin");
			/** global: Option */
			jQuery.each(response.data, function(index, item) {
				list.append(new Option(item,index));
			});
		});
	}
}
