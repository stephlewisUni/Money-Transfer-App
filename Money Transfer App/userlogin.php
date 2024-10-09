<?php
require("navbar1.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db';
    $db = new SQLite3($databaseFile);
    if (!$db) {
        die("Failed to connect to the database");
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    
    $hashed_password = hash('md2', $password);

    $sql = "SELECT * FROM Users WHERE userID=:username AND password=:password";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $hashed_password);
    $result = $stmt->execute();

    session_start();

    
    if ($row = $result->fetchArray()) {
        $_SESSION['user_id'] = $row['userID']; 

        header("Location: userhome.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="userlogin.php" method="POST">
            <input type="text" name="username" placeholder="Username (userID)" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>
