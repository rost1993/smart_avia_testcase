$(document).ready(function() { 
	'use strict';

	var attemp = 0;

	$('#btnGenerate').click(function() {
		if($('#btnGenerate').hasClass('disabled'))
			return;

		attemp += 1;
		console.log(attemp);

		if(attemp >= 3) {
			$('#btnGenerate').addClass('disabled');
		}

		if(attemp > 3) {
			return;
		}

		var table = $('#tableResult');
		var index = $(table).find('tr').length;

		table.append("<tr id='tr_" + index + "'>"
			+ "<td>" + index + "</td>"
			+ "<td class='generate-status'><span class='fa fa-spinner fa-spin'></span></td>"
			+ "<td class='text-left generate-result'></td>"
			+ "</tr>");

		$.ajax({
			type: 'POST',
			url: '/generate',
			cache: true,
			processData: true,
			contentType: 'application/x-www-form-urlencoded',
			dataType: 'json',
			
			success: function(data) {
				try {
					if(data.error) {
						$(table).find('#tr_' + index).find('.generate-result').html("<strong>Info:</strong> <em>" + data.error + "</em>");
						$(table).find('#tr_' + index).find('.generate-status').html("<span class='fa fa-warning text-primary'></span>");
					} else {
						$(table).find('#tr_' + index).find('.generate-result').html(
							  "<strong>addr:</strong> <em>" + data.addr + "</em>; "
							+ "<strong>pkey:</strong> <em>" + data.pkey + "</em>");
						$(table).find('#tr_' + index).find('.generate-status').html("<span class='fa fa-thumbs-up text-success'></span>");
					}
				} catch(e) {
					$(table).find('#tr_' + index).find('.generate-result').html("<strong>Неопределенная ошибка!</strong>");
					$(table).find('#tr_' + index).find('.generate-status').html("<span class='fa fa-thumbs-down text-danger'></span>");
				}

				attemp -= 1;

				if(attemp < 3)
					$('#btnGenerate').removeClass('disabled');
			},
			
			error: function(data, status, xhr) {
				console.log(data);

				try {
					if(data.responseText) {
						$(table).find('#tr_' + index).find('.generate-result').html("<strong>Error:</strong> <em>" + data.responseText + "</em>");
						$(table).find('#tr_' + index).find('.generate-status').html("<span class='fa fa-thumbs-down text-danger'></span>");
					} else if(data.status !== 200) {
						$(table).find('#tr_' + index).find('.generate-result').html("<strong>Error:</strong> <em>" + data.statusText + "</em>");
						$(table).find('#tr_' + index).find('.generate-status').html("<span class='fa fa-thumbs-down text-danger'></span>");
					}
				} catch(e) {
					$(table).find('#tr_' + index).find('.generate-result').html("<strong>Неопределенная ошибка!</strong>");
					$(table).find('#tr_' + index).find('.generate-status').html("<span class='fa fa-thumbs-down text-danger'></span>");
				}

				attemp -= 1;

				if(attemp < 3)
					$('#btnGenerate').removeClass('disabled');
			},
		});
	});
});