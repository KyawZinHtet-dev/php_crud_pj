<?php

session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
    echo "
        <script>
        alert('Register and Login first!');
        window.location.href = 'login.php';
        </script>";
}

require './config.php';
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM posts WHERE id = $id");
$stmt->execute();
header('location:./index.php');
