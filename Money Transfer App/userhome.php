<?php
require("navbaruser.php");
session_start();

$user_id = $_SESSION['user_id'];

$databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
$db = new SQLite3($databaseFile);

if (!$db) {
    die("Failed to connect to the database");
}

$query = "SELECT Account.accountID, Account.balance, Currency.currency_name 
          FROM Account 
          JOIN Currency ON Account.currencyID = Currency.currencyID 
          WHERE Account.userID = '$user_id'";
$result = $db->query($query);


?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Accounts</h1>
        <table>
            <tr>
                <th>Currency</th>
                <th>Balance</th>
            </tr>
            <?php while ($row = $result->fetchArray()) { ?>
                <tr>
                    <td><?php echo $row['currency_name']; ?></td>
                    <td><?php echo $row['balance']; ?></td>
                </tr>
            <?php } ?>
        </table>
        <a href="transfer.php"><button>Transfer Money</button></a>
    </div>
</body>

</html>
