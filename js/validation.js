// Validação de idade
const dataNascimentoInput = document.getElementById('data_nascimento');
if (dataNascimentoInput) {
    dataNascimentoInput.addEventListener('change', function() {
        const birthDate = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        if (age < 18) {
            alert('⚠️ Deve ter 18 anos ou mais para realizar uma compra.');
            this.value = '';
        }
    });
}

// Validação formulário checkout
const checkoutForm = document.getElementById('form-checkout');
if (checkoutForm) {
    checkoutForm.addEventListener('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            alert('O carrinho está vazio!');
            return false;
        }
    });
}