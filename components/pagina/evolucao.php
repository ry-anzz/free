<?php
session_start();
include('../../scripts/conexao.php');

$message = '';
$conduta = '';
$atividadesFeitas = []; // Array para armazenar atividades feitas pelo paciente

// Verifique se já temos o paciente na sessão
if (isset($_SESSION['paciente_id'])) {
    $paciente_id = $_SESSION['paciente_id'];
    $nome_paciente = $_SESSION['nome_paciente'];
} else {
    $paciente_id = null; // Inicializa o ID do paciente como nulo
}

// Processar busca de paciente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['procurar'])) {
    $nome_paciente = $_POST['nome'];
    
    // Consulta no banco de dados
    $query = "SELECT id, conduta FROM pacientes WHERE nome = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("s", $nome_paciente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result === false) {
        $message = "Erro na consulta: " . $conexao->error;
    } else {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $conduta = $row['conduta'];
            $paciente_id = $row['id']; // Atribui o ID do paciente
            
            // Armazena os dados do paciente na sessão
            $_SESSION['paciente_id'] = $paciente_id;
            $_SESSION['nome_paciente'] = $nome_paciente;
            
            // Consultar atividades já feitas do paciente
            $queryEvolucao = "SELECT atividades FROM evolucao WHERE paciente_id = ? AND feito = 'sim'";
            $stmtEvolucao = $conexao->prepare($queryEvolucao);
            $stmtEvolucao->bind_param("i", $paciente_id);
            $stmtEvolucao->execute();
            $resultEvolucao = $stmtEvolucao->get_result();
            
            // Armazenar as atividades feitas em um array
            while ($rowEvolucao = $resultEvolucao->fetch_assoc()) {
                $atividadesFeitas[] = $rowEvolucao['atividades'];
            }
        } else {
            $message = "Nenhuma conduta encontrada.";
        }
    }
}

// Função para dividir a conduta em semanas, dias e atividades
function parseConduta($conduta) {
    $semanas = [];
    
    // Dividir a conduta por semanas
    preg_match_all("/Semana\s+(\d+):\s*(.*?)\s*(?=Semana\s+\d+:|\Z)/s", $conduta, $matches);
    
    foreach ($matches[0] as $index => $match) {
        $semana = "Semana " . trim($matches[1][$index]);
        $atividadesSemana = trim($matches[2][$index]);

        // Dividir as atividades por dias
        preg_match_all("/(Segunda-feira|Quarta-feira|Sexta-feira):\s*(.*?)\s*(?=(Segunda-feira|Quarta-feira|Sexta-feira)|\Z)/s", $atividadesSemana, $atividadesMatches);

        foreach ($atividadesMatches[1] as $dayIndex => $dia) {
            $atividadesDia = trim($atividadesMatches[2][$dayIndex]);
            $atividades = explode("\n", $atividadesDia);
            
            foreach ($atividades as $atividade) {
                if (preg_match("/Exercício\s+\d+\s+-\s+(.+):\s*(.*)/", $atividade, $exercicioMatch)) {
                    $exercicio = trim($exercicioMatch[1]);
                    $detalhes = trim($exercicioMatch[2]);

                    if (!empty($exercicio) && !empty($detalhes)) {
                        $semanas[$semana][] = [
                            'dia' => trim($dia),
                            'exercicio' => $exercicio,
                            'detalhes' => $detalhes
                        ];
                    }
                }
            }
        }
    }
    return $semanas;
}

$semanas = parseConduta($conduta);

// Processar salvamento do progresso
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['salvar_progresso'])) {
    // Verificar se o paciente_id não é nulo
    if ($paciente_id !== null) {
        // Obter atividades feitas do paciente antes de atualizar
        $queryEvolucao = "SELECT atividades FROM evolucao WHERE paciente_id = ? AND feito = 'sim'";
        $stmtEvolucao = $conexao->prepare($queryEvolucao);
        $stmtEvolucao->bind_param("i", $paciente_id);
        $stmtEvolucao->execute();
        $resultEvolucao = $stmtEvolucao->get_result();
        $atividadesFeitasAntigas = [];

        // Armazenar as atividades feitas em um array
        while ($rowEvolucao = $resultEvolucao->fetch_assoc()) {
            $atividadesFeitasAntigas[] = $rowEvolucao['atividades'];
        }

        // Se houver atividades selecionadas
        if (!empty($_POST['feito'])) {
            // Atualizar as atividades
            foreach ($_POST['feito'] as $exercicio) {
                // Se a atividade não estava anteriormente como "feita", insira-a
                if (!in_array($exercicio, $atividadesFeitasAntigas)) {
                    $query = "INSERT INTO evolucao (paciente_id, atividades, feito) VALUES (?, ?, 'sim')";
                    $stmt = $conexao->prepare($query);
                    $stmt->bind_param("is", $paciente_id, $exercicio);
                    $stmt->execute();
                }
            }

            // Verificar atividades que foram desmarcadas
            foreach ($atividadesFeitasAntigas as $atividadeAntiga) {
                if (!in_array($atividadeAntiga, $_POST['feito'])) {
                    // Atualizar para "não feito"
                    $queryAtualizar = "UPDATE evolucao SET feito = 'não' WHERE paciente_id = ? AND atividades = ?";
                    $stmtAtualizar = $conexao->prepare($queryAtualizar);
                    $stmtAtualizar->bind_param("is", $paciente_id, $atividadeAntiga);
                    $stmtAtualizar->execute();
                }
            }
            $message = "Progresso salvo com sucesso!";
        } else {
            // Se não há atividades selecionadas, desmarcar todas como "não feito"
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

            <?php if ($conduta): ?>
                <h3>Evolução do Paciente: <?php echo htmlspecialchars($nome_paciente); ?></h3>
                
                <?php if (!empty($semanas)): ?>
                    <form action="evolucao.php" method="post">
                        <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome_paciente); ?>">
                        <input type="hidden" name="paciente_id" value="<?php echo htmlspecialchars($paciente_id); ?>">
                        <table>
                            <thead>
                                <tr>
                                    <th>Semana</th>
                                    <th>Dia</th>
                                    <th>Exercício</th>
                                    <th>Detalhes</th>
                                    <th>Feito</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($semanas as $semana => $atividades): ?>
                                    <?php foreach ($atividades as $atividade): ?>
                                        <tr>
                                            <td><?php echo $semana; ?></td>
                                            <td><?php echo $atividade['dia']; ?></td>
                                            <td><?php echo $atividade['exercicio']; ?></td>
                                            <td><?php echo $atividade['detalhes']; ?></td>
                                            <td>
                                                <input type="checkbox" name="feito[]" value="<?php echo htmlspecialchars($atividade['exercicio']); ?>" 
                                                    <?php echo in_array($atividade['exercicio'], $atividadesFeitas) ? 'checked' : ''; ?>>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn-salvar" name="salvar_progresso">Salvar</button>
                    </form>
                <?php else: ?>
                    <p>Nenhuma atividade disponível para este paciente.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
