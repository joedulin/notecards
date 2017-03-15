<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
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
						<span class="glyphicon glyphicon-th-list"></span>
						<span>Projects</span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu" id="projects_menu">
					</ul>
				</li>
				<li><p id="project_name" class="navbar-text"></p></li>
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
			<div class="navbar-form navbar-right">
				<div class="form-group">
					<input type="text" id="search" class="form-control disabled" placeholder="Search..">
				</div>
				<button type="button" id="search_submit" class="btn btn-default disabled">Search</button>
			</div>
		</div>
	</div>
</nav>
