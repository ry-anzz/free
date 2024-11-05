<?php
session_start();
include('../../scripts/conexao.php');

if (isset($_POST['email']) && isset($_POST['senha'])) {
    if (strlen($_POST['email']) == 0) {
        echo "Preencha o campo Email";
    } else if (strlen($_POST['senha']) == 0) {
        echo "Preencha o campo Senha";
    } else {
        $email = $conexao->real_escape_string($_POST['email']);
        $senha = $_POST['senha'];

        // Verifica se o email existe
        $sql_code = "SELECT * FROM fisioterapeutas WHERE email = '$email'";
        $sql_query = $conexao->query($sql_code) or die("Falha na execução do código SQL: ". $conexao->error);

        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();

            // Verifica se a senha digitada corresponde ao hash armazenado
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['crefito'] = $usuario['crefito'];
                
                header("Location: ../../components/pagina/index.php");
                exit();
            } else {
               $mensagem = "Falha ao logar! Email ou senha incorretos";
            }
        } else {
            $mensagem = "Falha ao logar! Email ou senha incorretos";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - FisioAvalia</title>
    <link rel="stylesheet" href="../../styles/login.css" />
</head>
<body>
    <div class="container">
        <div class="login-box">
            <img src="../../assets/logo.png" alt="FisioAvalia Logo" class="logo" />
            <h2>Bem-Vindo ao FisioAvalia</h2>
            <p>Entre na sua conta</p>
            <?php if (isset($mensagem)) echo "<p style='color:red;'>$mensagem</p>"; ?>
            <form method="POST" action="">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="usuario@gmail.com" />
                </div>
                <div class="input-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="senha" placeholder="********" />
                </div>
                <a href="../../components/login/recuperacao.php" class="forgot-password">Esqueceu a senha?</a>
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>