var TJDashboardChartjs = {
	renderData: function(method,sourceData)
	{
		this[method](sourceData);
	},
	tjdashgraph: function(sourceData)
	{
		var renderData = JSON.parse(sourceData.data);
		console.log("test");
		console.log(renderData);
		// var data = {
		// 	    datasets: [{
		// 	        data: [10, 20, 30]
		// 	    }],

		// 	    // These labels appear in the legend and in the tooltips when hovering different arcs
		// 	    labels: [
		// 	        'Red',
		// 	        'Yellow',
		// 	        'Blue'
		// 	    ]
		// 	};

		var elementdiv = Math.random();
		jQuery('#'+sourceData.element).html('<canvas id='+elementdiv+'></canvas>');
		var ctx = document.getElementById(elementdiv);
		
		// var myDoughnutChart = new Chart(ctx, {
		// 				    type: 'doughnut',
		// 				    data: data,
		// 				    options: []
		// 				});
				
		new Chart(ctx, renderData.data);	

	}
}
