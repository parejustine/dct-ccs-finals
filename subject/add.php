<?php

// Include other necessary files for header, footer, and sidebar
include '../admin/partials/footer.php';
include '../admin/partials/header.php';  
$logoutPage = '../admin/logout.php'; 
$dashboardPage = '../admin/dashboard.php'; 
include '../admin/partials/side-bar.php';  

// Function to check if request method is POST
function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// Function to safely get data from POST
function postData($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

// Fetch subjects function
function fetchSubjects() {
    global $conn;  // Ensure you're using the global $conn database connection
    
    // Check if the connection is established
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    
    $sql = "SELECT * FROM subjects";  // Adjust the table name if necessary
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Handle form submission (add subject)
if (isPost()) {
    $subject_code = postData("subject_code");
    $subject_name = postData("subject_name");
    $result = addSubject($subject_code, $subject_name);

    if ($result === true) {
        echo '<div class="alert alert-success">Subject added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">' . $result . '</div>';
    }
}
?>

<!-- Content Area -->
<div class="col-md-9 col-lg-10">
    <h3 class="text-left mb-5 mt-5">Add A New Subject</h3>
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="../admin/dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
        </ol>
    </nav>

    <!-- Add Subject Form -->
    <div class="card p-4 mb-5">
        <form method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" required>
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" required>
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Add Subject</button>
        </form>
    </div>

    <!-- Subject List Table -->
    <div class="card p-4">
        <h3 class="card-title text-center">Subject List</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $subjects = fetchSubjects(); // Fetch all subjects from the database
                if (!empty($subjects)): ?>
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td><?= htmlspecialchars($subject['subject_code']) ?></td>
                            <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                            <td>
                                <!-- Edit Button (Green) -->
                                <a href="edit.php?subject_code=<?= urlencode($subject['subject_code']) ?>" class="btn btn-primary btn-sm">Edit</a>

                                <!-- Delete Button (Red) -->
                                <a href="delete.php?subject_code=<?= urlencode($subject['subject_code']) ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No subjects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Include footer
include '../admin/partials/footer.php'; 
?>
