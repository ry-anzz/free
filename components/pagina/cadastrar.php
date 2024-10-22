<?php
include('../../scripts/conexao.php');

$message = ''; // Inicializa a variável de mensagem

if (isset($_POST['avaliar'])) {
    // Sanitização e validação dos inputs
    $nome = trim($_POST['nome']);
    $idade = filter_var($_POST['idade'], FILTER_SANITIZE_NUMBER_INT);
    $patologia = trim($_POST['patologia']);
    $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_STRING);

  
        $stmt = $conexao->prepare("INSERT INTO pacientes (nome, idade, patologia, telefone) VALUES (?, ?, ?, ?)");

        if ($stmt) {
            // Associa os parâmetros com a query preparada
            $stmt->bind_param("siss", $nome, $idade, $patologia, $telefone); // 'siss' - string, int, string, string

            // Executa a query
            if ($stmt->execute()) {
                $message = "Registro realizado com sucesso";
            } else {
                $erro = $stmt->error;

                // Verifica se o erro é de duplicata
                if (strpos($erro, 'Duplicate entry') !== false) {
                    $message = "Erro: Já existe um paciente cadastrado com o nome '" . htmlspecialchars($nome) . "'.";
                } else {
                    $message = "Erro: " . htmlspecialchars($erro);
                }
            }

            // Fecha a statement
            $stmt->close();
        } else {
            $message = "Erro ao preparar a consulta: " . htmlspecialchars($conexao->error);
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
            <form action="cadastrar.php" method="post">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome completo do paciente" required>
                </div>

                <div class="form-group">
                    <label for="idade">Idade</label>
                    <input type="text" id="idade" name="idade" maxlength="3" placeholder="Idade do paciente" required>
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
        document.getElementById('resultado').style.display = 'none';}
</script>

</body>
</html>
