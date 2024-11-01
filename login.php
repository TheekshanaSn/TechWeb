<?php

session_start();

$link = include("./connection.php");
$link->select_db('bict');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "<h3>Fill In All Fields</h3><br>";
    } else {

        $sql = "SELECT * FROM user WHERE username = ?";
        $st = $link->prepare($sql);

        if ($st === false) {
            die("Error preparing statement: " .mysqli_error($link));
        }

        $st->bind_param("s", $_POST['username']);

        if ($st->execute() === TRUE) {
            $result = $st->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (md5($_POST['password']) === $user['password']) {
                    $_SESSION['user_id'] = $user['userId'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: admin_page.php");
                    exit;
                } else {
                    echo "<h3>Invalid password.</h3>";
                }
            } else {
                echo "<h3>Please enter a valid username</h3><br>";
            }
        }

        $st->close();
        $link->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Submit">
        <input type="reset" value="Reset">
    </form>
</body>

</html>
