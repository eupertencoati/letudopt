<?php
require_once 'config.php';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM Utilizadores WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_tipo'] = $user['tipo'];
        $_SESSION['username'] = $user['username'];
        
        header('Location: ' . ($user['tipo'] === 'admin' ? 'admin.php' : 'index.php'));
        exit;
    } else {
        $erro = "Utilizador ou palavra-passe incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - letudo.pt</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <a href="index.php">← Voltar à Loja</a>
        </div>
    </div>

    <main class="container auth-container">
        <div class="auth-box">
            <h1>Login</h1>
            
            <?php if ($erro): ?>
                <div class="alert alert-error">❌ <?= $erro ?></div>
            <?php endif; ?>
            
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="username">Utilizador</label>
                    <input type="text" id="username" name="username" required 
                           value="<?= sanitize($_POST['username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Palavra-passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </form>
            
            <p class="auth-link">
                Não tem conta? <a href="register.php">Registar</a>
            </p>
        </div>
    </main>
</body>
</html>