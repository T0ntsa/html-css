<?php
session_start();
include 'config.php';  // Ensure config.php is included

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("? Tietokantayhteys ep‰onnistui: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ensure connection is successful before preparing query
    if ($conn) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];  // Set the role in the session
                echo "? Login successful!";
            } else {
                echo "? Invalid username or password!";
            }
        } else {
            echo "? User not found!";
        }
    } else {
        echo "? Database connection failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Kirjaudu sis‰‰n</h2>
    <form method="POST" action="login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
