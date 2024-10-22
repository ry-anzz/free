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
        <form>
          <div class="input-group">
            <label for="email">Email</label>
            <input
              type="text"
              id="email"
              name="email"
              placeholder="usuario@gmail.com"
            />
          </div>
          
          <button type="submit">Enviar</button>
        </form>
      </div>
    </div>
  </body>
</html>