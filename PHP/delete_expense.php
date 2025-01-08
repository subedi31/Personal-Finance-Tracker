<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$expense_id = $_GET['id'];

$host = 'localhost';
$db = 'personal_expenses_tracker';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$delete_query = "DELETE FROM expenses WHERE expense_id = $expense_id AND user_id = $user_id";
if ($conn->query($delete_query) === TRUE) {
    header("Location: home.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
