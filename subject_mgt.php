<?php

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== "administrator") {
    header("Location: login.php");
    exit;
}

$link = require("./connection.php");
$link->select_db('bict');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['subjectCode']) || empty($_POST['subjectName']) || empty($_POST['credits'])) {
        echo "<h3>Fill In All Fields</h3><br>";
    } else {
        $sql = "SELECT * FROM subject WHERE subjectCode = ?";

        $st = $link->prepare($sql);

        if ($st === false) {
            die("Error preparing statement: " .mysqli_error($link));
        }

        $subjectCode = $_POST['subjectCode'];

        $st->bind_param("s", $subjectCode);

        $st->execute();

        $result = $st->get_result();

        if ($result->num_rows == 0) {

            $sql = "INSERT INTO subject(subjectCode, subjectName, credits)
             VALUES (?, ?, ?)";


            $st = $link->prepare($sql);

            if ($st === false) {
                die("Error preparing statement: " .mysqli_error($link));
            }


            $subjectCode = $_POST['subjectCode'];
            $subjectName = $_POST['subjectName'];
            $credits = intval($_POST['credits']);


            $st->bind_param("ssi", $subjectCode, $subjectName, $credits);


            if ($st->execute() === TRUE) {
                header('Location: subject_mgt.php');
                exit;
            } else {
                echo "Error inserting user: " . $st->error . "<br>";
            }

            $st->close();
        }else{
            echo "Subject code is available in the database";
            exit;
        }
    }

    $link->close();
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Management</title>
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

    <h2>Subject Management</h2>
    <table border="1">
        <tr>
            <th>Subject Code</th>
            <th>Subject Name</th>
            <th>Credits</th>
            <th>Remove Records</th>
        </tr>
        <?php
        $sql = "SELECT * FROM subject";
        $result = $link->query($sql);

        if ($result === True) {
            echo "<h3>No records to show</h3>";
        } else {
            while ($res =  $result->fetch_assoc()) {
                $data =  sprintf("
                        <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%d</td>
                        <td><button><a href='delete_subject.php?sub_code=%s'>Remove</a></button></td>
                        </tr>
                    
                    ", htmlspecialchars($res['subjectCode']), htmlspecialchars($res['subjectName']), intval($res['credits']), htmlspecialchars($res['subjectCode']));

                echo $data;
            }
        }
        ?>
    </table>

    <br>
    <br>
    <br>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <label for="subjectCode">SubjectCode:</label>
        <input type="text" id="subjectCode" name="subjectCode" required><br><br>

        <label for="subjectName">SubjectName:</label>
        <input type="text" id="subjectName" name="subjectName" required><br><br>

        <label for="credits">Credits:</label>
        <input type="number" id="credits" name="credits" required><br><br>

        <input type="submit" value="Add">
        <input type="reset" value="Clear">
    </form>
</body>

</html>