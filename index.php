<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fazer avaliação</title>
    <link rel="stylesheet" href="styles/index.css"/>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />  
  </head>
  <body>
  
    <div class="sidebar">
      <div class="logo">
        <img src="assets/logo.png" alt="Logo AvaliaFisio" class="logo-img" />
      </div>
     
      <nav>
        <a
          href="components/avaliacao.php"
          class="nav-link active"
          target="contentFrame"
          data-section="avaliacao" 
          class="nav-link"
           onclick="showSection('avaliacao')"
        >
          <i class="fas fa-plus" id="icone"></i> Fazer avaliação
        </a>

        <a
          href="components/cadastrar.php"
          class="nav-link"
          target="contentFrame"
          data-section="cadastrar" 
          class="nav-link"
           onclick="showSection('cadastrar')"
        >
          <i class="fas fa-user" id="icone"></i> Cadastrar Paciente
        </a>

        <a
          href="components/conduta.php"
          class="nav-link"
          target="contentFrame"
          data-section="conduta" 
          class="nav-link"
           onclick="showSection('conduta')"
        >
          <i class="fas fa-clipboard" id="icone"></i> Conduta
        </a>
        
        <a
          href="components/evolucao.php"
          class="nav-link"
          target="contentFrame"
          data-section="evolucao" 
          class="nav-link"
           onclick="showSection('evolucao')"
        >
          <i class="fas fa-arrow-up" id="icone"></i> Evolucao
        </a>
        
        <a
          href="components/paciente.php"
          class="nav-link"
          target="contentFrame"
          data-section="paciente" 
          class="nav-link"
           onclick="showSection('paciente')"
        >
          <i class="fas fa-users" id="icone"></i> Pacientes
        </a>
        
      </nav>
      <a href="components/login.php" class="logout">
        <i class="fas fa-sign-out-alt"></i> Sair
      </a>
    </div>

    <div class="main-content-patrimonio">
      <iframe
      class="iframe"
        name="contentFrame"
        src="components/avaliacao.php"
        frameborder="0"
      ></iframe>
    </div>
  

    <script src="scripts/script.js"></script>
    <script src="scripts/iframeResizer.min.js"></script>
    <script src="scripts/main.js"></script>

  </body>
</html>