<?php
require("adminnav.php");

$databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
$db = new SQLite3($databaseFile);

if (!$db) {
    die("Failed to connect to the database");
}

function printCurrencyData($db) {
    $query = "SELECT * FROM Currency";
    $result = $db->query($query);

    if ($result) {
        echo "<h2>Currency Data</h2>";
        echo "<table class='currency-table'>";
        
        
        $columns = array();
        $columnResult = $db->query("PRAGMA table_info('Currency')");
        while ($row = $columnResult->fetchArray(SQLITE3_ASSOC)) {
            $columns[] = $row['name'];
        }

        echo "<tr>";
        foreach ($columns as $column) {
            echo "<th>$column</th>";
        }
        echo "</tr>";

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>";
            foreach ($row as $column) {
                echo "<td>$column</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Error: Unable to fetch data from Currency table";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Currency Data</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="application-container2">
        <?php printCurrencyData($db); ?>
    </div>
</body>

</html>
