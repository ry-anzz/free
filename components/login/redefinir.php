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
            <p>Um código será enviado no seu email</p>
            <div class="input-group">
                <label for="new-password">Nova senha</label>
                <input type="password" id="new-password" class="password-input">
            </div>
            <div class="input-group">
                <label for="confirm-password">Repita a senha</label>
                <input type="password" id="confirm-password" class="password-input">
            </div>
            <button>Enviar</button>
        </div>
    </div>

</body>
</html>