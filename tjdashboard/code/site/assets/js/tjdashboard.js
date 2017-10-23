var tjdashboard=
{
	getDashboardData: function(dashboard_id)
	{
		if (dashboard_id > 0)
		{
			jQuery.ajax(
			{
				url:'index.php?option=com_api&app=tjdashboard&resource=dashboard&format=raw',
				type:'GET',
				dataType:'json',
				data: {id: dashboard_id},
				success:function(result)
				{
					if (result.data)
					{
						//~ console.log(result);return false;
						jQuery('<h1><div data-dashboard-id="'+dashboard_id+'" class="tjdashboard-title">' + result.data['title'] + '</div></h1>').appendTo('.tjdashboard');
					}
				},
			});
		}
	}
}
