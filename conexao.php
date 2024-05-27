<?php
// CONEXÃO COM O BANCO DE DADOS PrO~ArT
$host = "localhost";
$user = "root";
$pass = ""; // Certifique-se de que a senha está correta
$base = "proart";

$con = mysqli_connect($host, $user, $pass, $base);
$banco = mysqli_select_db($con, $base);

// MENSAGEM DE FALHA DE CONEXÃO
if(mysqli_connect_errno()){
    die( "Falha de conexão com o banco de dados: ".
        mysqli_connect_errno() . "(" . mysqli_connect_errno() . ")"
    );
}

// CONFIGURAÇÃO DE CARACTERES
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, "SET character_set_connection=utf8");
mysqli_query($con, "SET character_set_client=utf8");
mysqli_query($con, "SET character_set_results=utf8");
?>

