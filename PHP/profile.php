<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your profile.";
    exit();
}

// Database connection
$host = "localhost"; 
$db_user = "root";   
$db_password = "";   
$db_name = "personal_expenses_tracker"; 
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user data based on session user_id
$user_id = $_SESSION['user_id']; 
$sql = "SELECT fullname, email, phone_no, address FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user data is found
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User data not found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile for User</title>
    <link rel="stylesheet" href="../CSS/profile.css">
</head>
<body>
    <div id="main">
        <div>
            <img src="../hh.jpg" alt="User Profile Image">
            <h2>Your Information</h2>
        
            <table>
                <tr>
                    <th>Full Name:</th>
                    <td><input type="text" value="<?php echo ($user['fullname']); ?>" readonly></td>
                </tr>
                <tr>
                    <th>Email Address:</th>
                    <td><input type="email" value="<?php echo ($user['email']); ?>" readonly></td>
                </tr>
                <tr>
                    <th>Phone Number:</th>
                    <td><input type="text" value="<?php echo ($user['phone_no']); ?>" readonly></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td><input type="text" value="<?php echo ($user['address']); ?>" readonly></td>
                </tr>
            </table>
            <br><br>

            <div id="link">
                <a href="../signup.html" id="one">Create New Account</a>
                <a href="logout.php" id="two">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
