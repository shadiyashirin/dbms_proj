<?php
session_start();
include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $user = $_POST['username'];
    $pass = $_POST['password'];

    try {
        // Connect to database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if username exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $user);
        $stmt->execute();

        // Fetch user data
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        // If user exists, verify password
        if ($user_data && password_verify($pass, $user_data['password'])) {
            // Successful login
            $_SESSION['username'] = $user;  // Store username in session
            header("Location: ../home.php");   // Redirect to home page
            exit();
        } else {
            // Invalid credentials
            echo "<p style='color: red;'>Invalid username or password.</p>";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
}
?>
