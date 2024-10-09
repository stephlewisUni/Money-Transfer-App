<?php
session_start();
require("navbaruser.php");

$databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
$db = new SQLite3($databaseFile);
if (!$db) {
    die("Failed to connect to the database");
}

$user_id = $_SESSION['user_id'];


$query_history = "SELECT * FROM Transactions WHERE send_from IN (SELECT accountID FROM Account WHERE userID = $user_id) OR send_to IN (SELECT accountID FROM Account WHERE userID = $user_id)";
$result_history = $db->query($query_history);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer History</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Transfer History</h2>
    <table>
        <tr>
            <th>Transfer ID</th>
            <th>From Account</th>
            <th>To Account</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $result_history->fetchArray()): ?>
            <tr>
                <td><?php echo $row['transactionID']; ?></td>
                <td><?php echo $row['send_from']; ?></td>
                <td><?php echo $row['send_to']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['date']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
