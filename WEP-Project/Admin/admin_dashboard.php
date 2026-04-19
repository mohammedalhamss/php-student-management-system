<?php
// Start session only if not started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "project_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get total students
$total_students = 0;
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_students = $row['total'];
}

// Get total admins
$total_admins = 0;
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM admins");
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $total_admins = $row['total'];
}




// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Banner -->
    <header class="banner">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['admin_name']); ?>!</p>
    </header>


    <!-- Flash Message -->
    <?php if (isset($_GET['message']) && $_GET['message'] == 'student_added'): ?>
        <div class="flash-message success">✅ Student added successfully!</div>
    <?php endif; ?>

    <!-- Navigation Menu -->
    <nav class="admin-menu">
        <ul>
            <li><a href="add_student.html">Add Student</a></li>
            <li><a href="view_students.php">View Students</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Slider -->
    <div class="slider">
        <div class="slide active">Welcome to the Admin Dashboard!</div>
        <div class="slide">Total Students: <?= $total_students ?></div>
        <div class="slide">Total Admins: <?= $total_admins ?></div>
    </div>

    <script>
        let slides = document.querySelectorAll('.slide');
        let current = 0;
        setInterval(() => {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, 3000);
    </script>

    <style>
        .slider {
            margin: 20px 0;
            height: 50px;
            overflow: hidden;
            position: relative;
        }

        .slide {
            display: none;
            text-align: center;
            font-weight: bold;
            font-size: 1.2em;
        }

        .slide.active {
            display: block;
        }
    </style>


    <!-- Dashboard Content -->
    <main class="dashboard-content">
        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Students</h3>
                <p><?= $total_students ?></p>
            </div>
            <div class="card">
                <h3>Total Admins</h3>
                <p><?= $total_admins ?></p>
            </div>

        </div>
    </main>



</body>

</html>