<?php
include('../../scripts/conexao.php');
session_start(); // Inicia a sessão

$message = '';
$conduta = $_SESSION['conduta'] ?? ''; // Captura a conduta armazenada na sessão

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do paciente
    $nome = $_POST['nome'] ?? '';
    $idade = (int)($_POST['idade'] ?? 0);
    $patologia = $_POST['patologia'] ?? '';
    $telefone = $_POST['telefone'] ?? '';

    // Verifica se o nome do paciente já existe no banco de dados
    $nome_normalizado = trim(strtolower($nome)); // Normaliza o nome para comparação
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM pacientes WHERE LOWER(nome) = ?");
    $stmt->bind_param("s", $nome_normalizado);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $message = "O nome do paciente já está cadastrado.";
    } else {
        // Insere o paciente no banco de dados
        $stmt = $conexao->prepare("INSERT INTO pacientes (nome, idade, patologia, telefone, conduta) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sisss", $nome, $idade, $patologia, $telefone, $conduta);
            if ($stmt->execute()) {
                $message = "Registro realizado com sucesso";
                // Limpa a sessão após o registro
                unset($_SESSION['conduta']);
            } else {
                $message = "Erro ao cadastrar paciente: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Erro ao preparar a consulta: " . $conexao->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="../../styles/cadastra.css">
</head>
<body>
<div class="main-content-cadastrar">
    <div id="section-cadastrar" class="content-section">
        <h2>Cadastrar paciente</h2>
        <p>Faça o cadastro do paciente</p>
        <div class="divisor"></div>

        <div class="form-content">
            <form action="../../components/pagina/cadastrar.php" method="post">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome completo do paciente" required>
                </div>
                <div class="form-group">
                    <label for="idade">Idade</label>
                    <input type="number" id="idade" name="idade" placeholder="Idade do paciente" required>
                </div>                         
                <div class="form-group">
                    <label for="patologia">Patologia</label>
                    <input type="text" id="patologia" name="patologia" placeholder="Patologia do paciente" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" maxlength="11" placeholder="Telefone do paciente" required>
                </div>
                
                <button type="submit" class="btn" name="avaliar">Cadastrar</button>
            </form>
        </div>
    </div>
</div>
<?php if ($message): ?>
    <div class='resultado' id='resultado'>
        <?php echo $message; ?>
        <button class='close-button' onclick='closeMessage()'>X</button>
    </div>
<?php endif; ?>
<script>
    function closeMessage() {
        document.getElementById('resultado').style.display = 'none';
    }
</script>
</body>
</html>
