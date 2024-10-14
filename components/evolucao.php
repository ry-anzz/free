
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evolução do Paciente</title>
    <link rel="stylesheet" href="../styles/evolucao.css">
</head>
<body>
    <div class="container">
        <h1>Evolução do Paciente</h1>

        <!-- Formulário para registrar evolução -->
        <form action="evolucao.php" method="POST" class="form-evolucao">
            <input type="hidden" name="paciente_id" value="<?php echo $paciente_id;?>">
            <label for="descricao">Descrição da Evolução:</label>
            <textarea name="descricao" required class="input-textarea"></textarea>
            <button type="submit" class="btn">Registrar Evolução</button>
        </form>

        <!-- Histórico de evoluções -->
        <h2>Histórico de Evoluções</h2>
        <div class="historico">
            <ul>
                <?php foreach ($evolucoes as $evolucao): ?>
                    <li>
                        <div class="evolucao-item">
                            <span class="data"><?php echo date('d/m/Y', strtotime($evolucao['data_avaliacao'])); ?></span>
                            <p><?php echo htmlspecialchars($evolucao['descricao']); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>

