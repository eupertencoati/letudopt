<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="letudo.pt - Livraria online">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>letudo.pt</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-wrapper">
            <a href="index.php" class="logo">
                <span class="logo-icon">📚</span>
                <span class="logo-text">letudo<span>.pt</span></span>
            </a>
            <nav class="main-nav">
                <a href="index.php">Loja</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin.php">Administração</a>
                    <?php endif; ?>
                    <a href="logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php">Entrar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>