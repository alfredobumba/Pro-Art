<?php
// Conexão ao banco de dados
include 'conexao.php';

$sql = "SELECT name, lat, lng, type FROM markers";
$result = mysqli_query($con, $sql);

$markers = array();

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $markers[] = $row;
    }
    echo json_encode($markers);
} else {
    echo json_encode([]);
}

mysqli_close($con);
?>
