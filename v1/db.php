<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-02-25 15:29:59
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-18 00:14:06
 */
	
	require $_SERVER['DOCUMENT_ROOT'].'/v1/config.php';
	require $_SERVER['DOCUMENT_ROOT'].'/v1/vendor/autoload.php';
	use \Firebase\JWT\JWT;

	$conn = mysqli_connect(
		host_1,
		username_1,
		password_1,
		partition_1
	) or exit("Unable to establish connection with MySQL.");
	
	function query($conn, $statement){
		$results = mysqli_query($conn, $statement);
		return $results;
	}

	function num_rows($results){
		$num_rows = mysqli_num_rows($results);
		return $num_rows;
	}

	function clean($conn, $value){
		$cleanedValue = mysqli_real_escape_string($conn, $value);
		$cleanedValue = trim($cleanedValue);
		return $cleanedValue;
	}

	// Decode token and check if valid (not tampered with).
	// If CaughtException Return Unauthorized. 
	function verifyUser($token){
		try { $token = JWT::decode($token, secreteKey, array('HS256')); return true; }
		catch (Exception $e) { return false; }
	}

?>