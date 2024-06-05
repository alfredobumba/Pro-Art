<?php
session_start();

if (isset($_SESSION['acesso'])) {
    echo '<center><h2>A sessão já está aberta</h2><br>';
    echo '<h4>Você será redirecionado para página de Administração.</h4></center>';
    echo '<meta http-equiv="refresh" content=2;url="adm.php">';
    exit();
}

include_once "conexao.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include_once "header.html"; ?>
    <title>Home</title>
    <script type="text/javascript">
        function validaCampos() {
            if (document.fmlogin.txtLogin.value === "") {
                alert("Por favor, preencha um login!");
                document.fmlogin.txtLogin.focus();
                return false;
            }
            if (document.fmlogin.txtSenha.value === "") {
                alert("Por favor, preencha a sua senha!");
                document.fmlogin.txtSenha.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="administracao">
    <!-- MENU SUPERIOR -->
    <?php include_once "menuSuperior.html"; ?>
    <!-- FIM MENU SUPERIOR -->

    <!-- PRINCIPAL -->
    <br>
    <main class="container mt-5">
        <h1 class="text-center mt-5">Usuários - Administração</h1><br>

        <div class="row">
            <div class="col-md-7 col-sm-7">
                <img src="imagens/login.png" class="w-100" alt="Imagem ilustrativa para o login">
            </div>
            <div class="col-md-5 col-sm-5">
                <?php
                if (isset($_POST['btnSubmitLogin'])) {
                    $usuario = mysqli_real_escape_string($con, $_POST['txtLogin']);
                    $senha = mysqli_real_escape_string($con, $_POST['txtSenha']);
                    $sql = "SELECT login, senha FROM usuarios WHERE login = '$usuario' AND senha = '$senha'";

                    $res = mysqli_query($con, $sql);
                    if ($res) {
                        $linhas = mysqli_num_rows($res);
                        if ($linhas > 0) {
                            $_SESSION['acesso'] = true;
                            echo '<div class="alert alert-success" role="alert">';
                            echo '<h2 class="text-center">Login efetuado com sucesso!</h2><br>';
                            echo '</div>';
                            echo '<meta http-equiv="refresh" content=2;url="adm.php">';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">';
                            echo '<h2 class="text-center">Usuário ou senha inválido!</h2><br><br>';
                            echo '<a href="login.php" class="alert-link" target="_self">Voltar</a>';
                            echo '</div>';
                        }
                    } else {
                        echo "<h3>Erro ao executar a query!</h3>";
                    }
                } else {
                ?>
                <form name="fmlogin" method="post" action="login.php" onsubmit="return validaCampos();">
                    <h2 class="text-center">Insira o seu login e senha:</h2><br>
                    <input type="text" name="txtLogin" placeholder="Insira aqui o seu login" class="form-control text-center"><br><br>
                    <input type="password" name="txtSenha" placeholder="Insira aqui a sua senha" class="form-control text-center"><br><br>
                    <button type="submit" name="btnSubmitLogin" class="btn btn-primary w-100">Entrar</button>
                </form>
                <?php
                }
                ?>
            </div>
        </div>
    </main>

    <!-- ENCERRANDO A CONEXÃO COM BANCO DE DADOS -->
    <?php if (isset($con)) { mysqli_close($con); } ?>
</body>
</html>
