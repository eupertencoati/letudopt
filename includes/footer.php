    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2026 letudo.pt - Todos os direitos reservados</p>
            <div class="footer-links">
                <a href="termos.php">Termos e Condições</a>
                <a href="privacidade.php">Política de Privacidade</a>
            </div>
        </div>
    </footer>
    
    <!-- Carregar cookies.js sempre -->
    <script src="js/cookies.js"></script>
    
    <!-- Carregar cart.js apenas se existir o elemento do carrinho -->
    <?php if (basename($_SERVER['PHP_SELF']) !== 'checkout_choice.php'): ?>
        <script src="js/cart.js"></script>
    <?php endif; ?>
</body>
</html>