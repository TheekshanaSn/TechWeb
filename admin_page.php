<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "administrator") {
    header("Location: login.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
</head>
<body>
    <div>
        <a href="user_mgt.php"><img src="./images/user.png" alt=""></a>
        <a href="subject_mgt.php"><img src="./images/subject.png" alt=""></a>
    </div>
</body>
</html>
