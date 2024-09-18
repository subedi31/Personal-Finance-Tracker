<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "personalfinance";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error messages
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $phone_no = trim($_POST['phone_no']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmpass = $_POST['confirm-password'];

    // Validate fullname
    if (!preg_match("/^[a-zA-Z\s'-]+$/", $fullname)) {
        $errors[] = "Name is not valid! It must not contain numbers or special characters.";
    }
    // validate phone_no
    if (!preg_match('/^[0-9]{10}$/', $phone_no)) {
        $errors[] = "Phone number is not valid";
    }
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password
    if (mb_strlen($password) < 8) {
        $errors[] = "Your Password Must Contain At Least 8 Characters!";
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        $errors[] = "Your Password Must Contain At Least 1 Capital Letter!";
    } elseif (!preg_match("#[\W]+#", $password)) {
        $errors[] = "Your Password Must Contain At Least 1 Special Character!";
    } elseif ($password !== $confirmpass) {
        $errors[] = "Passwords must match!";
    }

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT email FROM user WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Email already exists. Please login.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO user (fullname, email, password, phone_no, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fullname, $email, $hashed_password, $phone_no, $address );

            if ($stmt->execute()) {
                echo "Signup successful!";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }

    // Display errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<h4 class='error'>$error</h4>";
        }
    }
}

$conn->close();
?>
