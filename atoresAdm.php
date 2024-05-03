<!DOCTYPE html>
<html>
  <head>
      <?php
          include_once "header.html";?>

      <title>Administração</title>
      <link rel="shortcut icon" href="imagens/logotipo/logotipo1.png">
  </head>
  <body class="adm">

      <!-- Inicio DO MENU  SUPERIOR---------------->
      <?php include_once "menuSuperior.html";?>

      <!-- FIM DO MENU  SUPERIOR---------------->
      
    <main class="container">
        <h1  class="text-center">Administração</h1>
        <div class="row">

            <!-- INICIO DO MENU LATERAL---------------->
            <div class="col-md-3 col-sm-3">
            <?php include_once "menuAdm.html";?>
            </div>

            <!-- FIM DO MENU LATERAL---------------->
        </div>
</main>

</body>     
</html>