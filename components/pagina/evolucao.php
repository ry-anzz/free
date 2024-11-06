<?php
session_start();
include('../../scripts/conexao.php');

$id_fisio = $_SESSION['id'];
$message = '';
$atividadesFeitas = [];
$conduta = '';

// Verifica se já temos o paciente na sessão
if (isset($_SESSION['paciente_id'])) {
    $paciente_id = $_SESSION['paciente_id'];
    $nome_paciente = $_SESSION['nome_paciente'];
} else {
    $paciente_id = null;
}

// Processar busca de paciente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['procurar'])) {
    $nome_paciente = $_POST['nome'];
    
    // Consulta no banco de dados
    $query = "SELECT id, conduta FROM pacientes WHERE fisioterapeuta_id = ? AND nome = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("is", $id_fisio, $nome_paciente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $conduta = $row['conduta'];
        $paciente_id = $row['id'];

        // Armazena os dados do paciente na sessão
        $_SESSION['paciente_id'] = $paciente_id;
        $_SESSION['nome_paciente'] = $nome_paciente;
        
        // Consultar atividades já feitas do paciente
        $queryEvolucao = "SELECT atividades FROM evolucao WHERE paciente_id = ? AND feito = 'sim'";
        $stmtEvolucao = $conexao->prepare($queryEvolucao);
        $stmtEvolucao->bind_param("i", $paciente_id);
        $stmtEvolucao->execute();
        $resultEvolucao = $stmtEvolucao->get_result();
        
        while ($rowEvolucao = $resultEvolucao->fetch_assoc()) {
            $atividadesFeitas[] = $rowEvolucao['atividades'];
        }
    } else {
        $message = "Nenhuma conduta encontrada.";
    }
}

// Função para estruturar a conduta fisioterapêutica
function parseConduta($conduta) {
    $atividades = [];
    
    // Regex para capturar o conteúdo de "Conduta Fisioterapêutica"
    $pattern = "/Conduta Fisioterapêutica:(.*?)(?=\nAssinatura|$)/s";
    
    if (preg_match($pattern, $conduta, $match)) {
        $conduta_texto = trim($match[1]);

        // Quebrar o texto em linhas
        $lines = explode("\n", $conduta_texto);
        $current_activity = "";

        foreach ($lines as $line) {
            $line = trim($line);

            // Detectar linhas com títulos de atividades que terminam com ":"
            if (substr($line, -1) === ":") {
                // Salvar atividade anterior antes de iniciar uma nova
                if (!empty($current_activity)) {
                    // Remover a assinatura do fisioterapeuta, se presente
                    $current_activity['detalhes'] = preg_replace("/Assinatura do fisioterapeuta:.*$/", "", $current_activity['detalhes']);
                    $atividades[] = [
                        'exercicio' => $current_activity['exercicio'],
                        'detalhes' => $current_activity['detalhes']
                    ];
                }
                // Iniciar nova atividade
                $current_activity = [
                    'exercicio' => rtrim($line, ":"),
                    'detalhes' => ""
                ];
            } else {
                // Adicionar a linha ao campo 'detalhes' da atividade atual
                if (!empty($current_activity)) {
                    $current_activity['detalhes'] .= ($current_activity['detalhes'] ? " " : "") . $line;
                }
            }
        }

        // Adicionar a última atividade capturada, removendo a assinatura
        if (!empty($current_activity)) {
            $current_activity['detalhes'] = preg_replace("/Assinatura do fisioterapeuta:.*$/", "", $current_activity['detalhes']);
            $atividades[] = [
                'exercicio' => $current_activity['exercicio'],
                'detalhes' => $current_activity['detalhes']
            ];
        }
    }

    return $atividades;
}

if (isset($_POST['salvar_progresso']) && isset($_POST['feito'])) {
    // O array 'feito' conterá os exercícios que foram marcados
    $exerciciosFeitos = $_POST['feito'];
    
    // Primeiro, precisamos marcar todas as atividades como "não feitas" inicialmente
    $queryUpdate = "UPDATE evolucao SET feito = 'nao' WHERE paciente_id = ? AND feito = 'sim'";
    $stmtUpdate = $conexao->prepare($queryUpdate);
    $stmtUpdate->bind_param("i", $paciente_id);
    $stmtUpdate->execute();
    
    // Agora, marcamos as atividades selecionadas como "feitas"
    foreach ($exerciciosFeitos as $exercicio) {
        // Aqui você deve associar o exercício à atividade correta no banco de dados
        $queryUpdateFeito = "UPDATE evolucao SET feito = 'sim' WHERE paciente_id = ? AND atividades LIKE ?";
        $stmtUpdateFeito = $conexao->prepare($queryUpdateFeito);
        $stmtUpdateFeito->bind_param("is", $paciente_id, $exercicio);
        $stmtUpdateFeito->execute();
    }

    // Feedback para o usuário
    $message = "Progresso salvo com sucesso!";
}

$atividades = parseConduta($conduta);

?>

<!DOCTYPE html>
<html lang="pt-BR">
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
                <form action="evolucao.php" method="post">
                    <div class="form-group">
                        <label for="nome">Nome do Paciente</label>
                        <input type="text" id="nome" name="nome" placeholder="Nome completo do paciente" required>
                        <button type="submit" class="btn" name="procurar">Procurar</button>
                    </div>
                </form>
            </div>

            <?php if ($message): ?>
                <div class="resultado"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if (!empty($atividades)): ?>
    <h3>Evolução Fisioterapêutica: <?php echo htmlspecialchars($nome_paciente); ?></h3>
    
    <form action="evolucao.php" method="post">
        <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome_paciente); ?>">
        <input type="hidden" name="paciente_id" value="<?php echo htmlspecialchars($paciente_id); ?>">
        <table>
            <thead>
                <tr>
                    <th>Semana</th>
                    <th>Exercício</th>
                    <th>Detalhes</th>
                    <th>Feito</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $semana = 1;
                foreach ($atividades as $atividade): 
                ?>
                    <tr>
                        <td><?php echo "Semana " . $semana; ?></td> <!-- Coluna de semana -->
                        <td><?php echo htmlspecialchars($atividade['exercicio']); ?></td>
                        <td><?php echo htmlspecialchars($atividade['detalhes']); ?></td>
                        <td>
                            <input type="checkbox" name="feito[]" value="<?php echo htmlspecialchars($atividade['exercicio']); ?>" 
                                <?php echo in_array($atividade['exercicio'], $atividadesFeitas) ? 'checked' : ''; ?>>
                        </td>
                    </tr>
                    <?php $semana++; ?> <!-- Incrementa a semana para a próxima atividade -->
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn-salvar" name="salvar_progresso">Salvar</button>
    </form>
<?php else: ?>
    <p>Nenhuma conduta disponível para este paciente.</p>
<?php endif; ?>

        </div>
    </div>
</body>
</html>