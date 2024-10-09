<?php
require ("adminnav.php");
session_start();

$user_id = $_SESSION['user_id'];

$databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
$db = new SQLite3($databaseFile);

if (!$db) {
    die("Failed to connect to the database");
}
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

</body>
</head>