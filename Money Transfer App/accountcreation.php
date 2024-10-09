<?php
require ("navbar1.php");
session_start();

$databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
$db = new SQLite3($databaseFile);

if (!$db) {
    die("Failed to connect to the database");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passport_num = $_POST['passport_num'];
    $phone_num = $_POST['phone_num'];
    $postcode = $_POST['postcode'];
    $house_num = $_POST['house_num'];
    $source_account_num = $_POST['source_account_num'];
    $source_sortcode = $_POST['source_sortcode'];
    $reason = $_POST['reason'];

    
    $query_insert_account = "INSERT INTO Users (password, email, passport_num, first_name, middle_name, last_name, source_account_num, source_sortcode, phone_num, postcode, house_num) 
    VALUES ('$password', '$email', '$passport_num', '$first_name', '$middle_name', '$last_name', '$source_account_num', '$source_sortcode', '$phone_num', '$postcode', '$house_num')";

    $result = $db->exec($query_insert_account);
    if ($result) {
      
        header("Location: index.php");
        exit();
    } else {
        
        echo "Error creating new account: " . $db->lastErrorMsg();
    }
}

?>
<html>

<head>
    <title>Sign up</title>
</head>

<body>
    <div class="application-container">
        <h2>Create a new account</h2>
        <form action ="index.php" method ="POST">
            <input type="First Name" name="first_name" placeholder="First name" required><br><br>
            <input type="Middle Name" name="middle_name" placeholder="Midde name"><br><br>
            <input type="Last Name" name="last_name" placeholder="Last name" required><br><br>
            <input type="Email" name="email" placeholder="Email" required><br><br>
            <input type="Password" name="password" placeholder="Password" required><br><br>
            <input type="Password" name="password" placeholder="Confirm Password" required><br><br>
            <input type="Passport Number" name="passport_num" placeholder="Passport Number" required><br><br>
            <input type="Phone Number" name="phone_num" placeholder="Phone Number" required><br><br>
            <input type="Postcode" name="postcode" placeholder="Postcode" required><br><br>
            <input type="House Number" name="house_num" placeholder="House Number" required><br><br>
            <input type="Source Account Number" name="source_account_num" placeholder="Source Account Number"
                required><br><br>
            <input type="Source Sortcode" name="source_sortcode" placeholder="Source Sortcode" required><br><br>


            <input type="text" name="reason" placeholder="Reason for Account" required><br><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>

</html>