<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-04-13 21:58:01
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-13 22:03:53
 */

	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';

	if (isset($_GET['token']) && !empty($_GET['token'])) {

		if (verifyUser($_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {

					if (isset($_GET['id'])) {

						$id = clean($conn, $_GET['id']);
						
						$shapes = query($conn, "SELECT * FROM shape WHERE id = '$id'") or exit( http_response_code(500) );

						if (num_rows($shapes) > 0) {
							
							foreach ($shapes as $key => $shape) {
								$payload[] = array(
									'id' => $shape['id'],
									'name' => $shape['name'],
									'code' => $shape['code']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else {

						$shapes = query($conn, "SELECT * FROM shape") or exit( http_response_code(500) );

						if (num_rows($shapes) > 0) {

							foreach ($shapes as $key => $shape) {

								$payload[] = array(
									'id' => $shape['id'],
									'name' => $shape['name'],
									'code' => $shape['code']
								);

							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					}

			} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

				return http_response_code(200);
			
			}  else { return http_response_code(405); }

		} else { return http_response_code(401); }

	} else { return http_response_code(400); }

?>