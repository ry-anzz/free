<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - AvaliaFisio</title>
    <link rel="stylesheet" href="../../styles/login.css" />
  </head>
  <body>
    <div class="container">
      <div class="login-box">
        <img src="../../assets/logo.png" alt="AvaliaFisio Logo" class="logo" />
        <h2>Bem-Vindo ao AvaliaFisio</h2>
        <p>Entre na sua conta</p>
        <form>
          <div class="input-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="usuario@gmail.com" />
          </div>
          <div class="input-group">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="********"
            />
          </div>
  
          <a href="../../components/login/recuperacao.php" class="forgot-password"
            >Esqueceu a senha?</a
          >
          <button type="submit">Entrar</button>
        </form>
      </div>
    </div>
  </body>
</html>