<?php
require 'conexao.php';

// Consulta SQL para obter dados
$sql = "SELECT *, ofertas FROM atores";
$result = mysqli_query($con, $sql);

$atores = [];
$ofertas = [];

if (mysqli_num_rows($result) > 0) {
  // Processar cada linha de resultado
  while($row = mysqli_fetch_assoc($result)) {
    $atores[] = $row["nome"];
    $filmes[] = $row["ofertas"];
  }
} else {
  echo "0 results";
}
?>
