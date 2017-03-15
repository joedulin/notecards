$(function () {
	$submit.click(signup);
});

function signup() {
	$.ajax({
		url: '/signup',
		type: 'POST',
		data: {
			username: $username.val(),
			password: $password.val()
		},
		success: function (resp) {
			if (!rcheck(resp)) {
				return false;
			}
			location.href = '/';
		}
	});
}
