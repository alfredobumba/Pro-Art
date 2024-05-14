<?php
require 'consulta.php';

// Código HTML e JavaScript para criar o gráfico
echo '
<!DOCTYPE html>
<html>
<head>
    <title>Estatísticas de Atores</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="chart"></canvas>
    <script>
        var ctx = document.getElementById(\'chart\').getContext(\'2d\');
        var chart = new Chart(ctx, {
            type: \'bar\', // Altere para \'pie\' para um gráfico de pizza
            data: {
                labels: ' . json_encode($atores) . ',
                datasets: [{
                    label: \'Número de Filmes\',
                    data: ' . json_encode($ofertas) . ',
                    backgroundColor: \'rgba(75, 192, 192, 0.2)\',
                    borderColor: \'rgba(75, 192, 192, 1)\',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
';
?>
