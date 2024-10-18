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
    // Verifica se todos os campos estão preenchidos
    if (!empty($_POST['nome']) && !empty($_POST['idade']) && !empty($_POST['patologia']) && !empty($_POST['detalhes'])) {
        $nome = $_POST['nome'];
        $idade = $_POST['idade'];
        $patologia = $_POST['patologia'];
        $detalhes = $_POST['detalhes'];

        // Faz a consulta para a API usando as informações do paciente
        $resposta_chatgpt = chatgpt_query("Chat, gere uma conduta (somente a conduta, não precisa da avaliação e anamnese) fisioterapeutica baseada em evidências atuais para meu paciente " . $nome . " com " . $patologia . " e " . $idade . " anos , caso precise de alguns detalhes para uma conduta mais específica, os detalhes são esses: " . $detalhes);

        // Exibe a resposta dentro do iframe
        echo "<html><body>";
        echo "<h3>Resposta do ChatGPT:</h3>";
        echo "<p>" . nl2br(htmlspecialchars($resposta_chatgpt)) . "</p>";
        echo "</body></html>";
        exit();  // Finaliza a execução do script aqui para mostrar apenas o conteúdo no iframe
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
        exit();  // Para garantir que só o conteúdo apareça no iframe
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação</title>
    <link rel="stylesheet" href="../styles/avaliacao.css">
  
</head>
<body>
    
<div class="main-content-avaliacao">
    <div id="section-avaliacao" class="content-section">
        <h2>Fazer avaliação</h2>
        <p>Faça uma avaliação sobre o paciente</p>
        <div class="divisor"></div>
        <div class="form-content">

            <!-- O formulário envia a resposta para o iframe -->
            <form id="avaliacao-form" method="post" target="iframe" onsubmit="showLoading()">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome do paciente" required>
                </div>

                <div class="form-group">
                    <label for="idade">Idade</label>
                    <input type="text" id="idade" name="idade" placeholder="Idade do paciente" required>
                </div>

                <div class="form-group">
                    <label for="patologia">Patologia</label>
                    <input type="text" id="patologia" name="patologia" placeholder="Patologia do paciente" required>
                </div>

                <div class="form-group">
                    <label for="detalhes">Detalhes</label>
                    <textarea id="detalhes" name="detalhes" placeholder="Detalhes sobre o caso" required></textarea>
                </div>

                <button type="submit" class="btn">Avaliar</button>
            </form>

            <!-- Exibe a mensagem de carregamento -->
            <div id="loading">Aguarde, processando...</div>
        </div>
    </div>
</div>

<!-- Iframe onde a resposta será exibida -->
<iframe name="iframe" id="iframe" onload="showIframe()"></iframe>

<script>
    // Função para exibir a mensagem de carregamento
    function showLoading() {
        document.getElementById('loading').style.display = 'block';  // Exibe a mensagem de carregamento
        document.getElementById('iframe').style.display = 'none';    // Esconde o iframe durante o carregamento
    }

    // Função para mostrar o iframe e esconder o carregamento
    function showIframe() {
        document.getElementById('loading').style.display = 'none';   // Esconde a mensagem de carregamento
        document.getElementById('iframe').style.display = 'block';   // Mostra o iframe quando o conteúdo estiver carregado
    }
</script>

</body>
</html>
