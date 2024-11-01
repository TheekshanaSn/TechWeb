<?php

$servername = "localhost";
$username = "root";
$password = "";

$link = new mysqli(
    $servername, 
    $username, 
    $password,
);

if ($link->connect_error) {
  die("Connection failed: ".mysqli_error($link));
}

return $link;

?>