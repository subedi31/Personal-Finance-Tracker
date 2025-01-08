<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$income_id = $_GET['id'];

$host = 'localhost';
$db = 'personal_expenses_tracker';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the existing record
$query = "SELECT * FROM income WHERE income_id = $income_id AND user_id = $user_id";
$result = $conn->query($query);
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $income_date = $_POST['income_date'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];

    // Check if custom category is entered
    if ($category == 'Other' && isset($_POST['otherCategory'])) {
        $category = $_POST['otherCategory'];
    }

    // Update the record
    $update_query = "UPDATE income SET income_date='$income_date', category='$category', amount='$amount' WHERE income_id=$income_id";
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
    <title>Update Income</title>
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
                    inputField.placeholder = 'Enter your custom category';
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
            var incomeDate = document.getElementsByName('income_date')[0].value;
            var amount = document.getElementsByName('amount')[0].value;
            var today = new Date();
            var enteredDate = new Date(incomeDate);

            // Date validation
            var fiveYearsAgo = new Date();
            fiveYearsAgo.setFullYear(today.getFullYear() - 5);

            if (enteredDate > today) {
                alert("Income date cannot be in the future.");
                return false;
            }

            if (enteredDate < fiveYearsAgo) {
                alert("Income date cannot be older than five years.");
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
    <h1>Update Income</h1>

    <form method="POST" onsubmit="return validateForm()">
        <label for="income_date">Date: </label>
        <input type="date" name="income_date" value="<?php echo $row['income_date']; ?>" required><br>

        <label for="category">Category: </label>
        <select name="category" onchange="handleCategoryChange()" required>
            <option value="Salary" <?php if ($row['category'] == 'Salary') echo 'selected'; ?>>Salary</option>
            <option value="Freelancing" <?php if ($row['category'] == 'Freelancing') echo 'selected'; ?>>Freelancing</option>
            <option value="Interest" <?php if ($row['category'] == 'Interest') echo 'selected'; ?>>Interest</option>
            <option value="Business" <?php if ($row['category'] == 'Business') echo 'selected'; ?>>Business</option>
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
