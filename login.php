<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - GESPAT</title>
    <link rel="stylesheet" href="../login.css" />
  </head>
  <body>
    <div class="container">
      <div class="login-box">
        <img src="../logo.png" alt="GESPAT Logo" class="logo" />
        <h2>Bem-Vindo ao FISIO</h2>
        <p>Entre na sua conta</p>
        <form>
          <div class="input-group">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="usuario@gmail.com" />
          </div>
          <div class="input-group">
            <label for="password">Senha</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="********"
            />
          </div>
       
          <button type="submit">Entrar</button>

          
          <a href="/components-login/recuperação.html" class="forgot-password"
            >Esqueceu a senha?</a
          >

          <a href="/components-login/recuperação.html" class="register"
            >Cadastre-se</a
          >



        </form>
      </div>
    </div>
  </body>
</html>