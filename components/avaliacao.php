<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação</title>
    <link rel="stylesheet" href="../styles/avaliacao.css">
</head>
<body>
    
<div class="main-content-avaliacao">
   
    <div id="section-avaliacao" class="content-section">

        <h2>Fazer avaliação</h2>
        <p>Faça uma avaliação sobre o paciente</p>
        <div class="divisor"></div>
        <div class="form-content">

            <form>

                <div class="form-group">
                    <label for="codigo">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome do paciente">
                </div>

                <div class="form-group">
                    <label for="fabricante">Idade</label>
                    <input type="text" id="idade" name="idade" placeholder="Idade do paciente">
                </div>

                <div class="form-group">
                    <label for="cor">Patologia</label>
                    <input type="text" id="patologia" name="patologia" placeholder="Patologia do paciente">
                </div>          
               
                <div class="form-group">
                    <label for="detalhes">Detalhes</label>
                    <textarea id="detalhes" name="detalhes" placeholder="Detalhes sobre o caso"></textarea>
                </div>

        </div>
        
</div>

    <button type="submit" class="btn">Avaliar</button>

    

</body>
</html>