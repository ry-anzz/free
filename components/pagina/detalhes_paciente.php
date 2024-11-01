<?php
session_start();
include('../../scripts/conexao.php');

$message = '';
$paciente_id = null;
$paciente = [];
$nome_paciente = 'Paciente não encontrado'; // Inicializa a variável para evitar aviso
$conduta = ''; // Variável para armazenar a conduta do paciente

// Verifique se temos um ID de paciente na URL
if (isset($_GET['id'])) {
    $paciente_id = (int)$_GET['id']; // Captura o ID da URL
    
    // Buscar dados do paciente
    $query = "SELECT * FROM pacientes WHERE id = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $paciente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verifique se o paciente foi encontrado
    if ($result->num_rows > 0) {
        $paciente = $result->fetch_assoc();
        $nome_paciente = $paciente['nome']; // Atribui o nome do paciente
        $conduta = $paciente['conduta']; // Atribui a conduta do paciente
    } else {
        $message = "Paciente não encontrado.";
    }
} else {
    $message = "Nenhum ID de paciente fornecido na URL."; // Mensagem se nenhum ID estiver presente na URL
}

// Processar a exclusão do paciente
if (isset($_POST['deletar'])) {
    $queryDelete = "DELETE FROM pacientes WHERE id = ?";
    $stmtDelete = $conexao->prepare($queryDelete);
    $stmtDelete->bind_param("i", $paciente_id);
    if ($stmtDelete->execute()) {
        $message = "Paciente deletado com sucesso.";
        // Redirecionar ou destruir a sessão, se necessário
        session_destroy();
        header("Location: ../../components/pagina/pacientes.php"); // Redireciona para a lista de pacientes
        exit();
    } else {
        $message = "Erro ao deletar paciente: " . $conexao->error;
    }
}

// Processar a atualização dos dados do paciente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar'])) {
    $novo_nome = $_POST['nome'];
    $nova_idade = $_POST['idade'];
    $nova_patologia = $_POST['patologia'];
    $novo_telefone = $_POST['telefone'];

    $queryUpdate = "UPDATE pacientes SET nome = ?, idade = ?, patologia = ?, telefone = ? WHERE id = ?";
    $stmtUpdate = $conexao->prepare($queryUpdate);
    $stmtUpdate->bind_param("siisi", $novo_nome, $nova_idade, $nova_patologia, $novo_telefone, $paciente_id);
    if ($stmtUpdate->execute()) {
        $message = "Dados do paciente atualizados com sucesso.";
        // Atualiza a sessão se o nome foi alterado
        $_SESSION['nome_paciente'] = $novo_nome;
    } else {
        $message = "Erro ao atualizar dados: " . $conexao->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Paciente</title>
    <link rel="stylesheet" href="../../styles/detalhe_paciente.css">
</head>
<body>
    <div class="main-content">
        <h2>Detalhes do Paciente: <?php echo htmlspecialchars($nome_paciente); ?></h2>
        <?php if ($message): ?>
            <div class="resultado"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="detalhes_paciente.php?id=<?php echo htmlspecialchars($paciente_id); ?>" method="post">
            <div class="form-group">
            <a href="../../components/pagina/pacientes.php" class="back-arrow">←</a>
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?php echo isset($paciente['nome']) ? htmlspecialchars($paciente['nome']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="idade">Idade</label>
                <input type="number" id="idade" name="idade" value="<?php echo isset($paciente['idade']) ? htmlspecialchars($paciente['idade']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="patologia">Patologia</label>
                <input type="text" id="patologia" name="patologia" value="<?php echo isset($paciente['patologia']) ? htmlspecialchars($paciente['patologia']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo isset($paciente['telefone']) ? htmlspecialchars($paciente['telefone']) : ''; ?>" required>
            </div>
            <button type="submit" class="btn" name="atualizar">Atualizar</button>
            <button type="submit" class="btn btn-danger" name="deletar">Deletar Paciente</button>
        </form>

        <h3>Conduta do Paciente</h3>
        <div class="conduta">
            <?php echo nl2br(htmlspecialchars($conduta)); // Exibe a conduta do paciente, formatando quebras de linha ?>
        </div>
        
    </div>
</body>
</html>
