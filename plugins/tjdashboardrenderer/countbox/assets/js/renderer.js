var TJDashboardCountbox = {
	renderData: function(method,sourceData)
	{
		this[method](sourceData);
	},
	tjdashcount: function(sourceData)
	{
		var renderData = JSON.parse(sourceData.data);
		var content = "<div class=\"statbox-overlay total-time-spent\"><div class=\"enrolled-courses-label\"><span><strong>"+renderData.data.title+"</strong></span></div><div class=\"enrolled-courses-value\">	"+renderData.data.count+"</div></div>";
		
		jQuery("#"+sourceData.element).html(content);
	}
}
