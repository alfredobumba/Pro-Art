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
          ?>

      <title>HOME</title>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    function validaCampos(){
        if (document.fmCidades.txtCidade.value == "") {
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, preencha o nome da Cidade.',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false,
                background: '#fff url(/images/trees.png)',
                backdrop: `
                    rgba(0,0,123,0.4)
                    url("/images/nyan-cat.gif")
                    left top
                    no-repeat
                `
            });
            document.fmCategorias.txtCategoria.focus();
            return false;
        }
    }
</script>


      <link rel="shortcut icon" href="imagens/logotipo/logotipo1.png">
  </head>
  <body class="administracao">

      <!-- Inicio DO MENU  SUPERIOR---------------->
      <?php include_once "menuSuperior.html";?>

      <!-- FIM DO MENU  SUPERIOR---------------->

      
     <!-- INÍCIO DA VITRINE -->
<main class="container mt-5">
    <h1 class="text-center">Cidades-Administração</h1><br>
    <div class="row text-center">
        <div class="col-md-3 col-sm-3">
            <?php include_once "menuAdm.html" ?>
        </div>
        <div class="col-md-9 col-sm-9">
            <?php
            if (isset($_GET['eliminarCidade'])) {
                $idCidade = $_GET['eliminarCidade'];
                $sql = "CALL sp_deleta_cidade('$idCidade', @saida, @rotulo);";
                if ($res = mysqli_query($con, $sql)) {
                    $reg = mysqli_fetch_assoc($res);
                    $saida = isset($reg['saida']) ? $reg['saida'] : "Cidade excluída com sucesso";
                    $rotulo = isset($reg['saida_rotulo']) ? $reg['saida_rotulo'] : "";

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
                    }
                    ?>

                    <div class="alert <?php echo $alert; ?>" role="alert">
                        <h3><?php echo $rotulo; ?></h3>
                        <h3><?php echo $saida; ?></h3>
                        <a href='cidadesAdm.php' class="alert-link" target='_self'>Voltar</a>
                    </div>

                    <?php
                    mysqli_free_result($res); // Libera o resultado da memória

                    // Adicione esta linha para limpar os resultados
                    if (mysqli_more_results($con)) {
                        while (mysqli_next_result($con)) {
                            // Seu código aqui
                        }
                    }
                } else {
                    echo "ERRO ao executar a query ";
                }
            } else if (isset($_GET['editaCidade'])) {
                $_SESSION['idCidade'] = $_GET['editaCidade'];
                $nomeCidade = $_GET['nomeCidade'];
                ?>

                <h2 class="text-center">Alteração de Cidade</h2>
                <form name="fmCidades" method="get" action="editaCidadeAdm.php" onsubmit="return validaCampos()">
                    <label for="txtCidade">Nome da Cidade:</label><br>
                    <input type="text" id="txtCidade" name="txtCidade" value="<?php echo $nomeCidade; ?>"
                           class="form-control" maxlength="50"><br>
                    <button type="submit" class="btn btn-primary w-100" name="btnSubmitCidade">Alterar Cidade
                    </button><br>
                </form>
                <hr/>

                <?php
            } else if (isset($_GET['btnSubmitCidade'])) {
                $nomeCidade = $_GET['txtCidade'];
                $idCidade = $_SESSION['idCidade'];
                unset($_SESSION['idCidade']);
                $sql = "CALL sp_altera_cidade('$idCidade', '$nomeCidade', @saida, @rotulo);";

                if ($res = mysqli_query($con, $sql)) {
                    $reg = mysqli_fetch_assoc($res);
                    $saida = $reg['saida'];
                    $rotulo = $reg['saida_rotulo'];

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
                    }
                    ?>

                    <div class="alert <?php echo $alert; ?>" role="alert">
                        <h3><?php echo $rotulo; ?></h3>
                        <h3><?php echo $saida; ?></h3>
                        <a href='cidadesAdm.php' class="alert-link" target='_self'>Voltar</a>
                    </div>

                    <?php
                    mysqli_free_result($res); // Libera o resultado da memória

                    // Adicione esta linha para limpar os resultados
                    if (mysqli_more_results($con)) {
                        while (mysqli_next_result($con)) {
                            // Seu código aqui
                        }
                    }
                } else {
                    echo "ERRO ao executar a query ";
                }
            }
            ?>

</div>
</div>
                <?php
   mysqli_next_result($con); // Prepara o próximo conjunto de resultados
   $sql = "SELECT * FROM cidades;";
   if ($res = mysqli_query($con, $sql)) {
       $nomeCidade = array();
       while($reg=mysqli_fetch_assoc($res)){
           if (isset($reg['cidade'])) {
               $nomeCidade[] = $reg['cidade'];
           }
       }
       mysqli_free_result($res); // Libera o resultado da memória
       if (isset($nomeCidade[0])) {
           echo $nomeCidade[0];
       }
   } else {
       echo "Erro ao executar a consulta: " . mysqli_error($con);
   }
?>


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
