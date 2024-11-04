<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiKey = 'sk_0dab3a9690f6487d89eb319d993895b2'; // Substitua pela sua chave de API
    $url = 'https://api.pagar.me/1/transactions'; // Endpoint da API do Pagar.me

    // Verifica se todos os campos necessários estão definidos
    if (isset($_POST['card_number'], $_POST['card_expiration_month'], $_POST['card_expiration_year'], $_POST['card_cvv'])) {
        $data = [
            'amount' => 0100, // Valor em centavos (R$ 10,00)
            'payment_method' => 'credit_card',
            'card_number' => $_POST['card_number'],
            'card_expiration_month' => $_POST['card_expiration_month'],
            'card_expiration_year' => $_POST['card_expiration_year'],
            'card_cvv' => $_POST['card_cvv'],
            'customer' => [
                'name' => 'Nome do Cliente', // Adicione informações do cliente conforme necessário
                'document' => '12345678909', // CPF ou CNPJ
            ],
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        curl_close($ch);

        // Aqui você pode tratar a resposta da API
        $responseData = json_decode($response, true);

        // Adicione esta linha para depuração
        echo "<pre>";
        print_r($responseData);
        echo "</pre>";

        // Verifica se a resposta contém a chave 'status'
        if (isset($responseData['status'])) {
            if ($responseData['status'] === 'paid') {
                echo "Pagamento realizado com sucesso!";
            } else {
                // Verifica se há erros na resposta
                if (isset($responseData['errors'])) {
                    foreach ($responseData['errors'] as $error) {
                        echo "Erro: " . $error['message'] . " (Parâmetro: " . $error['parameter_name'] . ")\n";
                    }
                } else {
                    echo "Erro ao processar pagamento: resposta inesperada da API.";
                }
            }
        } else {
            echo "Erro ao processar pagamento: resposta inesperada da API.";
        }
    } else {
        echo "Todos os campos do cartão são obrigatórios.";
    }
} else {
    echo "Método não permitido.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar.me Checkout</title>
</head>
<body>
    <h1>Pagamento com Pagar.me</h1>
    <form id="payment-form" method="POST">
        <label for="card_number">Número do Cartão:</label>
        <input type="text" name="card_number" required><br>

        <label for="card_expiration_month">Mês de Expiração:</label>
        <input type="text" name="card_expiration_month" required><br>

        <label for="card_expiration_year">Ano de Expiração:</label>
        <input type="text" name="card_expiration_year" required><br>

        <label for="card_cvv">CVV:</label>
        <input type="text" name="card_cvv" required><br>

        <button type="submit">Pagar</button>
    </form>
</body>
</html>
