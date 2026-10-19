<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "nsp_pet";

try{
  $con = mysqli_connect($host,$user,$password,$database) or die("Unable to Connect with the Database.");
}
catch(Exception $e)
{
    echo "Unable to Connect with the Database.";
}

?>
