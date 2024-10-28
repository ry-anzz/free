<?php
// Inclui o arquivo de conexão
include('../../scripts/conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // O e-mail do fisioterapeuta
    $nova_senha = $_POST['new_password']; // Nova senha
    $confirm_senha = $_POST['confirm_password']; // Senha de confirmação

    // Verifica se as senhas coincidem
    if ($nova_senha !== $confirm_senha) {
        echo "As senhas não coincidem.";
        exit();
    }

    // Atualiza a senha no banco de dados usando password_hash
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT); // Cria um hash seguro para a nova senha

    $sql_update = "UPDATE fisioterapeutas SET senha = '$senha_hash' WHERE email = '$email'";
    if (mysqli_query($conexao, $sql_update)) {
        echo "Senha redefinida com sucesso!";
        // Você pode redirecionar para a página de login ou onde desejar
        header("Location: ../../components/login/login.php");
        exit();
    } else {
        echo "Erro ao redefinir a senha: " . mysqli_error($conexao);
    }

    mysqli_close($conexao);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="../../styles/redefinir.css">
</head>
<body class="redefinir-senha-pagina">

    <div class="container">
        <div class="login-box">
            <a href="../../components/login/codigo.php" class="back-arrow">←</a>
            <img src="../../assets/logo.png" alt="Logo AvaliaFisio" class="logo">
            <h2>Redefinir senha</h2>
            <form method="POST" action="">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
                <div class="input-group">
                    <label for="new-password">Nova senha</label>
                    <input type="password" id="new-password" name="new_password" required class="password-input">
                </div>
                <div class="input-group">
                    <label for="confirm-password">Repita a senha</label>
                    <input type="password" id="confirm-password" name="confirm_password" required class="password-input">
                </div>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>

</body>
</html>
