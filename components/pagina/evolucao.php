<?php
// Inicia a sessão
session_start();

// Inclui o arquivo de conexão com o banco de dados
include('../../scripts/conexao.php');

$message = '';
$conduta = '';

// Verifica se o formulário de busca foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['procurar'])) {
    $nome_paciente = $_POST['nome'];
    
    // Consulta no banco de dados para encontrar o paciente pelo nome
    $query = "SELECT id, conduta FROM pacientes WHERE nome = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("s", $nome_paciente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verifica se o paciente foi encontrado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $conduta = $row['conduta'];
        $paciente_id = $row['id'];
    } else {
        $message = "Paciente não encontrado.";
    }
}

// Função para dividir a conduta em fases e atividades
function parseConduta($conduta) {
    $fases = [];
    $sections = explode("**Fase", $conduta);
    
    foreach ($sections as $section) {
        if (trim($section)) {
            $faseInfo = explode(":", $section);
            if (count($faseInfo) > 1) {
                $faseNome = trim($faseInfo[0]);
                $atividades = explode("\n", trim($faseInfo[1]));
                $fases[$faseNome] = array_map('trim', $atividades);
            }
        }
    }
    return $fases;
}

$fases = parseConduta($conduta);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evolução do Paciente</title>
    <link rel="stylesheet" href="../../styles/evoluca.css">
</head>
<body>
    <div class="main-content-evolucao">
        <div id="section-evolucao" class="content-section">
            <h2>Evolução</h2>
            <p>Veja a evolução do paciente</p>
            <div class="divisor"></div>

            <!-- Formulário de busca de paciente -->
            <div class="form-content">
                <form action="evolucao.php" method="post">
                    <div class="form-group">
                        <label for="nome">Nome do Paciente</label>
                        <input type="text" id="nome" name="nome" placeholder="Nome completo do paciente" required>
                        <button type="submit" class="btn" name="procurar">Procurar</button>
                    </div>
                </form>
            </div>

            <!-- Exibe mensagem se o paciente não foi encontrado -->
            <?php if ($message): ?>
                <div class="error-message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Exibe a tabela de evolução se a conduta estiver disponível -->
            <?php if ($conduta): ?>
                <h3>Evolução do Paciente: <?php echo htmlspecialchars($nome_paciente); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Semana</th>
                            <th>Dia da Semana</th>
                            <th>Fase</th>
                            <th>Atividade</th>
                            <th>Realizado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Dias da semana
                        $diasDaSemana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
                        $atividadesPorSemana = [];

                        // Preenche as atividades por semana e dia
                        foreach ($fases as $fase => $atividades) {
                            foreach ($atividades as $atividade) {
                                // Supondo que cada atividade possa ser realizada em dias específicos (ex: "3 vezes por semana")
                                for ($semana = 1; $semana <= 4; $semana++) {
                                    for ($dia = 0; $dia < 7; $dia++) { // Para cada dia da semana
                                        if (!isset($atividadesPorSemana[$semana])) {
                                            $atividadesPorSemana[$semana] = [];
                                        }
                                        $atividadesPorSemana[$semana][$dia][] = [
                                            'fase' => $fase,
                                            'atividade' => trim($atividade),
                                        ];
                                    }
                                }
                            }
                        }

                        // Exibe as atividades organizadas por semana e dia
                        foreach ($atividadesPorSemana as $semana => $dias) {
                            foreach ($dias as $dia => $atividades) {
                                foreach ($atividades as $atividade) {
                                    echo "<tr>";
                                    echo "<td>Semana $semana</td>";
                                    echo "<td>" . htmlspecialchars($diasDaSemana[$dia]) . "</td>";
                                    echo "<td>" . htmlspecialchars($atividade['fase']) . "</td>";
                                    echo "<td>" . htmlspecialchars($atividade['atividade']) . "</td>";
                                    echo "<td><input type='checkbox' name='realizado[$semana][$dia][]' value='" . htmlspecialchars($atividade['atividade']) . "'></td>";
                                    echo "</tr>";
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <button type="submit" class="btn">Salvar Evolução</button>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
