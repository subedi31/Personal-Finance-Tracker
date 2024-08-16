<?php
    // Validate inputs
    if (empty($_POST['fullname']) || empty($_POST['email']) || empty($_POST['password'])) {
        die("All fields are required.");
    }

    $fullname = $_POST['fullname'];  
    $email = $_POST['email'];       
    $password = $_POST['password'];

    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "personalfinance";

    $conn = new mysqli($servername, $username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

    // Execute and check for errors
    if ($stmt->execute()) {
        echo "Signup Successful";
        // Optionally redirect to a different page
        // header("Location: success.php");
        // exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
?>

