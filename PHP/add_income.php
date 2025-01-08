<?php
// Start the session to retrieve the user_id
session_start();

// Database connection
$host = 'localhost';
$db = 'personal_expenses_tracker';
$user = 'root'; // your database username
$pass = ''; // your database password

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

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Get form data from POST request
$date = $_POST['date'];
$category = $_POST['Category'];
$amount = $_POST['amount'];

// If the "Other" category is selected, use the custom category
if ($category === 'Other' && isset($_POST['otherCategory'])) {
    $category = $_POST['otherCategory'];
}

// Prepare the SQL query to insert data into the income table
$sql = "INSERT INTO income (user_id, income_date, category, amount) 
        VALUES ('$user_id', '$date', '$category', '$amount')";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Income added successfully!";
    // Redirect to home page or history page after submission
    header("Location: home.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
