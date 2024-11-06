<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/paciente.css">
    <title>Pacientes</title>
</head>
<body>
<div class="main-content-cadastrar">
    <div id="section-cadastrar" class="content-section">
        <h2>Pacientes</h2>
        <p>Veja todos os pacientes</p>
        <div class="divisor"></div>

        <?php
session_start();
if (!isset($_SESSION['id'])) {
    echo "<p>Erro: ID do fisioterapeuta não encontrado. Por favor, faça login novamente.</p>";
    exit;
}

$id_fisio = $_SESSION['id'];
include("../../scripts/conexao.php");

// Consulta SQL para listar todos os pacientes
$sql = "SELECT * FROM pacientes WHERE fisioterapeuta_id = $id_fisio";
$result = $conexao->query($sql);

if ($result->num_rows > 0) {
    // Exibir os pacientes em uma tabela
    echo '<table>';
    echo "<tr><th>Nome</th><th>Idade</th><th>Patologia</th><th>Telefone</th></tr>"; // Cabeçalho da tabela

    while ($row = $result->fetch_assoc()) {
        // Adiciona um link em cada nome de paciente
        echo "<tr>
                <td><a href='detalhes_paciente.php?id=" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["nome"]) . "</a></td>
                <td>" . htmlspecialchars($row["idade"]) . "</td>
                <td>" . htmlspecialchars($row["patologia"]) . "</td>
                <td>" . htmlspecialchars($row["telefone"]) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhum paciente encontrado.</p>";
}
?>


    </div>
</div>
</body>
</html>
