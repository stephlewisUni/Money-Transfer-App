<?php
require ("navbaruser.php");
session_start();

$user_id = $_SESSION['user_id'];

$databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
$db = new SQLite3($databaseFile);

if (!$db) {
    die("Failed to connect to the database");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $currency_id = $_POST['currency'];
    $starting_funds = $_POST['starting_funds'];
    $reason = $_POST['reason'];


    $query_check_funds = "SELECT balance FROM Account WHERE userID = $user_id AND currencyID = 0";
    $result_check_funds = $db->querySingle($query_check_funds);

    if ($result_check_funds >= $starting_funds || $currency_id == 0) {

        $query_deduct_funds = "UPDATE Account SET balance = balance - $starting_funds WHERE userID = $user_id AND currencyID = 0";
        $db->exec($query_deduct_funds);


        $query_insert_account = "INSERT INTO Account (userID, balance, business, currencyID) VALUES ($user_id, $starting_funds, '$reason', $currency_id)";
        $db->exec($query_insert_account);

        echo "New account created successfully!";
    } else {
        echo "Error: Not enough funds in the account with currencyID 0.";
    }
}

$query = "SELECT * FROM Currency";
$result = $db->query($query);

?>

<html>

<head>
    <title>Sign up</title>
</head>

<body>
    <div class="application-container">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <select name="currency" id="currency">
                <?php
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    echo "<option value='" . $row['currencyID'] . "'>" . $row['currency_name'] . "</option>";
                }
                ?>
            </select><br><br>
            <input type="decimal" name="starting_funds" placeholder="0.00" required><br><br>
            <input type="text" name="reason" placeholder="reason for new account" required><br><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>

</html>