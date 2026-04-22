<?php
$pageTitle = "Criar Conta - Checkout";
require_once 'config.php';
include 'includes/header.php';

$erro = '';

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitização contra injeções XSS
    $nome          = htmlspecialchars(trim($_POST['nome'] ?? ''), ENT_QUOTES, 'UTF-8');
    $apelido       = htmlspecialchars(trim($_POST['apelido'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email         = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password      = $_POST['password'] ?? '';
    $confirm_pass  = $_POST['confirm_password'] ?? '';
    $data_nasc     = $_POST['data_nascimento'] ?? '';
    $morada        = htmlspecialchars(trim($_POST['morada'] ?? ''), ENT_QUOTES, 'UTF-8');
    $termos        = isset($_POST['termos']) ? 1 : 0;

    // Validação de password forte
    $password_valid = true;
    $password_errors = [];
    
    if (strlen($password) < 8) {
        $password_valid = false;
        $password_errors[] = "Mínimo 8 caracteres";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $password_valid = false;
        $password_errors[] = "Pelo menos uma letra minúscula";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $password_valid = false;
        $password_errors[] = "Pelo menos uma letra maiúscula";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $password_valid = false;
        $password_errors[] = "Pelo menos um número";
    }
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $password_valid = false;
        $password_errors[] = "Pelo menos um símbolo (!@#$%^&*...)";
    }

    // Validações obrigatórias
    if (empty($nome) || empty($apelido) || empty($email) || empty($password) || empty($data_nasc) || empty($morada)) {
        $erro = "Todos os campos obrigatórios devem ser preenchidos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Por favor, insira um email válido.";
    } elseif ($password !== $confirm_pass) {
        $erro = "As palavras-passe não coincidem.";
    } elseif (!$password_valid) {
        $erro = "A palavra-passe não cumpre os requisitos de segurança: " . implode(", ", $password_errors);
    } elseif (!$termos) {
        $erro = "Deve aceitar os Termos e Condições e confirmar que tem mais de 18 anos.";
    } else {
        // Validação de idade (>= 18 anos)
        $hoje       = new DateTime();
        $nascimento = new DateTime($data_nasc);
        $idade      = $hoje->diff($nascimento)->y;

        if ($idade < 18) {
            $erro = "Deve ter 18 anos ou mais para criar uma conta e efetuar compras.";
        } else {
            // Verificar se o email já existe
            $stmt = $pdo->prepare("SELECT id FROM Utilizadores WHERE username = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $erro = "Este email já está registado. Faça login ou utilize outro email.";
            } else {
                // Criar utilizador na base de dados
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO Utilizadores (username, password, tipo) VALUES (?, ?, 'comprador')");
                $stmt->execute([$email, $hash]);

                $user_id = $pdo->lastInsertId();

                // Iniciar sessão automaticamente
                $_SESSION['user_id']   = $user_id;
                $_SESSION['user_tipo'] = 'comprador';
                $_SESSION['username']  = $email;

                // Guardar dados para o passo final do checkout
                $_SESSION['checkout_data'] = [
                    'nome_completo'   => $nome . ' ' . $apelido,
                    'data_nascimento' => $data_nasc,
                    'morada'          => $morada
                ];

                // Redirecionar para a finalização da compra
                header('Location: checkout_final.php');
                exit;
            }
        }
    }
}

// Gerar CAPTCHA simples
$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$captcha = '';
for ($i = 0; $i < 5; $i++) {
    $captcha .= $chars[random_int(0, strlen($chars) - 1)];
}
$_SESSION['captcha_code'] = $captcha;
?>

<main class="register-checkout-container">
    <h1 class="page-title">Novo Registo</h1>

    <?php if ($erro): ?>
        <div class="alert alert-error">❌ <?= $erro ?></div>
    <?php endif; ?>

    <form method="POST" action="register_checkout.php" class="register-form" id="registerForm">
        <div class="form-row">
            <div class="form-group">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" required 
                       value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" 
                       placeholder="Insira o seu nome">
            </div>
            <div class="form-group">
                <label for="apelido">Apelido *</label>
                <input type="text" id="apelido" name="apelido" required 
                       value="<?= htmlspecialchars($_POST['apelido'] ?? '') ?>" 
                       placeholder="Insira o seu apelido">
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required 
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                   placeholder="Ex: nome@email.com">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">Palavra-passe *</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Mínimo 8 caracteres">
                <div class="password-strength" id="password-strength">
                    Força da password: <span>Sem palavra-passe</span>
                </div>
                <div class="password-requirements" id="password-requirements">
                    <strong>Requisitos:</strong>
                    <ul>
                        <li id="req-length">Mínimo 8 caracteres</li>
                        <li id="req-lower">Pelo menos uma letra minúscula</li>
                        <li id="req-upper">Pelo menos uma letra maiúscula</li>
                        <li id="req-number">Pelo menos um número</li>
                        <li id="req-symbol">Pelo menos um símbolo (!@#$%^&*...)</li>
                    </ul>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmar Palavra-passe *</label>
                <input type="password" id="confirm_password" name="confirm_password" required 
                       placeholder="Repita a palavra-passe">
            </div>
        </div>

        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento *</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required>
            <small>Deve ter 18 anos ou mais para efetuar compras.</small>
        </div>

        <div class="form-group">
            <label for="morada">Morada Completa *</label>
            <textarea id="morada" name="morada" rows="3" required 
                      placeholder="Rua, nº, código postal, localidade"><?= htmlspecialchars($_POST['morada'] ?? '') ?></textarea>
        </div>

        <div class="form-group checkbox-group">
            <label>
                <input type="checkbox" name="newsletter" value="1">
                Quero ser informado sobre novos produtos e promoções.
            </label>
        </div>

        <div class="form-group checkbox-group">
            <label>
                <input type="checkbox" name="termos" value="1" required>
                Declaro que li e aceito os <a href="termos.php" target="_blank">Termos e Condições</a> e a 
                <a href="privacidade.php" target="_blank">Política de Privacidade</a>, e confirmo que tenho mais de 18 anos. *
            </label>
        </div>

        <div class="form-group captcha-group">
            <label>Por favor, escreva as letras e números abaixo *</label>
            <div class="captcha-box">
                <span class="captcha-text"><?= $captcha ?></span>
                <button type="button" class="btn-recaptcha" id="refreshCaptcha">🔄 Recarregar</button>
            </div>
            <input type="text" name="captcha" id="captchaInput" required 
                   placeholder="Digite o código" autocomplete="off">
            <input type="hidden" name="captcha_correct" value="<?= $captcha ?>">
        </div>

        <button type="submit" class="btn btn-primary btn-large btn-block">
            Criar Conta e Continuar
        </button>
    </form>
</main>

<script>
// Validação de password forte em tempo real
const passInput = document.getElementById('password');
const strengthDisplay = document.getElementById('password-strength');
const requirements = {
    length: document.getElementById('req-length'),
    lower: document.getElementById('req-lower'),
    upper: document.getElementById('req-upper'),
    number: document.getElementById('req-number'),
    symbol: document.getElementById('req-symbol')
};

passInput.addEventListener('input', function() {
    const val = this.value;
    let score = 0;
    
    // Verificar requisitos
    const hasLength = val.length >= 8;
    const hasLower = /[a-z]/.test(val);
    const hasUpper = /[A-Z]/.test(val);
    const hasNumber = /[0-9]/.test(val);
    const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(val);
    
    // Atualizar visualização dos requisitos
    requirements.length.classList.toggle('valid', hasLength);
    requirements.lower.classList.toggle('valid', hasLower);
    requirements.upper.classList.toggle('valid', hasUpper);
    requirements.number.classList.toggle('valid', hasNumber);
    requirements.symbol.classList.toggle('valid', hasSymbol);
    
    // Calcular força
    if (hasLength) score++;
    if (hasLower) score++;
    if (hasUpper) score++;
    if (hasNumber) score++;
    if (hasSymbol) score++;
    if (val.length >= 12) score++;
    
    const levels = ['Muito fraca', 'Fraca', 'Média', 'Boa', 'Forte', 'Muito forte'];
    const classes = ['weak', 'weak', 'medium', 'strong', 'strong', 'strong'];
    
    if (val.length === 0) {
        strengthDisplay.innerHTML = 'Força: <span>Sem palavra-passe</span>';
        strengthDisplay.className = 'password-strength';
    } else {
        strengthDisplay.innerHTML = `Força: <span>${levels[score]}</span>`;
        strengthDisplay.className = 'password-strength ' + classes[score];
    }
});

// Validação de idade em tempo real
document.getElementById('data_nascimento').addEventListener('change', function() {
    const birth = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;

    if (age < 18) {
        alert('⚠️ Deve ter 18 anos ou mais para criar uma conta.');
        this.value = '';
    }
});

// Recarregar CAPTCHA sem refresh da página
document.getElementById('refreshCaptcha').addEventListener('click', function() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let newCaptcha = '';
    for(let i=0; i<5; i++) newCaptcha += chars[Math.floor(Math.random() * chars.length)];
    document.querySelector('.captcha-text').textContent = newCaptcha;
    document.querySelector('input[name="captcha_correct"]').value = newCaptcha;
    document.getElementById('captchaInput').value = '';
});
</script>

<?php include 'includes/footer.php'; ?>