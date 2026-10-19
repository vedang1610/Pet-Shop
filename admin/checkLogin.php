<?php

include_once("db.php");
session_start();
if(!isset($_SESSION['adminId']))
{
  header('location:logout.php');
}
// else 
// {
//   header('location:logout.php');
// }
?>
