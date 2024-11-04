<?php
// Inclui o arquivo de conexão
include('../../scripts/conexao.php');

// Inclui os arquivos do PHPMailer
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verifica se o e-mail está registrado no banco de dados
    $sql = "SELECT * FROM fisioterapeutas WHERE email = '$email'";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Gera um código aleatório de 6 dígitos
        $codigo_verificacao = rand(100000, 999999);

        // Atualiza o código no banco de dados
        $sql_update = "UPDATE fisioterapeutas SET codigo_verificacao = '$codigo_verificacao' WHERE email = '$email'";
        mysqli_query($conexao, $sql_update);

        // Configuração do PHPMailer para enviar o e-mail
        $mail = new PHPMailer(true);
        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'fisioavalia4@gmail.com';  // Seu e-mail
            $mail->Password   = 'g j b b m g p j w w g r m m f k';  // Use a senha de aplicativo aqui
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remetente e destinatário
            $mail->setFrom('fisioavalia4@gmail.com', 'AvaliaFisio'); // Seu e-mail
            $mail->addAddress($email);  // Destinatário

            // Conteúdo do e-mail
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = 'Código de Verificação';
            $mail->Body    = "Seu código de verificação é: <b>$codigo_verificacao</b>";

            // Envia o e-mail
            $mail->send();
            header("Location: ../../components/login/codigo.php?email=" . urlencode($email));
            exit(); // Importante para não continuar executando o script
        } catch (Exception $e) {
            echo "Falha ao enviar o e-mail. Erro: {$mail->ErrorInfo}";
        }
    } else {
        echo "E-mail não encontrado.";
    }

    mysqli_close($conexao);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Esqueceu sua senha - AvaliaFisio</title>
    <link rel="stylesheet" href="../../styles/recuperacao.css" />
</head>
<body>
    <div class="container">
        <div class="login-box">
            <a href="../../components/login/login.php" class="back-arrow">&larr;</a>
            <img src="../../assets/logo.png" alt="AvaliaFisio Logo" class="logo" />
            <h2>Esqueceu sua senha</h2>
            <p>Um código será enviado no seu email</p>
            <form method="POST" action="">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="usuario@gmail.com" required />
                </div>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
</body>
</html>
