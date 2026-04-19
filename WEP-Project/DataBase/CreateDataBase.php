<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
// 1. Connect to MySQL server
$conn = mysqli_connect("localhost", "root", "");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS project_db";
mysqli_query($conn, $sql);

// 3. Select database
mysqli_select_db($conn, "project_db");

// 4. Create students table
$sql_students = "CREATE TABLE IF NOT EXISTS students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','admin') DEFAULT 'student',
    status TINYINT(1) DEFAULT 1,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// 5. Create admins table
$sql_admins = "CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id VARCHAR(20) UNIQUE NOT NULL,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status TINYINT(1) DEFAULT 1,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute table creation
mysqli_query($conn, $sql_students);
mysqli_query($conn, $sql_admins);

// 6. Insert default admin if not exists
$check = mysqli_query($conn, "SELECT id FROM admins WHERE email='admin@uni.edu'");
if (mysqli_num_rows($check) == 0) {
    $pass = password_hash("admin123", PASSWORD_DEFAULT);
    mysqli_query($conn, 
        "INSERT INTO admins 
        (admin_id, firstname, lastname, email, password)
        VALUES ('ADM001','System','Admin','admin@uni.edu','$pass')"
    );
}

echo "✅ Setup completed successfully";

mysqli_close($conn);
?>


</body>

</html>