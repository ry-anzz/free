<?php
// require 'vendor/autoload.php'; // Instale com "composer require pagarme/pagarme-php"

// // Insira sua API Key da Pagar.me aqui
// $pagarme = new PagarMe\Client('SUA_API_KEY');

// // Captura os dados do formulário
// $card_number = $_POST['card_number'];
// $card_holder_name = $_POST['card_holder_name'];
// $card_expiration_date = $_POST['card_expiration_date'];
// $card_cvv = $_POST['card_cvv'];

// // Define o valor fixo de R$59,90 (5990 centavos)
// $amount = 5990;

// try {
//     // Cria a transação
//     $transaction = $pagarme->transactions()->create([
//         'amount' => $amount,
//         'payment_method' => 'credit_card',
//         'card_number' => $card_number,
//         'card_holder_name' => $card_holder_name,
//         'card_expiration_date' => $card_expiration_date,
//         'card_cvv' => $card_cvv,
//         'customer' => [
//             'external_id' => '1',
//             'name' => $card_holder_name,
//             'type' => 'individual',
//             'country' => 'br',
//             'documents' => [
//                 [
//                     'type' => 'cpf',
//                     'number' => '00000000000'
//                 ]
//             ],
//             'phone_numbers' => ['+5511999999999'],
//             'email' => 'cliente@example.com'
//         ]
//     ]);

//     // Verifica se a transação foi bem-sucedida
//     if ($transaction->status == 'paid') {
//         echo "Pagamento realizado com sucesso!";
//     } else {
//         echo "Falha no pagamento: " . $transaction->status;
//     }

// } catch (Exception $e) {
//     echo "Erro ao processar pagamento: " . $e->getMessage();
// }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento com Pagar.me</title>
    <link rel="stylesheet" href="../../styles/pagamento.css">
</head>
<body>
    <div class="container">
        <h2>Pagamento com Cartão de Crédito</h2>
        <form id="paymentForm" action="process_payment.php" method="POST">
        <div class="card-input">
    <label for="cardNumber">Número do Cartão</label>
    <div class="card-number-container">
        
        <img id="cardBrand" src=""  class="card-brand-icon">
        <input type="text" id="cardNumber" name="card_number" placeholder="Número do cartão" required maxlength="16">
    </div>
</div>


            <div class="card-details">
                <label for="cardExpirationDate">Data de Expiração</label>
                <input type="text" id="cardExpirationDate" name="card_expiration_date" placeholder="MM/AA" required maxlength="5">
                
                <label for="cardCVV">CVV</label>
                <input type="text" id="cardCVV" name="card_cvv" placeholder="CVV" required maxlength="3">
            </div>

            <label for="cardHolderName">Nome no Cartão</label>
            <input type="text" id="cardHolderName" name="card_holder_name" placeholder="Nome no cartão" required>

            
            <label for="country">País</label>
            <select id="country" name="country">
                <option value="BR">Brasil</option>
            </select>

            <label for="addressLine1">Endereço</label>
            <input type="text" id="addressLine1" name="address_line1" placeholder="Endereço Linha 1" required>

            <label for="city">Cidade</label>
            <input type="text" id="city" name="city" placeholder="Cidade" required>

            <label for="postalCode">Código Postal</label>
            <input type="text" maxlength="8" id="postalCode" name="postal_code" placeholder="Código postal" required>

            <label for="state">Estado</label>
            <input type="text" id="state" name="state" placeholder="Estado" required>

            <button type="submit">Pagar R$59,90</button>
        </form>
    </div>
    
    <script src="../../scripts/pagamento.js"></script>
</body>
</html>
