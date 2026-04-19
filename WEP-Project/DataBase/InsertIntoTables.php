<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>

    <?php
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "project_db");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if form is submitted
    if (isset($_POST['submit'])) {

        // Get form data
        $student_id = $_POST['student_id'];
        $firstname  = $_POST['firstname'];
        $lastname   = $_POST['lastname'];
        $email      = $_POST['email'];
        $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Prepared statement (secure)
        $sql = "INSERT INTO students 
            (student_id, firstname, lastname, email, password)
            VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $student_id,
            $firstname,
            $lastname,
            $email,
            $password
        );

        // Execute
        if (mysqli_stmt_execute($stmt)) {
            
            header( "Location: ../Admin/admin_dashboard.php");
            exit;
        } else {
            echo "<p class='error'>❌ Error: " . mysqli_error($conn) . "</p>";
        }


        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
    ?>

</body>

</html>