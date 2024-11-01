<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "administrator") {
    header("Location: login.php");
    exit;
}


$link = include("./connection.php");
$link->select_db('bict');

$sql = "DELETE FROM subject WHERE subjectCode = ?";

$st = $link->prepare($sql);

if ($st === false) {
    die("Error preparing statement: " .mysqli_error($link));
}


$subjectCode =  $_GET['sub_code'];

$st->bind_param("s", $subjectCode);


if ($st->execute() === TRUE) {
    header('Location: subject_mgt.php');
    exit;
} else {
    echo "Error inserting user: " . $st->error . "<br>";
}
