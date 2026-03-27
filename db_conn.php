<!-- This file establishes a connection to a MySQL database using PDO (PHP Data Objects). It defines the server name, username, password, and database name, and then attempts to create a new PDO instance. If the connection is successful, it sets the error mode to exception. If there is an error during the connection process, it catches the exception and outputs an error message. -->

<?php 

$sName = "localhost";
$uName = "root";
$pass = "";
$db_name = "blog_db";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}