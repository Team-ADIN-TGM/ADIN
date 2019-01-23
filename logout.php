<?php
	session_start();
	
	session_unset();
	session_destroy();
	http_redirect("home/");
?>