<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-04-05 21:19:58
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-18 00:13:29
 */

	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';

	if (isset($_GET['token']) && !empty($_GET['token'])) {

		if (verifyUser($_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {

					if (isset($_GET['id'])) {

						$id = clean($conn, $_GET['id']);
						
						$tanks = query($conn, "SELECT * FROM tank WHERE id = '$id'") or exit( http_response_code(500) );

						if (num_rows($tanks) > 0) {
							
							foreach ($tanks as $key => $tank) {
								$payload[] = array(
									'id' => $tank['id'],
									'uid' => $tank['uid'],
									'volume' => $tank['volume'],
									'createdAt' => $tank['createdAt'],
									'updatedAt' => $tank['updatedAt'],
									'Stationid' => $tank['Stationid'],
									'Fuelid' => $tank['Fuelid'],
									'TankTypeid' => $tank['TankTypeid']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else if (isset($_GET['Stationid'])) {

						$Stationid = clean($conn, $_GET['Stationid']);

						$tanks = query($conn, "SELECT tank.id, tank.uid, tank.volume, tank.Stationid, station.location, station.latitude, station.longitude, station.updatedAt, station.createdAt, CONCAT_WS(' ', user.firstName, user.lastName) AS 'manager', user.username, (SELECT COUNT(*) FROM user WHERE user.Stationid = tank.Stationid AND user.role = '2') AS 'subscribers' FROM tank JOIN station ON tank.Stationid = station.id JOIN user ON user.Stationid = station.id WHERE tank.Stationid = '$Stationid' AND user.role = '1'") or exit( http_response_code(500) );

						if (num_rows($tanks) > 0) {

							foreach ($tanks as $key => $tank) {

								$payload[] = array(
									'id' => $tank['id'],
									'uid' => $tank['uid'],
									'volume' => $tank['volume'],
									'Stationid' => $tank['Stationid'],
									'location' => $tank['location'],
									'latitude' => $tank['latitude'],
									'longitude' => $tank['longitude'],
									'manager' => $tank['manager'],
									'username' => $tank['username'],
									'subscribers' => $tank['subscribers'],
									'updatedAt' => $tank['updatedAt'],
									'createdAt' => $tank['createdAt']
								);

							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else if (isset($_GET['dataTable'])) {

						$tanks = query($conn, "SELECT *, (SELECT station.location FROM station WHERE station.id = tank.Stationid) AS 'StationLocation', (SELECT fuel.name FROM fuel WHERE fuel.id = tank.Fuelid) AS 'FuelName', (SELECT tanktype.name FROM tanktype WHERE tanktype.id = tank.TankTypeid) AS 'TankTypeName' FROM tank ORDER BY Stationid ASC") or exit( http_response_code(500) );

						if (num_rows($tanks) > 0) {

							foreach ($tanks as $key => $tank) {

								$payload[] = array(
									'id' => $tank['id'],
									'uid' => $tank['uid'],
									'volume' => $tank['volume'],
									'createdAt' => $tank['createdAt'],
									'updatedAt' => $tank['updatedAt'],
									'Stationid' => $tank['Stationid'],
									'StationLocation' => $tank['StationLocation'],
									'Fuelid' => $tank['Fuelid'],
									'FuelName' => $tank['FuelName'],
									'TankTypeid' => $tank['TankTypeid'],
									'TankTypeName' => $tank['TankTypeName']
								);

							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					}else {

						$tanks = query($conn, "SELECT * FROM tank ORDER BY Stationid ASC") or exit( http_response_code(500) );

						if (num_rows($tanks) > 0) {

							foreach ($tanks as $key => $tank) {

								$payload[] = array(
									'id' => $tank['id'],
									'uid' => $tank['uid'],
									'volume' => $tank['volume'],
									'createdAt' => $tank['createdAt'],
									'updatedAt' => $tank['updatedAt'],
									'Stationid' => $tank['Stationid'],
									'Fuelid' => $tank['Fuelid'],
									'TankTypeid' => $tank['TankTypeid']
								);

							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					}

			} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				if (isset($_POST['id'])) {
					
					if (isset($_POST['uid']) && isset($_POST['volume']) && isset($_POST['Stationid']) && isset($_POST['Fuelid']) && isset($_POST['TankTypeid'])) {

						if (!empty($_POST['id']) || !empty($_POST['uid']) || !empty($_POST['volume']) || !empty($_POST['Stationid']) || !empty($_POST['Fuelid']) || !empty($_POST['TankTypeid'])) {

							$id = clean($conn, $_POST['id']);

							$uid = clean($conn, $_POST['uid']);

							$volume = clean($conn, $_POST['volume']);

							$Stationid = clean($conn, $_POST['Stationid']);

							$Fuelid = clean($conn, $_POST['Fuelid']);

							$TankTypeid = clean($conn, $_POST['TankTypeid']);

							if (query($conn, "UPDATE tank SET uid = '$uid', volume = '$volume', updatedAt = NOW(), Stationid = '$Stationid', Fuelid = '$Fuelid', TankTypeid = '$TankTypeid' WHERE id = '$id'")) {

								return http_response_code(200);

							} else { return http_response_code(500); }
							
						
						} else { return http_response_code(400); }

					} else { return http_response_code(400); }
					
				} else {
					
					if (isset($_POST['uid']) && isset($_POST['volume']) && isset($_POST['Stationid']) && isset($_POST['Fuelid']) && isset($_POST['TankTypeid'])) {

						if (!empty($_POST['uid']) || !empty($_POST['volume']) || !empty($_POST['Stationid']) || !empty($_POST['Fuelid']) || !empty($_POST['TankTypeid'])) {

							$uid = clean($conn, $_POST['uid']);

							$volume = clean($conn, $_POST['volume']);

							$Stationid = clean($conn, $_POST['Stationid']);

							$Fuelid = clean($conn, $_POST['Fuelid']);

							$TankTypeid = clean($conn, $_POST['TankTypeid']);

							if (query($conn, "INSERT INTO tank(id, uid, volume, createdAt, updatedAt, Stationid, Fuelid, TankTypeid) VALUES (NULL, '$uid', '$volume', NOW(), NULL, '$Stationid', '$Fuelid', '$TankTypeid')")) {

								return http_response_code(200);

							} else { return http_response_code(500); }
							
						} else { return http_response_code(400); }

					} else { return http_response_code(400); }

				}		

			} else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {

				if (isset($_GET['id'])) {

					$id = clean($conn, $_GET['id']);

					if (query($conn, "DELETE FROM tank WHERE id = '$id'")) {

						return http_response_code(200);
						
					} else { return http_response_code(500); }
					
				} else { return http_response_code(400); }

			} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

				return http_response_code(200);
			
			}  else { return http_response_code(405); }

		} else { return http_response_code(401); }

	} else { return http_response_code(400); }

?>