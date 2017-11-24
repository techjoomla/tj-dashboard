/*
 * @version    SVN:<SVN_ID>
 * @package    com_tmt
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

var tjdashContentService = {
	postData: function(url, formData, params) {
		if(!params){
			params = {};
		}

		params['url']		= url;
		params['data'] 		= formData;
		params['type'] 		= typeof params['type'] != "undefined" ? params['type'] : 'POST';
		params['dataType'] 	= typeof params['datatype'] != "undefined" ? params['datatype'] : 'json';

		var promise = jQuery.ajax(params);
		return promise;
	}
}
