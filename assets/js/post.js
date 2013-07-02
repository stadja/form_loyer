$('#form_loyer').submit(function() {
	$('.alert-error').slideUp();
	$('.alert-info').slideUp();
	$.ajax({
		type: "POST",
		dataType: "json",
		url: '',
		data: $('#form_loyer').serialize(),
		success: function(data) {
			console.log(data);
			if (data.status == 'error') {
				$('.alert-error').html(data.message);
				$('.alert-error').slideDown();
			} else {
				$('.alert-info').html(data.message);
				$('.alert-info').slideDown();
				$('#form_loyer').slideUp();
				$('#newCalc').show();
			}

		}
	});
	return false;
});

$('#newCalc').click(function() {
	$(this).hide();
	$('#form_loyer').slideDown();
	$('.alert-info').slideUp();
	return false;
});
