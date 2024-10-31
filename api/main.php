<?php

function chatgpt_query($input) {
    $apiKey = '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';  // Use variáveis de ambiente
    $url = 'https://api.openai.com/v1/chat/completions';

    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'Você é um assistente de fisioterapia e eu sou fisioterapeuta.'],
            ['role' => 'user', 'content' => $input],
        ],
        'max_tokens' => 4000,
        'temperature' => 0.7,
    ];

    $retryCount = 0;
    $maxRetries = 3;  // Número máximo de tentativas
    $waitTime = 2;    // Tempo de espera entre tentativas, em segundos

    while ($retryCount < $maxRetries) {
        // Iniciar cURL
        $ch = curl_init($url);

        // Configurar as opções de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Authorization: Bearer $apiKey",
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Executar a requisição
        $response = curl_exec($ch);

        // Checar por erros
        if (curl_errno($ch)) {
            echo 'Erro no cURL: ' . curl_error($ch);
            curl_close($ch);
            die('Falha na conexão com a API.');
        }

        // Pegar o código de resposta HTTP
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Fechar a conexão cURL
        curl_close($ch);

        // Verifica se a resposta foi bem-sucedida (código 200)
        if ($httpcode === 200) {
            $result = json_decode($response, true);
            return $result['choices'][0]['message']['content'] ?? 'Nenhuma resposta recebida';
        }

        // Se erro 429 (Too Many Requests), tente novamente
        if ($httpcode === 429) {
            $retryCount++;
            sleep($waitTime);
        } else {
            die('Erro ao conectar com a API: Código ' . $httpcode);
        }
    }

    die('Erro na requisição à API após várias tentativas.');
}


?>
