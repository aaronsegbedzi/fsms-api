<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-03-13 15:09:52
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-18 00:13:17
 */

	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';

	if (isset($_GET['token']) && !empty($_GET['token'])) {

		if (verifyUser($_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {

				if (isset($_GET['id'])) {
					
					$id = clean($conn, $_GET['id']);
						
					$users = query($conn, "SELECT * FROM user WHERE id = '$id'") or exit( http_response_code(500) );

					if (num_rows($users) > 0) {
						
						foreach ($users as $key => $user) {
							$payload[] = array(
								'id' => $user['id'],
								'username' => $user['username'],
								'firstName' => $user['firstName'],
								'lastName' => $user['lastName'],
								'role' => $user['role'],
								'created_at' => $user['created_at'],
								'updated_at' => $user['updated_at'],
								'Stationid' => $user['Stationid']
							);
						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				} else if (isset($_GET['firstName'])) {

					$firstName = clean($conn, $_GET['firstName']);
						
					$users = query($conn, "SELECT * FROM user WHERE firstName = '$firstName'") or exit( http_response_code(500) );

					if (num_rows($users) > 0) {
						
						foreach ($users as $key => $user) {
							$payload[] = array(
								'id' => $user['id'],
								'username' => $user['username'],
								'firstName' => $user['firstName'],
								'lastName' => $user['lastName'],
								'role' => $user['role'],
								'created_at' => $user['created_at'],
								'updated_at' => $user['updated_at'],
								'Stationid' => $user['Stationid']
							);
						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				} else if (isset($_GET['lastName'])) {

					$lastName = clean($conn, $_GET['lastName']);
						
					$users = query($conn, "SELECT * FROM user WHERE lastName = '$lastName'") or exit( http_response_code(500) );

					if (num_rows($users) > 0) {
						
						foreach ($users as $key => $user) {
							$payload[] = array(
								'id' => $user['id'],
								'username' => $user['username'],
								'firstName' => $user['firstName'],
								'lastName' => $user['lastName'],
								'role' => $user['role'],
								'created_at' => $user['created_at'],
								'updated_at' => $user['updated_at'],
								'Stationid' => $user['Stationid']
							);
						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				} else if (isset($_GET['role'])) {

					$role = clean($conn, $_GET['role']);
						
					$users = query($conn, "SELECT * FROM user WHERE role = '$role'") or exit( http_response_code(500) );

					if (num_rows($users) > 0) {
						
						foreach ($users as $key => $user) {
							$payload[] = array(
								'id' => $user['id'],
								'username' => $user['username'],
								'firstName' => $user['firstName'],
								'lastName' => $user['lastName'],
								'role' => $user['role'],
								'created_at' => $user['created_at'],
								'updated_at' => $user['updated_at'],
								'Stationid' => $user['Stationid']
							);
						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				} else if (isset($_GET['MdataTable'])) {

					$Stationid = clean($conn, $_GET['Stationid']);

					$users = query($conn, "SELECT *, (SELECT station.location FROM station WHERE station.id = user.Stationid) AS 'location' FROM user WHERE user.Stationid = '$Stationid' AND user.role = '2'") or exit( http_response_code(500) );

					if (num_rows($users) > 0) {

						foreach ($users as $key => $user) {

							$payload[] = array(
								'id' => $user['id'],
								'username' => $user['username'],
								'firstName' => $user['firstName'],
								'lastName' => $user['lastName'],
								'role' => $user['role'],
								'created_at' => $user['created_at'],
								'updated_at' => $user['updated_at'],
								'Stationid' => $user['Stationid'],
								'location' => $user['location']
								
							);

						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }
				
				} else if (isset($_GET['Stationid'])) {

					$Stationid = clean($conn, $_GET['Stationid']);
						
					$users = query($conn, "SELECT * FROM user WHERE Stationid = '$Stationid'") or exit( http_response_code(500) );

					if (num_rows($users) > 0) {
						
						foreach ($users as $key => $user) {
							$payload[] = array(
								'id' => $user['id'],
								'username' => $user['username'],
								'firstName' => $user['firstName'],
								'lastName' => $user['lastName'],
								'role' => $user['role'],
								'created_at' => $user['created_at'],
								'updated_at' => $user['updated_at'],
								'Stationid' => $user['Stationid']
							);
						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				} else if (isset($_GET['dataTable'])) {
				
					$users = query($conn, "SELECT *, (SELECT station.location FROM station WHERE station.id = user.Stationid) AS 'location' FROM user") or exit( http_response_code(500) );

					if (num_rows($users) > 0) {

						foreach ($users as $key => $user) {

							$payload[] = array(
								'id' => $user['id'],
								'username' => $user['username'],
								'firstName' => $user['firstName'],
								'lastName' => $user['lastName'],
								'role' => $user['role'],
								'created_at' => $user['created_at'],
								'updated_at' => $user['updated_at'],
								'Stationid' => $user['Stationid'],
								'location' => $user['location']
								
							);

						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				} else {

					$users = query($conn, "SELECT * FROM user") or exit( http_response_code(500) );

					if (num_rows($users) > 0) {

						foreach ($users as $key => $user) {

							$payload[] = array(
								'id' => $user['id'],
								'username' => $user['username'],
								'firstName' => $user['firstName'],
								'lastName' => $user['lastName'],
								'role' => $user['role'],
								'created_at' => $user['created_at'],
								'updated_at' => $user['updated_at'],
								'Stationid' => $user['Stationid']	
							);

						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				}

			} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				if (isset($_POST['id'])) {
				
					if (isset($_POST['username']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['role'])) {
						
						if (!empty($_POST['id']) || !empty($_POST['username']) || !empty($_POST['firstName']) || !empty($_POST['lastName']) || !empty($_POST['role'])) {

							$id = clean($conn, $_POST['id']);

							$username = clean($conn, $_POST['username']);
							
							$firstName = clean($conn, ucfirst(strtolower($_POST['firstName'])));

							$lastName = clean($conn, ucfirst(strtolower($_POST['lastName'])));

							$role = clean($conn, $_POST['role']);

							$Stationid = clean($conn, $_POST['Stationid']);

							if(query($conn, "UPDATE user SET username = '$username', firstName = '$firstName', lastName = '$lastName', role = '$role', updated_at = NOW(), Stationid = '$Stationid' WHERE id = '$id'")){

								return http_response_code(200);

							} else { return http_response_code(500); }

						} else { return http_response_code(500); }
						
					} else { return http_response_code(400); }

				} else {

					if (isset($_POST['username']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['role']) && isset($_POST['Stationid'])) {
						
						if (!empty($_POST['username']) || !empty($_POST['firstName']) || !empty($_POST['lastName']) || !empty($_POST['role']) || !empty($_POST['Stationid'])) {

							$username = clean($conn, $_POST['username']);
							
							$firstName = clean($conn, ucfirst(strtolower($_POST['firstName'])));

							$lastName = clean($conn, ucfirst(strtolower($_POST['lastName'])));

							$role = clean($conn, ucfirst(strtolower($_POST['role'])));

							$Stationid = clean($conn, ucfirst(strtolower($_POST['Stationid'])));

							$password = password_hash($username, PASSWORD_DEFAULT);

							if(query($conn, "INSERT INTO user(id, username, password, firstName, lastName, change_password, security_question, security_answer, role, created_at, updated_at, Stationid) VALUES (NULL,'$username','$password','$firstName','$lastName','1','','','$role',NOW(),NULL, '$Stationid')")){

								return http_response_code(200);

							} else { return http_response_code(500); }

						} else { return http_response_code(500); }
						
					} else { return http_response_code(400); }
				}

			} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

				if (isset($_GET['id'])) {

					$id = clean($conn, $_GET['id']);

					if (query($conn, "DELETE FROM user WHERE id = '$id'")) {

						return http_response_code(200);
						
					} else { return http_response_code(500); }
					
				} else { return http_response_code(400); }

			} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

				return http_response_code(200);

			}  else { return http_response_code(405); }

		} else { return http_response_code(401); }

	} else { return http_response_code(400); }
	

?>