<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";

// Handle login
if (isset($_POST['login'])) {

    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM admins WHERE email=? AND status=1"
    );
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $admin  = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['firstname'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "❌ Invalid email or password";
    }
}



if (isset($_GET['message']) && $_GET['message'] == 'logged_out') {
    echo "<p style='color:green;'>You have successfully logged out.</p>";
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-box">
    <h2>Admin Login</h2>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Admin Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>
