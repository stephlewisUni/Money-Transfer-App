<?php
require("adminnav.php");

$databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
$db = new SQLite3($databaseFile);

if (!$db) {
    die("Failed to connect to the database");
}

function printTableData($db, $tableName) {
    $query = "SELECT * FROM $tableName";
    $result = $db->query($query);

    if ($result) {
        echo "<h3>$tableName Data</h3>";
        echo "<table class='data-table'>";
        
       
        $columns = array();
        $columnResult = $db->query("PRAGMA table_info($tableName)");
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
        echo "Error: Unable to fetch data from $tableName";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>All Data</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="application-container">
        <h2>All Data</h2>
        <?php 
            $tables = array("Account", "Employee", "Income_Request", "Transactions", "Users");
            foreach ($tables as $table) {
                printTableData($db, $table);
            }
        ?>
    </div>
</body>

</html>
