<?php
require_once 'config.php';
requireLogin();

$erro = '';
$sucesso = '';

// Obter dados do carrinho
$cart = json_decode($_SESSION['carrinho'] ?? '[]', true);

if (empty($cart)) {
    header('Location: index.php');
    exit;
}

// Se já temos dados da sessão (do registo), usá-los
$nome = $_SESSION['checkout_data']['nome'] ?? '';
$data_nascimento = $_SESSION['checkout_data']['data_nascimento'] ?? '';
$morada = $_SESSION['checkout_data']['morada'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitize($_POST['nome'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $morada = sanitize($_POST['morada'] ?? '');
    
    // Validações
    if (empty($nome) || empty($data_nascimento) || empty($morada)) {
        $erro = "Todos os campos são obrigatórios.";
    } else {
        // Validar idade >= 18
        $hoje = new DateTime();
        $nascimento = new DateTime($data_nascimento);
        $idade = $hoje->diff($nascimento)->y;
        
        if ($idade < 18) {
            $erro = "Deve ter 18 anos ou mais para realizar uma compra.";
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
                        $_SESSION['user_id'],
                        $nome,
                        $data_nascimento,
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
                    unset($_SESSION['checkout_data']);
                    
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $erro = "Erro ao processar a encomenda.";
                }
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
    <title>Finalizar Encomenda - letudo.pt</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <a href="index.php">← Voltar à Loja</a>
        </div>
    </div>

    <main class="container checkout-final-container">
        <h1 class="page-title">Finalizar Encomenda</h1>
        
        <?php if ($erro): ?>
            <div class="alert alert-error">❌ <?= $erro ?></div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="alert alert-success">
                <h2>✅ <?= $sucesso ?></h2>
                <p>A sua encomenda foi processada com sucesso!</p>
                <p><a href="index.php" class="btn btn-primary">Continuar Compras</a></p>
            </div>
        <?php else: ?>
            <div class="checkout-summary">
                <h3>Resumo da Encomenda</h3>
                <div class="cart-items-summary">
                    <?php 
                    $total = 0;
                    foreach ($cart as $item): 
                        $subtotal = $item['preco'] * $item['qty'];
                        $total += $subtotal;
                    ?>
                        <div class="cart-item">
                            <span><?= sanitize($item['nome']) ?> x<?= $item['qty'] ?></span>
                            <span><?= number_format($subtotal, 2, ',', '.') ?>€</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="cart-total-summary">
                    <strong>Total: <?= number_format($total, 2, ',', '.') ?>€</strong>
                </div>
            </div>
            
            <form method="POST" action="checkout_final.php" class="checkout-final-form">
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" required 
                           value="<?= sanitize($nome) ?>">
                </div>
                
                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento *</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" required 
                           value="<?= sanitize($data_nascimento) ?>">
                    <small>Deve ter 18 anos ou mais</small>
                </div>
                
                <div class="form-group">
                    <label for="morada">Morada Completa *</label>
                    <textarea id="morada" name="morada" rows="4" required><?= sanitize($morada) ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large">
                        Confirmar Encomenda
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 letudo.pt</p>
        </div>
    </footer>
</body>
</html>