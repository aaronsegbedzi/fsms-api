<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-04-11 10:29:12
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-27 19:10:49
 */

	function m3toL($m3) {
		// Convert m3 to L whereas 1m3 = 1000L
		$L = $m3 / 0.001;

		// Round to 2 decimal points.
		$L = round($L);

		// Return litre value.
		return $L;
	}

/////////////////////////////////////////////////////////////////////////////////

	function height($pressure, $density){
		$gravity = 9.8;
		$height = $pressure / ($gravity * $density);
		return $height;
	}

/////////////////////////////////////////////////////////////////////////////////

	// Calculate pressure value from ADC reading (12bit Resolution)
	// Using GreenTron HTTP DAQ values to derive linear equation.
	function p10bit($value){

		// Set value of linear equation gradient.
		$m = 52.330097087378640776699029126214;

		// Set value of y-slope intercept.
		$c = -10518.349514563106796116504854369;

		// Calculate pressure using linear formula y=mx+c
		$pressure = ($m * $value) + $c;

		return $pressure;
	}

	// Calculate pressure value from ADC reading (12bit Resolution)
	// Using King Pigeon M200T values to derive linear equation.
	function p12bit($value){

		// Set value of linear equation gradient.
		$m = 13.8;

		// Set value of y-slope intercept.
		$c = -11152.2;

		// Calculate pressure using linear formula y=mx+c
		$pressure = ($m * $value) + $c;

		return $pressure;
	}

/////////////////////////////////////////////////////////////////////////////////

	// Calculate volume of truncated cone.
	function tconeVolume($Radius, $radius, $depth){
		
		// Set value of pi.
		$pi = pi();

		$volume = ((1 / 3) * $pi * $depth) * (pow($Radius, 2) + ($Radius * $radius) + pow($radius, 2));

		return $volume;
	}

	// Calculate volume for vertical cylinder.
	function vcylinderVolume($diameter, $depth) {
		// Get value for pi as double.
		$pie = (double)pi();

		// Get value for circle radius.
		$radius = $diameter / 2;

		// Calculate volume of vertical cylinder.
		$volume = $pie * pow($radius, 2) * $depth;

		// Return volume of vertical cylinder.
		return $volume;
	}

	// Calculate volume for horizontal cylinder.
	function hcylinderVolume($diameter, $length, $depth) {
		// Get value for pi as double.
		$pie = (double)pi();

		// Get value for circle radius.
		$radius = $diameter / 2;

		// Get value of cylinder circle area.
		$areaCir = $pie * pow($radius, 2);
		
		// Get total volume of container.
		$volumeTotal = $areaCir * $length;

		// Get the value for Line OE.
		$lineOE = $radius - $depth;

		// Get the value for Line OA.
		$lineOA = $radius;

		// Get the value of Line AE.
		$lineAE = sqrt(pow($lineOA, 2) - pow($lineOE, 2));

		// Get angle of line OE. inverse cosine in degrees.
		$angleOE = rad2deg(acos($lineOE / $lineOA));

		// Get angle of line OB.
		$angleOB = $angleOE * 2;

		// Get area of sector.
		$areaSec = ($angleOB / 360) * $areaCir;

		// Get area of triangle.
		$areaTri = $lineAE * $lineOE;

		// Get area of segment.
		$areaSeg = $areaSec - $areaTri;

		// Get volume at the specified depth.
		$volume = $areaSeg / $areaCir * $volumeTotal;

		return $volume;
	}

	// Calculate volume for cubic container.
	function cubeVolume($length, $width, $depth) {
		// Get value of volume.
		$volume = $length * $width * $depth;

		return $volume;
	}

/////////////////////////////////////////////////////////////////////////////////

	// Calculate volume from ADC reading from Data Acquisition Unit (DAQ).
	function volume($res, $shape, $value, $density, $diameter, $length, $width, $Radius, $radius) {

		if ($res == 12) { $pressure = p12bit($value); } 
			else if ($res == 10) { $pressure = p10bit($value); }

		$depth = height($pressure, $density);
		
		if ($shape == 'tcone') { $volume = tconeVolume($Radius, $radius, $depth); } 
			else if ($shape == 'vcylinder') { $volume = vcylinderVolume($diameter, $depth); }
				else if ($shape == 'hcylinder') { $volume = hcylinderVolume($diameter, $length, $depth); }
					else if ($shape == 'cube') { $volume = cubeVolume($length, $width, $depth); }

		$volume = m3toL($volume);

		return $volume;
	}

?>