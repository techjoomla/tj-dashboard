/*
 * @version    SVN:<SVN_ID>
 * @package    com_tjlms
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

"use_strict";
var tjdashContentUI      = (typeof tjdashContentUI == 'undefined') ? {} : tjdashContentUI;
tjdashContentUI.root_url = (typeof root_url == 'undefined') ? root_url : root_url;

tjdashContentUI.dashboard = tjdashContentUI.dashboard ? tjdashContentUI.dashboard : {};
tmtContentUI.dashboard.apiurl = '';
tjdashContentUI.dashboard.init = function(id){
	var params = [];
	params['type'] = 'GET';
	var promise = tjdashContentService.postData(tjdashContentUI.root_url + this.apiurl + id, '', params);
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

			// TODO move html to better place
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
tjdashContentUI.widget.apiurl = "";
tjdashContentUI.widget.init = function(id){
	var params = [];
	params['type'] = 'GET';
	var promise = tjdashContentService.postData(tjdashContentUI.root_url + this.apiurl + id, '', params);
	var data = tjdashContentUI.utility.processPromise(promise);

	if(!data.dashboard_widget_id)
	{
		alert("no data");
		//tjdashContentUI.utility.displayMessage(Joomla.JText._('COM_TMT_TEST_FORM_MSG_NO_Q_FOUND'));
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

			if (sourceData && renderer)
			{
				//create widget div with convention
				tjdashContentUI.renderer.render(renderer, sourceData);
			}
		}
		else
		{
			//console.log(sourceData['element']);
			jQuery('<div class="alert alert-info">No data to render</div>').appendTo('#dashboard-widget-'+data.dashboard_widget_id);
		}
	}
}

tjdashContentUI.renderer = tjdashContentUI.renderer ? tjdashContentUI.renderer : {};
tjdashContentUI.renderer.render = function(renderer, data)
{
	 var redererDetail = renderer.split(".");

	 var library = redererDetail[0]; // morris
	 var method = redererDetail[1]; // method

	 if (tjdashContentUI.renderer[library] && data)
	 {
		 //console.log("library present");
		 tjdashContentUI.renderer[library].init(method, data);
	}
	else
	{
		jQuery('<div class="alert alert-info">Library not found</div>').appendTo('".'+data['element']+'"');
	}
}
//Rmove this lines framework should load things dynamically
tjdashContentUI.renderer.morris = tjdashContentUI.renderer.morris ? tjdashContentUI.renderer.morris : {};

tjdashContentUI.renderer.morris = {

	init: function(renderer, data)
	{
		this[renderer](data);
	},

	bar:function(data)
	{
		var renderData = JSON.parse(data.data);

		Morris.Bar({
			  element: data.element,
			  data: renderData,
			  xkey: 'x',
			  ykeys: ['y'],
			  labels: ['Y'],
			  barColors: 'rgb(0, 84, 141)',
			  barSize: '30',
			  yLabelMargin: 10,
		  });
	},

	donut: function(data)
	{
		var renderData = JSON.parse(data.data);

		//actual code
		Morris.Donut({
			  element: data.element,
			  data: renderData
			});
	}
}
