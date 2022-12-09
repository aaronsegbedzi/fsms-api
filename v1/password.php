<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-02-28 11:23:21
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-18 00:13:51
 */

	// Include database connection class.
	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if ($_GET['task'] == 'force_change_password') {

			if (isset($_POST['username']) && isset($_POST['opassword']) && isset($_POST['npassword'])  && isset($_POST['cpassword'])) {
				
				if (!empty($_POST['username']) && !empty($_POST['opassword']) && !empty($_POST['npassword'])  && !empty($_POST['cpassword'])) {
					
					$username = clean($conn, $_POST['username']);

					$opassword = clean($conn, $_POST['opassword']);

					$npassword = clean($conn, $_POST['npassword']);
					
					$cpassword = clean($conn, $_POST['cpassword']);

					if ($npassword == $cpassword) {

						$users = query($conn, "SELECT password FROM user WHERE username = '$username'") or exit( http_response_code(500) );

						if (num_rows($users) > 0) {
							
							foreach ($users as $key => $user) {
								
								if (password_verify($opassword, $user['password'])) {
									
									$password = password_hash($npassword, PASSWORD_DEFAULT);

									if(query($conn, "UPDATE user SET password = '$password', change_password = '0' WHERE username = '$username'")){

										return http_response_code(200);

									} else { return http_response_code(500); }

								} else { return http_response_code(401); }

							}

						} else { return http_response_code(404); }

					} else { return http_response_code(400); }	

				} else { return http_response_code(400); }		

			} else { return http_response_code(400); }
		
		} else if ($_GET['task'] == 'change_password') {

			if (verifyUser($_GET['token'])) {

				if (isset($_POST['username']) && isset($_POST['opassword']) && isset($_POST['npassword'])  && isset($_POST['cpassword'])) {
				
					if (!empty($_POST['username']) && !empty($_POST['opassword']) && !empty($_POST['npassword'])  && !empty($_POST['cpassword'])) {
						
						$username = clean($conn, $_POST['username']);

						$opassword = clean($conn, $_POST['opassword']);

						$npassword = clean($conn, $_POST['npassword']);
						
						$cpassword = clean($conn, $_POST['cpassword']);

						if ($npassword == $cpassword) {

							$users = query($conn, "SELECT password FROM user WHERE username = '$username'") or exit( http_response_code(500) );

							if (num_rows($users) > 0) {
								
								foreach ($users as $key => $user) {
									
									if (password_verify($opassword, $user['password'])) {
										
										$password = password_hash($npassword, PASSWORD_DEFAULT);

										if(query($conn, "UPDATE user SET password = '$password' WHERE username = '$username'")){

											return http_response_code(200);

										} else { return http_response_code(500); }

									} else { return http_response_code(401); }

								}

							} else { return http_response_code(404); }

						} else { return http_response_code(400); }	

					} else { return http_response_code(400); }		

				} else { return http_response_code(400); }

			} else { return http_response_code(401); }

		} else if ($_GET['task'] == 'forgot_password') {

			if (isset($_POST['username']) && isset($_POST['security_question']) && isset($_POST['security_answer'])) {
			
				if (!empty($_POST['username']) && !empty($_POST['security_question']) && !empty($_POST['security_answer'])) {
					
					$username = clean($conn, $_POST['username']);
					$security_question = clean($conn, $_POST['security_question']);
					$security_answer = clean($conn, $_POST['security_answer']);

					$users = query($conn, "SELECT username, security_question, security_answer FROM user WHERE username = '$username'") or exit( http_response_code(500) );

					if (num_rows($users) > 0) {
						
						foreach ($users as $key => $user) {
							
							if ($security_question == $user['security_question']) {
								
								if ($security_answer == $user['security_answer']) {
								
									$password_unhashed = mt_rand(10000000, 99999999);
									
									$password = password_hash($password_unhashed, PASSWORD_DEFAULT);

									$update = query($conn, "UPDATE user SET password = '$password', change_password = '1' WHERE username = '$username'") or exit( http_response_code(500) );

									$payload = array(
										'password' => $password_unhashed
									);

									// Return JSON data to the client application.
									header("Content-type:application/json");
									echo json_encode($payload);

								} else { return http_response_code(401); }	

							} else { return http_response_code(401); }	

						}

					} else { return http_response_code(404); }

				} else { return http_response_code(400); }

			} else { return http_response_code(400); }

		} else if ($_GET['task'] == 'security_info') {

			if (verifyUser($_GET['token'])) {
				
				if (isset($_POST['username']) && isset($_POST['security_question']) && isset($_POST['security_answer'])) {
				
					if (!empty($_POST['username']) && !empty($_POST['security_question']) && !empty($_POST['security_answer'])) {
						
						$username = clean($conn, $_POST['username']);

						$security_question = clean($conn, $_POST['security_question']);

						$security_answer = clean($conn, $_POST['security_answer']);

						if (query($conn, "UPDATE user SET security_question = '$security_question', security_answer = '$security_answer' WHERE username = '$username'")) {
							
							return http_response_code(200);

						} else { return http_response_code(500); } 

					} else { return http_response_code(400); }		

				} else { return http_response_code(400); }

			} else { return http_response_code(401); }
			
		} else { return http_response_code(400); }
		
	} else { return http_response_code(405); }

?>