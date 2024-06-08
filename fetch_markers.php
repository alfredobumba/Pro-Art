<?php
include 'conexao.php';

// Obter dados dos marcadores
$sql_markers = "SELECT id, name, address, lat, lng, type FROM markers";
$result_markers = mysqli_query($con, $sql_markers);

if (!$result_markers) {
    die('Erro na consulta de marcadores: ' . mysqli_error($con));
}

$markers = array();
while ($row = mysqli_fetch_assoc($result_markers)) {
    $markers[] = $row; // Adiciona o marcador ao array de marcadores
}

// Obter dados dos atores
$sql_atores = "SELECT a.nome, m.lat, m.lng FROM atores a 
               JOIN markers m ON a.markers_id = m.id";
$result_atores = mysqli_query($con, $sql_atores);

if (!$result_atores) {
    die('Erro na consulta de atores: ' . mysqli_error($con));
}

$atores = array();
while ($row = mysqli_fetch_assoc($result_atores)) {
    $atores[] = $row; // Adiciona o ator ao array de atores
}

// Combinar resultados
$response = array(
    'markers' => $markers,
    'atores' => $atores
);

echo json_encode($response);

mysqli_close($con);

?>
