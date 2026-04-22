<?php
require_once 'config.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode($_POST['carrinho'] ?? '[]', true);
    $nome = sanitize($_POST['nome'] ?? '');
    $nasc = $_POST['data_nascimento'] ?? '';
    $morada = sanitize($_POST['morada'] ?? '');
    
    // Validações
    if (empty($nome) || empty($nasc) || empty($morada)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (!validateAge($nasc)) {
        $erro = "Deve ter 18 anos ou mais para realizar uma compra.";
    } elseif (empty($cart)) {
        $erro = "O carrinho está vazio.";
    } else {
        // Verificar stock e calcular total
        $total = 0;
        $qtdTotal = 0;
        $valido = true;
        
        foreach ($cart as $item) {
            $stmt = $pdo->prepare("SELECT * FROM Produtos WHERE id = ?");
            $stmt->execute([$item['id']]);
            $prod = $stmt->fetch();
            
            if (!$prod) {
                $valido = false;
                $erro = "Produto inválido encontrado.";
                break;
            }
            
            if ($prod['quantidade'] < $item['qty']) {
                $valido = false;
                $erro = "Stock insuficiente para: " . $prod['nome'];
                break;
            }
            
            $total += $prod['preco'] * $item['qty'];
            $qtdTotal += $item['qty'];
        }
        
        if ($valido) {
            try {
                $pdo->beginTransaction();
                
                $stmt = $pdo->prepare("
                    INSERT INTO Encomendas (utilizador_id, nome_cliente, data_nascimento, morada, produtos_json, quantidade_total, preco_total)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $_SESSION['user_id'] ?? null,
                    $nome,
                    $nasc,
                    $morada,
                    json_encode($cart, JSON_UNESCAPED_UNICODE),
                    $qtdTotal,
                    $total
                ]);
                
                foreach ($cart as $item) {
                    $stmt = $pdo->prepare("UPDATE Produtos SET quantidade = quantidade - ? WHERE id = ?");
                    $stmt->execute([$item['qty'], $item['id']]);
                }
                
                $pdo->commit();
                $sucesso = "Encomenda realizada com sucesso! Obrigado, " . sanitize($nome) . ".";
                unset($_SESSION['carrinho']);
                
            } catch (Exception $e) {
                $pdo->rollBack();
                $erro = "Erro ao processar a encomenda.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - letudo.pt</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <a href="index.php">← Voltar à Loja</a>
        </div>
    </div>

    <main class="container checkout-container">
        <h1 class="page-title">Finalizar Compra</h1>
        
        <?php if ($erro): ?>
            <div class="alert alert-error">❌ <?= $erro ?></div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="alert alert-success">✅ <?= $sucesso ?></div>
            <p><a href="index.php" class="btn">Continuar Compras</a></p>
        <?php else: ?>
            <form method="POST" action="checkout.php" class="checkout-form">
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" required 
                           value="<?= sanitize($_POST['nome'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento *</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" required>
                    <small>Deve ter 18 anos ou mais</small>
                </div>
                
                <div class="form-group">
                    <label for="morada">Morada Completa *</label>
                    <textarea id="morada" name="morada" rows="4" required><?= sanitize($_POST['morada'] ?? '') ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Confirmar Encomenda</button>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 letudo.pt</p>
        </div>
    </footer>
    <!-- Cookie Banner (mesmo código do index.php) -->
    <div id="cookie-banner" class="cookie-banner">
        <div class="container cookie-banner-content">
            <div class="cookie-banner-text">
                <h3>Este website utiliza Cookies</h3>
                <p>
                    Ao clicar em "Aceitar todos os cookies", concorda com o armazenamento de cookies 
                    no seu dispositivo para melhorar a navegação no site, analisar a utilização do 
                    site e ajudar nas nossas iniciativas de marketing. 
                    <a href="#" class="cookie-policy-link">Política de Privacidade</a>
                </p>
            </div>
            <div class="cookie-banner-buttons">
                <button type="button" class="btn-cookie-settings" id="cookie-settings">
                    Definições de cookies
                </button>
                <button type="button" class="btn-cookie-accept" id="cookie-accept">
                    Aceitar todos os cookies
                </button>
            </div>
        </div>
    </div>

    <script src="js/cookies.js"></script>
</body>
</html>