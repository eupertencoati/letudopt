<?php
require_once 'config.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $msg = "Preencha todos os campos.";
    } elseif ($password !== $confirm_password) {
        $msg = "As palavras-passe não coincidem.";
    } elseif (strlen($password) < 6) {
        $msg = "A palavra-passe deve ter pelo menos 6 caracteres.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM Utilizadores WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            $msg = "Este utilizador já existe.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO Utilizadores (username, password, tipo) VALUES (?, ?, 'comprador')");
            $stmt->execute([$username, $hash]);
            
            $msg = "Conta criada com sucesso! Pode fazer login.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo - letudo.pt</title>
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
            <h1>Criar Conta</h1>
            
            <?php if ($msg): ?>
                <div class="alert <?= strpos($msg, 'sucesso') !== false ? 'alert-success' : 'alert-error' ?>">
                    <?= $msg ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="register.php">
                <div class="form-group">
                    <label for="username">Utilizador</label>
                    <input type="text" id="username" name="username" required 
                           value="<?= sanitize($_POST['username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Palavra-passe</label>
                    <input type="password" id="password" name="password" required>
                    <small>Mínimo 6 caracteres</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar Palavra-passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Registar</button>
            </form>
            
            <p class="auth-link">
                Já tem conta? <a href="login.php">Entrar</a>
            </p>
        </div>
    </main>
</body>
</html>