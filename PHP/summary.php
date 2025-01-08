<?php
// Start the session
session_start();

// Database connection (replace with your actual credentials)
$host = 'localhost';
$dbname = 'personal_expenses_tracker';
$username = 'root'; // Default username for XAMPP
$password = ''; // Default password for XAMPP (usually empty)

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to log in to view the summary.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Initialize variables for totals
$totalIncome = $totalExpenses = $netProfit = $netLoss = 0;
$noData = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';

    if (empty($startDate) || empty($endDate)) {
        $error = "Please select both start and end dates.";
    } else {
        // Fetch total income
        $queryIncome = "SELECT SUM(amount) AS total_income FROM income WHERE user_id = ? AND income_date BETWEEN ? AND ?";
        $stmtIncome = $conn->prepare($queryIncome);
        $stmtIncome->bind_param('iss', $user_id, $startDate, $endDate);
        $stmtIncome->execute();
        $resultIncome = $stmtIncome->get_result();
        $totalIncome = $resultIncome->fetch_assoc()['total_income'] ?? 0;

        // Fetch total expenses
        $queryExpenses = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE user_id = ? AND expense_date BETWEEN ? AND ?";
        $stmtExpenses = $conn->prepare($queryExpenses);
        $stmtExpenses->bind_param('iss', $user_id, $startDate, $endDate);
        $stmtExpenses->execute();
        $resultExpenses = $stmtExpenses->get_result();
        $totalExpenses = $resultExpenses->fetch_assoc()['total_expenses'] ?? 0;

        // Check if there's no data
        if ($totalIncome == 0 && $totalExpenses == 0) {
            $noData = true;
        }

        // Calculate net profit or loss
        $netAmount = $totalIncome - $totalExpenses;
        if ($netAmount > 0) {
            $netProfit = $netAmount;
        } else {
            $netLoss = abs($netAmount);
        }

        // Close statements
        $stmtIncome->close();
        $stmtExpenses->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary</title>
    <link rel="stylesheet" href="../CSS/summary.css">
</head>
<body>
    <div id="container">
        <h1>
            <a href="home.php">
                <img src="../arrow.png" id="arrow">
            </a>
            Summary
        </h1>
        <form method="POST" action="summary.php">
            <div id="thedate">
                <input type="date" id="startDate" name="startDate" class="dat" value="<?php echo htmlspecialchars($_POST['startDate'] ?? ''); ?>">
                <h4>To</h4>
                <input type="date" id="endDate" name="endDate" class="dat" value="<?php echo htmlspecialchars($_POST['endDate'] ?? ''); ?>">
                <button type="submit" id="filter">Filter</button>
            </div>
        </form>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php elseif ($noData): ?>
            <p>There is no income and expense on the selected date.</p>
        <?php else: ?>
            <div id="total">
                <h4>Total Income</h4>
                <h4>Total Expense</h4>
                <h4>Net Profit</h4>
                <h4>Net Loss</h4>
            </div>

            <div id="amt">
                <input type="text" id="num" value="<?php echo number_format($totalIncome, 2); ?>" readonly>
                <input type="text" id="num" value="<?php echo number_format($totalExpenses, 2); ?>" readonly>
                <input type="text" id="num" value="<?php echo number_format($netProfit, 2); ?>" readonly>
                <input type="text" id="num" value="<?php echo number_format($netLoss, 2); ?>" readonly>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<script>
    const today = new Date();
    const maxDate = today.toISOString().split('T')[0];
    const minDate = new Date(today.setFullYear(today.getFullYear() - 5)).toISOString().split('T')[0];

    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    const filterButton = document.getElementById('filter');

    startDate.setAttribute('max', maxDate);
    endDate.setAttribute('max', maxDate);
    startDate.setAttribute('min', minDate);
    endDate.setAttribute('min', minDate);

    filterButton.addEventListener('click', function(event) {
        if (!startDate.value || !endDate.value) {
            alert("Please select both start and end dates.");
            event.preventDefault();
        } else if (startDate.value > endDate.value) {
            alert("Start date cannot be later than end date.");
            event.preventDefault();
        }
    });

    startDate.addEventListener('change', function() {
        if (startDate.value > endDate.value && endDate.value) {
            alert("Start date cannot be later than end date.");
            startDate.value = '';
        }
    });

    endDate.addEventListener('change', function() {
        if (endDate.value < startDate.value && startDate.value) {
            alert("End date cannot be earlier than start date.");
            endDate.value = '';
        }
    });
</script>
