<?php
session_start();
include('../../scripts/conexao.php');

$id_fisio = $_SESSION['id'];
$message = '';
$atividadesFeitas = [];
$conduta = ''; // Inicializando a variável para evitar o aviso

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
    $stmt->bind_param("is", $id_fisio, $nome_paciente); // Correção da string de tipos
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

// Função para estruturar a conduta
function parseConduta($conduta) {
    $atividades = [];

    preg_match_all("/\*\*(.+):\*\*([^*]+)(?=\*\*|$)/", $conduta, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $atividade = trim($match[1]);
        $detalhes = trim($match[2]);
        if (!empty($atividade)) {
            $atividades[] = [
                'exercicio' => $atividade,
                'detalhes' => $detalhes
            ];
        }
    }

    return $atividades;
}

$atividades = parseConduta($conduta);

// Processar salvamento do progresso
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['salvar_progresso'])) {
    if ($paciente_id !== null) {
        $queryEvolucao = "SELECT atividades FROM evolucao WHERE paciente_id = ? AND feito = 'sim'";
        $stmtEvolucao = $conexao->prepare($queryEvolucao);
        $stmtEvolucao->bind_param("i", $paciente_id);
        $stmtEvolucao->execute();
        $resultEvolucao = $stmtEvolucao->get_result();
        $atividadesFeitasAntigas = [];

        while ($rowEvolucao = $resultEvolucao->fetch_assoc()) {
            $atividadesFeitasAntigas[] = $rowEvolucao['atividades'];
        }

        if (!empty($_POST['feito'])) {
            foreach ($_POST['feito'] as $exercicio) {
                if (!in_array($exercicio, $atividadesFeitasAntigas)) {
                    $query = "INSERT INTO evolucao (paciente_id, atividades, feito) VALUES (?, ?, 'sim')";
                    $stmt = $conexao->prepare($query);
                    $stmt->bind_param("is", $paciente_id, $exercicio);
                    $stmt->execute();
                }
            }

            foreach ($atividadesFeitasAntigas as $atividadeAntiga) {
                if (!in_array($atividadeAntiga, $_POST['feito'])) {
                    $queryAtualizar = "UPDATE evolucao SET feito = 'não' WHERE paciente_id = ? AND atividades = ?";
                    $stmtAtualizar = $conexao->prepare($queryAtualizar);
                    $stmtAtualizar->bind_param("is", $paciente_id, $atividadeAntiga);
                    $stmtAtualizar->execute();
                }
            }
            $message = "Progresso salvo com sucesso!";
        } else {
            foreach ($atividadesFeitasAntigas as $atividadeAntiga) {
                $queryAtualizar = "UPDATE evolucao SET feito = 'não' WHERE paciente_id = ? AND atividades = ?";
                $stmtAtualizar = $conexao->prepare($queryAtualizar);
                $stmtAtualizar->bind_param("is", $paciente_id, $atividadeAntiga);
                $stmtAtualizar->execute();
            }
            $message = "Nenhum progresso para salvar.";
        }
    } else {
        $message = "Erro: ID do paciente não encontrado.";
    }
}
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
                <h3>Evolução do Paciente: <?php echo htmlspecialchars($nome_paciente); ?></h3>
                
                <form action="evolucao.php" method="post">
                    <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome_paciente); ?>">
                    <input type="hidden" name="paciente_id" value="<?php echo htmlspecialchars($paciente_id); ?>">
                    <table>
                        <thead>
                            <tr>
                                <th>Exercício</th>
                                <th>Detalhes</th>
                                <th>Feito</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($atividades as $atividade): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($atividade['exercicio']); ?></td>
                                    <td><?php echo htmlspecialchars($atividade['detalhes']); ?></td>
                                    <td>
                                        <input type="checkbox" name="feito[]" value="<?php echo htmlspecialchars($atividade['exercicio']); ?>" 
                                            <?php echo in_array($atividade['exercicio'], $atividadesFeitas) ? 'checked' : ''; ?>>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn-salvar" name="salvar_progresso">Salvar</button>
                </form>
            <?php else: ?>
                <p>Nenhuma atividade disponível para este paciente.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
