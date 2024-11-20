<?php
include '../../functions.php'; // Include the functions
include '../partials/header.php';

$logoutPage = '../logout.php';
$dashboardPage = '../dashboard.php';
$subjectPage = './add.php';
include '../partials/side-bar.php';

// Fetch subject data based on subject_code passed in the URL
$subject_data = getSubjectCode($_GET['subject_code']);

if (!$subject_data) {
    echo "<p class='text-danger'>Subject not found.</p>";
    exit;
}

if (isPost()) {
    $subject_code = $subject_data['subject_code']; // Use existing subject_code
    $subject_name = postData('subject_name'); // Get the new subject name from the form

    $result = EditSubject($subject_code, $subject_name, "./add.php");

    if ($result !== true) {
        echo "<p class='text-danger'>$result</p>";
    }
}
?>

<div class="col-md-9 col-lg-10">
    <h3 class="text-left mb-5 mt-5">Edit Subject</h3>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
        </ol>
    </nav>

    <!-- Form to edit subject -->
    <div class="card p-4 mb-5">
        <form method="POST" action="">
            <div class="form-group">
                <label for="subject_code">Subject ID</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?= htmlspecialchars($subject_data['subject_code']) ?>" readonly>
            </div>
            <div class="form-group">
                <label for="subject_name">Subject Name</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?= htmlspecialchars($subject_data['subject_name']) ?>" required>
            </div>
            <!-- Adjust the margin of the submit button to move it slightly -->
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary btn-sm w-100">Update Subject</button>
            </div>
        </form>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
