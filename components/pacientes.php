<?php
include("../scripts/conexao.php");
// Consulta SQL para listar todos os eventos
$sql = "SELECT * FROM pacientes";
$result = $conexao->query($sql);

if ($result->num_rows > 0) {
// Exibir os eventos em uma tabela
echo '<table>';
                
echo "<table><tr><th>Nome</th><th>idade</th><th>patologia</th><th>telefone</th>";

while ($row = $result->fetch_assoc()) {

echo "<tr><td>" . $row["nome"] . "</td><td>" . $row["idade"] . "</td><td>" . $row["patologia"] . "</td><td>" . $row["telefone"] . "</td>". "</td></tr>";
}
echo "</table>";
} else {
   echo "Nenhum evento encontrado.";
}


?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/pacientes.css">
    <title>Document</title>
</head>
<body>
    
</body>
</html>