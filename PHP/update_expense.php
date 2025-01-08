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

// Fetch the existing record
$query = "SELECT * FROM expenses WHERE expense_id = $expense_id AND user_id = $user_id";
$result = $conn->query($query);
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $expense_date = $_POST['expense_date'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    // Check if custom category is entered
    if ($category == 'Other' && isset($_POST['otherCategory'])) {
        $category = $_POST['otherCategory'];
    }

    // Update the record
    $update_query = "UPDATE expenses SET expense_date='$expense_date', category='$category', amount='$amount' WHERE expense_id=$expense_id";
    if ($conn->query($update_query) === TRUE) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Expense</title>
    <script>
        // Function to handle category change and show input field for "Other"
        function handleCategoryChange() {
            var categorySelect = document.getElementsByName('category')[0];
            var customCategoryInput = document.getElementById('customCategoryInput');

            // If "Other" is selected, show the input field
            if (categorySelect.value === 'Other') {
                // If the input field is not already shown, create it
                if (!customCategoryInput) {
                    var inputField = document.createElement('input');
                    inputField.type = 'text';
                    inputField.id = 'customCategoryInput';
                    inputField.name = 'otherCategory';
                    inputField.placeholder = 'Enter your custom category';  // Placeholder text
                    var categoryRow = document.getElementById('cat');
                    categoryRow.appendChild(inputField);
                }
            } else {
                // Remove the input field if "Other" is not selected
                if (customCategoryInput) {
                    customCategoryInput.remove();
                }
            }
        }

        // Validate form inputs
        function validateForm() {
            var expenseDate = document.getElementsByName('expense_date')[0].value;
            var amount = document.getElementsByName('amount')[0].value;
            var today = new Date();
            var enteredDate = new Date(expenseDate);

            // Date validation
            var fiveYearsAgo = new Date();
            fiveYearsAgo.setFullYear(today.getFullYear() - 5);

            if (enteredDate > today) {
                alert("Expense date cannot be in the future.");
                return false;
            }

            if (enteredDate < fiveYearsAgo) {
                alert("Expense date cannot be older than five years.");
                return false;
            }

            // Amount validation
            if (amount <= 0) {
                alert("Amount must be greater than zero.");
                return false;
            }

            return true;
        }

        // Call the function to handle the initial category selection when the page loads
        window.onload = function() {
            handleCategoryChange(); // Check if 'Other' was previously selected
        };
    </script>
</head>
<body>
    <h1>Update Expense</h1>

    <form method="POST" onsubmit="return validateForm()">
        <label for="expense_date">Date: </label>
        <input type="date" name="expense_date" value="<?php echo $row['expense_date']; ?>" required><br>

        <label for="category">Category: </label>
        <select name="category" onchange="handleCategoryChange()" required>
            <option value="Rent" <?php if ($row['category'] == 'Rent') echo 'selected'; ?>>Rent</option>
            <option value="Food" <?php if ($row['category'] == 'Food') echo 'selected'; ?>>Food</option>
            <option value="Cloths" <?php if ($row['category'] == 'Cloths') echo 'selected'; ?>>Cloths</option>
            <option value="Travel" <?php if ($row['category'] == 'Travel') echo 'selected'; ?>>Travel</option>
            <option value="Other" <?php if ($row['category'] == 'Other') echo 'selected'; ?>>Other</option>
        </select><br>

        <div id="cat"></div> <!-- This will contain the custom category input if "Other" is selected -->

        <label for="amount">Amount: </label>
        <input type="number" name="amount" value="<?php echo $row['amount']; ?>" required><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>

<?php
$conn->close();
?>
