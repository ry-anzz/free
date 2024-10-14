<?php
// Ativa a exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclui o arquivo com a função para acessar a API
include('../api/main.php');

// Inicializa uma variável para armazenar a resposta da API
$resposta_chatgpt = '';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se a chave "condicao" existe no array $_POST
    if (isset($_POST['condicao']) && !empty($_POST['condicao'])) {
        $condicao_paciente = $_POST['condicao'];

        // Faz a consulta para a API usando a condição do paciente
        $resposta_chatgpt = chatgpt_query($condicao_paciente);
    } else {
        echo "Por favor, forneça a condição do paciente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conduta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .response {
            margin-top: 20px;
            padding: 10px;
            background-color: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 4px;
        }
        /* Estilo para a mensagem de carregamento */
        #loading {
            display: none;
            margin-top: 20px;
            padding: 10px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 4px;
            color: #856404;
        }
    </style>
</head>
<body>

    <form action="conduta.php" method="post" onsubmit="showLoading()">
        <label for="condicao">Descreva a condição do paciente:</label><br>
        <textarea name="condicao" id="condicao" required></textarea><br>
        <button type="submit">Enviar Avaliação</button>
    </form>

    <!-- Exibe a mensagem de carregamento -->
    <div id="loading">Aguarde, processando...</div>

    <!-- Exibe a resposta do ChatGPT se houver -->
    <?php if (!empty($resposta_chatgpt)): ?>
        <div class="response">
            <h3>Resposta do ChatGPT:</h3>
            <p><?php echo nl2br(htmlspecialchars($resposta_chatgpt)); ?></p>
        </div>
    <?php endif; ?>

    <script>
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }
    </script>

</body>
</html>
