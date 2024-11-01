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
    if (!empty($_POST['nome']) && !empty($_POST['idade']) && !empty($_POST['patologia']) && !empty($_POST['detalhes'])) {
        $nome = $_POST['nome'];
        $idade = $_POST['idade'];
        $patologia = $_POST['patologia'];
        $detalhes = $_POST['detalhes'];

        // Faz a consulta para a API usando as informações do paciente
        $resposta_chatgpt = chatgpt_query("Chat, gere uma conduta (somente a conduta, não precisa da avaliação e anamnese) fisioterapeutica baseada em evidências atuais com as separando as etapas, os exercicios de cada etapa para meu paciente detalhando cada exercicio e dando o tempo de cada etapa" . $nome . " com " . $patologia . " e " . $idade . "anos, e caso precise de detalhes: " . $detalhes);
        
        // Armazena a conduta na sessão para passar para a próxima página
        session_start();
        $_SESSION['conduta'] = $resposta_chatgpt;
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
    <link rel="stylesheet" href="../../styles/avaliacao.css">
</head>
<body>
<div class="main-content-avaliacao">
    <div id="section-avaliacao" class="content-section">
        <h2>Fazer avaliação</h2>
        <p>Faça uma avaliação sobre o paciente</p>
        <div class="divisor"></div>
        <div class="form-content">

            <!-- O formulário envia a resposta para a API -->
            <form id="avaliacao-form" method="post" onsubmit="showLoading()">
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

                <button type="submit" class="btn-avaliar">Avaliar</button>
            </form>

            <!-- Exibe a mensagem de erro, se houver -->
            <?php if ($message): ?>
                <div class="error-message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Exibe a resposta da API -->
            <?php if ($resposta_chatgpt): ?>
                <div class="conduta-result">
                <h3>Conduta:</h3>
                <p><?php echo nl2br(htmlspecialchars($resposta_chatgpt)); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="loading" style="display: none;">Aguarde, processando...</div>
<!-- Botão para salvar a conduta e redirecionar para a página de cadastro -->
    <?php if ($resposta_chatgpt): ?>
        <div>
            <form action="../../components/pagina/cadastrar.php" method="post">
                <input type="hidden" name="conduta" value="<?php echo htmlspecialchars($resposta_chatgpt); ?>">
                <button type="submit" class="btn-salvar">Salvar</button>
            </form>
        </div>
    <?php endif; ?>

<script>
    // Função para exibir a mensagem de carregamento
    function showLoading() {
        document.getElementById('loading').style.display = 'block';
    }
</script>

</body>
</html>
