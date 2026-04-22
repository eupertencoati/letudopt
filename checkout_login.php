<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM Utilizadores WHERE username = ? AND tipo = 'comprador'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_tipo'] = $user['tipo'];
        $_SESSION['username'] = $user['username'];
        
        header('Location: checkout_final.php');
        exit;
    } else {
        header('Location: checkout_choice.php?error=1');
        exit;
    }
}
?>