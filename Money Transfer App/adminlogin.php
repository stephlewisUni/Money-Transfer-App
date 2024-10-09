<?php require("navbar1.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $databaseFile = 'C:\xampp\htdocs\Money Transfer App/CTAdb.db'; 
    $db = new SQLite3($databaseFile);

    if (!$db) {
        die("Failed to connect to the database");
    }
    
    $staffID = $_POST['staffID'] ?? '';
    $password = $_POST['password'] ?? '';
    
    
    $hashed_password = hash('md2', $password);
    
    
    $sql = "SELECT * FROM Employee WHERE employeeID=:staffID AND password=:password";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':staffID', $staffID);
    $stmt->bindValue(':password', $hashed_password);
    $result = $stmt->execute();
    
   
    if ($row = $result->fetchArray()) {
        
        echo "Login successful!";
        header('Location: adminhome.php');
        exit;
    } else {
        echo "Invalid username or password.";
    }
   
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
<div class="login-page">
    <div class="login-container">
        <h2>Login</h2>
        <form action="adminlogin.php" method="POST">
            <input type="text" name="staffID" placeholder="Username (staffID)" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <input type="submit" value="Login">
        </form>
    </div>
</div>
</body>

</html>