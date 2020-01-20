var TJDashboardFilterbox = {
	renderData: function(method,sourceData)
	{
		this[method](sourceData);
	},
	tjdashfilter: function(sourceData)
	{
		var content = '<div class="panel-body"><div class="row">';

		content += '<form action="#" method="POST" name="searchDashboardForm" id="searchDashboardForm">';

		var renderData = JSON.parse(sourceData.data);

		var filters = renderData.data.filters;
		var filterName = '';

		var widgetTitle = jQuery("#"+sourceData.element).prev().closest('.widget-title').text();

		var content = '<h1 class="col-sm-4 widget-label-title font-500" >';

		var renderData = JSON.parse(sourceData.data);

		if (widgetTitle != '' && widgetTitle != undefined)
		{
			content += widgetTitle ;
		}
		else
		{
			content += renderData.data.widgetlabel;
		}

		content += "</h1>";

		if (typeof filters === "object" && filters !== null)
		{
			jQuery.each(filters, function(index, item) {
				content += '<div class="col-sm-4 pull-right filter-div">';
				jQuery.each(item, function(key, optionval) {

					if(key != filterName){
						content += '<select id="'+key+'" name="filter['+key+']" class="w-100 widget-filters" >';
						filterName = key;
					}

					jQuery(optionval).each(function() {
						content += "<option value='"+this.value +"'>" + this.text + "</option>";
					});

					if(key != filterName){
						content += '</select>';
					}
				});
				content += '</div>';
			});
		}

		content += "</form></div></div>";

		jQuery("#"+sourceData.element).html(content);

		/* IMP : to add chz-done into selects*/
		jQuery(".widget-filters").chosen({disable_search_threshold: 8});
	}
}

/* Code written for remove empty div */
jQuery(document).ready(function() {
	var divRow = 1;
	jQuery(".widget-boxes").each(function() {
		if (jQuery('.dashboard-widget-row-'+divRow).html() == '')
		{
			jQuery('.dashboard-widget-row-'+divRow).remove();
		}
		divRow++;
	});
});
