<?php

namespace App\Models;

use \PDO;

// class DB
// {
//     private $host = 'localhost';
//     private $user = 'root';
//     private $pass = '';
//     private $dbname = 'haj_db';

//     public function connect()
//     {
//         $conn_str = "mysql:host=$this->host;dbname=$this->dbname";
//         $conn = new PDO($conn_str, $this->user, $this->pass);
//         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//         return $conn;
//     }
// }

// $servername = "localhost";
// $username = "root";
// $password = "";

// // Create connection
// $conn = new mysqli($servername, $username, $password,"haj_db");

// // Check connection
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully";

$hostname = " localhost:3306";
$username = "haj_admin";
$password = "haj_admin";
$db='haj_db';

// $dsn = "mysql:host=$hostname;dbname=$db;charset=UTF8";

// try {
// 	$pdo = new PDO($dsn, $username, $password);

// 	if ($pdo) {
// 		echo "Connected to the $db database successfully!";
// 	}
// } catch (PDOException $e) {
// 	echo $e->getMessage();
// }

// $conn = "";
 
try {
    $servername = "localhost:3306";
    $dbname = "haj_db";
    $username = "root";
    $password = "";
 
    $conn = new PDO(
        "mysql:host=$servername; dbname=$dbname;",
        $username, $password
    );
     
    $conn->setAttribute(PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION);
    //echo "success";
     
} catch(PDOException $e) {
    echo "Connection failed: "
        . $e->getMessage();
}
 

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "haj_db";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);
// // Check connection
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }
