<?php
require '../functions.php';

// Start the session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle form submission
list($errors, $code, $name) = handleAddSubjectForm();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="custom-dashboard.css">
</head>
<body>

<!-- Main Content -->
<div class="main-content">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../admin/dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
        </ol>
    </nav>

    <h3 class="mb-4">Add a New Subject</h3>

    <!-- Display errors if any -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h5>Error System</h5>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="add.php">
        <div class="mb-3">
            <label for="subjectCode" class="form-label">Subject Code</label>
            <input type="text" class="form-control" id="subjectCode" name="subjectCode" placeholder="Enter Subject Code" value="<?php echo htmlspecialchars($code ?? '', ENT_QUOTES, 'UTF-8'); ?>" required oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
        </div>
        <div class="mb-3">
            <label for="subjectName" class="form-label">Subject Name</label>
            <input type="text" class="form-control" id="subjectName" name="subjectName" placeholder="Enter Subject Name" value="<?php echo htmlspecialchars($name ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Subject</button>
    </form>

    <hr class="my-4">
    <h4>Subject List</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Option</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $subjects = getSubjects(getConnection());
        if ($subjects->rowCount() > 0) {
            foreach ($subjects as $subject) {
                echo "<tr>
                        <td>{$subject['subject_code']}</td>
                        <td>{$subject['subject_name']}</td>
                        <td><a href='#' class='btn btn-danger btn-sm'>Delete</a></td>
                    </tr>";
            }
        }
        ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
