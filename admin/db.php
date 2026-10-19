<?php

// Database connection.
// On your own computer (XAMPP) the defaults below work as they are.
// On a web host, copy admin/db_config.example.php to admin/db_config.php and fill in the
// host's database details. db_config.php is kept out of git, so passwords never reach GitHub.
$host = "localhost";
$user = "root";
$password = "";
$database = "nsp_pet";

if (file_exists(__DIR__ . "/db_config.php")) {
    $dbConfig = require __DIR__ . "/db_config.php";
    $host = $dbConfig['host'];
    $user = $dbConfig['user'];
    $password = $dbConfig['password'];
    $database = $dbConfig['database'];
}

try{
  $con = mysqli_connect($host,$user,$password,$database) or die("Unable to Connect with the Database.");
  mysqli_set_charset($con, "utf8mb4");
}
catch(Exception $e)
{
    echo "Unable to Connect with the Database.";
}

?>
