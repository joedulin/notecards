<!DOCTYPE html>
<html>
	<?php include('head.template.php'); ?>
	<body>
		<?php
			echo '<div id="wrap">';
			if ($this->shownav) {
				include('navbar.template.php');
			}
			echo '<div class="container">';
			if (isset($this->pageheader)) {
				if (isset($this->pagesubheader)) {
					printf('<div class="page-header"><h1>%s <small>%s</small></h1></div>', $this->pageheader, $this->pagesubheader);
				} else {
					printf('<div class="page-header"><h1>%s</h1></div>', $this->pageheader);
				}
			}
			include($page_content_file);
			echo '</div></div>';
			include('foot.template.php');
		?>
	</body>
	<?php include('js-libraries.template.php'); ?>
</html>
