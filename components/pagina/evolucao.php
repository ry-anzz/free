
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evolução do Paciente</title>
    <link rel="stylesheet" href="../../styles/evolucao.css">
</head>
<body>

<div class="main-content-evolucao">
    <div id="section-evolucao" class="content-section">
        <h2>Evolução</h2>
        <p>Veja a evolução do paciente</p>
        <div class="divisor"></div>

        <div class="form-content">
            <form action="cadastrar.php" method="post">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome completo do paciente" required>
                    <button type="submit" class="btn" name="avaliar">Procurar</button>
                </div>
            </form>
        </div>
    </div>
</div>
         
</body>
</html>

