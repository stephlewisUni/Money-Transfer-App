<?php
session_start();
require("navbaruser.php");

$databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
$db = new SQLite3($databaseFile);
if (!$db) {
    die("Failed to connect to the database");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT Account.*, Currency.currency_name 
          FROM Account 
          INNER JOIN Currency ON Account.currencyID = Currency.currencyID 
          WHERE Account.userID = $user_id";
$result = $db->query($query);
$currencies = [];

$query_currencies = "SELECT * FROM Currency";
$result_currencies = $db->query($query_currencies);

if ($result_currencies) {
    
    while ($row_currency = $result_currencies->fetchArray()) {
        
        $currency_id = $row_currency['currencyID'];
        $currencies[$currency_id] = [
            'exchange_rate' => $row_currency['exchange_rate'],
            'business_rate' => $row_currency['business_rate']
        ];
    }
} else {
   
    echo "Failed to fetch currency data.";
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $send_from = $_POST["from_account"];
    $to_account_id = $_POST["to_account"];
    $amount = $_POST["amount"];

    $query_from_account = "SELECT * FROM Account WHERE accountID = $send_from AND userID = $user_id";
    $result_from_account = $db->query($query_from_account);
    $from_account = $result_from_account->fetchArray();

    $query_to_account = "SELECT * FROM Account WHERE accountID = $to_account_id";
    $result_to_account = $db->query($query_to_account);
    $to_account = $result_to_account->fetchArray();

    if (!$from_account || !$to_account) {
        $error = "Invalid account selection";
    } else {
        
        if (!isset($currencies[$from_account['currencyID']]) || !isset($currencies[$to_account['currencyID']])) {
            $error = "Currency data not available";
        } else {
            
            $exchange_rate = $from_account['business'] ? $currencies[$from_account['currencyID']]['business_rate'] : $currencies[$from_account['currencyID']]['exchange_rate'];
            $amount_to_transfer = $amount * $exchange_rate;

            
            if ($from_account['balance'] >= $amount_to_transfer) {
                
                $query_insert_transaction = "INSERT INTO Transactions (send_from, send_to, amount, status) VALUES ($send_from, $to_account_id, $amount, 'pending')";
                $db->exec($query_insert_transaction);

                $success = "Transfer pending approval!";
            } else {
                $error = "Insufficient balance in the source account";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Money</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Transfer Money</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="from_account">From Account:</label>
        <select name="from_account" id="from_account">
            <?php while ($row = $result->fetchArray()): ?>
                <option value="<?php echo $row['accountID']; ?>"><?php echo $row['currency_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <?php $result->reset(); ?>
        <label for="to_account">To Account:</label>
        <select name="to_account" id="to_account">
            <?php $result = $db->query($query);  ?>
            <?php while ($row = $result->fetchArray()): ?>
                <option value="<?php echo $row['accountID']; ?>"><?php echo $row['currency_name']; ?></option>
            <?php endwhile; ?>
        </select>
        <label for="amount">Amount:</label>
        <input type="text" name="amount" id="amount">
        <input type="submit" value="Transfer">
    </form>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
</div>
</body>
</html>

