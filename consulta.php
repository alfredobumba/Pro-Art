<?php
require 'conexao.php'; // Verifique se este é o arquivo correto para a conexão ao banco de dados.

// Consulta SQL para obter a contagem de atores por gênero
$sql = "SELECT genero, COUNT(*) as count FROM atores GROUP BY genero";
$result = mysqli_query($con, $sql);

// Preparar os dados para o gráfico
$chart_data = [['Gênero', 'Quantidade']];
while ($row = mysqli_fetch_assoc($result)) {
    $chart_data[] = [$row['genero'] == 'M' ? 'Masculino' : 'Feminino', (int)$row['count']];
}

// Converter os dados do gráfico para JSON
$chart_data_json = json_encode($chart_data);
?>