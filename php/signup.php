<?php

if (
    isset($_POST['fname']) &&
    isset($_POST['uname']) &&
    isset($_POST['pass'])
) {

    include "../db_conn.php";

    // Trim inputs
    $fname = trim($_POST['fname']);
    $uname = trim($_POST['uname']);
    $pass  = $_POST['pass'];

    $data = "fname=" . urlencode($fname) . "&uname=" . urlencode($uname);

    // Validation
    if (empty($fname)) {
        $em = "Full name is required";
        header("Location: ../signup.php?error=$em&$data");
        exit;
    } 
    else if (empty($uname)) {
        $em = "User name is required";
        header("Location: ../signup.php?error=$em&$data");
        exit;
    } 
    else if (empty($pass)) {
        $em = "Password is required";
        header("Location: ../signup.php?error=$em&$data");
        exit;
    } 
    else {

        // Hash password ONLY after validation
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (fname, username, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute([$fname, $uname, $hashedPass]);

            header("Location: ../signup.php?success=Your account has been created successfully");
            exit;

        } catch (PDOException $e) {

            // Check if error is due to duplicate username
            if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry error code
                $em = "Username already exists";
            } else {
                $em = "Something went wrong. Please try again.";
            }

            header("Location: ../signup.php?error=$em&$data");
            exit;
        }
    }

} else {
    header("Location: ../signup.php?error=Invalid request");
    exit;
}