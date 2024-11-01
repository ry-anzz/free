<?php
include('../../scripts/conexao.php'); // Inclua o arquivo de conexão com o banco de dados

// Inicializa a variável para a conduta
$conduta = '';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe o nome do paciente do formulário
    $nome_paciente = $_POST['nome'] ?? '';

    // Prepara e executa a consulta para buscar a conduta do paciente
    $stmt = $conexao->prepare("SELECT conduta FROM pacientes WHERE nome = ?");
    $stmt->bind_param("s", $nome_paciente);
    $stmt->execute();
    $stmt->bind_result($conduta);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conduta</title>
    <link rel="stylesheet" href="../../styles/condut.css">
</head>
<body>

<div class="main-content-conduta">
    <h2>Conduta do Paciente</h2>
    <p>Veja a conduta do paciente</p>
    <div class="divisor"></div>

    <div class="form-content">
        <form action="conduta.php" method="post">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Nome completo do paciente" required>
                <button type="submit" class="btn" name="avaliar">Procurar</button>
            </div>
        </form>
    </div>

    <?php if ($conduta): ?>
        <div class="conduta-result">
            <h3>Conduta do Paciente:</h3>
            <p><?php echo nl2br(htmlspecialchars($conduta)); ?></p>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="not-found">
            <p>Conduta não encontrada para o paciente: <strong><?php echo htmlspecialchars($nome_paciente); ?></strong>.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
