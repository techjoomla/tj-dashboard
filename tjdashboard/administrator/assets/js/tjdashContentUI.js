/*
 * @version    SVN:<SVN_ID>
 * @package    com_tjlms
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

"use_strict";
var tjdashContentUI      = (typeof tjdashContentUI == 'undefined') ? {} : tjdashContentUI;


tjdashContentUI.dashboard = tjdashContentUI.dashboard ? tjdashContentUI.dashboard : {};

tjdashContentUI.dashboard.apiurl = 'index.php?option=com_api&app=tjdashboard&resource=dashboard&format=raw&id=';
tjdashContentUI.dashboard.init = function(id){
	tjdashContentUI.root_url = (typeof root_url == 'undefined') ? root_url : root_url;

	var params = [];
	params['type'] = 'GET';
	var promise = tjdashContentService.postData(tjdashContentUI.root_url + tjdashContentUI.dashboard.apiurl + id, '', params);
	var data = tjdashContentUI.utility.processPromise(promise);

	if(!data.dashboard_id)
	{
		console.log("no length");
		//tjdashContentUI.utility.displayMessage(Joomla.JText._('COM_TMT_TEST_FORM_MSG_NO_Q_FOUND'));
		return false;
	}
	else
	{
		jQuery('<h1><div data-dashboard-id="'+data.dashboard_id+'" class="tjdashboard-title">' + data.title + '</div></h1>').appendTo('.tjdashboard');

		if (data.widget_data.length)
		{
			var divSpan = 0;
			var i = 0;
			var j = 1;
			jQuery('<div class="row dashboard-widget-row-'+j+'">').appendTo('.tjdashboard');


			jQuery.each (data.widget_data, function(index, value)
			{
				jQuery('<div class="widget-data span' +value.size+'"><div class="widget-title"><b>'+value.title+'</b></div><div data-dashboard-widget-id="'+value.dashboard_widget_id+'" id="dashboard-widget-'+value.dashboard_widget_id+'" style="min-height: 250px;"></div></div>').appendTo('.dashboard-widget-row-'+j);

				tjdashContentUI.widget.init(value.dashboard_widget_id);

				i++;
				divSpan = parseInt(divSpan) + parseInt(value.size);

				if (divSpan === 12 && data.widget_data.length !== i)
				{
					j++;
					jQuery('</div><div class="row dashboard-widget-row-'+j+'">').appendTo('.tjdashboard');
					divSpan = 0;
				}

				if (data.widget_data.length === i)
				{
					jQuery('</div>').appendTo('.tjdashboard');
				}
			});

			//jQuery('</div>').appendTo('.tjdashboard');
		}
		else
		{
			jQuery('<div class="alert alert-info"> No widgets found to show</div>').appendTo('.tjdashboard');
		}
	}
}

tjdashContentUI.utility  = tjdashContentUI.utility ? tjdashContentUI.utility : {};
tjdashContentUI.utility.processPromise = function(promise)
	{
		var data = [];
		promise.fail(
				function(response) {
					alert("Ajax Error.");
				}
			).done(
				function(r) {

					if (!r.success && r.err_msg)
					{
						alert(r.err_msg);
					}

					/*if (r.messages)
					{
						Joomla.renderMessages(r.messages);
					}*/

					if (r.data)
					{
						data =  r.data;
					}
				}
			);

		return data;
	}

tjdashContentUI.widget = tjdashContentUI.widget ? tjdashContentUI.widget : {};
tjdashContentUI.widget.apiurl = 'index.php?option=com_api&app=tjdashboard&resource=widget&format=raw&id=';
tjdashContentUI.widget.init = function(id){

	tjdashContentUI.root_url = (typeof root_url == 'undefined') ? root_url : root_url;

	var params = [];
	params['type'] = 'GET';
	var promise = tjdashContentService.postData(tjdashContentUI.root_url + tjdashContentUI.widget.apiurl + id, '', params);
	var data = tjdashContentUI.utility.processPromise(promise);

	if(!data.dashboard_widget_id)
	{
		alert("no data");
		return false;
	}
	else
	{
		if (data.widget_render_data)
		{
			var sourceData = [];
			sourceData['element'] = 'dashboard-widget-'+data.dashboard_widget_id;
			sourceData['data'] = data.widget_render_data;

			var renderer = data.renderer_plugin;
			var redererDetail = renderer.split(".");
			var library = redererDetail[0]; // morris
			var method = redererDetail[1]; // method

			if (sourceData && renderer)
			{
				loadScript(root_url+'/plugins/tjdashboardrenderer/'+library+'/assets/js/renderrer.js', function(){
					renderData(method,sourceData);
				});
			}
		}
		else
		{
			jQuery('<div class="alert alert-info">No data to render</div>').appendTo('#dashboard-widget-'+data.dashboard_widget_id);
		}
	}
}

tjdashContentUI.tjdashboard  = tjdashContentUI.tjdashboard ? tjdashContentUI.tjdashboard : {};

jQuery.extend(tjdashContentUI.tjdashboard, {
	getRenderers:function(){
		tjdashContentUI.root_url = (typeof root_url == 'undefined') ? root_url : root_url;
		var url     = tjdashContentUI.root_url + 'administrator/index.php?option=com_tjdashboard&task=widget.getSupportedRenderers&format=json';
		var $form   = jQuery('#jform_data_plugin');
		jQuery('#task',$form).val('widget.getSupportedRenderers');
		var promise = tjdashContentService.postData(url, $form.serialize());
		jQuery('#task',$form).val();
		jQuery('#jform_renderer_plugin').find('option').not(':first').remove();
		promise.fail(
			function(response) {
				if (response.status == 403)
				{
					alert(Joomla.JText._('JERROR_ALERTNOAUTHOR'));
				}
			}
		).done(
			function(response) {
				if (response.success)
				{
					// Append option to plugin dropdown list.
					var list = jQuery("#jform_renderer_plugin");
					jQuery.each(response.data, function(index, item) {
						list.append(new Option(item,index));
					});
				}
				else
				{
					console.log('Something went wrong.', response.message, response.messages)
				}
			}
		);
	}
});
function loadScript(url, callback){

    var script = document.createElement("script")
    script.type = "text/javascript";

    if (script.readyState){  //IE
        script.onreadystatechange = function(){
            if (script.readyState == "loaded" ||
                    script.readyState == "complete"){
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {  //Others
        script.onload = function(){
            callback();
        };
    }

    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}
