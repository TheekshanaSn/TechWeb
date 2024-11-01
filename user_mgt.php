<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "administrator") {
    header("Location: login.php");
    exit;
}

$link = require("./connection.php");
$link->select_db('bict');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['role'])) {
        echo "<h3>Fill In All Fields</h3><br>";
    } else {
        $sql = "INSERT INTO user(username, password, email, role) 
        VALUES (?, ?, ?, ?)";


        $st = $link->prepare($sql);

        if ($st === false) {
            die("Error preparing statement: " .mysqli_error($link));
        }


        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $email = $_POST['email'];
        $role = $_POST['role'];


        $st->bind_param("ssss", $username, $password, $email, $role);


        if ($st->execute() === TRUE) {
            header('Location: user_mgt.php');
            exit;
        } else {
            echo "Error inserting user: " . $st->error . "<br>";
        }
    }

    $st->close();
    $link->close();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0.5em;
        }
    </style>
</head>

<body>

    <h2>User Management</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Delete Records</th>
        </tr>
        <?php
        $sql = "SELECT * FROM user";
        $result = $link->query($sql);

        if ($result === True) {
            echo "<h3>No records to show</h3>";
        } else {
            while ($res =  $result->fetch_assoc()) {
                $data =  sprintf("
                        <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                        <td><button><a href='delete_user.php?u_id=%s'>Remove</a></button></td>
                        </tr>
                    
                    ", htmlspecialchars($res['username']), htmlspecialchars($res['email']), htmlspecialchars($res['role']), htmlspecialchars($res['userId']));

                echo $data;
            }
        }
        ?>
    </table>

    <br>
    <br>
    <br>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="Teacher">Teacher</option>
            <option value="Student">Student</option>
        </select><br><br>

        <input type="submit" value="Add">
        <input type="reset" value="Clear">
    </form>
</body>

</html>