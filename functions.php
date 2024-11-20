<?php
// Start the session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Utility function to get POST data
function postData($key)
{
    return isset($_POST[$key]) ? $_POST[$key] : null;
}

// Guard functions
function guardLogin()
{
    $dashboardPage = 'admin/dashboard.php';

    if (isset($_SESSION['email'])) {
        header("Location: $dashboardPage");
        exit();
    }
}

function guardDashboard()
{
    $loginPage = '../index.php';
    if (!isset($_SESSION['email'])) {
        header("Location: $loginPage");
        exit();
    }
}

// Database connection function
function getConnection()
{
    $host = 'localhost'; 
    $dbName = 'dct-ccs-finals'; 
    $username = 'root'; 
    $password = ''; 
    $charset = 'utf8mb4'; 

    try {
        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Login function
function login($email, $password)
{
    $validateLogin = validateLoginCredentials($email, $password);

    if (!empty($validateLogin)) {
        echo displayErrors($validateLogin);
        return;
    }

    $conn = getConnection();
    $hashedPassword = md5($password);

    $query = "SELECT * FROM users WHERE email = :email AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['email'] = $user['email'];
        header("Location: admin/dashboard.php");
        exit();
    } else {
        echo displayErrors(["Invalid email or password"]);
    }
}

// Validate login credentials
function validateLoginCredentials($email, $password)
{
    $errors = [];
    if (empty($email) && !empty($password)) {
        $errors[] = "Email is required";
        $errors[] = "Invalid password";
    } else if (!empty($email) && empty($password)) {
        $errors[] = "Invalid Email";
        $errors[] = "Password is required";
    } else {
        if (empty($email)) {
            $errors[] = "Email is required";
        }
        if (empty($password)) {
            $errors[] = "Password is required";
        }
    }

    return $errors;
}

// Error display function
function displayErrors($errors = [])
{
    if (empty($errors)) return "";

    $errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>System Alerts</strong><ul>';

    foreach ($errors as $error) {
        if (is_array($error)) {
            $errorHtml .= '<li>' . implode(", ", $error) . '</li>';
        } else {
            // Ensure htmlspecialchars is used properly to avoid deprecation warning
            $errorHtml .= '<li>' . htmlspecialchars($error ?? '', ENT_QUOTES, 'UTF-8') . '</li>';
        }
    }

    $errorHtml .= '</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

    return $errorHtml;
}

// Logout function
function logout($indexPage)
{
    unset($_SESSION['email']);
    session_destroy();
    header("Location: $indexPage");
    exit();
}

// Add subject functionality
function addSubject($conn, $code, $name)
{
    $sql = "INSERT INTO subjects (subject_code, subject_name) VALUES (:code, :name)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':code', $code);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
}

// Check if subject already exists
function isDuplicate($conn, $code, $name)
{
    $sql = "SELECT * FROM subjects WHERE subject_code = :code OR subject_name = :name";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':code', $code);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $stmt->rowCount() > 0;
}

// Get all subjects
function getSubjects($conn)
{
    $sql = "SELECT * FROM subjects";
    return $conn->query($sql);
}

// Function to handle form submission for adding subjects
function handleAddSubjectForm()
{
    $errors = [];
    $code = postData('subjectCode');
    $name = postData('subjectName');

    // Validate the input fields
    if (empty($code)) {
        $errors[] = "Subject Code is required";
    }

    if (empty($name)) {
        $errors[] = "Subject Name is required";
    }

    // Check if subject already exists
    if (empty($errors) && isDuplicate(getConnection(), $code, $name)) {
        $errors[] = "Duplicate Subject";
    }

    // If no errors, insert the new subject
    if (empty($errors)) {
        addSubject(getConnection(), $code, $name);
        // Clear the form values after successful insertion
        $code = "";
        $name = "";
    }

    return [$errors, $code, $name];
}
?>
