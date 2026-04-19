<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Pagination settings
$limit = 10; // students per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if ($search) {
    $query = "SELECT * FROM students 
              WHERE firstname LIKE '%$search%' 
                 OR lastname LIKE '%$search%' 
                 OR student_id LIKE '%$search%'
              LIMIT $offset, $limit";

    $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students 
                                         WHERE firstname LIKE '%$search%' 
                                            OR lastname LIKE '%$search%' 
                                            OR student_id LIKE '%$search%'");
} else {
    $query = "SELECT * FROM students LIMIT $offset, $limit";
    $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
}


// Execute main query
$result = mysqli_query($conn, $query);

// Calculate total pages
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Students</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Breadcrumbs -->
    <nav class="breadcrumbs">
        <a href="admin_dashboard.php">Home</a> &gt; Students
    </nav>

    <h2>Students List</h2>

    <!-- Search Form -->
    <div class="search-container">
    <form method="GET" action="view_students.php">
        <input type="text" name="search" placeholder="Search by name or Student ID"
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit">Search</button>
    </form>
</div>

    <!-- Students Table -->
    <table>
        <tr>
            <th>ID</th>
            <th>Student ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['student_id'] ?></td>
                    <td><?= $row['firstname'] ?></td>
                    <td><?= $row['lastname'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td>
                        <a href="update_student.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="delete_student.php?id=<?= $row['id'] ?>"
                            onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No students found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

<div class="nav-buttons">
    <button onclick="window.location.href='admin_dashboard.php'">Admin Dashboard</button>
    <button onclick="window.location.href='admin_logout.php'">Logout</button>
</div>

</body>

</html>