<?php
	function isManager($token) {
		global $db;
		return $db->selectWhere('admins',[
			[
				'token'=>$token,
				'cn'=>'='
			]
		])->num_rows;
	}
	// function errorHandler($errno, $errstr, $errfile, $errline) {
	// 	echo '<b>Custom error[' . $errno . ']:</b>' . $errstr . '<br/>On line ' . $errline . ' in ' . $errfile . "<br/>";
	// }
	// set_error_handler('errorHandler');
?>