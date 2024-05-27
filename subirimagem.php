<!DOCTYPE html>
<?php
    if (!isset($_SESSION)) {
        session_start();

    }
    if ($_SESSION['acesso'] == true) {
?>
<html>
  <head>
      <?php
          include_once "header.html";?>

      <title>Subir Imagem</title>
      <?php
        //FUNÇÃO PARA SUBIR IMAGEM
        function enviaImagem($imagem, $caminho, $imagemTemp){
            $extensao = pathinfo($imagem, PATHINFO_EXTENSION);
            $extensao = strtolower($extensao);

            if(strstr('.jpg;.jpeg;.png', $extensao)){
                $imagem = $caminho.mt_rand().".".$extensao;

                $diretorio = "imagens/".$caminho."/";

                move_uploaded_file($imagemTemp, $diretorio.$imagem);
            }else{ ?>
                <div class="alert alert-danger" role="alert">
                    A imagem deve ser no formato *.jpeg, *jpg e *.png!
                </div>
            <?php
            }
            return $imagem;

        }
      ?>
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
            <div class="col-md-9 col-sm-9">
                <?php
                    if (isset($_POST['btnSubmitImagem'])) {

                        $nomeImagem = $_FILES['fileImagem']['name'];

                        if($nomeImagem != "" && isset($_FILES['fileImagem']['name']) ) {
                            $nomeImagem = enviaImagem($_FILES['fileImagem']['name'], "teste", $_FILES['fileImagem']['tmp_name'] );

                        }else{
                            $nomeImagem = "";
                        } ?>
                            <h3 class="text-center">Tudo certo!</h3><br>
                            <h3 class="text-center">Imagem cadastrada com sucesso.</h3><br>
                            <a href="subirImagem.php">Voltar</a><br><br>

                        <?php
                    }else{
                
                ?>
            
                <form name="fmImagem" method="post" action="subirImagem.php" enctype="multipart/form-data">
                    <label>Insira a Imagem</label>
                    <input type="file" name="fileImagem" class="btn btn-success w-100" accept="/*"><br><br>
                    <button type="submit" class="btn btn-primary form-control" name="btnSubmitImagem">Cadastrar imagem</button>
                </form>
                <?php
                }
                ?>
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
