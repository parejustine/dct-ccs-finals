<?php
// All project functions should be placed here

session_start();
function postData($key)
{
    return $_POST["$key"];
}

function guardLogin()
{

    $dashboardPage = 'admin/dashboard.php';

    if (isset($_SESSION['email'])) {
        header("Location: $dashboardPage");
    }
}

function guardDashboard()
{
    $loginPage = '../index.php';
    if (!isset($_SESSION['email'])) {
        header("Location: $loginPage");
    }
}


function getConnection()
{
    // Database configuration
    $host = 'localhost'; // Replace with your host
    $dbName = 'dct-ccs-finals'; // Replace with your database name
    $username = 'root'; // Replace with your username
    $password = ''; // Replace with your password
    $charset = 'utf8mb4'; // Recommended for UTF-8 support

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

function login($email, $password)
{
    $validateLogin = validateLoginCredentials($email, $password);

    if (!empty($validateLogin)) {
        echo displayErrors($validateLogin);
        return;
    }


    // Get database connection
    $conn = getConnection();

    // Convert the input password to MD5
    $hashedPassword = md5($password);

    // SQL query to check if the email and hashed password match
    $query = "SELECT * FROM users WHERE email = :email AND password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    $stmt->execute();

    // Fetch the user data if found
    $user = $stmt->fetch();

    if ($user) {
        // Login successful
        // return $user;
        $_SESSION['email'] = $user['email'];
        header("Location: admin/dashboard.php");
    } else {
        // Login failed
        echo displayErrors(["Invalid email or password"]);
    }
}


function validateLoginCredentials($email, $password)
{
    // Initialize the $errors array
    $errors = [];
    // Check for empty fields and collect errors
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


function displayErrors($errors = [])
{
    if (empty($errors)) return "";

    $errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>System Alerts</strong><ul>';

    foreach ($errors as $error) {
        // Ensure $error is a string or handle arrays properly
        if (is_array($error)) {
            $errorHtml .= '<li>' . implode(", ", $error) . '</li>';
        } else {
            $errorHtml .= '<li>' . htmlspecialchars($error) . '</li>';
        }
    }

    $errorHtml .= '</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

    return $errorHtml;
}
// ******************************************************************

// Logout Fuction
function logout($indexPage)
{
    // Unset the 'email' session variable
    unset($_SESSION['email']);

    // Destroy the session
    session_destroy();

    // Redirect to the login page (index.php)
    header("Location: $indexPage");
    exit;
}
// ************************************************************************

// Subject
function isPost()
{
    return $_SERVER['REQUEST_METHOD'] == "POST";
}

function addSubject($subject_code, $subject_name)
{

    // Get database connection
    $conn = getConnection();

    try {
        // Prepare SQL query to insert subject into the database
        $sql = "INSERT INTO subjects (subject_code, subject_name) VALUES (:subject_code, :subject_name)";
        $stmt = $conn->prepare($sql);

        // Bind parameters to the SQL query
        $stmt->bindParam(':subject_code', $subject_code);
        $stmt->bindParam(':subject_name', $subject_name);

        // Execute the query
        if ($stmt->execute()) {
            return true; // Subject successfully added
        } else {
            return "Failed to add subject."; // Query execution failed
        }
    } catch (PDOException $e) {
        // Return error message if the query fails
        return "Error: " . $e->getMessage();
    }
}

function fetchSubjects()
{
    // Get the database connection
    $conn = getConnection();

    try {
        // Prepare SQL query to fetch all subjects
        $sql = "SELECT * FROM subjects";
        $stmt = $conn->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Fetch all subjects as an associative array
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the list of subjects
        return $subjects;
    } catch (PDOException $e) {
        // Return an empty array in case of error
        return [];
    }
}


function EditSubject($subject_code, $subject_name, $redirectPage)
{

    try {
        // Get the database connection
        $pdo = getConnection();

        // Prepare the SQL query for updating the subject
        $sql = "UPDATE subjects SET subject_name = :subject_name WHERE subject_code = :subject_code";
        $stmt = $pdo->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':subject_name', $subject_name, PDO::PARAM_STR);
        $stmt->bindParam(':subject_code', $subject_code, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>window.location.href = '$redirectPage';</script>";
        } else {
            //echo displayErrors(["Failed to update subject!"]);
            return 'Failed to update subject';
        }
    } catch (PDOException $e) {
        // echo displayErrors(["Error: " . $e->getMessage()]);
        return "Error: " . $e->getMessage();
    }
}
function getSubjectCode($subject_code)
{
    $pdo = getConnection();
    $query = "SELECT * FROM subjects WHERE subject_code = :subject_code";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':subject_code' => $subject_code]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteSubject($subject_code, $redirectPage)
{
    try {
        // Get the database connection
        $pdo = getConnection();

        // Prepare the SQL query to delete the subject
        $sql = "DELETE FROM subjects WHERE subject_code = :subject_code";
        $stmt = $pdo->prepare($sql);

        // Bind the parameter
        $stmt->bindParam(':subject_code', $subject_code, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>window.location.href = '$redirectPage';</script>";
        } else {
            return "Failed to delete the subject with code $subject_code.";
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}