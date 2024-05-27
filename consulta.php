<?php
require 'conexao.php'; 

// Consulta para obter a percentagem de gêneros
$sql_genero = "SELECT genero, COUNT(*) as count FROM atores GROUP BY genero";
$buscar_genero = mysqli_query($con, $sql_genero);

if (!$buscar_genero) {
    die('Erro na consulta de gêneros: ' . mysqli_error($con));
}

$total_genero = 0;
$genero_counts = array();

while ($atores = mysqli_fetch_array($buscar_genero)) {
    $genero = $atores['genero'];
    $count = $atores['count'];
    $total_genero += $count;
    $genero_counts[$genero] = $count;
}

// Consulta para obter os dados do histograma de cache
$sql_cache = "SELECT genero, cache FROM atores LIMIT 15";
$buscar_cache = mysqli_query($con, $sql_cache);

if (!$buscar_cache) {
    die('Erro na consulta de cache: ' . mysqli_error($con));
}

$atores_data = array();
while ($ator = mysqli_fetch_array($buscar_cache)) {
    $atores_data[] = array($ator['genero'], $ator['cache']);
}
?>