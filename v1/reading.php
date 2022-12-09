<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-04-06 17:14:23
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-18 00:12:46
 */
	require $_SERVER['DOCUMENT_ROOT'].'/v1/db.php';
	require $_SERVER['DOCUMENT_ROOT'].'/v1/functions/formula.php';

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {

		if (isset($_GET['token']) && !empty($_GET['token'])) {

			if (verifyUser($_GET['token'])) {

				if (isset($_GET['Tankuid']) && isset($_GET['gauge'])) {

					$Tankuid = clean($conn, $_GET['Tankuid']);
					
					$readings = query($conn, "SELECT reading.value, reading.dateTime, reading.Tankuid, tank.volume, tanktype.id, tanktype.length, tanktype.width, tanktype.height, tanktype.diameter, tanktype.radius1, tanktype.radius2, shape.code, fuel.name, fuel.density FROM reading JOIN tank ON reading.Tankuid = tank.uid JOIN fuel ON tank.Fuelid = fuel.id JOIN tanktype ON tank.TankTypeid = tanktype.id JOIN shape ON tanktype.Shapeid = shape.id WHERE Tankuid = '$Tankuid' ORDER BY reading.id DESC LIMIT 1") or exit( http_response_code(500) );

					if (num_rows($readings) > 0) {
						
						foreach ($readings as $key => $reading) {

							$volume = volume(12, $reading['code'], $reading['value'], $reading['density'], $reading['diameter'], $reading['length'], $reading['width'], $reading['radius1'], $reading['radius2']);

							$payload = array(
								'type' => 'gauge',
								'refresh' => array(
						            'type' =>'feed',
						            'transport' =>'http',
						            'url' =>'http://'.$_SERVER['SERVER_NAME'].'/v1/reading.php?Tankuid='.$Tankuid.'&plot=true&token='.$_GET['token'],
						            'interval' =>5000
						        ),
				             	'title' => array(
						     	  'text' => $reading['name'],
						     	  'y' => "62%",
						     	  'fontColor' =>"#515151",
						     	  'fontSize' => 15,
						     	  'bold' => true
						     	),
						     	'subtitle' => array(
						     	  'text' => "Litres",
						     	  'y' => "70%",
						     	  'fontSize' => 15,
						     	  'bold' => false
						     	),
								'scale-r' => array(
									'aperture' => 270,
					                'values' => '0:'.$reading['volume'].':'.$reading['volume']/10,
					                'markers' => array(
					                	array(
						                    'type' => 'area',
						                    'range' => [0, $reading['volume']/3],
						                    'offset-start' => 0.8,
						                    'offset-end' => 0.1,
						                    'background-color' => 'red',
						                    'alpha' => 0.7
						                ),
					                	array(
						                    'type' => 'area',
						                    'range' => [$reading['volume']/3, ($reading['volume']/3)*2],
						                    'offset-start' => 0.8,
						                    'offset-end' => 0.1,
						                    'background-color' => 'yellow',
						                    'alpha' => 0.7
						                ),
					                	array(
						                    'type' => 'area',
						                    'range' => [($reading['volume']/3)*2, (double)$reading['volume']],
						                    'offset-start' => 0.8,
						                    'offset-end' => 0.1,
						                    'background-color' => 'green',
						                    'alpha' => 0.7
						                )
					                ),
					                'item' => array(
					                    'offset-r' => -62.0,
					                    'angle' => 'auto'
					                ),
					                'minor-ticks' => 4,
				                    'center' => array(
				                    	'size' => 18,
								      	'background-color' =>'#000',
								      	'border-width' => 0
								    )
					            ),
				                'plot' => array(
						            'csize' => '5%',
						            'size' => '100%',
						            'background-color' => '#000',
						            'animation' => array(
							            'effect' => 2,
							            'method' => 3,
							            'sequence' => 1,
							            'speed' => 3000
							        ),
							        'value-box' => array(
						                'placement' => 'center',
						                'text' => '%vL',
						                'font-color' => 'white'
		            				)
						        ),
						        'series' => array(
						        	array(
							            'values' => [(double)$volume],
							            'csize' => '5%',
							            'size' => '100%',
							            'background-color' => '#000000'  
						        	)
						        ),
						        'payload' => array(
						        	array(
							            'volume' => $reading['volume'],
							            'dateTime' => $reading['dateTime']
						        	)
						        )
							);

						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode($payload);

					} else { return http_response_code(404); }

				} else if (isset($_GET['Tankuid']) && isset($_GET['line'])) {

					$Tankuid = clean($conn, $_GET['Tankuid']);

					$date = date("Y-m-d");
					
					$readings = query($conn, "SELECT reading.value, reading.dateTime, reading.Tankuid, tank.volume, tanktype.id, tanktype.length, tanktype.width, tanktype.height, tanktype.diameter, tanktype.radius1, tanktype.radius2, shape.code, fuel.name, fuel.density FROM reading JOIN tank ON reading.Tankuid = tank.uid JOIN fuel ON tank.Fuelid = fuel.id JOIN tanktype ON tank.TankTypeid = tanktype.id JOIN shape ON tanktype.Shapeid = shape.id WHERE Tankuid = '$Tankuid' AND reading.dateTime LIKE '".$date."%' ORDER BY reading.id ASC") or exit( http_response_code(500) );

					if (num_rows($readings) > 0) {
						$name = '';
						$volume = 0;

						foreach ($readings as $key => $reading) {

							$volume = $reading['volume'];
							$name = $reading['name'];

							$unix = strtotime($reading['dateTime']).'000';

							$values[] = array((int)$unix, volume(12, $reading['code'], $reading['value'], $reading['density'], $reading['diameter'], $reading['length'], $reading['width'], $reading['radius1'], $reading['radius2']));	
						}					

						$payload = array(
							 	'type' => 'line',
							 	'utc' => (boolean)true,
        						'timezone' => 0,
							 	'scroll-x' => array(),
								'scroll-y' => array(), 
							 	'legend' => array(
							 	  'adjustLayout' => true,
							 	  'align' => 'center',
							 	  'verticalAlign' => 'bottom'
							 	),
							 	'title' => array(
							 	  'adjustLayout' =>  (boolean)true,
							 	  'text' => $name
							 	),
							 	'plot' => array(
							 	  'valueBox' => array(
							 	    'text' => "%v"
							 	  )
							 	),
							 	'plotarea' => array(
							 	  'margin' => "dynamic 50 dynamic dynamic"
							 	),
							 	'scaleX' => array(
								 	'zooming' => (boolean)true,
								    'step' => 'minute',
								 	  'transform' => array(
								 	    'type' =>  'date',
								 	    'all' =>  '%h:%i %A'
								 	  )
							 	),
							 	'scaleY' => array(
							 		'zooming' => (boolean)true,
							 		'zooming-to' => [0,$volume],
								    'values' =>  '0:'.$volume.':'.$volume/10,
								 	  'guide' => array(
								 	    'lineStyle' => 'solid'
								 	  ),
								 	  'label' => array(
								 	    'text' =>  'Volume (Litres)'
								 	  )
								 	),
								 	'tooltip' => array(
								 	  'text' => "%v<br>%kv",
								 	  'borderRadius' =>  2
								 	),
								 	'crosshairX' => array(
								 	  'exact' =>  (boolean)true,
								 	  'lineColor' => '#000',
								    'scaleLabel' => array(
								      'borderRadius' =>  2
								    ),
								    'marker' => array(
								      'size' =>  5,
								      'backgroundColor' => 'white',
								      'borderWidth' =>  2,
								      'borderColor' => '#000'
								    )
							 	),
								'series' =>  [
									array(
										'values' => $values,
										'lineColor' => '#000',
										'marker' => array(
										  'backgroundColor' => '#000'
										)
									)
								]
							);

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode($payload);

					} else { return http_response_code(404); }

				} else if (isset($_GET['Tankuid']) && isset($_GET['plot'])) {

					$Tankuid = clean($conn, $_GET['Tankuid']);
					
					$readings = query($conn, "SELECT reading.value, reading.dateTime, reading.Tankuid, tank.volume, tanktype.id, tanktype.length, tanktype.width, tanktype.height, tanktype.diameter, tanktype.radius1, tanktype.radius2, shape.code, fuel.name, fuel.density FROM reading JOIN tank ON reading.Tankuid = tank.uid JOIN fuel ON tank.Fuelid = fuel.id JOIN tanktype ON tank.TankTypeid = tanktype.id JOIN shape ON tanktype.Shapeid = shape.id WHERE Tankuid = '$Tankuid' ORDER BY reading.id DESC LIMIT 1") or exit( http_response_code(500) );

					if (num_rows($readings) > 0) {
						
						foreach ($readings as $key => $reading) {

							$volume = volume(12, $reading['code'], $reading['value'], $reading['density'], $reading['diameter'], $reading['length'], $reading['width'], $reading['radius1'], $reading['radius2']);

							$payload = array(
								'plot0' => (double)$volume
							);
						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode($payload);

					} else { return http_response_code(404); }

				} else if (isset($_GET['Tankuid'])) {

					$Tankuid = clean($conn, $_GET['Tankuid']);
					
					$readings = query($conn, "SELECT * FROM reading WHERE Tankuid = '$Tankuid'") or exit( http_response_code(500) );

					if (num_rows($readings) > 0) {
						
						foreach ($readings as $key => $reading) {
							$payload[] = array(
								'id' => $reading['id'],
								'dateTime' => $reading['dateTime'],
								'value' => $reading['value'],
								'Tankuid' => $reading['Tankuid']
							);
						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				} else if (isset($_GET['Stationid'])) {

					$Stationid = clean($conn, $_GET['Stationid']);
					
					$readings = query($conn, "SELECT reading.* FROM reading JOIN tank ON reading.Tankuid = tank.uid JOIN station ON tank.Stationid = station.id WHERE station.id = '$Stationid'") or exit( http_response_code(500) );

					if (num_rows($readings) > 0) {
						
						foreach ($readings as $key => $reading) {
							$payload[] = array(
								'id' => $reading['id'],
								'dateTime' => $reading['dateTime'],
								'value' => $reading['value'],
								'Tankuid' => $reading['Tankuid']
							);
						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				} else {

					$readings = query($conn, "SELECT * FROM reading ORDER BY id DESC LIMIT 30 ") or exit( http_response_code(500) );

					if (num_rows($readings) > 0) {

						foreach ($readings as $key => $reading) {

							$payload[] = array(
								'id' => $reading['id'],
								'dateTime' => $reading['dateTime'],
								'value' => $reading['value'],
								'Tankuid' => $reading['Tankuid']
							);

						}

						// Return JSON data to the client application.
						header("Content-type:application/json");
						echo json_encode(array('data' => $payload));

					} else { return http_response_code(404); }

				}

			} else { return http_response_code(401); }

		} else { return http_response_code(400); }

	} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_GET['device'])) {

			$data = json_decode(file_get_contents('php://input'), true);

			$sql = "INSERT INTO reading (id, dateTime, value, Tankuid) VALUES";
			
			foreach ($data as $key => $value) {
				$sql .=  ( $key !== count( $data ) -1 ) ? "(NULL, '".$value['DateTime']."', '".$value['Data']."', '".$value['TankId']."')," : "(NULL, '".$value['DateTime']."', '".$value['Data']."', '".$value['TankId']."');";
			}

			if (query($conn, $sql)) {

				return http_response_code(200);

			} else { echo mysqli_error($conn); return http_response_code(500); }	
			
		} else {
			
			if (isset($_POST['data']) && isset($_POST['Tankuid'])) {

				if (!empty($_POST['data']) || !empty($_POST['Tankuid'])) {
					
					$value = clean($conn, $_POST['data']);

					// $dateTime = clean($conn, $_POST['dateTime']);

					$Tankuid = clean($conn, $_POST['Tankuid']);

					if (query($conn, "INSERT INTO reading (id, dateTime, value, Tankuid) VALUES (NULL, NOW(), '$value', '$Tankuid')")) {
						
						return http_response_code(200);

					} else { return http_response_code(500); }
					
				} else { return http_response_code(400); }
				
			} else { return http_response_code(400); }

		}	

	} else if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

		return http_response_code(200);
	
	}  else { return http_response_code(405); }		

?>