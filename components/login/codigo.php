<?php
// Inclui o arquivo de conexão
include('../../scripts/conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém o e-mail da URL
    $email = isset($_GET['email']) ? $_GET['email'] : '';

    // Verifica se a chave 'codigo' existe em $_POST
    if (isset($_POST['codigo']) && is_array($_POST['codigo'])) {
        $codigo_digitado = implode('', $_POST['codigo']); // Concatena o código digitado pelo usuário

        // Verifica se o e-mail existe
        $sql = "SELECT * FROM fisioterapeutas WHERE email = '$email'";
        $result = mysqli_query($conexao, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $codigo_armazenado = $row['codigo_verificacao'];

            if ($codigo_digitado === $codigo_armazenado) {
                // Código correto - redireciona para a página de alteração de senha
                header('Location: ../../components/login/redefinir.php?email=' . urlencode($email)); // Passa o email para a página de alteração
                exit();
            } else {
                $erro = "Código de verificação incorreto. Tente novamente.";
            }
        } else {
            $erro = "E-mail não encontrado.";
        }
    } else {
        $erro = "Por favor, digite o código de verificação.";
    }
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Código de Verificação</title>
    <link rel="stylesheet" href="../../styles/codigo.css" />
    <script>
        function moveToNextInput(current, nextInput) {
            if (current.value.length >= current.maxLength) {
                if (nextInput) {
                    nextInput.focus();
                }
            }
        }

        function moveToPreviousInput(current, previousInput) {
            if (current.value.length === 0) {
                if (previousInput) {
                    previousInput.focus();
                }
            }
        }
    </script>
</head>
<body class="verificacao-pagina">
    <div class="container">
        <div class="background-pattern">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <div class="login-box">
            <a href="../../components/login/recuperacao.php" class="back-arrow">←</a>
            <img src="../../assets/logo.png" alt="Logo" class="logo" />
            <h2>Código de verificação</h2>
            <p>Digite o código enviado no seu email</p>
            <form method="POST" action="">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <div class="input-group">
                    <input type="text" maxlength="1" name="codigo[]" oninput="moveToNextInput(this, this.nextElementSibling); moveToPreviousInput(this, this.previousElementSibling);" />
                    <input type="text" maxlength="1" name="codigo[]" oninput="moveToNextInput(this, this.nextElementSibling); moveToPreviousInput(this, this.previousElementSibling);" />
                    <input type="text" maxlength="1" name="codigo[]" oninput="moveToNextInput(this, this.nextElementSibling); moveToPreviousInput(this, this.previousElementSibling);" />
                    <input type="text" maxlength="1" name="codigo[]" oninput="moveToNextInput(this, this.nextElementSibling); moveToPreviousInput(this, this.previousElementSibling);" />
                    <input type="text" maxlength="1" name="codigo[]" oninput="moveToNextInput(this, this.nextElementSibling); moveToPreviousInput(this, this.previousElementSibling);" />
                    <input type="text" maxlength="1" name="codigo[]" oninput="moveToNextInput(this, this.nextElementSibling); moveToPreviousInput(this, this.previousElementSibling);" />
                </div>
                <button type="submit">Enviar</button>
            </form>
            <?php if (isset($erro)): ?>
                <p class="error-message"><?php echo htmlspecialchars($erro); ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
