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

      <title>Agências</title>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    function validaCampos(){
        if (document.fmAgencia.txtAgencia.value == "") {
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, preencha o nome da Agências.',
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
            document.fmAgencia.txtAgencia.focus();
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
    <h1 class="text-center">Agências-Administração</h1><br>
    <div class="row text-center">
        <div class="col-md-3 col-sm-3">
            <?php include_once "menuAdm.html" ?>
        </div>
        <div class="col-md-9 col-sm-9">
            <?php
            if (isset($_GET['eliminarAgencia'])) {
                $idAgencia = $_GET['eliminarAgencia'];
                $sql = "CALL sp_deleta_agencia('$idAgencia', @saida, @rotulo);";
                if ($res = mysqli_query($con, $sql)) {
                    $reg = mysqli_fetch_assoc($res);
                    $saida = isset($reg['saida']) ? $reg['saida'] : "Agência excluída com sucesso";
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
                        <a href='agenciasAdm.php' class="alert-link" target='_self'>Voltar</a>
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
            } else if (isset($_GET['editaAgencia'])) {
                $_SESSION['idAgencia'] = $_GET['editaAgencia'];
                $cidade = $_GET['agencia'];
                ?>

                <h2 class="text-center">Alteração de Agência</h2>
                <form name="fmAgencias" method="get" action="editaAgenciaAdm.php" onsubmit="return validaCampos()">
                    <label for="txtAgencia">Nome da Agencia:</label><br>
                    <input type="text" id="txtAgencia" name="txtAgencia" value="<?php echo $agencia; ?>"
                           class="form-control" maxlength="50"><br>
                    <button type="submit" class="btn btn-primary w-100" name="btnSubmitAgencia">Alterar Agencia
                    </button><br>
                </form>
                <hr/>

                <?php
            } else if (isset($_GET['btnSubmitAgencia'])) {
                $cgencia = $_GET['txtAgencia'];
                $idAgencia = $_SESSION['idAgencia'];
                unset($_SESSION['idAgencia']);
                $sql = "CALL sp_altera_agencia('$idAgencia', '$nomeAgencia', @saida, @rotulo);";

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
                        <a href='agenciasAdm.php' class="alert-link" target='_self'>Voltar</a>
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
   $sql = "SELECT * FROM agencias;";
   if ($res = mysqli_query($con, $sql)) {
       $agencia = array();
       while($reg=mysqli_fetch_assoc($res)){
           if (isset($reg['agencia'])) {
               $agencia[] = $reg['agencia'];
           }
       }
       mysqli_free_result($res); // Libera o resultado da memória
       if (isset($agencia[0])) {
           echo $agencia[0];
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