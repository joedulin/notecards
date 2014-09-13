<div class="col-xs-12">
	<form class="form-horizontal" role="form">
		<div class="form-group">
			<label for="curpassword" class="col-sm-3 col-sm-offset-1 control-label">Current Password</label>
			<div class="col-sm-4">
				<input type="password" id="curpassword" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label for="password1" class="col-sm-3 col-sm-offset-1 control-label">New Password</label>
			<div class="col-sm-4">
				<input type="password" id="password1" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label for="password2" class="col-sm-3 col-sm-offset-1 control-label">Confirm Password</label>
			<div class="col-sm-4">
				<input type="password" id="password2" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-4 col-sm-offset-4">
				<button type="button" class="btn btn-primary" id="update_button">Change Password</button>
			</div>
		</div>
	</form>
</div>

<script>
	$('#update_button').click(change_password);

	function change_password() {
		var curpassword = $('#curpassword').val();
		var password1 = $('#password1').val();
		var password2 = $('#password2').val();
		
		if (!password1 || !password2 || !curpassword) {
			alert('Password fields cannot be blank');
			return false;
		}
		if (password1 != password2) {
			alert('Passwords do not match. Fix that');
			return false;
		}

		$.ajax({
			url: '/account/changepass',
			type: 'POST',
			data: {
				curpass: curpassword,
				password: password1
			},
			success: function (resp) {
				if (resp.status != 0) {
					alert('Unable to change password');
					return false;
				}
				alert('Successfully changed password');
			}
		});
	}
</script>
