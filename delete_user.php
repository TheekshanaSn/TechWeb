<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "administrator") {
    header("Location: login.php");
    exit;
}


$link = require("./connection.php");
$link->select_db('bict');

$sql = "DELETE FROM user WHERE userId = ?";

$st = $link->prepare($sql);

if ($st === false) {
    die("Error preparing statement: " .mysqli_error($link));
}


$userId =  $_GET['u_id'];

$st->bind_param("d", $userId);


if ($st->execute() === TRUE) {
    header('Location: user_mgt.php');
    exit;
} else {
    echo "Error inserting user: " . $st->error . "<br>";
}
