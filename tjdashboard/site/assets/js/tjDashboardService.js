/*
 * @version    SVN:<SVN_ID>
 * @package    com_tmt
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

var TJDashboardService = {
	api_dashboard_url : 'index.php?option=com_api&app=tjdashboard&resource=dashboard&id=',
	api_widget_url : 'index.php?option=com_api&app=tjdashboard&resource=widget&id=',

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
		params = {};
		params.method = 'GET';

		return this.postData(root_url + this.api_dashboard_url + id, params);
	},

	getWidget: function(id) {
		params = {};
		params.method = 'GET';

		return this.postData(root_url + this.api_widget_url + id, params);
	},

	loadScript: function(url, callback){

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
}
