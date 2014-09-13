<!DOCTYPE html>
<html>
	<?php include('head.template.php'); ?>
	<body>
		<?php 
			echo '<div id="wrap">';
			if ($usernav) {
				include('navbar.template.php');
			}
			include($page_content_file);
			echo '</div>';
			include('foot.template.php');
		?>
	</body>
</html>
