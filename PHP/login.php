<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "personalfinance";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($user) {
        if (password_verify($password, $user["password"])) {
            echo"login successfull";
        } else {
            echo"Password doesn't match";
        }

    } else {
        echo"Email doesnt match";
    }
}

$conn->close();
?>
