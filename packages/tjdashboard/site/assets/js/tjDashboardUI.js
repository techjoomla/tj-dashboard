/*
 * @version    SVN:<SVN_ID>
 * @package    com_tjlms
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

var TJDashboardUI = {

	initDashboard : function(id){
debugger;
	/** global: TJDashboardService */
	var promise = TJDashboardService.getDashboard(id);

	promise.done(function(response) {
			if(!response.data.dashboard_id)
			{
				alert("no length");
				//TJDashboardUI.utility.displayMessage(Joomla.JText._('COM_TMT_TEST_FORM_MSG_NO_Q_FOUND'));
				return false;
			}

			// if(response.data.params.parseInt() && response.data.params.parseInt()==1){
			// 	response.data.title='';
			// }
			// if (response.data.color) {
			// 	var color = response.data.color;
			// }
			// else{
			// 	var color = "panel-default";
			// }


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
				jQuery('<div class="col-xs-' +value.size+'"><div class="widget-data panel panel-default"><div class="widget-title panel-heading"><b>'+value.title+'</b></div><div data-dashboard-widget-id="'+value.dashboard_widget_id+'" id="dashboard-widget-'+value.dashboard_widget_id+'" class=""></div></div></div>').appendTo('.dashboard-widget-row-'+j);

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

			return true;
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


			TJDashboardUI._addCssFiles(response.data.widget_css);
			TJDashboardUI._addJsFiles(response.data.widget_js);

			if (!TJDashboardUI._validWidget(response.data.widget_render_data))
			{
				jQuery('<div class="alert alert-info">No data to render</div>').appendTo('#dashboard-widget-'+response.data.dashboard_widget_id);
				return false;
			}
			// console.log(response);

			TJDashboardUI._addCssFiles(response.data.widget_css);
			TJDashboardUI._addJsFiles(response.data.widget_js);
			jQuery(window).trigger('resize');
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

			var libraryClassName = 'TJDashboard'+TJDashboardUI._jsUcFirst(library);
			window[libraryClassName].renderData(method,sourceData);

			return true;
			});
	},

	_addCssFiles: function(cssObj){
		jQuery.each(cssObj,function(index,value){
			var style = document.createElement('link');
			style.href = value;
			style.type = 'text/css';
			style.rel = 'stylesheet';
			if(jQuery.find("link [href='"+value+"']").length==0){
				jQuery('head').append(style);
			}
		});
	},

	_addJsFiles: function(jsObj){
		jQuery.each(jsObj,function(index,value){
			var script = document.createElement('script');
			script.src = value;
			script.type = 'text/javascript';
			if(jQuery.find("script [src='"+value+"']").length==0){
				//jQuery('head').append(script);
				jQuery.getScript(script);
			}
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

	_jsUcFirst: function(library)
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
	},

	_setRendererFields: function(){
		var selectedRendererPlugin = jQuery('#jform_renderer_plugin').val();
		/** global: TJDashboardService */
		if(selectedRendererPlugin=='countbox.tjdashcount' || selectedRendererPlugin=='numbercardbox.tjdashnumbercardbox'){
			jQuery("#jform_primary_text").closest(".control-group").toggleClass("hidden");
			jQuery("#jform_secondary_text").closest(".control-group").toggleClass("hidden");
			jQuery("#jform_color").closest(".control-group").toggleClass("hidden");
		}
	}

}
