<?php

$api_key = "sk_0dab3a9690f6487d89eb319d993895b2";

// Dados da transação
$data = [
    'amount' => 0100,
    'payment_method' => 'credit_card',
    'card_number' => '5502092139249915',
    'card_expiration_date' => '0231',
    'card_holder_name' => 'Ryan V Oliveira',
    'card_cvv' => '695',
    'customer' => [
        'name' => 'Ryan Vicente de Oliveira',
        'type' => 'individual',
        'country' => 'br',
        'email' => 'vicenteryan385@gmail.com',
        'documents' => [
            [
                'type' => 'cpf',
                'number' => '44149184801'
            ]
        ],
        'phone_numbers' => ['+5511914648331'],
        'birthday' => '2004-03-06'
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.pagar.me/1/transactions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_USERPWD, $api_key . ":");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Enviando a requisição e capturando a resposta
$response = curl_exec($ch);
curl_close($ch);

// Verificar a resposta completa para debug
var_dump($response);

// Decodificar a resposta JSON
$resposta = json_decode($response, true);

// Validar se a resposta é um array e se contém o campo 'status'
if ($resposta === null) {
    echo "Erro ao decodificar a resposta JSON.";
    exit;
}

if (isset($resposta['status']) && $resposta['status'] === 'paid') {
    echo "Pagamento aprovado!";
} elseif (isset($resposta['status'])) {
    echo "Erro no pagamento: " . $resposta['status'];
} else {
    echo "Erro inesperado na resposta: ";
    var_dump($resposta);
}