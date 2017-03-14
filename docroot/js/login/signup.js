$(function () {
	$submit.click(signup);
	$phone.keypress(function (e) {
		if (e.which == 13) {
			$submit.click();
		}
	});
});

function signup() {
	if (!$signup_form[0].checkValidity()) {
		return $fake_submit.click();
	}
	$.ajax({
		url: '/signup',
		type: 'POST',
		data: {
			username: $username.val(),
			password: $password.val(),
			email: $email.val(),
			phone: $phone.val()
		},
		success: function (resp) {
			if (!rcheck(resp)) {
				return false;
			}
			location.href = '/';
		}
	});
}
