<?php
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

// SQL queries to fetch income and expense data
$income_query = "SELECT income_id, income_date, category, amount FROM income WHERE user_id = $user_id ORDER BY income_date DESC";
$expense_query = "SELECT expense_id, expense_date, category, amount FROM expenses WHERE user_id = $user_id ORDER BY expense_date DESC";

// Execute the queries
$income_result = $conn->query($income_query);
$expense_result = $conn->query($expense_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income and Expenses</title>
    <link rel="stylesheet" href="../CSS/first_page.css">
</head>
<body>
    <div id="main">
        <h1>Your Today's Records</h1>

        <div id="forall">
            <div id="in">
                <a href="../add_income.html">Add Income</a>
            </div>

            <div id="ex">
                <a href="../add_expense.html">Add Expenses</a>
            </div>

            <div id="summ">
            <a href="summary.php">Summary</a>
            </div>

            <div id="user">
                <a href="profile.php"><img src="../user.svg" alt="User Profile"></a>
            </div>
        </div>

        <h2>History</h2>

        <table border="1">
            <tr>
                <th>Date</th>
                <th>Income/Expenses</th>
                <th>Amount</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>

            <?php
            // Display income records
            while ($row = $income_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['income_date'] . "</td>";
                echo "<td>Income</td>";
                echo "<td>" . $row['amount'] . "</td>";
                echo "<td>" . $row['category'] . "</td>";
                echo "<td>
                        <a href='update_income.php?id=" . $row['income_id'] . "'>Update</a> | 
                        <a href='delete_income.php?id=" . $row['income_id'] . "'>Delete</a>
                      </td>";
                echo "</tr>";
            }

            // Display expense records
            while ($row = $expense_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['expense_date'] . "</td>";
                echo "<td>Expense</td>";
                echo "<td>" . $row['amount'] . "</td>";
                echo "<td>" . $row['category'] . "</td>";
                echo "<td>
                        <a href='update_expense.php?id=" . $row['expense_id'] . "'>Update</a> | 
                        <a href='delete_expense.php?id=" . $row['expense_id'] . "'>Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
