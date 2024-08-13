<?php
echo "Request Method: " . $_SERVER["REQUEST_METHOD"];  // Debugging line
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);
    $errors = [];

    if (empty($fullname) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }

    if (empty($errors)) {
        $servername = "localhost";
        $username = "root";
        $db_password = "";
        $dbname = "sahayata";

        $conn = new mysqli($servername, $username, $db_password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            echo "Database connected successfully.<br>";
        }

        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "Signup successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
} else {
    echo "Invalid request method.";
}
?>
