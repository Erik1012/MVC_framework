function ajax_send_example()
	{
		var data = new FormData();
		data.append('param1', 'param1');
		data.append('param2', 'param2');
		console.log(data);
		jQuery.ajax({
			url: '/?ajax=1',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			type: 'POST',
			success: function(data){
				alert(data);
			}
		});
	}