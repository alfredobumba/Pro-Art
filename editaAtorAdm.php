<!DOCTYPE html>
<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION['acesso']) && $_SESSION['acesso'] == true) {
?>
<html>
  <head>
  <?php
          include_once "header.html";
          include_once "conexao.php";
          include_once "funcoes.php"; 
  ?>
  </head>
  <body class="adm">
      <!-- Inicio DO MENU  SUPERIOR---------------->
      <?php include_once "menuSuperior.html"; ?>
      <!-- FIM DO MENU  SUPERIOR------------------>

      <main class="container mt-5">
        <h1 class="text-center">Atores - Administração</h1><br>
        <div class="row text-center">
          <div class="col-md-3 col-sm-3">
            <?php include_once "menuAdm.html"; ?>
          </div>
          <div class="col-md-9 col-sm-9">
            <?php
                if (isset($_GET['excluirAtor'])) {
                    $idAtor = $_GET['excluirAtor'];
                    // Excluir imagens do ator
                    excluiTodasImagens($idAtor, "atores");

                    $sql = "CALL sp_deleta_atores($idAtor, @saida, @saida_rotulo)";
                    if (executaQuery($sql)) {
                        echo "<script>alert('Ator excluído com sucesso!'); window.location.href='atoresAdm.php';</script>";
                    } else {
                        echo "<script>alert('Erro ao excluir ator!');</script>";
                    }
                } elseif (isset($_GET['editaAtor'])) {


                    /* CRIAÇÃO DE ARRAYS DE SESSÃO */
                    $_SESSION['caminho_imagem'] = array();
                    $_SESSION['id_imagem'] = array();

                    /* CARREGAR AS INFORMAÇÕES DOS ATORES */
                    $id_Ator = $_GET['editaAtor'];
                    $_SESSION['id_ator'] = $id_Ator;

                    $sql = "SELECT * FROM vw_retorna_atores WHERE id_ator = $id_Ator";
                    if ($res = mysqli_query($con, $sql)) {
                        $reg = mysqli_fetch_assoc($res);
                        $nomeAtor = $reg['nome_ator'];
                        $cidadeAtor = $reg['cidade_ator'];
                        $biografiaAtor = $reg['biografia_ator'];
                    } else {
                        echo "Algo deu errado ao executar a query!";
                    }

                    $imagensAtor = array();
                    $imagensId = array();
                    $i = 0;

                    $sql = "SELECT * FROM imagens WHERE atores_id = $id_Ator";

                    if ($res = mysqli_query($con, $sql)) {
                        while ($reg = mysqli_fetch_assoc($res)) {
                            $imagensAtor[$i] = $reg['caminho'];
                            $imagensId[$i] = $reg['id'];

                            $_SESSION['caminho_imagem'][$i] = $reg['caminho'];
                            $_SESSION['id_imagem'][$i] =  $reg['id'];

        
                            $i++;
                        }
                    } elseif(isset($_GET['btnSubmitAtores'])) {

                        $_SESSION['caminho_imagem'][$i] = $reg['caminho'];
                        $_SESSION['id_imagem'][$i] =  $reg['id'];
                        $id_Ator = $_SESSION['id_ator'];
                        unset($_SESSION['id_ator']);

                        $nomeImagem = array();
                        $idImagem = array();

                        for ($i=0; $i <3 ; $i++) {

                            $nomeImagem[$i] = $_FILES["fileImagemAtor" $i ]['name'];
                            $idImagem[$i] = "";

                            if ( $nomeImagem[$i] <> "" && isset($_FILES["fileImagemAtor" $i ]['name'])){

                                $nomeImagem[$i] = enviaImagem($_FILES["fileImagemAtor" $i ]['name'], "atores", $_FILES["fileImagemAtor" $i ]['tmp_name']);

                            }elseif ( isset($_SESSION['caminho_imagem'][$i])) {

                                $nomeImagem[$i] = $_SESSION['caminho_imagem'][$i];

                            }
                            if ( isset($_SESSION['id_imagem'][$i])) {

                                $idImagem[$i] = $_SESSION['id_imagem'][$i];

                            if ( isset($_SESSION['caminho_imagem'][$i] &&isset($nomeImagem[$i]))) {
                                if ($_SESSION['caminho_imagem'][$i] <> $nomeImagem[$i]) {
                                    excluiUmaImagem($idImagem[$i], "atores");
                                }

                            }

                            if ( isset($_POST['chExcluir' $i])) {
                                excluiUmaImagem($idImagem[$i], "atores");
                                $idImagem[$i] = "";
                            } 
                        } /* FIM DO FOR */ 

                        if (isset($_SESSION['caminho_imagem']) || $_SESSION['id_imagem']) {
                            unset($_SESSION['caminho_imagem']);
                            unset($_SESSION['id_imagem']);
                        }

                        $nomeAtor = $_POST['txtNome'];
                        $cidadeAtor = $_POST['selCidade'];
                        $biografiaAtor = $_POST['txtBiografia'];

                        /* CALL sp_edita_ator(92, 'Kelvin',1,'um ator', 'imagem_2.jpg', '','','','','',@saida,@saida_rotulo); */
                        $sql = "CALL sp_edita_ator('$idAtor', ' $nomeAtor', ' $cidadeAtor', ' $biografiaAtor', $nomeImagem[0], '$idImagem[0]',)"

                    } else {
                        echo "Algo deu errado ao executar a query!";
                    }
            ?>
            <!---EXIBIR AS INFORMAÇÕES DOS ATORES NO FORMULARIO---->
            <form name="fmAtores" method="post" action="editaAtorAdm.php" enctype="multipart/form-data" onsubmit="return validaCampos()">
              <label>Nome:</label> <br>
              <input type="text" name="txtNome" class="form-control" maxlength="70" value="<?php echo $nomeAtor; ?>"><br>

              <label>Gênero:</label> <br>
              <select name="selGenero" class="form-control">
                <option value="M">Masculino</option>
                <option value="F">Feminino</option>
              </select><br>

              <label>Altura:</label>
              <input type="number" step="0.01" name="altura" class="form-control" required><br>

              <label>Línguas:</label>
              <input type="text" name="linguas" class="form-control"><br>

              <label>Idade:</label>
              <input type="number" name="idade" class="form-control" required><br>

              <label>Cache do Ator:</label>
              <input type="number" name="cache" step="0.01" class="form-control" required><br>

              <label>Cidade:</label> <br>
              <select name="selCidade" class="form-control">
                <option value="0">- - - - - - - - - - - - - - - - - -</option>
                <?php
                    $sql = "SELECT * FROM vw_retorna_cidade";
                    if ($res = mysqli_query($con, $sql)) {
                        while ($reg = mysqli_fetch_assoc($res)) {
                            $nomeCidade = $reg['nome_cidade'];
                            $idCidade = $reg['id_cidade'];
                ?>
                <option value="<?php echo $idCidade; ?>" <?php if ($idCidade == $cidadeAtor) { echo "selected"; } ?>><?php echo $nomeCidade; ?></option>
                <?php
                        }
                    }
                ?>
              </select><br>

              <label>Biografia do(a) ator/atriz:</label> <br>
              <textarea name="txtBiografia" class="form-control" rows="5" cols="50" maxlength="500" placeholder="Pequena história/descrição do ator/atriz..."><?php echo $biografiaAtor; ?></textarea><br>

              <br>
              <label>Fotos do(a) ator/atriz:</label> <br>
              <div class="row text-center">
                <div class="col-md-3"><strong>Imagem do(a) ator/atriz:</strong></div>
                <div class="col-md-6"><strong>Carregar uma nova imagem:</strong></div>
                <div class="col-md-3"><strong>Excluir imagem atual?</strong></div>
                <?php
                    for ($i = 0; $i < 3; $i++) {
                ?>
                <div class="col-md-3">
                  <?php
                    if (isset($imagensAtor[$i])) {
                  ?>
                  <img src="imagens/atores/<?php echo $imagensAtor[$i]; ?>" title="<?php echo $imagensAtor[$i]; ?>" style="max-width: 100px; padding: 5px;">
                  <?php
                    } else {
                  ?>
                  <img src="imagens/atores/sem_imagem.jpg" title="sem_imagem.jpg" style="max-width: 100px; padding: 5px;">
                  <?php
                    }
                  ?>
                </div>
                <div class="col-md-6">
                  <input type="file" name="fileImagemAtor<?php echo $i + 1; ?>" class="btn btn-success w-100" accept="image/*"><br><br>
                </div>
                <div class="col-md-3">
                  <input type="checkbox" name="excluirImagem<?php echo $i + 1; ?>" value="1"><br><br>
                </div>
                <?php
                    }
                ?>
              </div>
              <button type="submit" name="btnSubmitAtores" class="btn btn-primary w-100">Salvar as alterações</button><br><br>
              <div class="row">
                  <div class="col-md-6">
                        <a href="atoresAdm.php" class="btn btn-success w-100">Voltar</a><br>
                    </div>
                    <div class="col-md-6">
                        <a href="atoresAdm.php?excluirAtor=<?php echo $id_Ator; ?>" class="btn btn-danger w-100"
                         onclick="return confirm('Tem certeza que deseja eliminar este(a) ator/atriz?')">Eliminar</a><br>
                    </div>
                </div>
             <br><br> 
            </form>
            <?php

                }
            ?>
          </div>
        </div>
      </main>

      <!-- FECHANDO A CONEXÃO COM O BANCO DE DADOS -->
      <?php if (isset($con)) { mysqli_close($con); } ?>
  </body>
  <?php
    } else {
  ?>
    <meta http-equiv="refresh" content="0;url=login.php">
  <?php
    }
  ?>
</html>
