<?php

$omniscientRoutes->get('/index.php')
	->callback(function()
	{
			header('Location: ' . DEFAULT_URI);
			exit();
	})
	->name('omniscient_default_uri');