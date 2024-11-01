<?php
// Ativa a exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclui o arquivo com a função para acessar a API
include('../../api/main.php');

$message = '';
$resposta_chatgpt = '';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se todos os campos estão preenchidos
    if (!empty($_POST['assunto'])) {
        $assunto = $_POST['assunto'];

        // Faz a consulta para a API
        $resposta_chatgpt = chatgpt_query($assunto); // Corrigido para usar $assunto
        session_start();
        $_SESSION['pesquisa'] = $resposta_chatgpt;
    } else {
        $message = "Por favor, preencha todos os campos do formulário.";
    }
}
?>        

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação</title>
    <link rel="stylesheet" href="../../styles/estudo.css">
</head>
<body>
<div class="main-content-avaliacao">
    <div id="section-avaliacao" class="content-section">
        <h2>Fazer Pesquisa</h2>
        <p>Faça uma pesquisa sobre algo</p>
        <div class="divisor"></div>
        <div class="form-content">

            <!-- O formulário envia a resposta para a API -->
            <form id="pesquisa-form" method="post" onsubmit="showLoading()">
                <div class="form-group">
                    <label for="nome">Assunto</label>
                    <input type="text" id="assunto" name="assunto" placeholder="Assunto a ser pesquisado" required>
                    <button type="submit" class="btn">Pesquisar</button>
                </div>
            </form>

            <!-- Exibe a mensagem de erro, se houver -->
            <?php if ($message): ?>
                <div class="error-message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Exibe a resposta da API -->
            <?php if ($resposta_chatgpt): ?>
                <div class="pesquisa-result">
                <h3>Pesquisa</h3>
                <p><?php echo nl2br(htmlspecialchars($resposta_chatgpt)); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="loading" style="display: none;">Aguarde, processando...</div>

<script>
    // Função para exibir a mensagem de carregamento
    function showLoading() {
        document.getElementById('loading').style.display = 'block';
    }
</script>

</body>
</html>
