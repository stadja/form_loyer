$('#form_loyer').submit(function() {
	$('.alert-error').slideUp();
	$('.alert-success').slideUp();
	$.ajax({
		type: "POST",
		dataType: "json",
		url: '',
		data: $('#form_loyer').serialize(),
		success: function(data) {
			if (data.status == 'error') {
				$('.alert-error').html(data.message);
				$('.alert-error').slideDown();
			} else {
				$('.alert-success').html(data.message);
				$('.alert-success').slideDown();
				$('#form_loyer').slideUp();

				$('#newCalc').css('display', 'block');
			}

		}
	});
	return false;
});

$('#newCalc').click(function() {
	$(this).hide();
	$('#form_loyer').slideDown();
	$('.alert-success').slideUp();
	return false;
});
