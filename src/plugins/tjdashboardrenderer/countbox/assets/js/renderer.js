var TJDashboardCountbox = {
	renderData: function(method,sourceData)
	{
		this[method](sourceData);
	},
	tjdashcount: function(sourceData)
	{
		var renderData = JSON.parse(sourceData.data);
		var content = 		"<div class=\"panel-body\"><div><div class=\"\"><div class=\"huge\">"+renderData.data.count+"</div><div>"+renderData.data.title+"</div></div></a></div></div><div class=\"clearfix\"></div></div>"
		// "<div class=\"statbox-overlay total-time-spent\"><div class=\"\"><div class=\"enrolled-courses-label\"><span><strong>"+renderData.data.title+"</strong></span></div><div class=\"enrolled-courses-value\">	"+renderData.data.count+"</div></div>";
		jQuery("#"+sourceData.element).html(content);
	}
}
