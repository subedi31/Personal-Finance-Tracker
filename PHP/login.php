<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "personal_expenses_tracker";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password are not empty
    if (empty($email) || empty($password)) {
        echo "Both email and password are required!";
    } else {
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Verify the password
            if (password_verify($password, $user["password"])) {
                // Set session variables on successful login
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_id'] = $user['id'];

                // Redirect to a dashboard or home page after login
                header("Location: home.php");
                exit(); 
            } else {
                echo "Password doesn't match";
            }
        } else {
            echo "Email doesn't match";
        }
    }
}

$conn->close();
?>
