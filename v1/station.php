<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-04-02 13:52:46
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-20 00:16:02
 */

	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';

	if (isset($_GET['token']) && !empty($_GET['token'])) {

		if (verifyUser($_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {

					if (isset($_GET['id'])) {

						$id = clean($conn, $_GET['id']);
						
						$stations = query($conn, "SELECT * FROM station WHERE id = '$id'") or exit( http_response_code(500) );

						if (num_rows($stations) > 0) {
							
							foreach ($stations as $key => $station) {
								$payload[] = array(
									'id' => $station['id'],
									'location' => $station['location'],
									'longitude' => $station['longitude'],
									'latitude' => $station['latitude'],
									'createdAt' => $station['createdAt'],
									'updatedAt' => $station['updatedAt']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else if (isset($_GET['Stationid'])) {

						$Stationid = clean($conn, $_GET['Stationid']);

						$stations = query($conn, "SELECT station.id, station.location, station.latitude, station.longitude, station.updatedAt, station.createdAt, CONCAT_WS(' ', user.firstName, user.lastName) AS 'manager', user.username, (SELECT COUNT(*) FROM user WHERE user.Stationid = station.id AND user.role = '2') AS 'subscribers' FROM station JOIN user ON user.Stationid = station.id WHERE station.id = '$Stationid' AND user.role = '1'") or exit( http_response_code(500) );

						if (num_rows($stations) > 0) {

							foreach ($stations as $key => $station) {

								$payload[] = array(
									'id' => $station['id'],
									'location' => $station['location'],
									'latitude' => $station['latitude'],
									'longitude' => $station['longitude'],
									'manager' => $station['manager'],
									'username' => $station['username'],
									'subscribers' => $station['subscribers'],
									'updatedAt' => $station['updatedAt'],
									'createdAt' => $station['createdAt']
								);

							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else if (isset($_GET['location'])) {

						$location = clean($conn, $_GET['location']);

						$stations = query($conn, "SELECT * FROM station WHERE location = '$location'") or exit( http_response_code(500) );

						if (num_rows($stations) > 0) {

							foreach ($stations as $key => $station) {
								$payload[] = array(
									'id' => $station['id'],
									'location' => $station['location'],
									'longitude' => $station['longitude'],
									'latitude' => $station['latitude'],
									'createdAt' => $station['createdAt'],
									'updatedAt' => $station['updatedAt']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }
						
					} else if (isset($_GET['dataTable'])) {

						$stations = query($conn, "SELECT *, (SELECT COUNT(*) FROM tank WHERE Stationid = station.id) AS 'tankCount', (SELECT CONCAT_WS(' ', user.firstName, user.lastName) FROM user WHERE role = '1' AND Stationid = station.id) AS 'manager', (SELECT COUNT(*) FROM user WHERE role = '2' AND Stationid = station.id) AS 'subscriberCount' FROM station") or exit( http_response_code(500) );

						if (num_rows($stations) > 0) {

							foreach ($stations as $key => $station) {

								$payload[] = array(
									'id' => $station['id'],
									'location' => $station['location'],
									'longitude' => $station['longitude'],
									'latitude' => $station['latitude'],
									'createdAt' => $station['createdAt'],
									'updatedAt' => $station['updatedAt'],
									'manager' => $station['manager'],
									'subscriberCount' => $station['subscriberCount'],
									'tankCount' => $station['tankCount']
								);

							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else {

						$stations = query($conn, "SELECT * FROM station") or exit( http_response_code(500) );

						if (num_rows($stations) > 0) {

							foreach ($stations as $key => $station) {

								$payload[] = array(
									'id' => $station['id'],
									'location' => $station['location'],
									'longitude' => $station['longitude'],
									'latitude' => $station['latitude'],
									'createdAt' => $station['createdAt'],
									'updatedAt' => $station['updatedAt']
								);

							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					}

			} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				if (isset($_POST['id'])) {
					
					if (isset($_POST['location']) && isset($_POST['longitude']) && isset($_POST['latitude'])) {

						if (!empty($_POST['id']) || !empty($_POST['location']) || !empty($_POST['longitude']) || !empty($_POST['latitude'])) {

							$id = clean($conn, $_POST['id']);

							$location = clean($conn, $_POST['location']);

							$longitude = clean($conn, $_POST['longitude']);

							$latitude = clean($conn, $_POST['latitude']);

							if (query($conn, "UPDATE station SET location = '$location', longitude = '$longitude', latitude = '$latitude', updatedAt = NOW() WHERE id = '$id'")) {

								return http_response_code(200);

							} else { return http_response_code(500); }
							
						
						} else { return http_response_code(400); }

					} else { return http_response_code(400); }
					
				} else {
					
					if (isset($_POST['location']) && isset($_POST['longitude']) && isset($_POST['latitude'])) {

						if (!empty($_POST['location']) || !empty($_POST['longitude']) || !empty($_POST['latitude'])) {

							$location = clean($conn, $_POST['location']);

							$longitude = clean($conn, $_POST['longitude']);

							$latitude = clean($conn, $_POST['latitude']);

							if (query($conn, "INSERT INTO station(id, location, latitude, longitude, createdAt, updatedAt) VALUES (NULL, '$location', '$latitude', '$longitude', NOW(), NULL)")) {

								return http_response_code(200);

							} else { return http_response_code(500); }
							
						} else { return http_response_code(400); }

					} else { return http_response_code(400); }

				}		

			} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

				if (isset($_GET['id'])) {

					$id = clean($conn, $_GET['id']);

					if (query($conn, "DELETE FROM station WHERE id = '$id'")) {

						return http_response_code(200);
						
					} else { return http_response_code(500); }
					
				} else { return http_response_code(400); }

			} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

				return http_response_code(200);
			
			}  else { return http_response_code(405); }

		} else { return http_response_code(401); }

	} else { return http_response_code(400); }

?>