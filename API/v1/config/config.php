<?php
	include './config/dbConfig.php';

	class db_mysqli extends Dbconfig {

	    public $connectionString;
	    public $dataSet;
	    private $sqlQuery;
	    
	    protected $databaseName;
	    protected $hostName;
	    protected $userName;
	    protected $passCode;

	    function dbmysqli() {
	        $this -> connectionString = NULL;
	        $this -> sqlQuery = NULL;
	        $this -> dataSet = NULL;

	        $dbPara = new Dbconfig();
	        $this -> databaseName = $dbPara -> dbName;
	        $this -> hostName = $dbPara -> serverName;
	        $this -> userName = $dbPara -> userName;
	        $this -> passCode = $dbPara ->passCode;
	        $dbPara = NULL;
	    }
	  	
	    function dbConnect()    {
	    	$dbPara = new Dbconfig();
	        $this -> connectionString = mysqli_connect($dbPara -> serverName,$dbPara -> userName,$dbPara -> passCode, $dbPara -> dbName);
	        return $this -> connectionString;
	    }

	    function dbDisconnect() {
	        $this -> connectionString = NULL;
	        $this -> sqlQuery = NULL;
	        $this -> dataSet = NULL;
	        $this -> databaseName = NULL;
	        $this -> hostName = NULL;
	        $this -> userName = NULL;
	        $this -> passCode = NULL;
	    }

	    function selectAll($tableName)  {
	        $this -> sqlQuery = 'SELECT * FROM ' . $tableName;
	        $this -> dataSet = mysqli_query($this -> connectionString,$this -> sqlQuery);
	        return $this -> dataSet;
	    }
	    function selectWhere($tableName,$conditions, $extra="")   {
	        $this -> sqlQuery = 'SELECT * FROM '.$tableName.' WHERE ';
	        if (gettype($conditions) == "array") {
	        	foreach ($conditions as $keys => $values) {
		        	foreach ($values as $key => $value) {
		        		if ($key !== 'cn') {
		        			$this -> sqlQuery .= mysqli_real_escape_string($this -> connectionString, $key) . " " . $values['cn'] . "'";
			        		$this -> sqlQuery .= mysqli_real_escape_string($this -> connectionString, $values[$key]);
			        		$this -> sqlQuery .= "' and ";
		        		}
		        	}
		        }
		        $this -> sqlQuery = substr($this -> sqlQuery, 0,strlen($this -> sqlQuery)-4);
	        }else{
	        	$this -> sqlQuery .= $conditions;
	        }
	        $this -> sqlQuery .= $extra;
	        $this -> dataSet = mysqli_query($this -> connectionString, $this -> sqlQuery);
	        $this -> sqlQuery = NULL;
	        return $this -> dataSet;
	        // return $this -> sqlQuery;
	    }

	    function insertInto($tableName,$values=[]) {
	        $this -> sqlQuery = 'INSERT INTO '.$tableName;
	        $columns = "(";
	        $VALUES = "(";
	        foreach ($values as $key => $value) {
	        	$columns .= mysqli_real_escape_string($this -> connectionString,$key) . ',';
	        	$VALUES .= "'";
	        	$VALUES .= mysqli_real_escape_string($this -> connectionString,$value);
	        	$VALUES .= "',";
	        }
	        $columns = substr($columns, 0,strlen($columns)-1);
	        $VALUES = substr($VALUES, 0,strlen($VALUES)-1);
	        $columns .= ")";
	        $VALUES .= ")";
	        $this -> sqlQuery .= $columns . " VALUES " . $VALUES;
	        mysqli_query($this ->connectionString,$this -> sqlQuery);
	        return $this -> sqlQuery;
	    }
	    function update($tableName,$values=[], $conditions=[],$extra="") {
	        $this -> sqlQuery = 'UPDATE '.$tableName . ' SET ';
	        
	        foreach ($values as $key => $value) {
	        	$this -> sqlQuery .=  mysqli_real_escape_string($this -> connectionString,$key);
	        	$this -> sqlQuery .=  "='";
	        	$this -> sqlQuery .=  mysqli_real_escape_string($this -> connectionString,$value);
	        	$this -> sqlQuery .=  "',";
	        }
	        $this -> sqlQuery = substr($this -> sqlQuery, 0,strlen($this -> sqlQuery)-1);
	        $this -> sqlQuery .= " WHERE ";
	        foreach ($conditions as $key => $value) {
	        	if ($key !== 'cn') {
	        		$this -> sqlQuery .= mysqli_real_escape_string($this -> connectionString, $key) . " " . $conditions['cn'] . "'";
	        		$this -> sqlQuery .= mysqli_real_escape_string($this -> connectionString, $conditions[$key]);
	        		$this -> sqlQuery .= "' and ";
	        	}
	        }
	        $this -> sqlQuery = substr($this -> sqlQuery, 0,strlen($this -> sqlQuery)-4);
	        $this -> sqlQuery .= $extra;
	        return mysqli_query($this ->connectionString,$this -> sqlQuery);
	        // return $this -> sqlQuery;
	    }
	    function delete($tableName,$conditions=[])   {
	    	$this -> sqlQuery = 'DELETE FROM '.$tableName.' WHERE ';
	    	foreach ($conditions as $keys => $values) {
	        	foreach ($values as $key => $value) {
		          	if ($key !== 'cn') {
		            	$this -> sqlQuery .= mysqli_real_escape_string($this -> connectionString, $key) . " " . $values['cn'] . "'";
		            	$this -> sqlQuery .= mysqli_real_escape_string($this -> connectionString, $values[$key]);
		            	$this -> sqlQuery .= "' and ";
		          	}
	        	}
	      	}
	      	$this -> sqlQuery = substr($this -> sqlQuery, 0,strlen($this -> sqlQuery)-4);
	      	$this -> dataSet = mysqli_query($this -> connectionString, $this -> sqlQuery);
	      	// $this -> sqlQuery = NULL;
	      	return $this -> dataSet;
	      	// return $this -> sqlQuery;
      	}
	    function selectFreeRun($query) {
	        $this -> dataSet = mysqli_query($query,$this -> connectionString);
	        return $this -> dataSet;
	    }

	    function freeRun($query) {
	        return mysqli_query($query,$this -> connectionString);
	    }
	}

	trait Helper
	{
		protected $projectApiPATH = 'https://komiljonovdev.uz/okdeveloper/sites/stackOverFlowUz/API/';
		public $data;

		public function Help()
		{
			$this->data = array();
			$this->data['ok'] = false; 
			$this->data['code'] = null; 
			$this->data['message'] = null; 
			$this->data['result'] = [];
		}
		
		public function extract($requests)
		{
			foreach ($requests as $key => $value) {
				$this->$key = $value;
			}
		}
		public function isManager($token) {
			return $this->selectWhere('admins',[
				[
					'token'=>$token,
					'cn'=>'='
				]
			])->num_rows;
		}
		public function isUser($token) {
			return $this->selectWhere('users',[
				[
					'token'=>$token,
					'cn'=>'='
				]
			])->num_rows;
		}
		public function checkPassword($pwd, $errors) {
		    $errors_init = $errors;

		    if (strlen($pwd) < 8) {
		        $errors[] = "Password too short!";
		    }

		    if (!preg_match("#[0-9]+#", $pwd)) {
		        $errors[] = "Password must include at least one number!";
		    }

		    if (!preg_match("#[a-z]+#", $pwd)) {
		        $errors[] = "Password must include at least one lower case letter!";
		    }

		    if (!preg_match("#[A-Z]+#", $pwd)) {
		        $errors[] = "Password must include at least one upper case letter!";
		    }
		    if ($errors) {
			    $this->data['ok'] = false;
			    $this->data['code'] = 401;
			    $this->data['message'] = 'password is invalid';
			    $this->data['errors'] = $errors;
		    }
		    return ($errors == $errors_init) ? ['ok'=>true,'result'=>[]] : ['ok'=>false,'result'=>$errors];
		}
	}
?>