<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-04-05 20:33:43
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-18 00:13:08
 */

	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';

	if (isset($_GET['token']) && !empty($_GET['token'])) {

		if (verifyUser($_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {

					if (isset($_GET['id'])) {

						$id = clean($conn, $_GET['id']);
						
						$tanktypes = query($conn, "SELECT * FROM tanktype WHERE id = '$id'") or exit( http_response_code(500) );

						if (num_rows($tanktypes) > 0) {
							
							foreach ($tanktypes as $key => $tanktype) {
								$payload[] = array(
									'id' => $tanktype['id'],
									'name' => $tanktype['name'],
									'length' => $tanktype['length'],
									'width' => $tanktype['width'],
									'height' => $tanktype['height'],
									'diameter' => $tanktype['diameter'],
									'radius1' => $tanktype['radius1'],
									'radius2' => $tanktype['radius2'],
									'Shapeid' => $tanktype['Shapeid'],
									'createdAt' => $tanktype['createdAt'],
									'updatedAt' => $tanktype['updatedAt']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else if (isset($_GET['name'])) {

						$name = clean($conn, $_GET['name']);
						
						$tanktypes = query($conn, "SELECT * FROM tanktype WHERE name = '$name'") or exit( http_response_code(500) );

						if (num_rows($tanktypes) > 0) {
							
							foreach ($tanktypes as $key => $tanktype) {
								$payload[] = array(
									'id' => $tanktype['id'],
									'name' => $tanktype['name'],
									'length' => $tanktype['length'],
									'width' => $tanktype['width'],
									'height' => $tanktype['height'],
									'diameter' => $tanktype['diameter'],
									'radius1' => $tanktype['radius1'],
									'radius2' => $tanktype['radius2'],
									'Shapeid' => $tanktype['Shapeid'],
									'createdAt' => $tanktype['createdAt'],
									'updatedAt' => $tanktype['updatedAt']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else {

						$tanktypes = query($conn, "SELECT * FROM tanktype") or exit( http_response_code(500) );

						if (num_rows($tanktypes) > 0) {

							foreach ($tanktypes as $key => $tanktype) {

								$payload[] = array(
									'id' => $tanktype['id'],
									'name' => $tanktype['name'],
									'length' => $tanktype['length'],
									'width' => $tanktype['width'],
									'height' => $tanktype['height'],
									'diameter' => $tanktype['diameter'],
									'radius1' => $tanktype['radius1'],
									'radius2' => $tanktype['radius2'],
									'Shapeid' => $tanktype['Shapeid'],
									'createdAt' => $tanktype['createdAt'],
									'updatedAt' => $tanktype['updatedAt']
								);

							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					}

			} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				if (isset($_POST['id'])) {
					
					if (isset($_POST['name']) && isset($_POST['length']) && isset($_POST['width']) && isset($_POST['height']) && isset($_POST['diameter']) && isset($_POST['radius1']) && isset($_POST['radius2']) && isset($_POST['Shapeid'])) {

						if (!empty($_POST['id']) || !empty($_POST['name']) || !empty($_POST['length']) || !empty($_POST['width']) || !empty($_POST['height']) || !empty($_POST['diameter']) || !empty($_POST['radius1']) || !empty($_POST['radius2']) || !empty($_POST['Shapeid'])) {

							$id = clean($conn, $_POST['id']);

							$name = clean($conn, $_POST['name']);

							$length = clean($conn, $_POST['length']);

							$width = clean($conn, $_POST['width']);

							$height = clean($conn, $_POST['height']);

							$diameter = clean($conn, $_POST['diameter']);

							$radius1 = clean($conn, $_POST['radius1']);

							$radius2 = clean($conn, $_POST['radius2']);

							$Shapeid = clean($conn, $_POST['Shapeid']);

							if (query($conn, "UPDATE tankType SET name = '$name', length = '$length', width = '$width', height = '$height', diameter = '$diameter', radius1 = '$radius1', radius2 = '$radius2', Shapeid = '$Shapeid', updatedAt = NOW() WHERE id = '$id'")) {

								return http_response_code(200);

							} else { echo mysqli_error($conn); return http_response_code(500); }
							
						} else { return http_response_code(400); }

					} else { return http_response_code(400); }
					
				} else {
					
					if (isset($_POST['name']) && isset($_POST['length']) && isset($_POST['width']) && isset($_POST['height']) && isset($_POST['diameter']) && isset($_POST['radius1']) && isset($_POST['radius2']) && isset($_POST['Shapeid'])) {

						if (!empty($_POST['name']) || !empty($_POST['length']) || !empty($_POST['width']) || !empty($_POST['height']) || !empty($_POST['diameter']) || !empty($_POST['radius1']) || !empty($_POST['radius2']) || !empty($_POST['Shapeid'])) {

							$name = clean($conn, $_POST['name']);

							$length = clean($conn, $_POST['length']);

							$width = clean($conn, $_POST['width']);

							$height = clean($conn, $_POST['height']);

							$diameter = clean($conn, $_POST['diameter']);

							$radius1 = clean($conn, $_POST['radius1']);

							$radius2 = clean($conn, $_POST['radius2']);

							$Shapeid = clean($conn, $_POST['Shapeid']);

							if (query($conn, "INSERT INTO tankType(id, name, length, width, height, diameter, radius1, radius2, createdAt, updatedAt, Shapeid) VALUES (NULL, '$name', '$length', '$width', '$height', '$diameter', '$radius1', '$radius2', NOW(), NULL, '$Shapeid');")) {

								return http_response_code(200);

							} else { return http_response_code(500); }
							
						} else { return http_response_code(400); }

					} else { return http_response_code(400); }

				}		

			} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

				if (isset($_GET['id'])) {

					$id = clean($conn, $_GET['id']);

					if (query($conn, "DELETE FROM tanktype WHERE id = '$id'")) {

						return http_response_code(200);
						
					} else { return http_response_code(500); }
					
				} else { return http_response_code(400); }

			} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

				return http_response_code(200);
			
			}  else { return http_response_code(405); }

		} else { return http_response_code(401); }

	} else { return http_response_code(400); }

?>