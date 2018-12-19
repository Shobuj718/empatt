<?php
	
	if(!isset($_SESSION)){
		session_start();
	}
	$conn = new mysqli('localhost', 'root', '', 'employee_attend');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

