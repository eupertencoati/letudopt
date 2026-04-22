<?php
$pageTitle = "Finalizar Compra";
require_once 'config.php';
include 'includes/header.php';
?>

<main class="checkout-choice-container">
    <h1 class="page-title">Finalizar Compra</h1>
    
    <div class="checkout-options">
        <!-- Opção 1: Novo Cliente -->
        <div class="checkout-option">
            <h2>Proceder para o checkout como novo cliente</h2>
            <div class="option-benefits">
                <p>Criar uma conta oferece muitas vantagens:</p>
                <ul>
                    <li>✓ Ver encomenda e estado do envio</li>
                    <li>✓ Acompanhe o histórico da encomenda</li>
                    <li>✓ Finalizar a compra mais rápido</li>
                </ul>
            </div>
            <a href="register_checkout.php" class="btn btn-primary">Criar uma Conta</a>
        </div>

        <div class="checkout-divider">
            <span>OU</span>
        </div>

        <!-- Opção 2: Cliente Registado -->
        <div class="checkout-option">
            <h2>Proceder para o checkout com a tua conta</h2>
            <form method="POST" action="checkout_login.php" class="checkout-login-form">
                <div class="form-group">
                    <label for="email">Endereço de Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Palavra-passe *</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Entrar</button>
                
                <?php if (isset($_GET['error'])): ?>
                    <p class="error-message">Email ou palavra-passe incorretos.</p>
                <?php endif; ?>
                
                <p class="forgot-password">
                    <a href="#">Esqueceu-se da Palavra-passe?</a>
                </p>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>