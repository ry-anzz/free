<?php
include('../../scripts/conexao.php');
session_start(); // Ini if(!isset($_SESSION)){

// Armazena a conduta da sessão, se existir
$conduta = $_SESSION['conduta'] ?? '';

$message = $_SESSION['message'] ?? ''; // Captura a mensagem da sessão, se existir
unset($_SESSION['message']); // Limpa a mensagem da sessão após capturá-la

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'], $_POST['idade'], $_POST['patologia'], $_POST['telefone'])) {
    // Recebe os dados do paciente
    $nome = $_POST['nome'];
    $idade = (int)$_POST['idade'];
    $patologia = $_POST['patologia'];
    $telefone = $_POST['telefone'];

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
                $_SESSION['message'] = "Registro realizado com sucesso"; // Armazena a mensagem de sucesso na sessão

                // Limpa a conduta da sessão após o cadastro bem-sucedido
                unset($_SESSION['conduta']);
                
                // Redireciona para evitar reenvio e para exibir a mensagem
                header("Location: cadastrar.php");
                exit();
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
    <link rel="stylesheet" href="../../styles/cadastr.css">
</head>
<body>
<div class="main-content-cadastrar">
    <div id="section-cadastrar" class="content-section">
        <h2>Cadastrar paciente</h2>
        <p>Faça o cadastro do paciente</p>
        <div class="divisor"></div>

        <div class="form-content">
            <form id="cadastrar-form" action="../../components/pagina/cadastrar.php" method="post" onsubmit="return checkConduta()">
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

                <!-- Campo oculto para armazenar a conduta -->
                <input type="hidden" id="conduta" name="conduta" value="<?php echo htmlspecialchars($conduta); ?>">

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
    function checkConduta() {
        // Verifica se a conduta está presente
        const conduta = document.getElementById('conduta').value;
        if (!conduta) {
            alert("É necessário realizar a avaliação antes de cadastrar o paciente.");
            return false; // Impede o envio do formulário
        }
        return true; // Permite o envio se a conduta estiver presente
    }

    function closeMessage() {
        document.getElementById('resultado').style.display = 'none';
    }
</script>
</body>
</html>
        