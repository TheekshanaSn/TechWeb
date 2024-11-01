<?php

$link = include('./connection.php');


$sql = "CREATE DATABASE bict";

if ($link->query($sql) === TRUE) {
  echo "Created Database BICT" . "<br>";
} else {
  echo "Error: " . $sql . "<br>" .mysqli_error($link);
}

$link->select_db('bict');


$sql = "
CREATE TABLE user(
  userId INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50),
  password VARCHAR(100),
  email VARCHAR(50),
  role VARCHAR(20)
)";

if ($link->query($sql) === TRUE) {
  echo "Created Table user" . "<br>";
} else {
  echo "Error: " . $sql . "<br>" .mysqli_error($link);
}


$sql = "
  CREATE TABLE subject(
  subjectCode CHAR(10) PRIMARY KEY,
  subjectName VARCHAR(50),
  credits INT
)";

if ($link->query($sql) === TRUE) {
  echo "Created Table subject" . "<br>";
} else {
  echo "Error: " . $sql . "<br>" .mysqli_error($link);
}


$sql = "
CREATE TABLE student(
  studentId INT PRIMARY KEY,
  studentName VARCHAR(50),
  age INT,
  subjects VARCHAR(100)
)
";

if ($link->query($sql) === TRUE) {
  echo "Created Table student" . "<br>";
} else {
  echo "Error: " . $sql . "<br>" .mysqli_error($link);
}


$sql = "INSERT INTO user 
(username, password, email, role)
 VALUES (?, ?, ?, ?)";


$st = $link->prepare($sql);

if ($st === false) {
    die("Error preparing statement: " .mysqli_error($link));
}


$username = 'admin';
$password = md5('admin123');
$email = 'admin@gmail.com';
$role = 'administrator';


$st->bind_param("ssss", $username, $password, $email, $role);


if ($st->execute() === TRUE) {
    echo "New user inserted successfully" . "<br>";
} else {
    echo "Error inserting user: " . $st->error . "<br>";
}


$st->close();
$link->close();

?>