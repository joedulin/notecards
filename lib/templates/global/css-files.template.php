<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/animate.css">
<link rel="stylesheet" href="/css/custom.css">

<?php

foreach ($this->cssfiles as $path) {
	printf('<link rel="stylesheet" href="%s">', $path);
}
