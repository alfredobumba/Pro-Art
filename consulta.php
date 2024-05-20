<?php
require 'conexao.php';

// Consulta SQL para obter dados
$sql = "SELECT atores.*, ofertas_atores.* FROM atores 
JOIN ofertas_atores ON atores.id = ofertas_atores.atores_id";


$result = mysqli_query($con, $sql);

$atores = [];
$ofertas = [];

if (mysqli_num_rows($result) > 0) {
  // Processar cada linha de resultado
  while($row = mysqli_fetch_assoc($result)) {
    $atores[] = $row["nome"];
    $filmes[] = $row["ofertas_atores"];
  }
} else {
  echo "0 results";
}
?>
