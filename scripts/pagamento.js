const cardNumberInput = document.getElementById('cardNumber');
const cardBrandImg = document.getElementById('cardBrand');

// Defina o ícone genérico inicialmente
cardBrandImg.src = 'https://via.placeholder.com/24x24?text=Card'; // Ícone cinza ou genérico

cardNumberInput.addEventListener('input', () => {
    const cardNumber = cardNumberInput.value.replace(/\D/g, ""); // Remove caracteres não numéricos

    if (cardNumber.startsWith('4')) {
        cardBrandImg.src = 'https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg';
    } else if (cardNumber.startsWith('5')) {
        cardBrandImg.src = 'https://upload.wikimedia.org/wikipedia/commons/a/a4/Mastercard_2019_logo.svg';
    } else if (cardNumber.startsWith('3')) {
        cardBrandImg.src = 'https://download.logo.wine/logo/American_Express/American_Express-Logo.wine.png';
    } else if (cardNumber.startsWith('6')) {
        cardBrandImg.src = 'https://upload.wikimedia.org/wikipedia/commons/5/5a/Discover_Card_logo.svg';
    } else if (cardNumber === "") {
        // Volta para o ícone genérico quando o campo está vazio
        cardBrandImg.src = 'https://via.placeholder.com/24x24?text=Card'; // Ícone cinza ou genérico
    } else {
        // Caso nenhum padrão seja detectado, remove o ícone da bandeira
        cardBrandImg.src = '';
    }
});


document.getElementById('postalCode').addEventListener('blur', function () {
    const postalCode = this.value.replace(/\D/g, ''); // Remove caracteres não numéricos

    if (postalCode.length === 8) {
        fetch(`https://viacep.com.br/ws/${postalCode}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('addressLine1').value = data.logradouro;
                    document.getElementById('city').value = data.localidade;
                    document.getElementById('state').value = data.uf;
                } else {
                    alert('CEP não encontrado.');
                }
            })
            .catch(error => {
                console.error('Erro ao buscar o CEP:', error);
            });
    }
})