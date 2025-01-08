<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'personal_expenses_tracker';
$user = 'root'; 
$pass = ''; 

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

// Get form data
$date = $_POST['date'];
$category = $_POST['Category'];
$amount = $_POST['amount'];

// Check if custom category is entered
if ($category == 'Other' && isset($_POST['otherCategory'])) {
    $category = $_POST['otherCategory'];
}

// Insert data into the database
$query = "INSERT INTO expenses (user_id, expense_date, category, amount) VALUES ('$user_id', '$date', '$category', '$amount')";

if ($conn->query($query) === TRUE) {
    echo "Expense record added successfully.";
    // Redirect back to the home page or to another page
    header("Location: home.php");
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
