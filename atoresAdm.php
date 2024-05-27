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
      <title>Cadastro de Atores - Administração</title>
      <script type="text/javascript">
        function validaCampos() {
            if (document.fmAtores.txtNome.value == "") {
                alert("O campo nome não pode estar vazio!");
                document.fmAtores.txtNome.focus();
                return false;
            }
            if (document.fmAtores.txtBiografia.value == "") {
                alert("O campo Biografia não pode estar vazio!");
                document.fmAtores.txtBiografia.focus();
                return false;
            }
            if (document.fmAtores.selCiadade.value == 0) {
                alert("Por favor escolhe uma ciadade!");
                document.fmAtores.selCiadade.focus();
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
                <h1 class="text-center">Atores - Administração</h1><br>
    <div class="row text-center">
                <div class="col-md-3 col-sm-3">
                <?php include_once "menuAdm.html" ?>
                </div>
        <div class="col-md-9 col-sm-9">
             <?php
                if(isset($_POST['btnSubmitAtores'])) {
                    $nomeImagem1 = $_FILES['fileImagemAtor1']['name'];
                    $nomeImagem2 = $_FILES['fileImagemAtor2']['name'];
                    $nomeImagem3 = $_FILES['fileImagemAtor3']['name'];

                    if ($nomeImagem1 <> "" && isset( $_FILES['fileImagemAtor1']['name'])) {
                        $nomeImagem1 = enviaImagem($_FILES['fileImagemAtor1']['name'], "atores", $_FILES['fileImagemAtor1']['tmp_name']);

                    }else{
                        $nomeImagem1 = "";
                    }

                    if ($nomeImagem2 <> "" && isset( $_FILES['fileImagemAtor2']['name'])) {
                        $nomeImagem2 = enviaImagem($_FILES['fileImagemAtor2']['name'], "atores", $_FILES['fileImagemAtor2']['tmp_name']);

                    }else{
                        $nomeImagem2 = "";
                    }

                    if ($nomeImagem3 <> "" && isset( $_FILES['fileImagemAtor3']['name'])) {
                        $nomeImagem3 = enviaImagem($_FILES['fileImagemAtor3']['name'], "atores", $_FILES['fileImagemAtor3']['tmp_name']);

                    }else{
                        $nomeImagem3 = "";
                    }


                    $nome = $_POST['txtNome'];
                    $genero = $_POST['selGenero'];
                    $altura = $_POST['altura'];
                    $linguas = $_POST['linguas'];
                    $idade = $_POST['idade'];
                    $cache = $_POST['cache'];
                    $cidade = $_POST['selCidade'];
                    $bio = $_POST['txtBiografia'];
                    
                    $sql = "CALL sp_cadastra_atores('$nome','$cidade', '$bio', '$idade', '$altura', '$cache', '$linguas', '$genero', '$nomeImagem1', '$nomeImagem2', '$nomeImagem3',@saida, @saida_rotulo)";

                    if ($res = mysqli_query($con,$sql)) {
                        $reg = mysqli_fetch_assoc($res);
                        
                        $saida = isset($reg['saida']) ? $reg['saida'] : '';
                        $rotulo = isset($reg['saida_rotulo']) ? $reg['saida_rotulo'] : '';
                    
                        // Definindo a classe de alerta com base no rótulo
                        switch ($rotulo) {
                            case 'Tudo certo!':
                                $alert = 'alert-success';
                                break;
                            case 'OPS!':
                                $alert = 'alert-warning';
                                break;
                            case 'ERRO!':
                                $alert = 'alert-danger';
                                break;
                            default:
                                $alert = 'alert-info';
                                break;
                        }
                    
                        // Exibindo a mensagem de alerta
                        echo "<div class='alert $alert' role='alert'>";
                        echo "<h3>$rotulo</h3>";
                        echo "<p>$saida</p>";
                        echo "<a href='atoresAdm.php' class='alert-link' target='_self'>Voltar</a>";
                        echo "</div>";
                    
                        // Liberando o resultado da memória
                        mysqli_free_result($res);
                    
                        // Limpando quaisquer resultados pendentes
                        while (mysqli_more_results($con)) {
                            mysqli_next_result($con);
                        }
                    } else {
                        echo "ERRO ao executar a query.";
                     }
                }else{

            
            ?>
            <ul class="nav nav-tabs" role="tablist">
                <li class=" nav-item" role="presentation">
                    <a href="#tabFormulario"  class="nav-link active" id="linkFormulario" data-toggle="tab" role="tab" aria-controls="
                    tabFormulario">Cadastro</a>
                </li>
                <li class=" nav-item" role="presentation">
                    <a href="#tabExibicao" class="nav-link" id="linkExibicao" data-toggle="tab" role="tab" aria-controls="
                    tabExibicao">Atores e atrizes cadastrados</a>
                </li>
            </ul>

            <div class="tab-content" id="meusConteudos">
                <div class="tab-pane fade show active" id="tabFormulario" role="tabpanel" aria-labelledby="linkFormulario">
                    
            <br>
            <h3>Cadastrar novo(a) ator/atriz:</h3>
            <form name="fmAtores" method="post" action="atoresAdm.php" enctype="multipart/form-data" onsubmit="return validaCampos()">

                <label>Nome:</label> <br>
                <input type="text" name="txtNome" class="form-control" maxlength="70"><br>

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
                    
                <label>Biografia do ator:</label> <br>
                <textarea name="txtBiografia" class="form-control" rows="5" cols="50" maxlength="500" placeholder="Pequena história/descrição do ator/atriz..."></textarea><br>

                <br>
                <label>Fotos do ator/atriz:</label> <br>
                <input type="file" name="fileImagemAtor1" class="btn btn-success w-100" accept="image/*"><br><br>
                <input type="file" name="fileImagemAtor2" class="btn btn-success w-100" accept="image/*"><br><br>
                <input type="file" name="fileImagemAtor3" class="btn btn-success w-100" accept="image/*"><br><br>

                <button type="submit" name="btnSubmitAtores" class="btn btn-primary w-100">Cadastrar</button><br>
                <br>





                    </div>














                <div class="tab-pane fade" id="tabExibicao" role="tabpanel" aria-labelledby="linkExibicao">
            <br>
            <h3>Atores e atrizes cadastrados:</h3>
            <?php
                $sql = "SELECT a.nome, a.genero, a.idade, a.altura, a.cache, a.linguas, a.biografia, c.cidade
                        FROM atores a
                        JOIN cidades c ON a.cidades_id = c.id";

                if ($res = mysqli_query($con, $sql)) {
                    while ($ator = mysqli_fetch_assoc($res)) {
                        echo "<div class='card mb-3'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>{$ator['nome']}</h5>";
                        echo "<p class='card-text'>Gênero: {$ator['genero']}</p>";
                        echo "<p class='card-text'>Idade: {$ator['idade']}</p>";
                        echo "<p class='card-text'>Altura: {$ator['altura']}</p>";
                        echo "<p class='card-text'>Cachê: {$ator['cache']}</p>";
                        echo "<p class='card-text'>Línguas: {$ator['linguas']}</p>";
                        echo "<p class='card-text'>Biografia: {$ator['biografia']}</p>";
                        echo "<p class='card-text'>Cidade: {$ator['cidade']}</p>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
            ?>
                    Exibição dos cadastros
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