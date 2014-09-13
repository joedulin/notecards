<div class="col-xs-12" style="position: absolute; top: 20%;">
	<div class="col-sm-4 col-sm-offset-4">
		<div class="panel panel-default">
			<div class="panel-body" style="padding: 2em;">
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label for="username" class="col-sm-4 control-label">Username</label>
						<div class="col-sm-8">
							<input type="text" id="username" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-4 control-label">Password</label>
						<div class="col-sm-8">
							<input type="password" id="password" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-8 col-sm-offset-4">
							<button type="button" class="btn btn-primary" id="login_button">Login</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$('#login_button').click(check_login);

	function check_login() {
		var username = $('#username').val();
		var password = $('#password').val();
		
		$.ajax({
			url: '/login',
			type: 'POST',
			data: {
				username: username,
				password: password
			},
			success: function (resp) {
				if (resp.status == 0) {
					window.location.href = '/';
				} else {
					alert('Invalid credentials');
				}
			}
		});
	}
</script>
