<!DOCTYPE html>
<html>
  <head>
      <?php
          include_once "header.html";
          include_once "conexao.php";?>

      <title>HOME</title>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    function validaCampos(){
        if (document.fmCategorias.txtCategoria.value == "") {
            Swal.fire({
                title: 'Erro!',
                text: 'Por favor, preencha o nome da categoria.',
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
      <br>
      <main class="container mt-5">
        <h1  class="text-center">Categorias-Administração</h1><br>
        <div class="row text-center">
            <div class="col-md-3 col-sm-3">
            <?php include_once "menuAdm.html";?>
            </div>
            <div class="col-md-9  col-sm-9">
            <?php
    if(isset($_GET['btnSubmitCategoria'])){
        $nomeCategoria = $_GET['txtCategoria'];
        $link = $nomeCategoria;
        $sql = "CALL sp_cadastra_categoria('$nomeCategoria', '$link' ,@saida, @rotulo);";
        if($res=mysqli_query($con,$sql)){
            $reg=mysqli_fetch_assoc($res); {
                $saida = $reg['saida'];
                $rotulo = $reg['saida_rotulo'];

                switch (rotulo){}

                ?>
                <div class="alert alert-warning" role="alert">
                    <h3><?php echo $rotulo; ?></h3>
                    <h3><?php echo $saida; ?></h3>

                <a href='categoriasAdm.php' class="alert-link" target='_self'>Voltar</a>  

                </div>
                <?php

            }
            mysqli_free_result($res); // Libera o resultado da memória

            // Add this line to clear the results
            while(mysqli_more_results($con) && mysqli_next_result($con));
        }else{
            echo "ERRO ao executar a query.";
        }
    }
?>


            <h2 class="text-center">Cadastro de categorias</h2>
            <form name="fmCategorias" method="get" action="categoriasAdm.php" onsubmit="return validaCampos()">
                    <label>Nome da categoria:</label><br>
                    <input type="text" name="txtCategoria" class="form-control" maxlength="50"><br>
                    <button type="submit" class="btn- btn-primary w-100" name="btnSubmitCategoria">Cadastrar</button><br>
                </form>
                <hr/>
                <h2 class="text-center">Categorias Cadastradas:</h2>
                <div class="row">
                <?php
                   
                   $sql = 'SELECT * from vw_retorna_categorias';
if ($res=mysqli_query($con, $sql)) {
    $nomeCategoria = array();
    $linkCategoria = array();
    $idCategoria = array();
    $i = 0;
    while($reg=mysqli_fetch_assoc($res)) {

        $nomeCategoria[$i] = $reg['Nome_Categoria'];
        $linkCategoria[$i] = $reg['Link_Categoria'];
        $idCategoria[$i] = $reg['Id_Categoria'];
        ?>

        <div class="col-md-3 itensCadastrados text-center">
        <h4><?php echo $nomeCategoria[$i]; ?></h4>
        <div class="btn-group btn-group-sm" role="group" arial-label="Basic sample">
        <a href="editaCategoriaAdm.php?editaCategoria=<?php echo $idCategoria[$i];?>" class="btn btn-primary">Editar</a>
        <a href="categoriasAdm.php?eliminarCategoria=<?php echo  $idCategoria[$i];?>" class="btn btn-primary" onclick="return confirm(' Tem certeza que deseja eliminar esta categoria?')">Eliminar</a>
                </div>
             </div>
    
         <?php
              $i++;          
             
            }

     }
            
         ?>
            </div>  


                <?php   
                
                ?>

</div>
</div>
                <?php
   mysqli_next_result($con); // Prepara o próximo conjunto de resultados
   $sql = "SELECT * FROM categorias;";
   if ($res = mysqli_query($con, $sql)) {
       $nomeCategoria = array();
       while($reg=mysqli_fetch_assoc($res)){
           if (isset($reg['categoria'])) {
               $nomeCategoria[] = $reg['categoria'];
           }
       }
       mysqli_free_result($res); // Libera o resultado da memória
       if (isset($nomeCategoria[0])) {
           echo $nomeCategoria[0];
       }
   } else {
       echo "Erro ao executar a consulta: " . mysqli_error($con);
   }
?>


</main>

<!-- FECHANDO A CONEXÃO COM O BANCO DE DADOS -->
<?php if(isset($con)) { mysqli_close($con); } ?>

</body>
</html>