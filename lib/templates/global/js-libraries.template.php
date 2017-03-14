<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="/js/bootstrap-notify.js"></script>
<script src="/js/custom.js"></script>

<?php
foreach ($this->jsfiles as $src) {
	printf('<script src="%s"></script>', $src);
}
?>
