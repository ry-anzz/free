<?php
include('../../scripts/conexao.php'); // Inclui a conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Coleta os dados do formulário
    $nome = $conexao->real_escape_string($_POST['nome']);
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];
    $crefito = $conexao->real_escape_string($_POST['crefito']);
    
    // Cria um hash seguro para a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Verifica se o email já existe no banco de dados
    $sql_check = "SELECT * FROM fisioterapeutas WHERE email = '$email'";
    $check_result = $conexao->query($sql_check);

    if ($check_result->num_rows > 0) {
        $mensagem = "Este email já está cadastrado. Tente outro.";
    } elseif (!preg_match('/^\d{5}-[A-Z]$/', $crefito)) {
        $mensagem = "Número do CREFITO inválido. Formato esperado: 12345-F";
    } else {
        // Insere o novo usuário no banco de dados
        $sql_insert = "INSERT INTO fisioterapeutas (nome, email, senha, crefito) VALUES ('$nome', '$email', '$senhaHash', '$crefito')";

        if ($conexao->query($sql_insert) === TRUE) {
            $mensagem = "Cadastro realizado com sucesso!";
            header("Location: login.php"); // Redireciona para a página de login após o cadastro
            exit();
        } else {
            $mensagem = "Erro ao cadastrar: " . $conexao->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastre-se</title>
    <link rel="stylesheet" href="../../styles/login.css" />
    <script>
        function formatarCrefito(input) {
            let valor = input.value.replace(/[^a-zA-Z0-9]/g, ''); // Remove caracteres especiais, mas mantém números e letras
            if (valor.length > 5) {
                input.value = valor.slice(0, 5) + '-' + valor.slice(5, 6).toUpperCase(); // Adiciona o traço e permite uma letra no final
            } else {
                input.value = valor;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <img src="../../assets/logo.png" alt="GESPAT Logo" class="logo" />
            <h2>Bem-Vindo ao FisioAvalia</h2>
            <p>Cadastre sua conta</p>
            
            <!-- Exibe uma mensagem, caso exista -->
            <?php if (isset($mensagem)) echo "<p style='color:red;'>$mensagem</p>"; ?>

            <form method="POST">
                <div class="input-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="seu nome" required />
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="usuario@gmail.com" required />
                </div>
                <div class="input-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="********" required />
                </div>
                <div class="input-group">
                    <label for="crefito">Crefito</label>
                    <input type="text" id="crefito" name="crefito" placeholder="12345-F" oninput="formatarCrefito(this)" maxlength="7" required />
                </div>
                <button type="submit">Cadastrar</button>
            </form>
        </div>
    </div>
</body>
</html>
