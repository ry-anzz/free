<?php
include('../../scripts/conexao.php');
session_start();

$id_fisio = $_SESSION['id'];
$conduta = $_SESSION['conduta'] ?? '';
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'], $_POST['idade'], $_POST['patologia'], $_POST['telefone'])) {
    $nome = $_POST['nome'];
    $idade = (int)$_POST['idade'];
    $patologia = $_POST['patologia'];
    $telefone = $_POST['telefone'];

    $nome_normalizado = trim(strtolower($nome));
    // Verifica se já existe um paciente com o mesmo nome associado ao fisioterapeuta logado
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM pacientes WHERE LOWER(nome) = ? AND fisioterapeuta_id = ?");
    $stmt->bind_param("si", $nome_normalizado, $id_fisio);

    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $message = "O nome do paciente já está cadastrado.";
    } else {
        $stmt = $conexao->prepare("INSERT INTO pacientes (fisioterapeuta_id, nome, idade, patologia, telefone, conduta) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("isisss", $id_fisio, $nome, $idade, $patologia, $telefone, $conduta);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Registro realizado com sucesso";
                unset($_SESSION['conduta']);
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
        