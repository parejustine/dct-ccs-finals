<?php
include '../../functions.php'; // Include the functions
include '../partials/header.php';

$logoutPage = '../logout.php';
$dashboardPage = '../dashboard.php';
$studentPage = '../student/register.php';
include '../partials/side-bar.php';
?>

<!-- Content Area -->
<div class="col-md-9 col-lg-10">
    <h3 class="text-left mb-5 mt-5">Add A New Subject</h3>
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
        </ol>
    </nav>

    <?php
    // Initialize an array to store error messages
    $errors = [];
    $subject_code = 1001; // Default value
    $subject_name = ''; // Default values

    // Check for the latest subject code in the database and increment it by 1
    $latestSubject = fetchLatestSubjectCode();
    if ($latestSubject) {
        $subject_code = $latestSubject + 1; // Set subject code to the next available number
    }

    if (isPost()) {
        // Get the posted data
        $subject_code = postData("subject_code");
        $subject_name = postData("subject_name");

        // Validate Subject Code
        if (empty($subject_code)) {
            $errors[] = "Subject code is required.";
        } elseif (!ctype_digit((string)$subject_code)) {
            $errors[] = "Subject code must contain only numbers.";
        }

        // Validate Subject Name
        if (empty($subject_name)) {
            $errors[] = "Subject name is required.";
        }

        // Check for duplicate Subject Code
        if (empty($errors)) {
            $existingSubject = findSubjectByCode($subject_code);
            if ($existingSubject) {
                $errors[] = "Duplicate Subject";
            }
        }

        // If there are no errors, proceed to add the subject
        if (empty($errors)) {
            $result = addSubject($subject_code, $subject_name);

            if ($result === true) {
                // Increment subject code for next entry
                $subject_code = $subject_code + 1; // Increment subject code for next use
                $subject_name = ''; // Clear subject name upon success
            } else {
                $errors[] = "System Error: Unable to add subject. Please try again.";
            }
        }
    }

    /**
     * Simulated function to check for duplicate subject codes.
     * Replace this with actual database query logic.
     */
    function findSubjectByCode($subject_code) {
        // Example: replace with real database query to find subject
        $allSubjects = fetchSubjects(); // Fetch all subjects
        foreach ($allSubjects as $subject) {
            if ($subject['subject_code'] === $subject_code) {
                return true;
            }
        }
        return false;
    }

    // Fetch the latest subject code from the database (Simulated function)
    function fetchLatestSubjectCode() {
        $subjects = fetchSubjects();
        $latestCode = 1000; // Default start code
        foreach ($subjects as $subject) {
            if ($subject['subject_code'] > $latestCode) {
                $latestCode = $subject['subject_code'];
            }
        }
        return $latestCode;
    }
    ?>

    <!-- Display error messages at the top -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>System Errors:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Add Subject Form -->
    <div class="card p-4 mb-5">
        <form method="POST">
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?= htmlspecialchars($subject_code) ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?= htmlspecialchars($subject_name) ?>">
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
                <?php $subjects = fetchSubjects();
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
include '../partials/footer.php';
?>
