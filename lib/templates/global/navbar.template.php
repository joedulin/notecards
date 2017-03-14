<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main_nav">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span calss="icon-bar"></span>
				<span calss="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/"><?php echo PROJECT_NAME; ?></a>
		</div>
		<div class="collapse navbar-collapse" id="main_nav">
			<ul class="nav navbar-nav navbar-left">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="glyphicon glyphicon-phone-alt"></span>
						<span>Numbers</span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/numbers">My Numbers</a></li>
						<li><a href="/numbers/add">Add Numbers</a></li>
					</ul>
				</li>
				<li>
					<a href="/ma/addresses">
						<span class="glyphicon glyphicon-fire"></span>
						<span>Addresses</span>
					</a>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="glyphicon glyphicon-user"></span>
						<span><?php echo $_SESSION['user']->username; ?></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="/logout">Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
