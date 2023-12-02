<?php
session_start();

// Sample user credentials (in a real application, use a database)
$validUsername = 'admin';
$validPassword = 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['username'])) {
    echo json_encode(['loggedIn' => true, 'username' => $_SESSION['username'], 'password' => $_SESSION['password']]);
    exit;
}
?>
