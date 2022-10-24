<?php
	function isManager($token) {
		return $this->selectWhere('admins'[
			[
				'token'=>$token,
				'cn'=>'='
			],
		])->num_rows;
	}
?>