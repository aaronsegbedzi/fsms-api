<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-04-16 10:57:53
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-06-04 13:07:26
 */

	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';
	require $_SERVER['DOCUMENT_ROOT'].'/v1/functions/formula.php';

	if (isset($_GET['token']) && !empty($_GET['token'])) {

		if (verifyUser($_GET['token'])) {

			if ($_SERVER['REQUEST_METHOD'] == 'GET') {

					if (isset($_GET['users'])) {
						
						$statistics = query($conn, "SELECT COUNT(*) AS 'total', COUNT(CASE WHEN user.role = '0' THEN 1 END) AS 'administrators', COUNT(CASE WHEN user.role = '1' THEN 1 END) AS 'managers', COUNT(CASE WHEN user.role = '2' THEN 1 END) AS 'subscribers' FROM user") or exit( http_response_code(500) );

						if (num_rows($statistics) > 0) {
							
							foreach ($statistics as $key => $statistic) {
								$payload[] = array(
									'total' => $statistic['total'],
									'administrators' => $statistic['administrators'],
									'managers' => $statistic['managers'],
									'subscribers' => $statistic['subscribers']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else if (isset($_GET['volume'])) {
						
						$statistics = query($conn, "SELECT reading.id, reading.value, reading.dateTime, reading.Tankuid, tank.volume, tanktype.id, tanktype.length, tanktype.width, tanktype.height, tanktype.diameter, tanktype.radius1, tanktype.radius2, shape.code, fuel.name, fuel.density FROM `reading` JOIN `tank` ON `reading`.`Tankuid` = `tank`.`uid` JOIN `fuel` ON `tank`.`Fuelid` = `fuel`.`id` JOIN `tanktype` ON `tank`.`TankTypeid` = `tanktype`.`id` JOIN `shape` ON `tanktype`.`Shapeid` = `shape`.`id` WHERE `reading`.`id` IN (SELECT MAX(id) FROM reading GROUP BY Tankuid) ORDER BY reading.id ASC") or exit( http_response_code(500) );

						if (num_rows($statistics) > 0) {
							
							$total = 0;
							$date = "";
							
							foreach ($statistics as $key => $statistic) {

								$total = $total + volume(12, $statistic['code'], $statistic['value'], $statistic['density'], $statistic['diameter'], $statistic['length'], $statistic['width'], $statistic['radius1'], $statistic['radius2']);

								$date = $statistic['dateTime'];
							}

							$payload = array(
								'total' => $total,
								'dateTime' => $date
							);

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else if (isset($_GET['stations'])) {
						
						$statistics = query($conn, "SELECT COUNT(*) AS 'total' FROM station") or exit( http_response_code(500) );

						if (num_rows($statistics) > 0) {
							
							foreach ($statistics as $key => $statistic) {
								$payload[] = array(
									'total' => $statistic['total']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else if (isset($_GET['tanks'])) {
						
						$statistics = query($conn, "SELECT COUNT(*) AS 'total' FROM tank") or exit( http_response_code(500) );

						if (num_rows($statistics) > 0) {
							
							foreach ($statistics as $key => $statistic) {
								$payload[] = array(
									'total' => $statistic['total']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else if (isset($_GET['readings'])) {
						
						$statistics = query($conn, "SELECT COUNT(*) AS 'total' FROM reading") or exit( http_response_code(500) );

						if (num_rows($statistics) > 0) {
							
							foreach ($statistics as $key => $statistic) {
								$payload[] = array(
									'total' => $statistic['total']
								);
							}

							// Return JSON data to the client application.
							header("Content-type:application/json");
							echo json_encode(array('data' => $payload));

						} else { return http_response_code(404); }

					} else { return http_response_code(400); }

			} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

				return http_response_code(200);
			
			}  else { return http_response_code(405); }

		} else { return http_response_code(401); }

	} else { return http_response_code(400); }

?>