<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize $student to prevent undefined variable
$student = null;

// Get student data
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast to int for safety
    $result = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");

    if ($result && mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        echo "Student not found.";
        exit;
    }
} else {
    echo "No student ID provided.";
    exit;
}

// Update student
if (isset($_POST['update'])) {
    $id        = (int)$_POST['id'];
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname  = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "UPDATE students SET 
            firstname='$firstname',
            lastname='$lastname',
            email='$email'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: view_students.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Student</title>
     <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Update Student</h2>

<form method="post">
    <input type="hidden" name="id" value="<?= $student['id'] ?>">
    <input type="text" name="firstname" value="<?= htmlspecialchars($student['firstname']) ?>" required><br>
    <input type="text" name="lastname" value="<?= htmlspecialchars($student['lastname']) ?>" required><br>
    <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required><br>
    <button type="submit" name="update">Update</button>
</form>

</body>
</html>
