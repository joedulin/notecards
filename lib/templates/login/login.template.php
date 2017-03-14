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
							<p class="form-control-static">
								<a href="/forgot">Forgot Password</a> | <a href="/signup">Signup</a>
							</p>
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
	$('#password').keypress(function (e) {
		if (e.which == 13) {
			$('#login_button').click();
		}
	});

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
				if (!rcheck(resp)) {
					return false;
				}
				location.href = '/';
			}
		});
	}
</script>
