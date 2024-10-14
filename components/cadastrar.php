<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="../styles/avaliacao.css">
</head>
<body>
    
<div class="main-content-avaliacao">
   
    <div id="section-avaliacao" class="content-section">

        <h2>Cadastrar paciente</h2>
        <p>Faça o cadastro do paciente</p>
        <div class="divisor"></div>
        <div class="form-content">

            <form>

                <div class="form-group">
                    <label for="codigo">Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome completo do paciente">
                </div>

                <div class="form-group">
                    <label for="cor">Idade</label>
                    <input type="text" id="idade" name="idade" placeholder="Idade do paciente">
                </div>                         

                <div class="form-group">
                    <label for="fabricante">CPF</label>
                    <input type="text" id="cpf" name="cpf" placeholder="CPF do paciente">
                </div>

                <div class="form-group">
                    <label for="fabricante">Patologia</label>
                    <input type="text" id="patologia" name="patologia" placeholder="Patologia do paciente">
                </div>

                <div class="form-group">
                    <label for="fabricante">Telefone</label>
                    <input type="text" id="telefone" name="telefone" placeholder="Telefone do paciente">
                </div>
            
        </div>
        
</div>

    <button type="submit" class="btn">Avaliar</button>

    

</body>
</html>