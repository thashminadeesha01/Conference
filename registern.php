<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "register"; // The database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data (make sure the form sends POST data)
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$contact = $_POST['contact'] ?? '';
$session = $_POST['session'] ?? '';
$password = $_POST['password'] ?? '';

// Basic Validation
if (empty($name) || empty($email) || empty($password)) {
    echo "Error: Name, email, and password are required fields.";
    exit(); // Stop execution if essential fields are empty
}

// Check if the email format is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: Invalid email format.";
    exit(); // Stop execution if email format is invalid
}

// Check if the email already exists
$email_check_sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($email_check_sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email already exists
    echo "Error: The email address is already registered.";
} else {
    // Hash the password before inserting it into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Secure password hashing

    // Insert into the database
    $insert_sql = "INSERT INTO users (name, email, contact, session, password)
                   VALUES (?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sssss", $name, $email, $contact, $session, $hashed_password);

    if ($insert_stmt->execute()) {
        // Registration successful, redirect to login page
        header("Location: login.html"); // Redirect to the login page
        exit(); // Ensure no further code is executed
    } else {
        echo "Error: " . $insert_stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
