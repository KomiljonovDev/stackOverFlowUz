<?php

	require_once './config/config.php';

	/**
	 * 
	 */
	class Post extends dbmysqli
	{
		
		function __construct()
		{
			return $this-> connectionString;
		}
	}


?>