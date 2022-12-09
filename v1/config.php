<?php

/**
 * @Author: Aaron Segbedzi
 * @Date:   2018-02-27 23:26:36
 * @Last Modified by:   Aaron Segbedzi
 * @Last Modified time: 2018-04-18 20:02:59
 */

	// ** API Secrete Key ***
	// For secure authentication, this key must not be shared.
	define('secreteKey', 'XmKZk5#rVY4P4ew68J70');
	// 1. Header Configurations.
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
	header('Access-Control-Allow-Headers: X-Requested-With,content-type,If-Modified-Since');
	// 2. Time and Date Configurations.
	date_default_timezone_set('Africa/Accra');
	// 3. Database Connection Configurations.
	define('host_1', 'db732479423.db.1and1.com');
	define('username_1', 'dbo732479423');
	define('password_1', 'Cocolove.1234');
	define('partition_1', 'db732479423');
?>
