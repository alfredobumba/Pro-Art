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
          include_once "header.html";
          include_once "conexao.php";
          include_once "funcoes.php"; 

          ?>
      <title>Cadastro de Agências - Administração</title>
      <script type="text/javascript">
        function validaCampos() {
            if (document.fmAgencias.txtNome.value == "") {
                alert("O campo nome não pode estar vazio!");
                document.fmAgencias.txtNome.focus();
                return false;
            }
            if (document.fmAgencias.txtBiografia.value == "") {
                alert("O campo Biografia não pode estar vazio!");
                document.fmAAgencias.txtBiografia.focus();
                return false;
            }
            if (document.fmAgencias.selCiadade.value == 0) {
                alert("Por favor escolhe uma ciadade!");
                document.fmAgencias.selCiadade.focus();
                return false;
            }
            
        }
      </script>

  </head>
  <body class="adm">

      <!-- Inicio DO MENU  SUPERIOR---------------->
      <?php include_once "menuSuperior.html";?>

      <!-- FIM DO MENU  SUPERIOR---------------->

      <!-- FIM DO RODAPÉ------------------------------>

        <main class="container mt-5">
                <h1 class="text-center">Agências - Administração</h1><br>
    <div class="row text-center">
                <div class="col-md-3 col-sm-3">
                <?php include_once "menuAdm.html" ?>
                </div>
        <div class="col-md-9 col-sm-9">
             <?php
                if(isset($_POST['btnSubmitAgencias'])) {
                    $nomeImagem1 = $_FILES['fileImagemAgencia1']['name'];
                    $nomeImagem2 = $_FILES['fileImagemAgencia2']['name'];
                    $nomeImagem3 = $_FILES['fileImagemAgencia3']['name'];

                    if ($nomeImagem1 <> "" && isset( $_FILES['fileImagemAgencia1']['name'])) {
                        $nomeImagem1 = enviaImagem($_FILES['fileImagemAgencia1']['name'], "agencias", $_FILES['fileImagemAgencia1']['tmp_name']);

                    }else{
                        $nomeImagem1 = "";
                    }

                    if ($nomeImagem2 <> "" && isset( $_FILES['fileImagemAgencia2']['name'])) {
                        $nomeImagem2 = enviaImagem($_FILES['fileImagemAgencia2']['name'], "agencias", $_FILES['fileImagemAgencia2']['tmp_name']);

                    }else{
                        $nomeImagem2 = "";
                    }

                    if ($nomeImagem3 <> "" && isset( $_FILES['fileImagemAgencia3']['name'])) {
                        $nomeImagem3 = enviaImagem($_FILES['fileImagemAgencia3']['name'], "agencias", $_FILES['fileImagemAgencia3']['tmp_name']);

                    }else{
                        $nomeImagem3 = "";
                    }


                     // Recupere os dados do formulário
                    $nome_agencia = $_POST['txtNome'];
                    $cidade_agencia = $_POST['selCidade'];
                    $biografia_agencia = $_POST['txtBiografia'];
                    $marker_agencia = isset($_POST['marker']) ? $_POST['marker'] : '';

                    // Verifique se o valor do marcador enviado está presente na tabela de marcadores
                    $sql_check_marker = "SELECT COUNT(*) AS count FROM markers WHERE id = '$marker_agencia'";
                    $result_check_marker = mysqli_query($con, $sql_check_marker);
                    $row_check_marker = mysqli_fetch_assoc($result_check_marker);

                    if ($row_check_marker['count'] > 0) {
                        // O marcador existe na tabela de marcadores; agora você pode prosseguir com a inserção na tabela de agências
                        $sql = "CALL sp_cadastra_agencias('$nome_agencia', '$cidade_agencia', '$biografia_agencia', '$marker_agencia', '$nomeImagem1', '$nomeImagem2', '$nomeImagem3', @saida, @saida_rotulo)";
                        $result = mysqli_query($con, $sql);
                        executaQuery($sql, "agenciasAdm.php");
                    } else {
                        // O marcador não existe na tabela de marcadores; exiba uma mensagem de erro ou tome outra ação adequada
                        echo "Erro: O marcador especificado não existe na tabela de marcadores.";
                    }

                } else {
            ?>
            <ul class="nav nav-tabs" role="tablist">
                 <li class=" nav-item" role="presentation">
                    <a href="#tabExibicao" class="nav-link" id="linkExibicao" data-toggle="tab" role="tab" aria-controls="
                    tabExibicao">Agencias cadastradas</a>
                </li>
                <li class=" nav-item" role="presentation">
                    <a href="#tabFormulario"  class="nav-link active" id="linkFormulario" data-toggle="tab" role="tab" aria-controls="
                    tabFormulario">Cadastro</a>
                </li>
            </ul>

            <div class="tab-content" id="meusConteudos">
                <div class="tab-pane fade" id="tabFormulario" role="tabpanel" aria-labelledby="linkFormulario">
                    
            <br>
            <h3>Cadastrar nova Agencia:</h3>
            <form name="fmAgencias" method="post" action="agenciasAdm.php" enctype="multipart/form-data" onsubmit="return validaCampos()">

                <label>Nome:</label> <br>
                <input type="text" name="txtNome" class="form-control" maxlength="70"><br>

               
                <label>Cidade:</label> <br>
                <select name="selCidade" class="form-control">
                    <option value="0">- - - - - - - - - - - - - - - - - - </option>
                    <?php
                        $sql ="SELECT * FROM vw_retorna_cidade";
                        if ($res = mysqli_query($con,$sql)) {
                            $nomeCidade = array();
                            $idCidade = array();
                            $i =0;
                            while ($reg = mysqli_fetch_assoc($res)){
                                $nomeCidade[$i] = $reg['nome_cidade'];
                                $idCidade[$i] = $reg['id_cidade'];
                                ?>
                                    <option value="<?php echo $idCidade[$i];?>"><?php echo $nomeCidade[$i];?></option>
                                <?php
                                $i++;

                            }
                        }
                    ?>

                </select>

                    <br>
                    
                <label>Biografia do Agencia:</label> <br>
                <textarea name="txtBiografia" class="form-control" rows="5" cols="50" maxlength="500" placeholder="Pequena história/descrição do ator/atriz..."></textarea><br>

                <br>
                <label>Fotos da Agencia:</label> <br>
                <input type="file" name="fileImagemAtor1" class="btn btn-success w-100" accept="image/*"><br><br>
                <input type="file" name="fileImagemAtor2" class="btn btn-success w-100" accept="image/*"><br><br>
                <input type="file" name="fileImagemAtor3" class="btn btn-success w-100" accept="image/*"><br><br>

                <button type="submit" name="btnSubmitAtores" class="btn btn-primary w-100">Cadastrar</button><br>
                <br>
            </form>

        </div>
        <div class="tab-pane fade show active" id="tabExibicao" role="tabpanel" aria-labelledby="linkExibicao">
            <br>
            
            <br>
                    <h2 class="text-center">Agências cadastrados:</h2><br>
                    <div class="row">
                        <?php
                            $sql = "SELECT * FROM vw_retorna_agencias";
                            if ($res=mysqli_query($con, $sql)) {

                                $nomeAgencia = array();
                                $idAgencia = array();
                                $imagemAgencia = array();
                                $i = 0;

                                while($reg=mysqli_fetch_assoc($res)) {
                                    $nomeAgencia[$i] = $reg['nome_agencia'];
                                    $idAgencia[$i] = $reg['id_agencia'];
                                    $imagemAgencia[$i] = $reg['caminho_imagem'];

                                    if (!isset($imagemAgencia[$i])){
                                        $imagemAgencia[$i] = "sem_imagem.jpg";
                                    }
                                    ?>
                                    <div class="col-md-4 itensCadastrados text-center">
                                        <img src="imagens/agencias/<?php echo  $imagemAgencia[$i]; ?>" class="img-responsive img-thumbnail">
                                        <h4><?php echo  $nomeAgencia[$i]; ?></h4>
                                        <div class="btn-group" role="group" aria-label="Basic sample">
                                            <a href="editaAgenciaAdm.php?editaAgencia=<?php echo $idAgencia[$i]; ?>&nomeAgencia=<?php echo $nomeAgencia[$i]; ?>" class="btn btn-primary">Editar</a>
                                            <a href="agenciasAdm.php?excluirAgencia=<?php echo $idAgencia[$i]; ?>" class="btn btn-secondary" onclick="return confirm('Tem certeza que deseja eliminar esta Agencia')">Eliminar</a>

                                        </div>
                                    </div>

                                    <?php
                                    $i++;
                                }
                            } else{
                                echo "Erro ao executar a query!";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</main>    
<!-- FECHANDO A CONEXÃO COM O BANCO DE DADOS -->
<?php if(isset($con)) { mysqli_close($con); } ?>
</body> 
<?php
    }else{
        ?>
        <meta http-equiv="refresh" content=0;url="login.php">
        <?php
    }
?>       
</html>