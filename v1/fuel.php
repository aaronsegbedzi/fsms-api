<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-04-04 15:59:16
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-18 00:13:57
 */

	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';

	if (isset($_GET['token']) && !empty($_GET['token'])) {

		if (verifyUser($_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {

					if (isset($_GET['id'])) {

						$id = clean($conn, $_GET['id']);
						
						$fuels = query($conn, "SELECT * FROM fuel WHERE id = '$id'") or exit( http_response_code(500) );

						if (num_rows($fuels) > 0) {
							
							foreach ($fuels as $key => $fuel) {
								$payload[] = array(
									'id' => $fuel['id'],
									'name' => $fuel['name'],
									'density' => $fuel['density'],
									'unit' => $fuel['unit'],
									'createdAt' => $fuel['createdAt'],
									'updatedAt' => $fuel['updatedAt']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else {

						$fuels = query($conn, "SELECT * FROM fuel") or exit( http_response_code(500) );

						if (num_rows($fuels) > 0) {

							foreach ($fuels as $key => $fuel) {

								$payload[] = array(
									'id' => $fuel['id'],
									'name' => $fuel['name'],
									'density' => $fuel['density'],
									'unit' => $fuel['unit'],
									'createdAt' => $fuel['createdAt'],
									'updatedAt' => $fuel['updatedAt']
								);

							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					}

			} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				if (isset($_POST['id'])) {
					
					if (isset($_POST['name']) && isset($_POST['density']) && isset($_POST['unit'])) {

						if (!empty($_POST['id']) || !empty($_POST['name']) || !empty($_POST['density']) || !empty($_POST['unit'])) {

							$id = clean($conn, $_POST['id']);

							$name = clean($conn, $_POST['name']);

							$density = clean($conn, $_POST['density']);

							$unit = clean($conn, $_POST['unit']);

							if (query($conn, "UPDATE fuel SET name = '$name', density = '$density', unit = '$unit', updatedAt = NOW() WHERE id = '$id'")) {

								return http_response_code(200);

							} else { return http_response_code(500); }
							
						
						} else { return http_response_code(400); }

					} else { return http_response_code(400); }
					
				} else {
					
					if (isset($_POST['name']) && isset($_POST['density']) && isset($_POST['unit'])) {

						if (!empty($_POST['id']) || !empty($_POST['name']) || !empty($_POST['density']) || !empty($_POST['unit'])) {

							$name = clean($conn, $_POST['name']);

							$density = clean($conn, $_POST['density']);

							$unit = clean($conn, $_POST['unit']);

							if (query($conn, "INSERT INTO fuel(id, name, density, unit, createdAt, updatedAt) VALUES (NULL, '$name', '$density', '$unit', NOW(), NULL)")) {

								return http_response_code(200);

							} else { return http_response_code(500); }
							
						} else { return http_response_code(400); }

					} else { return http_response_code(400); }

				}		

			} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

				if (isset($_GET['id'])) {

					$id = clean($conn, $_GET['id']);

					if (query($conn, "DELETE FROM fuel WHERE id = '$id'")) {

						return http_response_code(200);
						
					} else { return http_response_code(500); }
					
				} else { return http_response_code(400); }

			} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

				return http_response_code(200);
			
			}  else { return http_response_code(405); }

		} else { return http_response_code(401); }

	} else { return http_response_code(400); }

?>