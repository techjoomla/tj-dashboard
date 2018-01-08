/*
 * @version    SVN:<SVN_ID>
 * @package    com_tjdashboard
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

var TJDashboardService = {
	api_dashboard_url : 'index.php?option=com_api&app=tjdashboard&resource=dashboard&id=',
	api_widget_url : 'index.php?option=com_api&app=tjdashboard&resource=widget&id=',
	get_renderers_url : 'administrator/index.php?option=com_tjdashboard&task=widget.getSupportedRenderers',

	postData: function(url, params, formData) {
		if(!params){
			params = {};
		}
		params['url']		= url;
		params['data'] 		= formData;
		params['type'] 		= typeof params['type'] != "undefined" ? params['type'] : 'POST';
		params['async'] 	= typeof params['async'] != "undefined" ? params['async'] :false;
		params['dataType'] 	= typeof params['datatype'] != "undefined" ? params['datatype'] : 'json';

		var promise = jQuery.ajax(params);
		return promise;
	},

	getDashboard: function(id) {
		var params = {};
		params.method = 'GET';
		/** global: root_url */

		return this.postData(root_url + this.api_dashboard_url + id, params);
	},

	getWidget: function(id) {
		var params = {};
		params.method = 'GET';
		/** global: root_url */
		return this.postData(root_url + this.api_widget_url + id, params);
	},

	getRenderers: function(selectedDataPlugin) {
		var formData = {};
		formData.pluginName = selectedDataPlugin;
		/** global: root_url */
		return this.postData(root_url + this.get_renderers_url, '', formData);
	}
}
