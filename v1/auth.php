<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-02-25 11:44:41
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-18 00:14:24
 */

	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';
	require $_SERVER['DOCUMENT_ROOT'].'/v1/vendor/autoload.php';

	use \Firebase\JWT\JWT;

	// Client request method is GET.
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {

		// Check if token value is set.
		if (isset($_GET['token'])) {

			// Check if token value is not empty or null.
			if (!empty($_GET['token'])) {
				
				// Prevent SQL injection from user input.
				$token = clean($conn, $_GET['token']);

				// Decode token and check if valid (not tampered with).
				// If CaughtException Return Unauthorized. 
				try { 

					$token = JWT::decode($token, secreteKey, array('HS256')); 
					
					return http_response_code(200);

				} catch (Exception $e) { return http_response_code(401); }
				
			// Return 400 for Bad Request.
			} else { return http_response_code(400); }
			
		// Return 400 for Bad Request.
		} else { return http_response_code(400); }
		

	// Client request method is POST.
	} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		// Check if username and password values are set.
		if (isset($_POST['username']) && isset($_POST['password'])) {

			// Check if username and password values are not empty or null.
			if (!empty($_POST['username']) && !empty($_POST['password'])) {

				// Prevent SQL injection from user input.
				$username = clean($conn, $_POST['username']);
				$password = clean($conn, $_POST['password']);

				// Query database.
				$users = query($conn, "SELECT * FROM user WHERE username = '$username'") or exit( http_response_code(500) );

				// Check if rows exist.
				if (num_rows($users) > 0) {

					foreach ($users as $key => $user) {

						// Verify hashed password.
						if (password_verify($password, $user['password'])) {

							// Check if account password has to be changed.
							if ($user['change_password'] == true) {

								// Payload for client application.
								$payload = array(
									'username' => $user['username'],
									'redirect' => 'change_password' 
								);

								// Return JSON data to the client application.
								header("Content-type:application/json");
								echo json_encode($payload);


							} else {

								// Payload for client application.
								$payload = array(
									'id' => $user['id'],
									'username' => $user['username'],
									'firstName' => $user['firstName'],
									'lastName' => $user['lastName'],
									'created_at' => $user['created_at'],
									'updated_at' => $user['updated_at'],
									'Stationid' => $user['Stationid'],
									'role' => $user['role'],
									'exp' => time()+24*60*60
								);

								// Return JSON encoded token to the client application.
								try { echo JWT::encode($payload, secreteKey); } 
								catch (UnexpectedValueException $e) { return http_response_code(500); }

							}

						// Return 401 for Unauthorized.
						} else { return http_response_code(401); }

					}
				
				// Return 404 for Not Found.
				} else { return http_response_code(404); }

			// Return 400 for Bad Request.
			} else { return http_response_code(400); }
		
		// Return 400 for Bad Request.
		} else { return http_response_code(400); }
	
	// Return 405 for Request Method Not Allowed.
	} else { return http_response_code(405); }
	
?>