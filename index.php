<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="letudo.pt - Livraria online com os melhores livros">
    <title>letudo.pt | Livraria Online</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <span>📞 Apoio: +351 123 456 789</span>
            <div class="top-links">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin.php">Administração</a>
                    <?php endif; ?>
                    <a href="logout.php">Sair</a>
                <?php else: ?>
                    <a href="login.php">Entrar</a>
                    <a href="register.php">Registar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container header-content">
            <a href="index.php" class="logo">📚 letudo<span>.pt</span></a>
            <nav class="nav">
                <a href="index.php" class="active">Loja</a>
                <a href="#carrinho">Carrinho (<span id="cart-count">0</span>)</a>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <section class="banner">
        <div class="container">
            <h1>Bem-vindo à letudo.pt</h1>
            <p>Os melhores livros ao melhor preço!</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container main-content">
        <h2 class="page-title">Catálogo de Livros</h2>
        
        <div class="products-grid" id="productsGrid">
            <?php
            $stmt = $pdo->query("SELECT * FROM Produtos ORDER BY nome ASC");
            $produtos = $stmt->fetchAll();

            if (count($produtos) > 0):
                foreach ($produtos as $produto):
            ?>
                <article class="product-card" 
                         data-id="<?= $produto['id'] ?>" 
                         data-nome="<?= sanitize($produto['nome']) ?>" 
                         data-preco="<?= $produto['preco'] ?>"
                         data-max="<?= $produto['quantidade'] ?>">
                    
                    <img src="<?= !empty($produto['imagem']) && file_exists('img/' . $produto['imagem']) ? 'img/' . sanitize($produto['imagem']) : 'img/placeholder.jpg' ?>" 
                         alt="<?= sanitize($produto['nome']) ?>" 
                         class="product-image"
                         loading="lazy">
                    
                    <div class="product-info">
                        <h3 class="product-name"><?= sanitize($produto['nome']) ?></h3>
                        <p class="product-price"><?= number_format($produto['preco'], 2, ',', '.') ?>€</p>
                        <p class="product-stock">📦 Stock: <?= (int)$produto['quantidade'] ?></p>

                        <?php if ((int)$produto['quantidade'] > 0): ?>
                            <div class="product-actions">
                                <label for="qty-<?= $produto['id'] ?>">Qtd:</label>
                                <input type="number" 
                                       id="qty-<?= $produto['id'] ?>" 
                                       class="qty-input" 
                                       value="1" 
                                       min="1" 
                                       max="<?= (int)$produto['quantidade'] ?>">
                                <button type="button" 
                                        class="btn btn-add-cart" 
                                        data-id="<?= $produto['id'] ?>" 
                                        data-nome="<?= sanitize($produto['nome']) ?>" 
                                        data-preco="<?= $produto['preco'] ?>" 
                                        data-max="<?= (int)$produto['quantidade'] ?>">
                                    Adicionar
                                </button>
                            </div>
                        <?php else: ?>
                            <button type="button" class="btn btn-disabled" disabled>Esgotado</button>
                        <?php endif; ?>
                    </div>
                </article>
            <?php 
                endforeach;
            else: 
            ?>
                <p class="no-products">Sem produtos disponíveis.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Cart Bar -->
    <aside class="cart-bar" id="carrinho">
        <div class="container cart-content">
            <div class="cart-summary">
                <div class="cart-total">
                    Total: <strong id="cart-total">0,00€</strong>
                </div>
                <div class="cart-items">
                    (<span id="cart-items-count">0</span> itens)
                </div>
            </div>
            <form action="checkout_choice.php" method="GET" id="form-checkout">
                <input type="hidden" name="carrinho" id="input-carrinho" value="[]">
                <button type="submit" class="btn btn-checkout" id="btn-checkout">
                    Concluir Compra
                </button>
            </form>
        </div>
    </aside>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 letudo.pt - Todos os direitos reservados</p>
        </div>
    </footer>

    <script src="js/cart.js"></script>
    <!-- Cookie Banner -->
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

    <!-- Cookie Settings Modal -->
    <div id="cookie-modal" class="cookie-modal">
        <div class="cookie-modal-content">
            <div class="cookie-modal-header">
                <h2>Definições de Cookies</h2>
                <button type="button" class="cookie-modal-close" id="cookie-modal-close">&times;</button>
            </div>
            <div class="cookie-modal-body">
                <div class="cookie-option">
                    <div class="cookie-option-info">
                        <h4>Cookies Essenciais</h4>
                        <p>Necessários para o funcionamento do site. Não podem ser desativados.</p>
                    </div>
                    <label class="cookie-toggle">
                        <input type="checkbox" checked disabled>
                        <span class="cookie-toggle-slider"></span>
                    </label>
                </div>
                
                <div class="cookie-option">
                    <div class="cookie-option-info">
                        <h4>Cookies de Análise</h4>
                        <p>Ajudam-nos a melhorar o site através da recolha de informações anónimas.</p>
                    </div>
                    <label class="cookie-toggle">
                        <input type="checkbox" id="cookie-analytics">
                        <span class="cookie-toggle-slider"></span>
                    </label>
                </div>
                
                <div class="cookie-option">
                    <div class="cookie-option-info">
                        <h4>Cookies de Marketing</h4>
                        <p>Utilizados para personalizar anúncios e conteúdo.</p>
                    </div>
                    <label class="cookie-toggle">
                        <input type="checkbox" id="cookie-marketing">
                        <span class="cookie-toggle-slider"></span>
                    </label>
                </div>
            </div>
            <div class="cookie-modal-footer">
                <button type="button" class="btn-cookie-save" id="cookie-save">
                    Guardar Preferências
                </button>
            </div>
        </div>
    </div>

    <script src="js/cookies.js"></script>
    <script src="js/cart.js"></script>
</body>
</html>
