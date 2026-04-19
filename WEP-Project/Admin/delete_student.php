<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// If delete confirmed
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM students WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: view_students.php");
    exit;
}

// Get student data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT firstname, lastname FROM students WHERE id=$id");
    $student = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Delete Student</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h3>Delete Student</h3>

    <p>
        Are you sure you want to delete
        <b><?= $student['firstname'] ?> <?= $student['lastname'] ?></b>?
    </p>

    <form method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit" name="delete">Yes</button>
        <button type="button" onclick="window.location.href='view_students.php'">No</button>
    </form>

</body>

</html>