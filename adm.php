<!DOCTYPE html>
<html>
<?php
    if (!isset($_SESSION)) {
        session_start();

    }
    if ($_SESSION['acesso'] == true) {
?>
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
        <div class="row text-center">

        <div class="col-md-3 opcoes">
            <a href="atoresAdm.php">
            <i class="fa fa-user" aria-hidden="true"></i>
                <p>Cadastrar Novo Ator</p>
            </a>
            </div>

                 
        <div class="col-md-3 opcoes">
            <a href="#">
            <i class="fa fa-male" aria-hidden="true"></i>
                <p>Atores Cadastrados</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="#">
            <i class="fa fa-video-camera" aria-hidden="true"></i>
                <p> Cadastrar Nova Agência</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="#">
            <i class="fa fa-video-camera" aria-hidden="true"></i>
                <p>Agências Cadastradas</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="agenciasAdm.php">
            <i class="fa fa-bullhorn" aria-hidden="true"></i>
                <p>Agências</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="map.php">
            <i class="fa fa-film" aria-hidden="true"></i>
                <p>Mapa</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="#">
            <i class="fa fa-film" aria-hidden="true"></i>
                <p>Produtoras Cadastradas</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="#">
            <i class="fa fa-bullhorn" aria-hidden="true"></i>
                <p>Produtoras</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="atoresAdm.php">
            <i class="fas fa-theater-masks"></i>
                <p>Atores</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="categoriasAdm.php">
            <i class="fa fa-list" aria-hidden="true"></i>
                <p>Categorias</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="banner.php">
            <i class="fa fa-th-large" aria-hidden="true"></i>
                <p>Banner Principal</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="estatisticas.php">
            <i class="fa fa-th-large" aria-hidden="true"></i>
                <p>Dashboard</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="cidadesAdm.php">
            <i class="fa fa-city" aria-hidden="true"></i>
                <p>Cidades</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="usuariosAdm.php">
            <i class="fa fa-users" aria-hidden="true"></i>
                <p>Usuarios</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="#">
            <i class="fa fa-gear" aria-hidden="true"></i>
                <p>Minha Conta</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="subirimagem.php">
            <i class="fa fa-gear" aria-hidden="true"></i>
                <p>Subir Imagem</p>
            </a>
            </div>

            <div class="col-md-3 opcoes">
            <a href="logoff.php">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
                <p>Sair</p>
            </a>
            </div>
        </div>
</main>

</body>
<?php
    }else{
        ?>
        <meta http-equiv="refresh" content=0;url="login.php">
        <?php
    }
?>     
</html>