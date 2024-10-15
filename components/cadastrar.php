<?php
include('../scripts/conexao.php');

$message = ''; // Inicializa a variável de mensagem

if (isset($_POST['avaliar'])) {
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $patologia = $_POST['patologia'];
    $telefone = $_POST['telefone'];
    
    $sql = "INSERT INTO pacientes (nome, idade, patologia, telefone) VALUES ('$nome', '$idade', '$patologia', '$telefone')";
    $resultado = mysqli_query($conexao, $sql);

    if ($resultado) {
        $message = "Registro realizado com sucesso";
    } else {
        // Captura qualquer erro de SQL
        $erro = mysqli_error($conexao);
        
        // Verifica se o erro é de duplicata
        if (strpos($erro, 'Duplicate entry') !== false) {
            $message = "Erro: Já existe um paciente cadastrado com o nome '" . htmlspecialchars($nome) . "'.";
        } else {
            $message = "Erro: " . htmlspecialchars($erro);
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
    <link rel="stylesheet" href="../styles/cadastrar.css">

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
                    <input type="text" id="idade" name="idade" placeholder="Idade do paciente" required>
                </div>                         

                <div class="form-group">
                    <label for="patologia">Patologia</label>
                    <input type="text" id="patologia" name="patologia" placeholder="Patologia do paciente" required>
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" placeholder="Telefone do paciente" required>
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
