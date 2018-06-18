var TJDashboardCountbox = {
	renderData: function(method,sourceData)
	{
		this[method](sourceData);
	},
	tjdashcount: function(sourceData)
	{
		var renderData = JSON.parse(sourceData.data);

		var title = renderData.data.title;
		if (sourceData.params!=undefined){
			var params = sourceData.params;

			if (params.primary_text!=undefined) {
				var title = params.primary_text;
			}
		}
		var content = 		"<div class=\"panel-body\"><div><div class=\"\"><div class=\"huge\">"+renderData.data.count+"</div><div>"+title+"</div></div></a></div></div><div class=\"clearfix\"></div></div>"

		jQuery("#"+sourceData.element).html(content);
	}
}
