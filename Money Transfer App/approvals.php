<?php
require("adminnav.php");

$databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
$db = new SQLite3($databaseFile);

if (!$db) {
    die("Failed to connect to the database");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve'])) {
      
        $transactionID = $_POST['transactionID'];
        $query_approve = "UPDATE Transactions SET status = 'approved' WHERE transactionID = $transactionID";
        $db->exec($query_approve);
    } elseif (isset($_POST['deny'])) {
      
        $transactionID = $_POST['transactionID'];
        $query_deny = "UPDATE Transactions SET status = 'denied', refunded = 1 WHERE transactionID = $transactionID";
        $db->exec($query_deny);

        
        $query_refund = "SELECT send_from, amount FROM Transactions WHERE transactionID = $transactionID";
        $result = $db->querySingle($query_refund, true);
        $sender_account = $result['send_from'];
        $amount = $result['amount'];

        $query_update_balance = "UPDATE Account SET balance = balance + $amount WHERE accountID = $sender_account";
        $db->exec($query_update_balance);
    }

   
    header("Location: adminhome.php");
    exit();
}

function printTransactions($db) {
    $query = "SELECT * FROM Transactions WHERE status = 'pending'";
    $result = $db->query($query);

    if ($result) {
        echo "<h2>All Transactions</h2>";
        echo "<table class='transaction-table'>";
        echo "<tr><th>ID</th><th>Sender Account</th><th>Receiver Account</th><th>Amount</th><th>Status</th><th>Action</th></tr>";

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['transactionID']}</td>";
            echo "<td>{$row['send_from']}</td>";
            echo "<td>{$row['send_to']}</td>";
            echo "<td>{$row['amount']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>";
            if ($row['status'] == 'pending') {
                echo "<form method='post' action='approvals.php'>";
                echo "<input type='hidden' name='transactionID' value='{$row['transactionID']}'>";
                echo "<button type='submit' name='approve'>Approve</button>";
                echo "<button type='submit' name='deny'>Deny</button>";
                echo "</form>";
            } else {
                echo "N/A";
            }
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Error: Unable to fetch transactions";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>All Transactions</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="application-container">
        <?php printTransactions($db); ?>
    </div>
</body>

</html>
