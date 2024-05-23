<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    unset($_SESSION['acesso']);
    unset($_SESSION['idCategoria']);

    session_destroy();

?>
<html>
        <!-- ENCERRANDO A CONEXÃO COM BANCO DE DADOS -->
    <?php if (isset($con)) { mysqli_close($con); } ?>
    <br><br>
    <center><h2>Terminar sessão! </h2></center>
    <meta http-equiv="refresh" content=2;url="index.php">

</html>