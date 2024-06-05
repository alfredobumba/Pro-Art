<!DOCTYPE html>
<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    if ($_SESSION['acesso'] == true) {
?>
<html>
<head>
    <?php  include_once "header.html";
            include_once "conexao.php";
            include_once "funcoes.php";
     ?>

    <title>Home</title>
    <script type="text/javascript">
        function ValidaCampos(){
            if (document.fmUsuarios.txtNome.value == "") {
                alert("Por favor, preencha um nome!");
                document.fmUsuarios.txtNome.focus();
                return false;
            }
            if (document.fmUsuarios.txtEmail.value == "") {
                alert("Por favor, preencha um E-mail!");
                document.fmUsuarios.txtEmail.focus();
                return false;
            }
            if (document.fmUsuarios.txtLogin.value == "") {
                alert("Por favor, preencha um Login!");
                document.fmUsuarios.txtLogin.focus();
                return false;
            }
            if (document.fmUsuarios.txtSenha1.value == "") {
                alert("Por favor, preencha uma Senha!");
                document.fmUsuarios.txtSenha1.focus();
                return false;
            }
            if (document.fmUsuarios.txtSenha2.value == "") {
                alert("Por favor, repita a senha!");
                document.fmUsuarios.txtSenha2.focus();
                return false;
            }
            if (document.fmUsuarios.txtSenha1.value != document.fmUsuarios.txtSenha2.value) {
                alert("As senhas devem ser iguais!");
                document.fmUsuarios.txtSenha2.focus();
                return false;
            }
        }
    </script>
</head>
<body class="administracao">

    <!-- MENU SUPERIOR-->

<?php include_once "menuSuperior.html"; ?>

<!-- FIM MENU DO SUPERIOR-->

<!-- PRINCIPAL-->
<br>
<main class="container mt-5">
<h1 class="text-center  mt-5">Usuários - Administração</h1><br>

    <div class="row">
        <div class="col-md-3 col-sm-3">
            <?php include_once "menuAdm.html" ?>
        </div>
        <div class="col-md-9 col-sm-9">
            <?php
                if (isset($_POST['btnSubmitUsuario'])) {
                    $nome = $_POST['txtNome'];
                    $email = $_POST['txtEmail'];
                    $login = $_POST['txtLogin'];
                    $senha = $_POST['txtSenha1'];
                    $nivel = $_POST['selNivel'];
                    $salt = '123';

                    $sql = "CALL sp_cadastra_usuario ( ' $nome', '$login', '$email','$senha','$salt', $nivel, @saida,@rotulo)";

                    executaQuery($sql, "usuariosAdm.php");

                    /*
                    if ($res = mysqli_query($con, $sql)) {
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
                        echo "<a href='usuariosAdm.php' class='alert-link' target='_self'>Voltar</a>";
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
                    */

                }else{
            ?>

                
            <ul class="nav nav-tabs" role="tablist">
                <li class=" nav-item" role="presentation">
                    <a href="#tabFormulario"  class="nav-link active" id="linkFormulario" data-toggle="tab" role="tab" aria-controls="
                    tabFormulario">Cadastro</a>
                </li>
                <li class=" nav-item" role="presentation">
                    <a href="#tabExibicao" class="nav-link" id="linkExibicao" data-toggle="tab" role="tab" aria-controls="
                    tabExibicao">Usuários cadastrados</a>
                </li>
            </ul>

            <div class="tab-content" id="meusConteudos">
                <div class="tab-pane fade show active" id="tabFormulario" role="tabpanel" aria-labelledby="linkFormulario">
                    

            <br>
            <h3>Cadastrar novo usuario:</h3>
            <form name="fmUsuarios" method="post" action="usuariosAdm.php" onsubmit="return ValidaCampos()">

                <label>Nome:</label> <br>
                <input type="text" name="txtNome" class="form-control" maxlength="70"><br>

                <label>E-mail:</label> <br>
                <input type="email" name="txtEmail" class="form-control" maxlength="50" aria-describedby="emailHelp"><br>

                <label>Login:</label> <br>
                <input type="text" name="txtLogin" class="form-control" maxlength="30"><br>

                <label>Senha:</label> <br>
                <input type="password" name="txtSenha1" class="form-control" maxlength="16"><br>

                <label>Repita a senha:</label> <br>
                <input type="password" name="txtSenha2" class="form-control" maxlength="16"><br>

                <label>Nível de usuários:</label> <br>
                <select name="selNivel" class="form-control">
                    <option value="1">1 - Administrador</option>
                    <option value="2">2 - Moderador</option>
                </select><br>

                <button type="submit" name="btnSubmitUsuario" class="btn btn-primary w-100">Cadastrar usuário</button>
                <br>
                <br>
            </form>



                </div>
                <div class="tab-pane fade" id="tabExibicao" role="tabpanel" aria-labelledby="linkExibicao">
                    <br>
                    <h2 class="text-center">Usuários cadastrados:</h2><br>
                    <div class="row">
                        <?php
                            $sql = "SELECT * FROM vw_usuarios";
                            if ($res=mysqli_query($con, $sql)) {

                                $nomeUsuario = array();
                                $idUsuario = array();
                                $i = 0;

                                while($reg=mysqli_fetch_assoc($res)) {
                                    $nomeUsuario[$i] = $reg['nome_usuario'];
                                    $idUsuario[$i] = $reg['id_usuario'];
                                    ?>
                                    <div class="col-md-4 itensCadastrados text-center">
                                        <h4><?php echo  $nomeUsuario[$i]; ?></h4>
                                        <div class="btn-group" role="group" aria-label="Basic sample">
                                            <a href="#" class="btn btn-primary">Editar</a>
                                            <a href="#" class="btn btn-secondary">Eliminar</a>

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
<!------------ENCERANDO A CONEXÃO COM BANCO DE DASOS-------->
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
