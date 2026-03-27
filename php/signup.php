<?php

if (
	isset($_POST['fname']) &&
	isset($_POST['uname']) &&
	isset($_POST['pass'])
) {

	include "../db_conn.php";

	$fname = $_POST['fname'];
	$uname = $_POST['uname'];
	$pass = $_POST['pass'];

	$data = "fname=" . $fname . "&uname=" . $uname;

	if (empty($fname)) {
		$em = "Full name is required";
		header("Location: ../signup.php?error=$em&$data");
		exit;
	} else if (empty($uname)) {
		$em = "User name is required";
		header("Location: ../signup.php?error=$em&$data");
		exit;
	} else if (empty($pass)) {
		$em = "Password is required";
		header("Location: ../signup.php?error=$em&$data");
		exit;
	} else {

		// hashing the password
		$pass = password_hash($pass, PASSWORD_DEFAULT);

		//checking the database if the username is already taken
		$sql = "SELECT * FROM users WHERE username = ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$uname]);


		if ($stmt->rowCount() > 0) {
			$em = "Username already taken";
			header("Location: ../signup.php?error=$em&$data");
			exit;
		}


		$sql = "INSERT INTO users(fname, username, password) 
    	        VALUES(?,?,?)";
		$stmt = $conn->prepare($sql);

		//handling errors during database insertion
		try {
			$stmt->execute([$fname, $uname, $pass]);
		} catch (PDOException $e) {
			$em = "Username already exists";
			header("Location: ../signup.php?error=$em&$data");
			exit;
		}

		header("Location: ../signup.php?success=Your account has been created successfully");
		exit;
	}
} else {
	header("Location: ../signup.php?error=error");
	exit;
}
