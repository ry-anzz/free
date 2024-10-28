


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
        include("../../scripts/conexao.php");
        // Consulta SQL para listar todos os eventos
        $sql = "SELECT * FROM pacientes";
        $result = $conexao->query($sql);

        if ($result->num_rows > 0) {
            // Exibir os eventos em uma tabela
            echo '<table>';
            echo "<tr><th>Nome</th><th>Idade</th><th>Patologia</th><th>Telefone</th></tr>"; // Cabeçalho da tabela

            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row["nome"]) . "</td><td>" . htmlspecialchars($row["idade"]) . "</td><td>" . htmlspecialchars($row["patologia"]) . "</td><td>" . htmlspecialchars($row["telefone"]) . "</td></tr>";
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
