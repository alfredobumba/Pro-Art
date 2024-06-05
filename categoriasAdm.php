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
        include_once "header.html";
        include_once "conexao.php";
       
    ?>

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
    <?php include_once "menuSuperior.html"; ?>
    <!-- FIM DO MENU  SUPERIOR---------------->

    <main class="container mt-5">
        <h1 class="text-center">Categorias-Administração</h1>
        <div class="row text-center">
            <div class="col-md-3 col-sm-3">
                <?php include_once "menuAdm.html"; ?>
            </div>
            <div class="col-md-9 col-sm-9">
                <?php
                    $alert = '';  // Define $alert antes do bloco if

                    function executaQuery($sql, $redirect){
                        global $con, $alert;
                        if($res = mysqli_query($con, $sql)){
                            // Processar resultados
                            $reg = mysqli_fetch_assoc($res);
                            $saida = isset($reg['saida']) ? $reg['saida'] : '';
                            $rotulo = isset($reg['saida_rotulo']) ? $reg['saida_rotulo'] : '';
                            
                            switch ($rotulo){
                                case '!':
                                    $alert = 'alert-success';
                                    break;
                                case 'OPS!':
                                    $alert = 'alert-warning';
                                    break;
                                case 'ERRO!':
                                    $alert = 'alert-danger';
                                    break;  
                            }
                            echo "<div class='alert $alert' role='alert'>";
                            echo "<h3>$rotulo</h3>";
                            echo "<h3>$saida</h3>";
                            echo "<a href='$redirect' class='alert-link' target='_self'>Voltar</a>";  
                            echo "</div>";
                            mysqli_free_result($res);
                            if(mysqli_more_results($con)) {
                                while(mysqli_next_result($con)) {
                                    // Clear results
                                }
                            }
                        } else {
                            echo "ERRO ao executar a query.";
                        }
                    }

                    if(isset($_GET['btnSubmitCategoria'])){
                        $nomeCategoria = $_GET['txtCategoria'];
                        $link = $nomeCategoria;
                        $sql = "CALL sp_cadastra_categoria('$nomeCategoria', '$link' ,@saida, @rotulo);";

                        executaQuery($sql, "categoriasAdm.php");
                    }
                ?>
                <h2 class="text-center">Cadastro de categorias</h2>
                <form name="fmCategorias" method="get" action="categoriasAdm.php" onsubmit="return validaCampos()">
                    <label>Nome da categoria:</label><br>
                    <input type="text" name="txtCategoria" class="form-control" maxlength="50"><br>
                    <button type="submit" class="btn btn-primary w-100" name="btnSubmitCategoria">Cadastrar</button><br>
                </form>
                <hr/>
                <h2 class="text-center">Categorias Cadastradas:</h2>
                <div class="row">
                    <?php
                        $sql = 'SELECT * from vw_retorna_categorias';
                        if ($res = mysqli_query($con, $sql)) {
                            $nomeCategoria = array();
                            $linkCategoria = array();
                            $idCategoria = array();
                            $i = 0;
                            while($reg = mysqli_fetch_assoc($res)) {
                                $nomeCategoria[$i] = $reg['Nome_Categoria'];
                                $linkCategoria[$i] = $reg['Link_Categoria'];
                                $idCategoria[$i] = $reg['Id_Categoria'];
                                echo "<div class='col-md-3 itensCadastrados text-center'>";
                                echo "<h4>$nomeCategoria[$i]</h4>";
                                echo "<div class='btn-group btn-group-sm' role='group' arial-label='Basic sample'>";
                                echo "<a href='editaCategoriaAdm.php?editaCategoria=$idCategoria[$i]&nomeCategoria=$nomeCategoria[$i]' class='btn btn-primary'>Editar</a>";
                                echo "<a href='editaCategoriaAdm.php?eliminarCategoria=$idCategoria[$i]' class='btn btn-primary' onclick='return confirm(\"Tem certeza que deseja eliminar esta categoria?\")'>Eliminar</a>";
                                echo "</div></div>";
                                $i++;
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <!-- FECHANDO A CONEXÃO COM O BANCO DE DADOS -->
    <?php if(isset($con)) { mysqli_close($con); } ?>

</body>
<?php
    } else {
?>
<meta http-equiv="refresh" content="0;url=login.php">
<?php
    }
?>
</html>
