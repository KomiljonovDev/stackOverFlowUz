<?php
	// error_reporting(0);
	// header('Access-Control-Allow-Origin: *');
	// header('Content-Type: application/json');
	// date_default_timezone_set('Asia/Tashkent');

	// $data = ['ok'=>false, 'code'=>null, 'message'=>null, 'result'=>[]];
	// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// 	require './config/config.php';
	// 	$db = new dbmysqli;
 //    	$db->dbConnect();
 //    	require './helpers/functions.php';
	// 	extract($_REQUEST);
	// 	$action = strtolower(trim(getenv('ORIG_PATH_INFO') ? : getenv('PATH_INFO'), '/'));
	// 	if ($action == 'managerlogin') {
	// 		if (isset($login) && isset($password)) {
	// 			$password = md5($password);
	// 			$manager = $db->selectWhere('manager',[
	// 				[
	// 					'login'=>$login,
	// 					'cn'=>'='
	// 				],
	// 				[
	// 					'pass_word'=>$password,
	// 					'cn'=>'='
	// 				],
	// 			]);
	// 			if ($manager->num_rows) {
	// 				$manager = mysqli_fetch_assoc($manager);
	// 				if (md5($manager['pass_word']) == md5($password)) {
	// 					$data['ok'] = true;
	// 					$data['code'] = 200;
	// 					$data['message'] = 'Login successfully.';
	// 					foreach ($manager as $key => $value) $data['result'][$key] = $value;
	// 				}
	// 			}else{
	// 				$data['code'] = 402;
 //                	$data['message'] = 'login or password are invalid.';
	// 			}
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'login and password are required.';
	// 		}
	// 	}else if ($action == 'addsection') {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				if (isset($name)) {
	// 					$description = ($description) ? $description : '';
	// 					$add_section = $db->insertInto('sections',[
	// 						'name'=>$name,
	// 						'des'=>$description,
	// 						'date'=>strtotime('now')
	// 					]);
	// 					if ($add_section) {
	// 						$data['ok'] = true;
	// 						$data['code'] = 200;
	// 						$data['message'] = 'Section added successfully';
	// 						$sections = $db->selectAll('sections');
	// 						foreach ($sections as $key => $value) $data['result'][$key] = $value;
	// 					}else{
	// 						$data['code'] = 500;
	// 						$data['message'] = 'Set interval error';
	// 					}
	// 				}else{
	// 					$data['code'] = 402;
 //               			$data['message'] = 'Section name (name) is required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if ($action == 'addteacher') {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				if (isset($section_id) && isset($name) && isset($lastname)) {
	// 					$section = $db->selectWhere('sections',[
	// 						[
	// 							'id'=>$section_id,
	// 							'cn'=>'='
	// 						]
	// 					]);
	// 					if ($section->num_rows) {
	// 						$description = ($description) ? $description : '';
	// 						$addTeacher = $db->insertInto('teachers',[
	// 							'section_id'=>$section_id,
	// 							'name'=>$name,
	// 							'lastname'=>$lastname,
	// 							'des'=>$description,
	// 							'date'=>strtotime('now')
	// 						]);
	// 						if ($addTeacher) {
	// 							$data['ok'] = true;
	// 							$data['code'] = 200;
	// 							$data['message'] = 'teacher added successfully';
	// 							$teachers = $db->selectAll('teachers');
	// 							foreach ($sections as $key => $value) $data['result'][$key] = $value;
	// 						}else{
	// 							$data['code'] = 500;
	// 							$data['message'] = 'Set interval error.';
	// 						}
	// 					}else{
	// 						$data['code'] = 403;
 //                			$data['message'] = 'section_id is invalid';
	// 					}
	// 				}else{
	// 					$data['code'] = 402;
 //                		$data['message'] = 'section_id,name,lastname are required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if ($action == 'newgroup') {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				if (isset($section_id) && isset($teacher_id) && isset($name)) {
	// 					$section = $db->selectWhere('sections',[
	// 						[
	// 							'id'=>$section_id,
	// 							'cn'=>'='
	// 						]
	// 					]);
	// 					if ($section->num_rows) {
	// 						$teacher = $db->selectWhere('teachers',[
	// 							[
	// 								'id'=>$teacher_id,
	// 								'cn'=>'='
	// 							]
	// 						]);
	// 						if ($teacher->num_rows) {
	// 							$description = ($description) ? $description : '';
	// 							$newgroup = $db->insertInto('groups',[
	// 								'section_id'=>$section_id,
	// 								'teacher_id'=>$teacher_id,
	// 								'name'=>$name,
	// 								'des'=>$description,
	// 								'date'=>strtotime('now')
	// 							]);
	// 							if ($newgroup) {
	// 								$data['ok'] = true;
	// 								$data['code'] = 200;
	// 								$data['message'] = 'New group added successfully';
	// 								$groups = $db->selectAll('groups');
	// 								foreach ($groups as $key => $value) $data['result'][$key] = $value;
	// 							}else{
	// 								$data['code'] = 500;
	// 								$data['message'] = 'Set interval error.';
	// 							}
	// 						}else{
	// 							$data['code'] = 403;
	//                 			$data['message'] = 'teacher_id is invalid';
	// 						}
	// 					}else{
	// 						$data['code'] = 403;
 //                			$data['message'] = 'section_id is invalid';
	// 					}
	// 				}else{
	// 					$data['code'] = 402;
 //                		$data['message'] = 'section_id,teacher_id,name are required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if ($action == 'addstudent') {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				if (isset($group_id) && isset($fullname) && isset($address) && isset($school_name) && isset($phone)) {
	// 					$group = $db->selectWhere('groups',[
	// 						[
	// 							'id'=>$group_id,
	// 							'cn'=>'='
	// 						]
	// 					]);
	// 					if ($group->num_rows) {
	// 						$description = ($description) ? $description : '';
	// 						$addstudent = $db->insertInto('students',[
	// 							'group_id'=>$group_id,
	// 							'fullname'=>$fullname,
	// 							'address'=>$address,
	// 							'school_name'=>$school_name,
	// 							'phone'=>$phone,
	// 							'des'=>$description,
	// 							'date'=>strtotime('now')
	// 						]);
	// 						if ($addstudent) {
	// 							$data['ok'] = true;
	// 							$data['code'] = 200;
	// 							$data['message'] = 'Student successfully added';
	// 							$students = $db->selectAll('students');
	// 							foreach ($students as $key => $value) $data['result'][$key] = $value;
	// 						}else{
	// 							$data['code'] = 500;
	// 							$data['message'] = 'Set interval error.';
	// 						}
							
	// 					}else{
	// 						$data['code'] = 403;
 //                			$data['message'] = 'group_id is invalid';
	// 					}
	// 				}else{
	// 					$data['code'] = 402;
 //                		$data['message'] = 'group_id,fullname,address,school_name and phone are required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if ($action == 'updatemanagerdata') {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				$manager = $db->selectWhere('manager',[
	// 					[
	// 						'token'=>$token,
	// 						'cn'=>"="
	// 					]
	// 				]);
	// 				$manager = mysqli_fetch_assoc($manager);
	// 				$name = ($name) ? $name : $manager['name'];
	// 				$lastname = ($lastname) ? $lastname : $manager['lastname'];
	// 				$login = ($login) ? $login : $manager['login'];
	// 				$password = ($password) ? md5($password) : $manager['pass_word'];
	// 				$db->update('manager',[
	// 					'name'=>$name,
	// 					'lastname'=>$lastname,
	// 					'login'=>$login,
	// 					'pass_word'=>$password,
	// 				],[
	// 					'token'=>$token,
	// 					'cn'=>"="
	// 				]);
	// 				$manager = $db->selectWhere('manager',[
	// 					[
	// 						'token'=>$token,
	// 						'cn'=>"="
	// 					]
	// 				]);
	// 				$manager = mysqli_fetch_assoc($manager);
	// 				$data['ok'] = true;
	// 				$data['code'] = 200;
	// 				$data['message'] = 'Manager data successfully updated';
	// 				foreach ($manager as $key => $value) $data['result'][$key] = $value;
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if ($action == 'updatesectiondata') {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				if (isset($section_id)) {
	// 					$section = $db->selectWhere('sections',[
	// 						[
	// 							'id'=>$section_id,
	// 							'cn'=>"="
	// 						]
	// 					]);
	// 					if ($section->num_rows) {
	// 						$section = mysqli_fetch_assoc($section);
	// 						$name = ($name) ? $name : $section['name'];
	// 						$description = ($description) ? $description : $section['des'];
	// 						$db->update('sections',[
	// 							'name'=>$name,
	// 							'des'=>$description,
	// 						],[
	// 							'id'=>$section_id,
	// 							'cn'=>"="
	// 						]);
	// 						$section = $db->selectWhere('sections',[
	// 							[
	// 								'id'=>$section_id,
	// 								'cn'=>"="
	// 							]
	// 						]);
	// 						$section = mysqli_fetch_assoc($section);
	// 						$data['ok'] = true;
	// 						$data['code'] = 200;
	// 						$data['message'] = 'Section data successfully updated';
	// 						foreach ($section as $key => $value) $data['result'][$key] = $value;
	// 					}else{
	// 						$data['code'] = 403;
 //                			$data['message'] = 'section_id is invalid';
	// 					}
	// 				}else{
	// 					$data['code'] = 402;
 //                		$data['message'] = 'section_id is required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if ($action == 'updateteacherdata') {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				if (isset($teacher_id)) {
	// 					$teacher = $db->selectWhere('teachers',[
	// 						[
	// 							'id'=>$teacher_id,
	// 							'cn'=>"="
	// 						]
	// 					]);
	// 					if ($teacher->num_rows) {
	// 						$teacher = mysqli_fetch_assoc($teacher);
	// 						$name = ($name) ? $name : $teacher['name'];
	// 						$description = ($description) ? $description : $teacher['des'];
	// 						$db->update('teachers',[
	// 							'name'=>$name,
	// 							'des'=>$description,
	// 						],[
	// 							'id'=>$teacher_id,
	// 							'cn'=>"="
	// 						]);
	// 						$teacher = $db->selectWhere('teachers',[
	// 							[
	// 								'id'=>$teacher_id,
	// 								'cn'=>"="
	// 							]
	// 						]);
	// 						$teacher = mysqli_fetch_assoc($teacher);
	// 						$data['ok'] = true;
	// 						$data['code'] = 200;
	// 						$data['message'] = 'Teacher data successfully updated';
	// 						foreach ($teacher as $key => $value) $data['result'][$key] = $value;
	// 					}else{
	// 						$data['code'] = 403;
 //                			$data['message'] = 'teacher_id is invalid';
	// 					}
	// 				}else{
	// 					$data['code'] = 402;
 //                		$data['message'] = 'teacher_id is required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if ($action == 'updategroupdata') {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				if (isset($group_id)) {
	// 					$group = $db->selectWhere('groups',[
	// 						[
	// 							'id'=>$group_id,
	// 							'cn'=>"="
	// 						]
	// 					]);
	// 					if ($group->num_rows) {
	// 						$group = mysqli_fetch_assoc($group);
	// 						$section_id = ($section_id) ? $section_id : $group['section_id'];
	// 						$teacher_id = ($teacher_id) ? $teacher_id : $group['teacher_id'];
	// 						$name = ($name) ? $name : $group['name'];
	// 						$description = ($description) ? $description : $group['des'];
	// 						$db->update('groups',[
	// 							'section_id'=>$section_id,
	// 							'teacher_id'=>$teacher_id,
	// 							'name'=>$name,
	// 							'des'=>$description,
	// 						],[
	// 							'id'=>$group_id,
	// 							'cn'=>"="
	// 						]);
	// 						$group = $db->selectWhere('groups',[
	// 							[
	// 								'id'=>$group_id,
	// 								'cn'=>"="
	// 							]
	// 						]);
	// 						$group = mysqli_fetch_assoc($group);
	// 						$data['ok'] = true;
	// 						$data['code'] = 200;
	// 						$data['message'] = 'Group data successfully updated';
	// 						foreach ($group as $key => $value) $data['result'][$key] = $value;
	// 					}else{
	// 						$data['code'] = 403;
 //                			$data['message'] = 'group_id is invalid';
	// 					}
	// 				}else{
	// 					$data['code'] = 402;
 //                		$data['message'] = 'group_id is required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if ($action == 'updatestudentdata') {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				if (isset($student_id)) {
	// 					$student = $db->selectWhere('students',[
	// 						[
	// 							'id'=>$student_id,
	// 							'cn'=>"="
	// 						]
	// 					]);
	// 					if ($student->num_rows) {
	// 						$student = mysqli_fetch_assoc($student);
	// 						$group_id = ($group_id) ? $group_id : $student['group_id'];
	// 						$fullname = ($fullname) ? $fullname : $student['fullname'];
	// 						$address = ($address) ? $address : $student['address'];
	// 						$school_name = ($school_name) ? $school_name : $student['school_name'];
	// 						$phone = ($phone) ? $phone : $student['phone'];
	// 						$description = ($description) ? $description : $student['des'];
	// 						$db->update('students',[
	// 							'group_id'=>$group_id,
	// 							'fullname'=>$fullname,
	// 							'address'=>$address,
	// 							'school_name'=>$school_name,
	// 							'des'=>$description,
	// 						],[
	// 							'id'=>$student_id,
	// 							'cn'=>"="
	// 						]);
	// 						$student = $db->selectWhere('students',[
	// 							[
	// 								'id'=>$student_id,
	// 								'cn'=>"="
	// 							]
	// 						]);
	// 						$student = mysqli_fetch_assoc($student);
	// 						$data['ok'] = true;
	// 						$data['code'] = 200;
	// 						$data['message'] = 'Student data successfully updated';
	// 						foreach ($student as $key => $value) $data['result'][$key] = $value;
	// 					}else{
	// 						$data['code'] = 403;
 //                			$data['message'] = 'student_id is invalid';
	// 					}
	// 				}else{
	// 					$data['code'] = 402;
 //                		$data['message'] = 'student_id is required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if(mb_stripos($action, 'delete/')!==false){
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				$table = explode('delete/', $action)[1];
	// 				if ($table) {
	// 					if (isset($id)) {
	// 						$del = $db->delete($table,[
	// 							[
	// 								'id'=>$id,
	// 								'cn'=>'='
	// 							]
	// 						]);
	// 						$data['ok'] = true;
	// 						$data['code'] = 200;
	// 						$data['message'] = $table . ' successfully deleted';
	// 						$table = $db->selectAll($table);
	// 						foreach ($table as $key => $value) $data['result'][$key] = $value;
	// 					}else{
	// 						$data['code'] = 402;
 //                			$data['message'] = 'id is required';
	// 					}
	// 				}else{
	// 					$data['code'] = 402;
 //                		$data['message'] = 'table name (/*) is required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else if (mb_stripos($action, 'get/')!==false) {
	// 		if (isset($token)) {
	// 			if (isManager($token)) {
	// 				$table = trim(explode('get/', $action)[1]);
	// 				if ($table) {
	// 					$data['ok'] = true;
	// 					$data['code'] = 200;
	// 					$data['message'] = $table . " data successfully gived";
	// 					$table = $db->selectAll($table);
	// 					foreach ($table as $key => $value) $data['result'][$key] = $value;
	// 				}else{
	// 					$data['code'] = 402;
 //                		$data['message'] = 'table name (/*) is required';
	// 				}
	// 			}else{
 //                	$data['code'] = 403;
 //                	$data['message'] = 'token is invalid';
 //                }
	// 		}else{
	// 			$data['code'] = 402;
 //                $data['message'] = 'manager access token (token) is required';
	// 		}
	// 	}else{
	// 		$data['code'] = 401;
 //            $data['message'] = 'Method not found';
	// 	}
	// }else{
	// 	$data['code'] = 400;
	// 	$data['message'] = "Method not allowed. Allowed Method: POST";
	// }
	// unset($data['result']['pass_word']);
	// echo json_encode($data,  JSON_PRETTY_PRINT);
	require_once 'helpers/classes.php';
	$db = new dbmysqli;
	$db->dbConnect();

	$class = new Post;
	print_r($class);
?>