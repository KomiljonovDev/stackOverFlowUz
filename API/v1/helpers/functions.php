<?php
	function isManager($token) {
		global $db;
		$manager = $db->selectWhere('manager',[
            [
                'token'=>$token,
                'cn'=>'='
            ],
        ]);
        return ($manager->num_rows) ? true : false;
	}


?>