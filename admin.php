<?php
require_once 'config.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $nome = sanitize($_POST['nome']);
        $qty = (int)$_POST['quantidade'];
        $preco = (float)$_POST['preco'];
        $imagem = sanitize($_POST['imagem'] ?? '');
        
        $stmt = $pdo->prepare("INSERT INTO Produtos (nome, quantidade, preco, imagem) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $qty, $preco, $imagem]);
        
        header('Location: admin.php?msg=produto_adicionado');
        exit;
        
    } elseif (isset($_POST['update_product'])) {
        $id = (int)$_POST['id'];
        $qty = (int)$_POST['quantidade'];
        $preco = (float)$_POST['preco'];
        
        $stmt = $pdo->prepare("UPDATE Produtos SET quantidade = ?, preco = ? WHERE id = ?");
        $stmt->execute([$qty, $preco, $id]);
        
        header('Location: admin.php?msg=produto_atualizado');
        exit;
    }
}

$encomendas = $pdo->query("SELECT * FROM Encomendas ORDER BY data_compra DESC")->fetchAll();
$produtos = $pdo->query("SELECT * FROM Produtos ORDER BY nome ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - letudo.pt</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <span>Bem-vindo, <?= sanitize($_SESSION['username']) ?></span>
            <div class="top-links">
                <a href="index.php">Ver Loja</a>
                <a href="logout.php">Sair</a>
            </div>
        </div>
    </div>

    <main class="container admin-container">
        <h1 class="page-title">🔐 Painel de Administração</h1>
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <?= $_GET['msg'] === 'produto_adicionado' ? 'Produto adicionado!' : 'Produto atualizado!' ?>
            </div>
        <?php endif; ?>

        <!-- Encomendas -->
        <section class="admin-section">
            <h2>📦 Encomendas</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Produtos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($encomendas as $enc): ?>
                        <tr>
                            <td>#<?= $enc['id'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($enc['data_compra'])) ?></td>
                            <td><?= sanitize($enc['nome_cliente']) ?></td>
                            <td><strong><?= number_format($enc['preco_total'], 2, ',', '.') ?>€</strong></td>
                            <td>
                                <details>
                                    <summary>Ver detalhes</summary>
                                    <?php 
                                    $prods = json_decode($enc['produtos_json'], true);
                                    foreach ($prods as $p): ?>
                                        <small>• <?= sanitize($p['nome']) ?> x<?= $p['qty'] ?></small><br>
                                    <?php endforeach; ?>
                                </details>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Produtos -->
        <section class="admin-section">
            <h2>📚 Gestão de Produtos</h2>
            
            <form method="POST" class="form-add-product">
                <h3>Adicionar Novo Produto</h3>
                <div class="form-grid">
                    <input type="text" name="nome" placeholder="Nome do livro" required>
                    <input type="number" name="quantidade" placeholder="Quantidade" min="0" required>
                    <input type="number" name="preco" placeholder="Preço (€)" step="0.01" min="0" required>
                    <input type="text" name="imagem" placeholder="Nome do ficheiro de imagem">
                </div>
                <button type="submit" name="add_product" class="btn btn-primary">Adicionar Produto</button>
            </form>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Stock</th>
                            <th>Preço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $prod): ?>
                        <tr>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $prod['id'] ?>">
                                <td><?= $prod['id'] ?></td>
                                <td><?= sanitize($prod['nome']) ?></td>
                                <td>
                                    <input type="number" name="quantidade" value="<?= $prod['quantidade'] ?>" 
                                           min="0" style="width: 70px;">
                                </td>
                                <td>
                                    <input type="number" name="preco" value="<?= $prod['preco'] ?>" 
                                           step="0.01" style="width: 80px;"> €
                                </td>
                                <td>
                                    <button type="submit" name="update_product" class="btn btn-sm">Guardar</button>
                                </td>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 letudo.pt - Área de Administração</p>
        </div>
    </footer>
</body>
</html>