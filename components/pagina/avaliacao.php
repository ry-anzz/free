<?php
 if(!isset($_SESSION)){
    session_start();
    $nome = $_SESSION['nome'];
  }

// Ativa a exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclui o arquivo com a função para acessar a API
include('../../api/main.php');

$message = '';
$resposta_chatgpt = '';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se todos os campos estão preenchidos
    if (!empty($_POST['nome']) && !empty($_POST['idade']) && !empty($_POST['patologia']) && !empty($_POST['sexo']) && !empty($_POST['tempo']) && !empty($_POST['queixa']) && !empty($_POST['tratamento'])  && !empty($_POST['inspecao']) && !empty($_POST['palpacao']) && !empty($_POST['adm']) && !empty($_POST['forca']) && !empty($_POST['tst_pos'])  && !empty($_POST['tst_neg']) && !empty($_POST['data'])) {
        $nome = $_POST['nome'];
        $idade = $_POST['idade'];
        $patologia = $_POST['patologia'];
        $sexo = $_POST['sexo'];
        $tempo = $_POST['tempo'];
        $queixa = $_POST['queixa'];
        $tratamento = $_POST['tratamento'];
        $inspecao = $_POST['inspecao'];
        $palpacao = $_POST['palpacao'];
        $adm = $_POST['adm'];
        $forca = $_POST['forca'];
        $tst_pos = $_POST['tst_pos'];
        $tst_neg = $_POST['tst_neg'];
        $data = $_POST['data'];


        // Faz a consulta para a API usando as informações do paciente
        $resposta_chatgpt = chatgpt_query("*Ficha de Avaliação Fisioterapêutica*

*Dados do Paciente:*
- Nome:" . $nome . "
- Data da Avaliação:" . $data . "  
- Idade:" . $idade . "
- Sexo:" . $sexo . "

*Queixa Principal:*
 - " . $queixa . "

*História Clínica:*
- Diagnóstico: " . $patologia . "
- Tempo de sintomas: " . $tempo . "
- Tratamentos prévios:" . $tratamento . "

*Avaliação Física:*

1. *Inspeção:*
   -" . $inspecao . "

2. *Palpação:*
   - " . $palpacao . " 

3. *Amplitude de Movimento (ADM):*
   -" . $adm . "

4. *Força Muscular:*
   -" . $forca . " 

5. *Testes Específicos:*
   - *Testes Positivos:*
     -" . $tst_pos . "
   - *Testes Negativos:*
     -" . $tst_neg . "

*Avaliação Funcional:*
- [chatgpt, Descreva a capacidade do paciente em realizar atividades diárias e esportivas, se aplicável.]

*Objetivos da Fisioterapia:*
1. Reduzir a dor e a inflamação.
2. Melhorar a amplitude de movimento e a força.
3. Retornar às atividades funcionais sem dor.

*Plano de Tratamento:*
- [chatgpt, Descreva as intervenções propostas, como eletroterapia, exercícios terapêuticos, alongamentos, e orientações posturais.]

*Observações:*
- [chatgpt, descreva Qualquer outra informação relevante que possa auxiliar no tratamento.]
 
E me de a ficha colocando os dados que te passei no mesmo formato que te mandei, colocando cada topico que dei e cada informação que você colocou  

E depois faça uma conduta gere uma conduta fisioterapeutica baseada em evidências atuais para esse paciente

E me de a conduta separando em topicos e especificando o que deve ser realizado em cada um") . "                                          Assinatura do fisioterapeuta: " . $_SESSION['nome'] ."/". $_SESSION['crefito'];

        // Armazena a conduta na sessão para passar para a próxima página
        $_SESSION['conduta'] = $resposta_chatgpt;

    } else {
        $message = "Por favor, preencha todos os campos do formulário.";
    }
}
?>        

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação</title>
    <link rel="stylesheet" href="../../styles/avaliac.css">
    
</head>
<body>
<div class="main-content-avaliacao">
    <div id="section-avaliacao" class="content-section">
        <h2>Fazer avaliação</h2>
        <p>Faça uma avaliação e conduta do paciente</p>
        <div class="divisor"></div>
        <div class="form-content">

            <!-- O formulário envia a resposta para a API -->
            <form id="avaliacao-form" method="post" onsubmit="showLoading()">
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" placeholder="Nome do paciente" required>
                        </div>

                        <div class="form-group">
                            <label for="idade">Idade</label>
                            <input type="text" id="idade" name="idade" placeholder="Idade do paciente" required>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <input type="text" id="sexo" name="sexo" placeholder="Sexo do paciente" required>
                        </div>

                        <div class="form-group">
                            <label for="patologia">Patologia</label>
                            <input type="text" id="patologia" name="patologia" placeholder="Patologia do paciente" required>
                        </div>

                        <div class="form-group">
                            <label for="tempo">Tempo de sintomas</label>
                            <input type="text" id="tempo" name="tempo" placeholder="Tempo dos sintomas" required>
                        </div>

                        <div class="form-group">
                            <label for="queixa">Queixa Principal</label>
                            <textarea id="queixa" name="queixa" placeholder="Queixa principal do paciente" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="tratamento">Tratamento prévio</label>
                            <input type="text" id="tratamento" name="tratamento" placeholder="Tratamento Prévio" required>
                        </div>
                    </div>

                    <div class="form-column">
                        <div class="form-group">
                            <label for="inspecao">Inspeção</label>
                            <input type="text" id="inspecao" name="inspecao" placeholder="Descrição de quaisquer alterações visíveis, como inchaço ou atrofia muscular" required>
                        </div>

                        <div class="form-group">
                            <label for="palpacao">Palpação</label>
                            <input type="text" id="palpacao" name="palpacao" placeholder="Dor à palpação na região do tendão do supraespinhal e possíveis pontos de dor referida" required>
                        </div>

                        <div class="form-group">
                            <label for="adm">Amplitude de Movimento</label>
                            <input type="text" id="adm" name="adm" placeholder="Inserir valores específicos de ADM ativa e passiva para abdução, flexão, extensão, rotação interna e externa." required>
                        </div>

                        <div class="form-group">
                            <label for="forca">Força Muscular</label>
                            <input type="text" id="forca" name="forca" placeholder="Inserir avaliação de força para os músculos relacionados, utilizando a escala de 0 a 5" required>
                        </div>

                        <div class="form-group">
                            <label for="tst_pos">Testes Positivos</label>
                            <input type="text" id="tst_pos" name="tst_pos" placeholder="Inserir testes que resultaram positivos, como o teste de Jobe ou o teste de Neer" required>
                        </div>

                        <div class="form-group">
                            <label for="tst_neg">Testes Negativos</label>
                            <input type="text" id="tst_neg" name="tst_neg" placeholder="Inserir testes que resultaram negativos, como o teste de Hawkins-Kennedy" required>
                        </div>

                        <div class="form-group">
                            <label for="data">Data da avaliação</label>
                            <input type="date" id="data" name="data" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-avaliar">Avaliar</button>
            </form>

            <!-- Exibe a mensagem de erro, se houver -->
            <?php if ($message): ?>
                <div class="error-message"><?php echo $message; ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
            <!-- Exibe a resposta da API -->
            <?php if ($resposta_chatgpt): ?>
                <div class="conduta-result">
                <h3>Resultado:</h3>
                <p><?php echo nl2br(htmlspecialchars($resposta_chatgpt)); ?></p>
                
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="loading" style="display: none;">Aguarde, processando...</div>
<!-- Botão para salvar a conduta e redirecionar para a página de cadastro -->
    <?php if ($resposta_chatgpt): ?>
        <div>
            <form action="../../components/pagina/cadastrar.php" method="post">
                <input type="hidden" name="conduta" value="<?php echo htmlspecialchars($resposta_chatgpt); ?>">
                <button type="submit" class="btn-salvar">Salvar</button>
            </form>
        </div>
    <?php endif; ?>

<script>
    // Função para exibir a mensagem de carregamento
    function showLoading() {
        document.getElementById('loading').style.display = 'block';
    }
</script>

</body>
</html>
