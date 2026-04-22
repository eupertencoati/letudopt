// Verificar se estamos numa página com produtos
const productsGrid = document.querySelector('.products-grid');
if (!productsGrid) {
    // Não estamos na página inicial, sair
    return;
}

let cart = [];

// Carregar carrinho do sessionStorage
if (sessionStorage.getItem('cart')) {
    cart = JSON.parse(sessionStorage.getItem('cart'));
    updateCartUI();
}

// Adicionar ao carrinho
document.querySelectorAll('.btn-add-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const nome = this.dataset.nome;
        const preco = parseFloat(this.dataset.preco);
        const maxStock = parseInt(this.dataset.max);
        
        const qtyInput = document.getElementById('qty-' + id);
        if (!qtyInput) return;
        
        let qty = parseInt(qtyInput.value);
        
        // Validar quantidade
        if (qty < 1 || qty > maxStock) {
            showNotification('⚠️ Quantidade inválida! Stock disponível: ' + maxStock, 'error');
            return;
        }
        
        // Verificar se já existe no carrinho
        const existingItem = cart.find(item => item.id == id);
        
        if (existingItem) {
            if (existingItem.qty + qty > maxStock) {
                showNotification('⚠️ Stock insuficiente! Disponível: ' + maxStock, 'error');
                return;
            }
            existingItem.qty += qty;
        } else {
            cart.push({
                id: id,
                nome: nome,
                preco: preco,
                qty: qty
            });
        }
        
        // Guardar e atualizar
        sessionStorage.setItem('cart', JSON.stringify(cart));
        updateCartUI();
        
        // Mostrar notificação de sucesso (sem bloquear)
        showNotification('✅ ' + nome + ' adicionado ao carrinho!', 'success');
    });
});

// Atualizar interface do carrinho
function updateCartUI() {
    const cartTotalEl = document.getElementById('cart-total');
    const cartItemsEl = document.getElementById('cart-items-count');
    const cartCountEl = document.getElementById('cart-count');
    const inputCarrinhoEl = document.getElementById('input-carrinho');
    const checkoutBtn = document.getElementById('btn-checkout');
    
    let total = 0;
    let itemCount = 0;
    
    cart.forEach(item => {
        total += item.preco * item.qty;
        itemCount += item.qty;
    });
    
    if (cartTotalEl) cartTotalEl.textContent = total.toFixed(2).replace('.', ',') + '€';
    if (cartItemsEl) cartItemsEl.textContent = itemCount;
    if (cartCountEl) cartCountEl.textContent = itemCount;
    if (inputCarrinhoEl) inputCarrinhoEl.value = JSON.stringify(cart);
    
    if (checkoutBtn) {
        checkoutBtn.disabled = cart.length === 0;
    }
}

// Função para mostrar notificações toast
function showNotification(message, type = 'success') {
    // Remover notificações anteriores
    const existing = document.querySelector('.toast-notification');
    if (existing) {
        existing.remove();
    }
    
    // Criar notificação
    const notification = document.createElement('div');
    notification.className = 'toast-notification ' + type;
    notification.textContent = message;
    
    // Adicionar ao body
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}